<?php

namespace Drupal\anonmigrate\Plugin\migrate\process;

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
      $hh = $matches[1];
      if (!empty($matches[3])) {
        if ($matches[3] == 'P') {
          if ($hh != 12) {
            $hh += 12;
          }
        }
        elseif ($matches[3] == 'A') {
          if ($hh == 12) {
            $hh = 0;
          }
        }
      }
      $mm = $matches[2];
      return $hh * 60 + $mm;
    }

    return NULL;
  }

}
