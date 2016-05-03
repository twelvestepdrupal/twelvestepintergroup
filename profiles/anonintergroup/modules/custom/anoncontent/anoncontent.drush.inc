<?php

/**
 * @file
 * Drush integration for the anoncontent module.
 */

use Symfony\Component\Serializer\Serializer;
use Drupal\Core\Entity\EntityInterface;

/**
 * Implements hook_drush_command().
 */
function anoncontent_drush_command() {
  $items = [];

  $items['anon-content-export'] = [
    'description' => dt('Exports anon* content entities.'),
    'aliases' => ['ace'],
    'arguments' => [
      'name' => '',
    ],
  ];

  $items['anon-content-import'] = [
    'description' => dt('Imports anon* content entities defined in anoncontent/data director defined in the anoncontent/data directory.'),
    'aliases' => ['aci'],
  ];

  return $items;
}

/**
 * Exports all anonymous content into export_content json.
 *
 * @param
 *   $name - the directory name under anoncontent/data to serialize to.
 */
function drush_anoncontent_anon_content_export($name = NULL) {
  if (!$name) {
    $name = 'test';
  }

  $dir = drupal_get_path('module', 'anoncontent') . "/data/$name";
  file_prepare_directory($dir, FILE_CREATE_DIRECTORY);

  /** @var \Symfony\Component\Serializer\Serializer $serializer */
  $serializer = \Drupal::service('serializer');

  foreach (_anoncontent_types() as $entity_type_id) {
    $entity_ids = \Drupal::entityQuery($entity_type_id)
      ->execute();

    $controller = \Drupal::entityManager()->getStorage($entity_type_id);

    $entities = $controller->loadMultiple($entity_ids);

    $export = $serializer->serialize($entities, 'json', ['json_encode_options' => JSON_PRETTY_PRINT]);
    file_put_contents("$dir/$entity_type_id.json", $export);
  }
}

/**
 * Imports all anonymous content from this module.
 */
function drush_anoncontent_anon_content_import($name = NULL) {
  if (!$name) {
    $name = 'test';
  }
  $dir = drupal_get_path('module', 'anoncontent') . "/data/$name";

  foreach (_anoncontent_types() as $entity_type_id) {
    $filepath = "$dir/$entity_type_id.json";
    $import = @file_get_contents($filepath);
    if (!$import) {
      drush_print(dt('Cannot read !file', ['!file' => $filepath]));
      continue;
    }

    $entities = json_decode($import);
    if (!$entities) {
      continue;
    }

    // Find existing entities.
    $existing_ids = \Drupal::entityQuery($entity_type_id)
      ->condition('id', array_keys((array) $entities))
      ->execute();

    foreach ($entities as $entity_id => $entity) {
      // Skip entities that already exist.
      if (in_array($entity_id, $existing_ids)) {
        drush_print(dt('Skipping !entity_type_id !entity_id that already exists', ['!entity_type_id' => $entity_type_id, '!entity_id' => $entity_id]));
        continue;
      }
    }
  }
}

/**
 * Return array of entity types to serialize.
 *
 * @return array
 */
function _anoncontent_types() {
  return ['anonlocation', 'anongroup', 'anonmeeting'];
}
