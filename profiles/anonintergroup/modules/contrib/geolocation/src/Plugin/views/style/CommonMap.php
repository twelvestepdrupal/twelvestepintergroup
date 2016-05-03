<?php

/**
 * @file
 * Contains \Drupal\geolocation\Plugin\views\style\CommonMap.
 */

namespace Drupal\geolocation\Plugin\views\style;

use Drupal\views\Plugin\views\style\StylePluginBase;
use Drupal\Core\Form\FormStateInterface;


/**
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "maps_common",
 *   title = @Translation("Geolocation - CommonMap"),
 *   help = @Translation("Display geolocations on a common map."),
 *   theme = "views_view_list",
 *   display_types = {"normal"},
 * )
 */
class CommonMap extends StylePluginBase {

  protected $usesFields = TRUE;
  protected $usesRowPlugin = TRUE;
  protected $usesRowClass = FALSE;
  protected $usesGrouping = FALSE;

  /**
   * Overrides \Drupal\views\Plugin\views\display\PathPluginBase::render().
   */
  public function render() {

    if (!empty($this->options['geolocation_field'])) {
      $geo_field = $this->options['geolocation_field'];
      $this->view->field[$geo_field]->options['exclude'] = TRUE;
    }
    else {
      // TODO: Throw some exception here, we're done.
      return [];
    }

    if (!empty($this->options['title_field'])) {
      $title_field = $this->options['title_field'];
      $this->view->field[$title_field]->options['exclude'] = TRUE;
    }

    $id = \Drupal\Component\Utility\Html::getUniqueId($this->pluginId);
    $build = [
      '#theme' => 'geolocation_common_map_display',
      '#id' => $id,
      '#attached' => [
        'library' => [
          'geolocation/geolocation.commonmap',
        ],
        'drupalSettings' => [
          'geolocation' => [
            'commonMap' => [
              'id' => $id,
            ],
          ],
        ],
      ],
    ];

    foreach ($this->view->result as $row) {
      $title = empty($title_field) ? '' : $this->view->field[$title_field]->theme($row);

      $geo_items = $this->view->field[$geo_field]->getItems($row);
      foreach ($geo_items as $delta => $item) {
        $geolocation = $item['raw'];
        $position = [
          'lat' => $geolocation->lat,
          'lng' => $geolocation->lng,
        ];

        $build['#locations'][] = [
          '#theme' => 'geolocation_common_map_location',
          '#content' => $this->view->rowPlugin->render($row),
          '#title' => $title,
          '#position' => $position,
        ];
      }
    }

    $centre = [
      'lat' => 0,
      'lng' => 0,
    ];
    switch ($this->options['centre']) {
      case 'fixed_value':
        $centre = [
          'lat' => (float)$this->options['centre_fixed_values']['latitude'],
          'lng' => (float)$this->options['centre_fixed_values']['longitude'],
        ];
        break;

      case 'first_row':
      default:
        if (!empty($build['#locations'][0]['#position'])) {
          $centre = $build['#locations'][0]['#position'];
        }
        break;
    }
    $build['#centre'] = $centre;

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();

    $options['geolocation_field'] = ['default' => ''];
    $options['title_field'] = ['default' => ''];
    $options['centre'] = ['default' => 'first_row'];
    $options['centre_fixed_values'] = ['default' => [
      'latitude' => 0,
      'longitude' => 0,
    ]];

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);

    $labels = $this->displayHandler->getFieldLabels();
    $fieldMap = \Drupal::entityManager()->getFieldMap();
    $geo_options = [];
    $title_options = [];
    $fields = $this->displayHandler->getOption('fields');
    foreach ($fields as $field_name => $field) {
      if ($field['plugin_id'] == 'geolocation_field') {
        $geo_options[$field_name] = $labels[$field_name];
      }
      if (
        $field['plugin_id'] == 'field'
        && !empty($field['entity_type'])
        && !empty($field['entity_field'])
      ) {
        if (
          !empty($fieldMap[$field['entity_type']][$field['entity_field']]['type'])
          && $fieldMap[$field['entity_type']][$field['entity_field']]['type'] == 'geolocation'
        ) {
          $geo_options[$field_name] = $labels[$field_name];
        }
      }
      if ($field['type'] == 'string') {
        $title_options[$field_name] = $labels[$field_name];
      }
    }
    $form['geolocation_field'] = [
      '#title' => $this->t('Geolocation source field'),
      '#type' => 'select',
      '#default_value' => $this->options['geolocation_field'],
      '#description' => $this->t("The source of geodata for each entity."),
      '#options' => $geo_options,
    ];

    $form['title_field'] = [
      '#title' => $this->t('Title source field'),
      '#type' => 'select',
      '#default_value' => $this->options['title_field'],
      '#description' => $this->t("The source of the title for each entity. Must be string"),
      '#options' => $title_options,
    ];

    $form['centre'] = [
      '#title' => $this->t('Source for centre coordinates'),
      '#type' => 'radios',
      '#default_value' => $this->options['centre'],
      '#description' => $this->t("How to determine the centre of the map."),
      '#options' => [
        'first_row' => $this->t('Use first row as centre.'),
        'none' => $this->t('Solely rely on Google fitBounds function to include all locations.'),
        'fixed_value' => $this->t('Provide fixed latitude and longitude.'),
      ],
    ];
    $form['centre_fixed_values'] = [
      '#title' => $this->t('Fixed values for centre'),
      '#type' => 'container',
      'latitude' => [
        '#type' => 'textfield',
        '#title' => t('Latitude'),
        '#default_value' => $this->options['centre_fixed_values']['latitude'],
        '#size' => 60,
        '#maxlength' => 128,
      ],
      'longitude' => [
        '#type' => 'textfield',
        '#title' => t('Longitude'),
        '#default_value' => $this->options['centre_fixed_values']['longitude'],
        '#size' => 60,
        '#maxlength' => 128,
      ],
      '#description' => $this->t("The source of geodata for each entity. Must be string"),
      '#states' => [
        'visible' => [
          ':input[name="style_options[centre]"]' => ['value' => 'fixed_value'],
        ],
      ],
    ];
  }
}
