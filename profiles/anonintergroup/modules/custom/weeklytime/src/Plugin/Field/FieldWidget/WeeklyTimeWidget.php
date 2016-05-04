<?php

/**
 * @file
 * Contains \Drupal\weeklytime\Plugin\Field\FieldWidget\WeeklyTimeWidget.
 */

namespace Drupal\weeklytime\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\weeklytime\Plugin\Field\FieldType\WeeklyTimeField;

/**
 * Plugin implementation of the 'weeklytime_widget' widget.
 *
 * @FieldWidget(
 *   id = "weeklytime_widget",
 *   label = @Translation("Weekly Time Widget"),
 *   field_types = {
 *     "weeklytime"
 *   }
 * )
 */
class WeeklyTimeWidget extends WidgetBase {
  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element = [];

    // Convert stored time to HH:MM
    $time = NULL;
    if ($items[$delta]->time) {
      $hh = floor($items[$delta]->time / 60);
      $mm = $items[$delta]->time % 60;
      $time = sprintf("%02.2d:%02.2d", $hh, $mm);
    }

    $element['time'] = [
      '#type' => 'textfield',
      '#title' => t('Time'),
      '#default_value' => $time,
      '#description' => t('Time of day in 24 hour clock HH:MM'),
      '#size' => 5,
      '#maxlength' => 5,
    ];

    foreach (WeeklyTimeField::weekDays() as $key => $label) {
      $element[$key] = [
        '#type' => 'checkbox',
        '#title' => t($label),
        '#default_value' => isset($items[$delta]->$key) ? $items[$delta]->$key : FALSE,
      ];
    }

    $element['length'] = [
      '#type' => 'select',
      '#title' => t('Length'),
      '#default_value' => $items[$delta]->length,
      '#description' => t('Length of meeting'),
      '#options' => [
        60 => t('1 hour'),
        90 => t('90 minutes'),
        // @todo: support other meeting lengths. We'll want to do this in JS so that the 1 hour choice is easiest.
      ],
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    // @todo: where do we do validation in D8?

    foreach ($values as &$value) {
      $hh = substr($value['time'], 0, 2);
      $mm = substr($value['time'], 3, 2);
      $value['time'] = $hh * 60 + $mm;
    }

    return $values;
  }

}
