<?php

/**
 * @file
 * Contains \Drupal\anongroups\Form\AnonGroupSettingsForm.
 */

namespace Drupal\anongroups\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class AnonGroupSettingsForm.
 *
 * @package Drupal\anongroups\Form
 *
 * @ingroup anongroups
 */
class AnonGroupSettingsForm extends FormBase {
  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'AnonGroup_settings';
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
   * Defines the settings form for Anonymous 12 Step Group entities.
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
    $form['AnonGroup_settings']['#markup'] = 'Settings form for Anonymous 12 Step Group entities. Manage field settings here.';
    return $form;
  }

}
