<?php

/**
 * @file
 * Contains \Drupal\weeklytime\Plugin\Field\FieldType\WeeklyTimeField.
 */

namespace Drupal\weeklytime\Plugin\Field\FieldType;

use Drupal\Component\Utility\Random;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'weeklytime' field type.
 *
 * @FieldType(
 *   id = "weeklytime",
 *   label = @Translation("Weekly time"),
 *   description = @Translation("Weekly Time Field is a repeating time that occurs on the same time and day every week"),
 *   default_widget = "weeklytime_widget",
 *   default_formatter = "weeklytime_widget"
 * )
 */
class WeeklyTimeField extends FieldItemBase {
  /**
   * Return keyed array of Days of the Week.
   *
   * @return array
   */
  public static function weekDays() {
    return [
      'sat' => 'Saturday',
      'sun' => 'Sunday',
      'mon' => 'Monday',
      'tue' => 'Tuesday',
      'wed' => 'Wednesday',
      'thu' => 'Thursday',
      'fri' => 'Friday',
    ];
  }

  /**
   * Return the key representing today in WeeklyTimeField::weekDays().
   *
   * @return string
   */
  public static function today() {
    return strtolower(date('D'));
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties = [];

    $properties['time'] = DataDefinition::create('integer')
      ->setLabel(new TranslatableMarkup('Time'))
      ->setRequired(TRUE);

    $properties['length'] = DataDefinition::create('integer')
      ->setLabel(new TranslatableMarkup('Length'))
      ->setRequired(TRUE);

    foreach (WeeklyTimeField::weekDays() as $key => $label) {
      $properties[$key] = DataDefinition::create('boolean')
        // Prevent early t() calls by using the TranslatableMarkup.
        ->setLabel(new TranslatableMarkup($label))
        ->setRequired(TRUE);
    }

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $schema = [
      'columns' => [
        'time' => [
          'type' => 'int',
          'not null' => FALSE,
        ],
        'length' => [
          'type' => 'int',
          'not null' => FALSE,
        ],
      ],
    ];
    foreach (WeeklyTimeField::weekDays() as $key => $label) {
      $schema['columns'][$key] = [
        'type' => 'int',
        'size' => 'tiny',
        'default' => 0,
        'not null' => TRUE,
        'unsigned' => TRUE,
      ];
    }

    return $schema;
  }

  /**
   * {@inheritdoc}
   */
  public static function generateSampleValue(FieldDefinitionInterface $field_definition) {
    $values = [];
    $values['time'] = mt_rand(0, 48) * 30;
    $values['length'] = 60 + mt_rand(0, 2) * 15;
    foreach (WeeklyTimeField::weekDays() as $key => $label) {
      $values[$key] = mt_rand(0, 1);
    }
    return $values;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    // This time is empty if there is no time-of-day value.
    $time = $this->get('time')->getValue();
    if ($time === NULL || $time === '') {
      return TRUE;
    }

    // Since there is a time, this time is NOT empty if it is assigned to a day.
    foreach (array_keys(self::weekDays()) as $day) {
      if ($this->get($day)->getValue()) {
        return FALSE;
      }
    }

    // And empty if it is not assigned to any day.
    return TRUE;
  }

}
