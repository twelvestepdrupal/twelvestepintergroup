<?php

namespace Drupal\weeklytime\Plugin\views\filter;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\filter\FilterPluginBase;
use Drupal\weeklytime\Plugin\Field\FieldType\WeeklyTimeField;

/**
 * Filter to handle dates stored as a weekly time field.
 *
 * @ingroup views_filter_handlers
 *
 * @ViewsFilter("weeklytime")
 */
class WeeklyTimeFilter extends FilterPluginBase {

  /**
   * {@inheritdoc}
   */
  protected function valueForm(&$form, FormStateInterface $form_state) {
    if (!empty($this->value['time'])) {
      $time_default = $this->value['time'];
    }
    else {
      $time_default = WeeklyTimeField::now();
    }

    if (!empty($this->value['days'])) {
      $days_default = $this->value['days'];
    }
    else {
      $days_default = [WeeklyTimeField::today()];
    }

    $form['time'] = [
      '#type' => 'select',
      '#options' => WeeklyTimeField::timeOptions(),
      '#default_value' => $time_default,
      '#multiple' => TRUE,
    ];

    $form['days'] = [
      '#type' => 'select',
      '#options' => WeeklyTimeField::weekDays(),
      '#default_value' => $days_default,
      '#multiple' => TRUE,
    ];

    parent::valueForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function opSimple($field) {
    // @todo:
  }

}
