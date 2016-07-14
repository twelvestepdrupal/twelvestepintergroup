<?php

namespace Drupal\geolocation\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'geolocation_latlng' widget.
 *
 * @FieldWidget(
 *   id = "geolocation_latlng",
 *   label = @Translation("Geolocation Lat/Lng"),
 *   field_types = {
 *     "geolocation"
 *   }
 * )
 */
class GeolocationLatlngWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

    $element['lat'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Latitude'),
      '#empty_value' => '',
      '#default_value' => (isset($items[$delta]->lat)) ? $items[$delta]->lat : NULL,
      '#maxlength' => 255,
      '#description' => $this->t('Latitude'),
      '#required' => $this->fieldDefinition->isRequired(),
    );

    $element['lng'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Longitude'),
      '#empty_value' => '',
      '#default_value' => (isset($items[$delta]->lng)) ? $items[$delta]->lng : NULL,
      '#maxlength' => 255,
      '#description' => $this->t('Longitude'),
      '#required' => $this->fieldDefinition->isRequired(),
    );

    return $element;
  }

}
