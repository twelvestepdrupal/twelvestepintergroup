<?php

namespace Drupal\baltimoreaa\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * @MigrateProcessPlugin(
 *   id = "meeting_format"
 * )
 */
class MeetingFormat extends ProcessPluginBase {

  public static $mapFormat = [
    'mOpen' => [
      'o' => 'open',
      'c' => 'closed',
    ],
    'mSmoke' => [
      'nsm' => 'nonsmoking',
      'w' => 'womens',
      's' => 'smoking',
      'm' => 'mens',
      'yp' => 'youngpeoples',
      'g' => 'gay',
    ],
    'mType' => [
      'discussion' => 'discussion',
      'discusssion' => 'discussion',
      'd' => 'discussion',
      'speaker' => 'speaker',
      'step' => 'stepstudy',
      'stepstudy' => 'stepstudy',
      'begstep' => ['beginners', 'step'],
      'beginners' => 'beginners',
      'literature' => 'literature',
      'o' => 'open',
      'bigbook' => ['bigbook', 'literature'],
      'bb' => ['literature', 'bigbook'],
      'traditions' => 'traditions',
    ],
    'mAccess' => [
      'h' => 'handicap',
    ],
    'mNotes' => [
      // @todo: convert more of the free-text notes.
      'gay' => 'gay',
      'spanish' => 'spanish',
      '12&12' => ['traditions', 'study', 'literature'],
      'twelve&twelve' => ['traditions', 'study', 'literature'],
    ],
  ];

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    // Set the format.
    $values = [];
    foreach (self::$mapFormat as $field_name => $field_map) {
      // Normalize the field value to lower case all characters.
      $field_value = preg_replace('/[^a-z0-9&]/', '', strtolower($row->getSourceProperty($field_name)));

      // Map the field value to the anonmeeting.field_type.
      if (isset($field_map[$field_value])) {
        if (is_array($field_map[$field_value])) {
          $values = array_merge($values, $field_map[$field_value]);
        }
        else {
          $values[] = $field_map[$field_value];
        }
      }
    }
    $values = array_unique($values);
    asort($values);
    return $values;
  }

}
