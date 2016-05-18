<?php

namespace Drupal\anonmigrate\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;
use Drupal\weeklytime\Plugin\Field\FieldType\WeeklyTimeField;
use Drupal\Component\Serialization\Json;
use Drupal\migrate\Row;

/**
 * Generic source for AnonIntergroup JSON.
 *
 * @code
 * source:
 *   plugin: anonintergroup_json
 *   keys:
 *     id: [ add1, add2, city, zip ]
 *     day: mDayNo
 *   path: https://example.org/wp-admin/admin-ajax.php?action=meetings
 * @endcode
 *
 * @MigrateSource(
 *   id = "anonintergroup_json"
 * )
 */
class AnonIntergroupJSON extends SourcePluginBase {

  protected $header;

  /**
   * {@inheritdoc}
   */
  public function __toString() {
    return 'AnonIntergroupJSON::' . $this->configuration['path'];
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    return $this->header;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    $ids = [];
    foreach ($this->configuration['keys']['id'] as $key) {
      $ids[$key]['type'] = 'string';
    }
    return $ids;
  }

  /**
   * {@inheritdoc}
   */
  public function initializeIterator() {
    // Validate keys.
    if (empty($this->configuration['keys']['id'])) {
      throw new MigrateException('You must define keys/id.');
    }
    if (empty($this->configuration['keys']['day'])) {
      throw new MigrateException('You must define keys/day.');
    }

    // Open the file and read the header.
    // @todo: read this in a more modern way.
    $data = file_get_contents($this->configuration['path']);
    $json = Json::decode($data);

    // Create array of keys useful in creating the unique row key below.
    $keys = array_fill_keys($this->configuration['keys']['id'], 1);

    // Initialize days data.
    $day_map = [
      '1' => 'sun',
      '2' => 'mon',
      '3' => 'tue',
      '4' => 'wed',
      '5' => 'thu',
      '6' => 'fri',
      '7' => 'sat',
    ];
    $init_days = array_fill_keys($day_map, 0);
    $day_key = $this->configuration['keys']['day'];

    // Read the file, combining rows where the meeting id (mID) and
    // meeting time are the same every day.
    $rows = [];
    foreach ($json as $data) {
      // Get the rows unique key.
      $key_values = array_intersect_key($data, $keys);
      // @todo: check for other 'NULL' values?
      if (!in_array('null', $key_values)) {
        continue;
      }
      $row_key = implode('|', array_intersect_key($data, $keys));

      // Get the existing row, or start a new one.
      if (isset($rows[$row_key])) {
        $row_data = $rows[$row_key];
      }
      else {
        $row_data = $data + $init_days;
      }

      // Set the meeting day.
      $day_value = $data[$day_key];
      $day_field = NULL;
      if (is_numeric($day_value)) {
        // The day is a week day number from the map above.
        $day_field = $day_map[$day_value];
      }
      else {
        // Check if the day value is the string value of the day of week.
        $day_value = strtolower(substr($day_value, 0, 3));
        if (in_array($day_value, $day_map)) {
          $day_field = $day_value;
        }
      }
      if ($day_field) {
        $row_data[$day_field] = 1;
      }

      $rows[$row_key] = $row_data;
    }

    // Turn the meeting rows into an iterator.
    return (new \ArrayObject($rows))->getIterator();
  }
}
