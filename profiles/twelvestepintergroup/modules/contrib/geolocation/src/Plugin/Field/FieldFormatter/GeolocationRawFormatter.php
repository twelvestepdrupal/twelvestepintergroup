<?php

namespace Drupal\geolocation\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'geolocation_raw' formatter.
 *
 * @FieldFormatter(
 *   id = "geolocation_raw",
 *   module = "geolocation",
 *   label = @Translation("Geolocation Raw"),
 *   field_types = {
 *     "geolocation"
 *   }
 * )
 */
class GeolocationRawFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return array(
      'value' => 'lat',
    ) + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element['value'] = array(
      '#title' => t('Raw value'),
      '#type' => 'radios',
      '#options' => array(
        'lat' => t('Latitude'),
        'lng' => t('Longitude'),
        'lat_sin' => t('Precalculated latitude sine'),
        'lat_cos' => t('Precalculated latitude cosine'),
        'lng_rad' => t('Precalculated radian longitude'),
      ),
      '#default_value' => $this->getSetting('value'),
      '#description' => t('Renders a single raw value.'),
      '#required' => TRUE,
    );
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = array();
    $summary[] = t('Raw value: @item', array('@item' => $this->getSetting('value')));
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = array();

    foreach ($items as $delta => $item) {
      $element[$delta] = array(
        '#markup' => $item->{$this->settings['value']},
      );
    }

    return $element;
  }

}
