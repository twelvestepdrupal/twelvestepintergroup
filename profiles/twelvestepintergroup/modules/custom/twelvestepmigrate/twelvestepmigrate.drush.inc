<?php
/**
 * @file twelvestepmigrate.drush.inc
 *
 * Drush commands for the TwelveStepMigrate module.
 */

use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\Plugin\MigrateSourceInterface;
use Drupal\migrate\MigrateExecutable;
use Drupal\migrate\MigrateMessage;
use Symfony\Component\DomCrawler\Crawler;
use Drupal\Component\Serialization\Json;

/**
 * Implements hook_drush_command().
 */
function twelvestepmigrate_drush_command() {
  $items = [];
  $items['migrate-meetings-file'] = [
    'description' => 'Migrate a file adhering to the Meeting Guide API (https://meetingguide.org/api).',
    'arguments' => [
      'path' => 'The path to the file (or directory of files) to migrate.',
    ],
    'drupal dependencies' => ['twelvestepmigrate'],
    'aliases' => ['mm-file'],
  ];
  $items['migrate-meetings-download'] = [
    'description' => 'Migrate all meeting guide directories found in https://meetingguide.org.',
    'drupal dependencies' => ['twelvestepmigrate'],
    'aliases' => ['mm-dl'],
  ];
  return $items;
}

/**
 * Call back function drush_twelvestepmigrate_command_migrate_meetings_file()
 */
function drush_twelvestepmigrate_migrate_meetings_file($path = '') {
  // Determine the files.
  if (!$path) {
    return drush_set_error(dt('Missing file argument'));
  }
  if (!is_file($path) && !is_dir($path)) {
    $fullpath = drush_cwd() . '/' . $path;
    if (!is_file($fullpath) && !is_dir($path)) {
      return drush_set_error(dt('@path: file does not exist.', ['@path' => $path]));
    }
    $path = $fullpath;
  }
  if (is_dir($path)) {
    $files = file_scan_directory($path, '/\.area\.json$/', ['recurse' => FALSE]);
    $paths = array_keys($files);
  }
  else {
    $paths = [$path];
  }

  /** @var MigrationInterface $migrations[] */
  $migrations = \Drupal::service('plugin.manager.migration')
    ->createInstancesByTag('example-json');

  foreach ($paths as $path) {
    drush_print(dt('Migrating @path...', ['@path' => $path]));

    // Read the file.
    $data = @file_get_contents($path);
    $json_data = Json::Decode($data);
    // If we are reading a cached file, adjust the JSON to just be the body.
    if (isset($json_data['headers']) && isset($json_data['body'])) {
      $json_data = $json_data['body'];
    }

    foreach ($migrations as $migration) {
      $migration->getSourcePlugin()->setJsonData($json_data);
      $executable = new MigrateExecutable($migration, new MigrateMessage());

      try {
        $executable->import();
      }
      catch (Exception $e) {
        drush_print(dt(' * Migration exception: @message', ['@message' => $e->getMessage()]));
      }
    }
  }
}

/*
 * Call back function drush_twelvestepmigrate_command_migrate_meetings_download()
 */
function drush_twelvestepmigrate_migrate_meetings_download() {
  // Make sure that the cached results directory exists.
  $cache_dir = 'public://meetingguide.org';
  file_prepare_directory($cache_dir, FILE_CREATE_DIRECTORY);

  // Get all of the meeting areas. This URL must return data.
  if (!$areas = _twelvestepmigrate_get_file('https://meetingguide.org/v1/areas', "$cache_dir/areas.json")) {
    return drush_set_error(dt('Cannot retrieve @url.', ['@url' => $url]));
  }

  /** @var MigrationInterface $migrations[] */
  $migrations = \Drupal::service('plugin.manager.migration')
    ->createInstancesByTag('example-json');

  // Loop through each meeting area.
  foreach ($areas as $area) {
    // Get the meetings from this area.
    $area_file = "$cache_dir/$area[slug].area.json";
    if (empty($area['api'])) {
      // @todo: Why are some area's missing the API?
      continue;
    }
    if (!$json_data = _twelvestepmigrate_get_file($area['api'], $area_file)) {
      continue;
    }

    // Migrate the meetings from this area.
    drush_print(dt('Migrating @name...', ['@name' => $area['name']]));
    foreach ($migrations as $migration) {
      $migration->getSourcePlugin()->setJsonData($json_data);
      $executable = new MigrateExecutable($migration, new MigrateMessage());
      try {
        $executable->import();
      }
      catch (Exception $e) {
        drush_print(dt(' * Migration exception: @message', ['@message' => $e->getMessage()]));
      }
    }
  }
}

/**
 * Retrieve the file, from HTTP or from a cached local file.
 *
 * @param $url
 * @param $file
 *
 * @return array
 *   Returns the decoded JSON file.
 */
function _twelvestepmigrate_get_file($url, $file) {
  // Get an HTTP client.
  static $client;
  if (empty($client)) {
    $client = \Drupal::httpClient();
  }

  // If the file is already cached, set the HTTP request header last modified.
  $headers = [];
  if (file_exists($file)) {
    $body = @file_get_contents($file);
    $json_data = Json::Decode($body);
    if (!empty($json_data['headers']['Cache-Control'][0]) && $json_data['headers']['Cache-Control'][0] != 'no-cache') {
      if (!empty($json_data['headers']['Date'][0])) {
        $headers['If-Modified-Since'] = $json_data['headers']['date'][0];
      }
    }
  }

  // Request the file via HTTP, using the If-Modified-Since so that we only
  // retrieve the file if it's changed.
  // @todo: If-Modified-Since isn't working.
  try {
    $response = $client->get($url, ['headers' => $headers]);
  }
  catch (RequestException $e) {
    drush_print(dt(' * request exception: @message', ['@message' => $e->getMessage()]));
    return NULL;
  }
  catch (Exception $e1) {
    drush_print(dt(' * http exception: @message', ['@message' => $e1->getMessage()]));
    return NULL;
  }

  if ($response && $response->getStatusCode() == 200) {
    // The file has changed. Cache the results with the HTTP response date.
    $body = $response->getBody();
    $json_data = [
      'body' => Json::Decode($body),
      'headers' => $response->getHeaders(),
    ];
    file_put_contents($file, Json::Encode($json_data));
  }
  else {
    // The file was not retrieved. Get the cached file, if it exists.
    if (!$body = @file_get_contents($file)) {
      return NULL;
    }
    $json_data = Json::Decode($body);
  }
  return $json_data['body'];
}
