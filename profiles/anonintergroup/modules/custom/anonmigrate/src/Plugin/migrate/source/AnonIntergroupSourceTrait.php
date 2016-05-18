<?php

namespace Drupal\anonmigrate\Plugin\migrate\source;

trait AnonIntergroupSourceTrait {

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
    // Validate configuration.
    if (empty($this->configuration['path'])) {
      throw new MigrateException('You must define a source path.');
    }
    if (empty($this->configuration['keys']['id'])) {
      throw new MigrateException('You must define keys/id.');
    }
    if (empty($this->configuration['keys']['day'])) {
      throw new MigrateException('You must define keys/day.');
    }

    // Create array of keys useful in creating the unique row key below.
    $keys = array_fill_keys($this->configuration['keys']['id'], 1);

    // Initialize days data.
    // @todo: make prettier, more elegant.
    $config_day_key = explode(':', $this->configuration['keys']['day']);
    $config_day_key += [1 => 0];
    list($day_key, $day_offset) = $config_day_key;
    $day_map = [
      0 + $day_offset => 'sun',
      1 + $day_offset => 'mon',
      2 + $day_offset => 'tue',
      3 + $day_offset => 'wed',
      4 + $day_offset => 'thu',
      5 + $day_offset => 'fri',
      6 + $day_offset => 'sat',
    ];
    $init_days = array_fill_keys($day_map, 0);

    // Read the file, combining rows where the meeting id is the same.
    $rows = [];
    $this->initSource();
    while ($row = $this->nextSourceRow()) {
      
      // Get this rows unique key.
      $id_values = array_intersect_key($row, $keys);

      // Skip rows without any unique id values.
      if (!array_filter($id_values)) {
        continue;
      }

      // Get the existing row, or start a new one.
      $row_key = implode('|', $id_values);
      if (isset($rows[$row_key])) {
        $row_data = $rows[$row_key];
      }
      else {
        $row_data = $row + $init_days;
      }

      // Set the meeting day.
      $day_value = $row[$day_key];
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
