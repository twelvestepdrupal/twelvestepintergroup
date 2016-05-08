<?php

namespace Drupal\baltimoreaa\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateException;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * @MigrateProcessPlugin(
 *   id = "explode_position"
 * )
 */
class ExplodePosition extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if (!empty($value)) {
      $delimiter = !empty($this->configuration['delimiter']) ? $this->configuration['delimiter'] : ',';
      $values = explode($delimiter, $value);
      $position = !empty($this->configuration['position']) ? $this->configuration['position'] : 0;
      if (isset($values[$position])) {
        return $values[$position];
      }
    }

    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function multiple() {
    return TRUE;
  }
}
