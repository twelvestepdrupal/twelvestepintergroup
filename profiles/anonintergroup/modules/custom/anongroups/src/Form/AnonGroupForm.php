<?php

/**
 * @file
 * Contains \Drupal\anongroups\Form\AnonGroupForm.
 */

namespace Drupal\anongroups\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\Language;

/**
 * Form controller for Anonymous 12 Step Group edit forms.
 *
 * @ingroup anongroups
 */
class AnonGroupForm extends ContentEntityForm {
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\anongroups\Entity\AnonGroup */
    $form = parent::buildForm($form, $form_state);
    $entity = $this->entity;

    $form['langcode'] = array(
      '#title' => $this->t('Language'),
      '#type' => 'language_select',
      '#default_value' => $entity->langcode->value,
      '#languages' => Language::STATE_ALL,
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submit(array $form, FormStateInterface $form_state) {
    // Build the entity object from the submitted values.
    $entity = parent::submit($form, $form_state);

    return $entity;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;
    $status = $entity->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Anonymous 12 Step Group.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Anonymous 12 Step Group.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.anongroup.edit_form', ['anongroup' => $entity->id()]);
  }

}
