<?php

namespace Drupal\baltimoreaa_migrate\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * @MigrateProcessPlugin(
 *   id = "meeting_time"
 * )
 */
class MeetingTime extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if (preg_match('/^(\d+):(\d\d)\s*([AP]?)/', $value, $matches)) {
      $hh = $value[1];
      if (!empty($value[3]) && $value[3] == 'P') {
        $hh += 12;
      }
      $mm = $value[2];
      return $hh * 60 + $mm;
    }

    return NULL;
  }

}
