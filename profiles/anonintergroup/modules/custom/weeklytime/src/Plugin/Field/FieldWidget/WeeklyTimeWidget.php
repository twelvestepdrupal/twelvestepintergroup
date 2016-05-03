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
    $value = NULL;
    if ($items[$delta]->value) {
      $hh = floor($items[$delta]->value / 60);
      $mm = $items[$delta]->value % 60;
      $value = sprintf("%02.2d:%02.2d", $hh, $mm);
    }

    $element['value'] = [
      '#type' => 'textfield',
      '#title' => t('Time'),
      '#default_value' => $value,
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

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    // @todo: where do we do validation in D8?

    foreach ($values as &$value) {
      $hh = substr($value['value'], 0, 2);
      $mm = substr($value['value'], 3, 2);
      $value['value'] = $hh * 60 + $mm;
    }

    return $values;
  }

}
