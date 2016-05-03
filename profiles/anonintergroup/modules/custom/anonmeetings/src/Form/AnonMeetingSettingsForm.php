<?php

/**
 * @file
 * Contains \Drupal\anonmeetings\Form\AnonMeetingSettingsForm.
 */

namespace Drupal\anonmeetings\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class AnonMeetingSettingsForm.
 *
 * @package Drupal\anonmeetings\Form
 *
 * @ingroup anonmeetings
 */
class AnonMeetingSettingsForm extends FormBase {
  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'AnonMeeting_settings';
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
   * Defines the settings form for Anonymous 12 Step Meeting entities.
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
    $form['AnonMeeting_settings']['#markup'] = 'Settings form for Anonymous 12 Step Meeting entities. Manage field settings here.';
    return $form;
  }

}
