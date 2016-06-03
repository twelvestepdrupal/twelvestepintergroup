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
    $form['value'] = [
      '#type' => 'select',
      '#options' => WeeklyTimeField::timeOptions(),
      '#default_value' => is_numeric($this->value) ? $this->value : [],
      '#multiple' => $this->options['expose']['multiple'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    $table = $this->ensureMyTable();
    $operator = $this->operator == '=' ? 'IN' : 'NOT IN';
    $values = is_array($this->value) ? $this->value : [$this->value];
    $value = implode(', ', $values);
    $this->query->addWhereExpression($this->options['group'], "{$table}.field_time_time {$operator} ({$value})");
  }

}
