<?php

namespace Drupal\geolocation\Plugin\views\filter;

use Drupal\Core\Form\FormStateInterface;
use Drupal\geolocation\GeolocationCore;
use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\Plugin\views\filter\NumericFilter;
use Drupal\views\ViewExecutable;
use Drupal\views\Plugin\views\query\Sql;

/**
 * Filter handler for search keywords.
 *
 * @ingroup views_filter_handlers
 *
 * @ViewsFilter("geolocation_filter_proximity")
 */
class ProximityFilter extends NumericFilter {

  /**
   * The field alias.
   *
   * @var string
   */
  protected $fieldAlias;

  /**
   * The query expression.
   *
   * @var string
   */
  protected $queryFragment;

  /**
   * {@inheritdoc}
   */
  public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL) {
    parent::init($view, $display, $options);

    // Set the field alias.
    $this->fieldAlias = $this->options['id'] . '_filter';
  }

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();

    $options += [
      'expose_units' => ['default' => 0],
    ];
    $options['value']['contains'] += [
      'lat' => ['default' => ''],
      'lng' => ['default' => ''],
      'units' => ['default' => 'km'],
    ];

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  protected function valueForm(&$form, FormStateInterface $form_state) {

    parent::valueForm($form, $form_state);

    // Get the value element.
    if (isset($form['value']['#tree'])) {
      $value_element = &$form['value'];
    }
    else {
      $value_element = &$form;
    }
    // Set the value title to Distance.
    $value_element['value']['#title'] = $this->t('Distance');
    $value_element['value']['#weight'] = 30;
    // Create the units element if not exposed or exposing units is enabled.
    $units = ($exposed = $form_state->get('exposed') && !$this->options['expose_units'])
      // Don't add units if exposed and exposed units is not enabled.
      ? []
      : [
        'units' => [
          '#type' => 'select',
          '#default_value' => !empty($this->value['units']) ? $this->value['units'] : '',
          '#weight' => 40,
          '#options' => [
            'mile' => $this->t('Miles'),
            'km' => $this->t('Kilometers'),
          ],
        ],
      ];
    // Add the Latitude and Longitude elements.
    $value_element += [
      'lat' => [
        '#type' => 'textfield',
        '#title' => $this->t('Latitude'),
        '#default_value' => !empty($this->value['lat']) ? $this->value['lat'] : '',
        '#weight' => 10,
      ],
      'lng' => [
        '#type' => 'textfield',
        '#title' => $this->t('Longitude'),
        '#default_value' => !empty($this->value['lng']) ? $this->value['lng'] : '',
        '#weight' => 20,
      ],
    ] + $units;

    if (!$form_state->get('exposed')) {
      $form['expose_units'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Expose Units?'),
        '#default_value' => !empty($this->options['expose_units']) ? $this->options['expose_units'] : FALSE,
        '#states' => [
          'visible' => [
            [
              'input[name="options[expose_button][checkbox][checkbox]"]' => ['checked' => TRUE],
            ],
          ],
        ],
        '#weight' => -1000,
      ];
    }
  }

  /**
   * {@inheritdoc}
   */
  public function defaultExposeOptions() {
    parent::defineOptions();

    $this->options['expose']['label'] = $this->t('Distance (in @units)', ['@units' => $this->value['units'] === 'km' ? 'kilometers' : 'miles']);
  }

  /**
   * {@inheritdoc}
   */
  public function acceptExposedInput($input) {
    // We need to add out options to what will be the value array.
    $value = &$input[$this->options['expose']['identifier']];
    $value = [
      'value' => $value,
      'lat' => !empty($input['lat']) ? $input['lat'] : $this->value['lat'],
      'lng' => !empty($input['lng']) ? $input['lng'] : $this->value['lng'],
      'units' => !empty($input['units']) ? $input['units'] : $this->value['units'],
    ];

    $rc = parent::acceptExposedInput($input);

    return $rc;
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    // Get the field alias.
    $lat = $this->value['lat'];
    $lng = $this->value['lng'];

    // Get the earth radius from the units.
    $earth_radius = $this->value['units'] === 'mile' ? GeolocationCore::EARTH_RADIUS_MILE : GeolocationCore::EARTH_RADIUS_KM;
    // Build the query expression.
    $this->queryFragment = \Drupal::service('geolocation.core')->getProximityQueryFragment($this->ensureMyTable(), $this->realField, $lat, $lng, $earth_radius);
    // Get operator info.
    $info = $this->operators();
    // Create a placeholder.
    $field = $this->placeholder();
    // Make sure a callback exists and add a where expression for the chosen
    // operator.
    if (!empty($info[$this->operator]['method']) && method_exists($this, $info[$this->operator]['method'])) {
      $this->{$info[$this->operator]['method']}($field);
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function opBetween($field) {
    if (!($this->query instanceof Sql)) {
      return;
    }
    if ($this->operator == 'between') {
      $this->query->addWhereExpression($this->options['group'], "{$this->queryFragment} BETWEEN {$field}_min AND {$field}_max",
        [
          $field . '_min' => $this->value['min'],
          $field . '_max' => $this->value['max'],
        ]
      );
    }
    else {
      $this->query->addWhereExpression($this->options['group'], "{$this->queryFragment} <= {$field}_min OR {$field} >= {$field}_max",
        [
          $field . '_min' => $this->value['min'],
          $field . '_max' => $this->value['max'],
        ]
      );
    }

  }

  /**
   * {@inheritdoc}
   */
  protected function opSimple($field) {
    if (!($this->query instanceof Sql)) {
      return;
    }
    $this->query->addWhereExpression($this->options['group'], "{$this->queryFragment} {$this->operator} {$field}",
      [
        $field => $this->value['value'],
      ]
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function opEmpty($field) {
    if (!($this->query instanceof Sql)) {
      return;
    }
    if ($this->operator == 'empty') {
      $operator = "IS NULL";
    }
    else {
      $operator = "IS NOT NULL";
    }

    $this->query->addWhereExpression($this->options['group'], "{$this->queryFragment} {$operator}");
  }

  /**
   * {@inheritdoc}
   */
  protected function opRegex($field) {
    if (!($this->query instanceof Sql)) {
      return;
    }
    $this->query->addWhereExpression($this->options['group'], "{$this->queryFragment} 'REGEXP' {$field}",
      [
        $field => $this->value['value'],
      ]
    );
  }

}
