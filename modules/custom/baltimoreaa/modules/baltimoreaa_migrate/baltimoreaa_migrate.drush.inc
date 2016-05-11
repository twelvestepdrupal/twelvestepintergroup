<?php

/**
 * @file
 * Command useful during development for repeatedly testing the migrate code.
 *
 * For actual migration, use migrate_tools.
 */

use Drupal\migrate\MigrateExecutable;
use Drupal\migrate\MigrateMessage;

/**
 * Implements hook_drush_command().
 */
function baltimoreaa_migrate_drush_command() {
  $items = [];
  $items['devel-baltimoreaa-migrate'] = [
    'description' => dt('Migrate Baltimore AA CSV file, for use development only.'),
    'aliases' => ['dbm'],
  ];
  return $items;
}

/**
 * A command callback.
 */
function drush_baltimoreaa_migrate_devel_baltimoreaa_migrate() {
  db_query("DELETE FROM {key_value} where collection='migrate_status'");

  $migrations = \Drupal::service('plugin.manager.migration')
    ->createInstancesByTag('baltimoreaa');

  foreach ($migrations as $migration) {
    $executable = new MigrateExecutable($migration, new MigrateMessage());
    $executable->import();
  }
}
