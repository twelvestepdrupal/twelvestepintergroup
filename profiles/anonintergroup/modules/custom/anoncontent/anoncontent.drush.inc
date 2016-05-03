<?php

/**
 * @file
 * Drush integration for the anoncontent module.
 */

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
 * @param $module
 *   - The name of the module to export to.
 *
 * @todo: simplify by using existing serialize export.
 */
function drush_anoncontent_anon_content_export($module = NULL) {
  if (!$module) {
    $module = 'anoncontent';
  }

  // Ensure that the export directory exists.
  $dir = drupal_get_path('module', $module) . "/anoncontent";
  file_prepare_directory($dir, FILE_CREATE_DIRECTORY);

  // Export each type.
  foreach (_anoncontent_types() as $entity_type_id) {
    // Gather all of the entities of the type.
    $entity_ids = \Drupal::entityQuery($entity_type_id)
      ->execute();

    // Load the entities.
    $controller = \Drupal::entityManager()->getStorage($entity_type_id);
    $entities = $controller->loadMultiple($entity_ids);

    // Convert the entities to an export array.
    $export = [];
    foreach ($entities as $entity_id => $entity) {
      $export[$entity_id] = $entity->toArray();
    }

    // Export the entities as JSON.
    $export_json = json_encode($export, JSON_PRETTY_PRINT) . "\n";
    file_put_contents("$dir/$entity_type_id.json", $export_json);
  }
}

/**
 * Imports all anonymous content from this module.
 *
 * @param $module
 *   - The name of the module to import from.
 *
 * @todo: simplify by using existing serialize import.
 */
function drush_anoncontent_anon_content_import($module = NULL) {
  if (!$module) {
    $module = 'anoncontent';
  }
  $dir = drupal_get_path('module', $module) . "/anoncontent";

  foreach (_anoncontent_types() as $entity_type_id) {
    $filepath = "$dir/$entity_type_id.json";
    $import_json = @file_get_contents($filepath);
    if (!$import_json) {
      drush_print(dt('Cannot read !file', ['!file' => $filepath]));
      continue;
    }

    $import = json_decode($import_json, TRUE);
    if (!$import) {
      continue;
    }

    // Find existing entities.
    $existing_ids = \Drupal::entityQuery($entity_type_id)
      ->condition('id', array_keys($import))
      ->execute();

    foreach ($import as $entity_id => $values) {
      // Skip entities that already exist.
      if (in_array($entity_id, $existing_ids)) {
        drush_print(dt('Skipping !entity_type_id !entity_id that already exists', ['!entity_type_id' => $entity_type_id, '!entity_id' => $entity_id]));
        continue;
      }

      // Import the new entity.
      \Drupal::entityManager()
        ->getStorage($entity_type_id)
        ->create($values)
        ->save();
    }
  }
}

/**
 * Return array of entity types to serialize.
 *
 * @return array
 */
function _anoncontent_types() {
  // These are ordered correctly so that when importing, the dependent
  // entities are created first.
  return ['anonlocation', 'anongroup', 'anonmeeting'];
}
