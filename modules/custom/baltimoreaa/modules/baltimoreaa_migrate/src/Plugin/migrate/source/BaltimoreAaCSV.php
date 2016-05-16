<?php

namespace Drupal\baltimoreaa_migrate\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;
use Drupal\weeklytime\Plugin\Field\FieldType\WeeklyTimeField;
use Drupal\migrate\Row;

/**
 * Source for Baltimore CSV.
 *
 * This file could be defined configuration, but because it is used in multiple
 * migrations, it's defined in here once in code.
 *
 * Here is the configuration that could be used in migration_templates/anon*.yml.
 *
 * @code
 * source:
 *   plugin: csv
 *   path: modules/custom/baltimoreaa/modules/baltimoreaa_migrate/meetings.csv
 * @endcode
 *
 * @MigrateSource(
 *   id = "baltimoreaa_csv"
 * )
 */
class BaltimoreAaCSV extends SourcePluginBase {

  protected $header;

  /**
   * {@inheritdoc}
   */
  public function __toString() {
    return 'BaltimoreAaCSV::' . $this->getFilePath();
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
    foreach ($this->configuration['keys'] as $key) {
      $ids[$key]['type'] = 'string';
    }
    return $ids;
  }

  /**
   * Return the CSV file path
   *
   * @return string
   */
  protected function getFilePath() {
    return !empty($this->configuration['path']) ? $this->configuration['path'] : 'modules/custom/baltimoreaa/modules/baltimoreaa_migrate/meetings.csv';
  }

  /**
   * {@inheritdoc}
   */
  public function initializeIterator() {
    // Open the file and read the header.
    /** @var \SplFileObject $file */
    $file = new \SplFileObject($this->getFilePath());
    $file->rewind();
    $this->header = $file->fgetcsv();
    $header_count = count($this->header);

    // Create array of keys useful in creating the unique row key below.
    $keys = array_fill_keys($this->configuration['keys'], 1);

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

      $day_key = $day_map[$data['mDayNo']];
      $row_data[$day_key] = 1;

      $rows[$row_key] = $row_data;
    }

    // Turn the meeting rows into an iterator.
    return (new \ArrayObject($rows))->getIterator();
  }
}
