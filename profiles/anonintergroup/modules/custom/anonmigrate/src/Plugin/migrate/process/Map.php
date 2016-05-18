<?php

namespace Drupal\anonmigrate\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * @MigrateProcessPlugin(
 *   id = "map"
 * )
 */
class Map extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    // Set the format.
    $values = [];
    foreach ($this->configuration['map'] as $field_name => $field_map) {
      // Normalize the field value to lower case all characters.
      $field_value = preg_replace('/[^a-z0-9&]/', '', strtolower($row->getSourceProperty($field_name)));

      // Map the field value to the anonmeeting.field_type.
      if (isset($field_map[$field_value])) {
        if (is_array($field_map[$field_value])) {
          $values = array_merge($values, $field_map[$field_value]);
        }
        else {
          $values[] = $field_map[$field_value];
        }
      }
    }
    $values = array_unique($values);
    asort($values);
    return $values;
  }

}
