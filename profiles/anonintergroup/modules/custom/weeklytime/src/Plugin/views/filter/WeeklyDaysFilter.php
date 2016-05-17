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
 * @ViewsFilter("weeklydays")
 */
class WeeklyDaysFilter extends FilterPluginBase {

  /**
   * {@inheritdoc}
   */
  protected function valueForm(&$form, FormStateInterface $form_state) {
    $form['value'] = [
      '#type' => 'select',
      '#options' => ['today' => $this->t('Today')] + WeeklyTimeField::dayOptions(),
      '#default_value' => is_array($this->value) ? $this->value : [],
      '#multiple' => $this->options['expose']['multiple'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    $table = $this->ensureMyTable();
    foreach ($this->value as $day) {
      if ($day == 'today') {
        $day = WeeklyTimeField::today();
      }
      $value = ($this->operator == '=') ? '1' : '0';
      $this->query->addWhereExpression($this->options['group'], "{$table}.field_time_{$day} {$this->operator} {$value}");
    }
  }
}
