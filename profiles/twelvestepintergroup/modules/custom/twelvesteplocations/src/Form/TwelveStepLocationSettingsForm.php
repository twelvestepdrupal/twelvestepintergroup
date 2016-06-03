<?php

/**
 * @file
 * Contains \Drupal\twelvesteplocations\Form\TwelveStepLocationSettingsForm.
 */

namespace Drupal\twelvesteplocations\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class TwelveStepLocationSettingsForm.
 *
 * @package Drupal\twelvesteplocations\Form
 *
 * @inlocation twelvesteplocations
 */
class TwelveStepLocationSettingsForm extends FormBase {
  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'TwelveStepLocation_settings';
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Empty implementation of the abstract submit class.
  }


  /**
   * Defines the settings form for Twelve Step Location entities.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   Form definition array.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['TwelveStepLocation_settings']['#markup'] = 'Settings form for Twelve Step Location entities. Manage field settings here.';
    return $form;
  }

}
