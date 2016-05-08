<?php

namespace Drupal\baltimoreaa\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateException;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * @MigrateProcessPlugin(
 *   id = "coordinate"
 * )
 */
class Coordinate extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if (is_string($value)) {
      $delimiter = isset($this->configuration['delimiter']) ? $this->configuration['delimiter'] : ',';
      $value = explode($delimiter, $value);
      $position = isset($this->configuration['position']) ? $this->configuration['position'] : 0;
      if (!isset($value[$position])) {
        return $value[$position];
      }
      else {
        throw new MigrateException('position has no value');
      }
    }
    else {
      throw new MigrateException(sprintf('%s is not a string', var_export($value, TRUE)));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function multiple() {
    return TRUE;
  }
}
