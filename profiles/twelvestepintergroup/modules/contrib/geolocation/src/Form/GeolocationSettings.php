<?php

namespace Drupal\geolocation\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements the GeolocationGoogleMapAPIkey form controller.
 *
 * @see \Drupal\Core\Form\FormBase
 */
class GeolocationSettings extends ConfigFormBase {

  /**
   * Build the GeolocationSettings form.
   *
   * @param array $form
   *   Default form array structure.
   * @param FormStateInterface $form_state
   *   Object containing current form state.
   *
   * @return array
   *   The render array defining the elements of the form.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = \Drupal::config('geolocation.settings');
    $form['google_map_api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Google Maps API key'),
      '#default_value' => $config->get('google_map_api_key'),
      '#description' => $this->t('This is not required to use maps, but enables higher usage quota and statistics. Using the <a href="https://console.developers.google.com/apis">Google API Manager</a>, you can enable the <em>Google Maps JavaScript API</em>. That will create (or reuse) a <em>Browser key</em> which you can paste here.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * Getter method for Form ID.
   *
   * @return string
   *   The unique ID of the form defined by this class.
   */
  public function getFormId() {
    return 'geolocation_settings';
  }

  /**
   * Return the editable config names.
   *
   * @return array
   *   The config names.
   */
  protected function getEditableConfigNames() {
    return [
      'geolocation.settings',
    ];
  }

  /**
   * Implements a form submit handler.
   *
   * @param array $form
   *   The render array of the currently built form.
   * @param FormStateInterface $form_state
   *   Object describing the current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    /** @var \Drupal\Core\Config\Config $config */
    $config = \Drupal::service('config.factory')->getEditable('geolocation.settings');
    $config->set('google_map_api_key', $form_state->getValue('google_map_api_key'));
    $config->save();
  }

}
