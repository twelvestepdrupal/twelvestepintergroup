<?php

/**
 * @file
 * Contains \Drupal\office_hours\Plugin\Field\FieldWidget\OfficeHoursWidgetBase.
 */

namespace Drupal\office_hours\Plugin\Field\FieldWidget;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;

/**
 * Base class for the 'office_hours_*' widgets.
 */
class OfficeHoursWidgetBase extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    $settings = array(
    //  'format' => array('name' => 'name',),
      'date_element_type' => 'datelist',
    ) + parent::defaultSettings();

    return $settings;
  }

  /**
   * Returns the array of field settings, added with hours data.
   *
   * @return array
   *   The array of settings.
   */
  public function getFieldSettings() {
    $settings = parent::getFieldSettings();
    return $settings;
  }
  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element = parent::settingsForm($form, $form_state);

    $element['date_element_type'] = array(
      '#type' => 'select',
      '#title' => t('Time element type'),
      '#description' => t('Select the widget type for inputing time.'),
      '#options' => array(
        'datelist' => 'Select list',
        'datetime' => 'HTML5 time input',
      ),
      '#default_value' => $this->getSetting('date_element_type'),
    );
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = parent::settingsSummary();
    $summary[] = t('Time element type: @date_element_type', array('@date_element_type' => $this->getSetting('date_element_type')));
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
//  public function errorElement(array $element, ConstraintViolationInterface $error, array $form, FormStateInterface $form_state) {
//    return $element;
//  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    return $element;
  }

}
