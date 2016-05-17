<?php

namespace Drupal\anonmigrate\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;
use Drupal\weeklytime\Plugin\Field\FieldType\WeeklyTimeField;
use Drupal\migrate\Row;

/**
 * Generic source for AnonIntergroup CSV.
 *
 * @code
 * source:
 *   plugin: anonintergroup_csv
 *   keys:
 *     id: [ mAdd1, mAdd2, mCity, mZip ]
 *     day: mDayNo
 *   path: path/to/csv/meetings.csv
 * @endcode
 *
 * @MigrateSource(
 *   id = "anonintergroup"
 * )
 */
class AnonIntergroupCSV extends SourcePluginBase {

  protected $header;

  /**
   * {@inheritdoc}
   */
  public function __toString() {
    return 'AnonIntergroupCSV::' . $this->configuration['path'];
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
      throw new MigrateException('Please define keys/id.');
    }
    if (empty($this->configuration['keys']['day'])) {
      throw new MigrateException('Please define keys/day.');
    }
    
    // Open the file and read the header.
    /** @var \SplFileObject $file */
    $file = new \SplFileObject($this->configuration['path']);
    $file->rewind();
    $this->header = $file->fgetcsv();
    $header_count = count($this->header);

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
    while ($row = $file->fgetcsv()) {
      // Skip empty rows.
      // @todo: fill in missing values.
      if (count($row) != $header_count) {
        continue;
      }

      // Create the data from the header and this row's value.
      $data = array_combine($this->header, $row);

      // Get the rows unique key.
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
