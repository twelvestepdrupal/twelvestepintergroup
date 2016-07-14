<?php

namespace Drupal\geolocation\Plugin\views\style;

use Drupal\views\Plugin\views\style\StylePluginBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\geolocation\Plugin\views\field\GeolocationField;
use Drupal\geolocation\GoogleMapsDisplayTrait;

/**
 * Allow to display several field items on a common map.
 *
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

  use GoogleMapsDisplayTrait;

  protected $usesFields = TRUE;
  protected $usesRowPlugin = TRUE;
  protected $usesRowClass = FALSE;
  protected $usesGrouping = FALSE;

  /**
   * {@inheritdoc}
   */
  public function render() {

    if (!empty($this->options['geolocation_field'])) {
      $geo_field = $this->options['geolocation_field'];
      $this->view->field[$geo_field]->options['exclude'] = TRUE;
    }
    else {
      \Drupal::logger('geolocation')->error("The geolocation common map views style was called without a geolocation field defined in the views style settings.");
      return [];
    }

    if (!empty($this->options['title_field'])) {
      $title_field = $this->options['title_field'];
      $this->view->field[$title_field]->options['exclude'] = TRUE;
    }

    $id = uniqid($this->pluginId);

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
              $id => [
                'settings' => $this->getGoogleMapsSettings($this->options),
                'google_map_api_key' => \Drupal::config('geolocation.settings')->get('google_map_api_key'),
              ],
            ],
            'google_map_api_key' => \Drupal::config('geolocation.settings')->get('google_map_api_key'),
          ],
        ],
      ],
    ];

    foreach ($this->view->result as $row) {
      if (!empty($title_field)) {
        $title_field_handler = $this->view->field[$title_field];
        $title_build = array(
          '#theme' => $title_field_handler->themeFunctions(),
          '#view' => $title_field_handler->view,
          '#field' => $title_field_handler,
          '#row' => $row,
        );
      }

      if ($this->view->field[$geo_field] instanceof GeolocationField) {
        /** @var \Drupal\geolocation\Plugin\views\field\GeolocationField $geolocation_field */
        $geolocation_field = $this->view->field[$geo_field];
        $geo_items = $geolocation_field->getItems($row);
      }
      else {
        return $build;
      }

      foreach ($geo_items as $delta => $item) {
        $geolocation = $item['raw'];
        $position = [
          'lat' => $geolocation->lat,
          'lng' => $geolocation->lng,
        ];

        $build['#locations'][] = [
          '#theme' => 'geolocation_common_map_location',
          '#content' => $this->view->rowPlugin->render($row),
          '#title' => empty($title_build) ? '' : $title_build,
          '#position' => $position,
        ];
      }
    }

    $centre = NULL;
    $fitbounds = FALSE;
    if (!is_array($this->options['centre'])) {
      return $build;
    }

    foreach ($this->options['centre'] as $id => $option) {
      // Ignore if not enabled.
      if (empty($option['enable'])) {
        continue;
      }

      // Ignore if fitBounds is enabled, as it will supersede any other option.
      if ($fitbounds) {
        break;
      }

      // Ignore if center is already set.
      if (!empty($centre['lat']) && !empty($centre['lng'])) {
        break;
      }

      switch ($id) {

        case 'fixed_value':
          $centre = [
            'lat' => (float) $option['settings']['latitude'],
            'lng' => (float) $option['settings']['longitude'],
          ];
          break;

        case (preg_match('/proximity_filter_*/', $id) ? TRUE : FALSE):
          $filter_id = substr($id, 17);
          /** @var \Drupal\geolocation\Plugin\views\filter\ProximityFilter $handler */
          $handler = $this->displayHandler->getHandler('filter', $filter_id);
          if ($handler->value['lat'] && $handler->value['lng']) {
            $centre = [
              'lat' => (float) $handler->value['lat'],
              'lng' => (float) $handler->value['lng'],
            ];
          }
          break;

        case 'first_row':
          if (!empty($build['#locations'][0]['#position'])) {
            $centre = $build['#locations'][0]['#position'];
          }
          break;

        case 'fit_bounds':
          // fitBounds will only work when at least one result is available.
          if (!empty($build['#locations'][0]['#position'])) {
            $fitbounds = TRUE;
          }
          break;

      }
    }

    if (!empty($centre)) {
      $build['#centre'] = $centre ?: ['lat' => 0, 'lng' => 0];
    }
    $build['#fitbounds'] = $fitbounds;

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();

    $options['geolocation_field'] = ['default' => ''];
    $options['title_field'] = ['default' => ''];
    $options['centre'] = ['default' => ''];

    foreach (self::getGoogleMapDefaultSettings() as $key => $setting) {
      $options[$key] = ['default' => $setting];
    }

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);

    $labels = $this->displayHandler->getFieldLabels();
    $fieldMap = \Drupal::service('entity_field.manager')->getFieldMap();
    $geo_options = [];
    $title_options = [];
    $filters = $this->displayHandler->getOption('filters');
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

      if (!empty($field['type']) && $field['type'] == 'string') {
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
      '#description' => $this->t("The source of the title for each entity. Field type must be 'string'."),
      '#options' => $title_options,
    ];

    /*
     * Centre handling.
     */
    $options = [
      'fit_bounds' => $this->t('Automatically fit map bounds to results. Disregards any set center or zoom.'),
      'first_row' => $this->t('Use first row as centre.'),
      'fixed_value' => $this->t('Provide fixed latitude and longitude.'),
    ];

    foreach ($filters as $filter_name => $filter) {
      if (empty($filter['plugin_id']) || $filter['plugin_id'] != 'geolocation_filter_proximity') {
        continue;
      }
      /** @var \Drupal\geolocation\Plugin\views\filter\ProximityFilter $proximity_filter_handler */
      $proximity_filter_handler = $this->displayHandler->getHandler('filter', $filter_name);
      $options['proximity_filter_' . $filter_name] = $proximity_filter_handler->adminLabel();
    }

    $form['centre'] = [
      '#type' => 'table',
      '#prefix' => t('Please note: Each option will, if it can be applied, supersede any following option.'),
      '#header' => [
        t('Enable'),
        t('Option'),
        t('settings'),
        [
          'data' => t('Settings'),
          'colspan' => '1',
        ],
      ],
      '#attributes' => ['id' => 'geolocation-centre-options'],
      '#tabledrag' => [
        [
          'action' => 'order',
          'relationship' => 'sibling',
          'group' => 'geolocation-centre-option-weight',
        ],
      ],
    ];

    foreach ($options as $id => $label) {
      $weight = isset($this->options['centre'][$id]['weight']) ? $this->options['centre'][$id]['weight'] : 0;
      $form['centre'][$id]['#weight'] = $weight;

      $form['centre'][$id]['enable'] = [
        '#type' => 'checkbox',
        '#default_value' => isset($this->options['centre'][$id]['enable']) ? $this->options['centre'][$id]['enable'] : TRUE,
      ];

      $form['centre'][$id]['option'] = [
        '#markup' => $label,
      ];

      // Add tabledrag supprt.
      $form['centre'][$id]['#attributes']['class'][] = 'draggable';
      $form['centre'][$id]['weight'] = [
        '#type' => 'weight',
        '#title' => t('Weight for @option', ['@option' => $label]),
        '#title_display' => 'invisible',
        '#size' => 4,
        '#default_value' => $weight,
        '#attributes' => ['class' => ['geolocation-centre-option-weight']],
      ];
    }

    $form['centre']['fixed_value']['settings'] = [
      '#type' => 'container',
      'latitude' => [
        '#type' => 'textfield',
        '#title' => t('Latitude'),
        '#default_value' => isset($this->options['centre']['fixed_value']['settings']['latitude']) ? $this->options['centre']['fixed_value']['settings']['latitude'] : '',
        '#size' => 60,
        '#maxlength' => 128,
      ],
      'longitude' => [
        '#type' => 'textfield',
        '#title' => t('Longitude'),
        '#default_value' => isset($this->options['centre']['fixed_value']['settings']['longitude']) ? $this->options['centre']['fixed_value']['settings']['longitude'] : '',
        '#size' => 60,
        '#maxlength' => 128,
      ],
      '#states' => [
        'visible' => [
          ':input[name="style_options[centre][fixed_value][enable]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    uasort($form['centre'], 'Drupal\Component\Utility\SortArray::sortByWeightProperty');

    /*
     * Additional map settings.
     */
    $form += $this->getGoogleMapsSettingsForm($this->options);
  }

  /**
   * {@inheritdoc}
   */
  public function validateOptionsForm(&$form, FormStateInterface $form_state) {
    parent::validateOptionsForm($form, $form_state);
    $this->validateGoogleMapsSettingsForm($form, $form_state, 'style_options');
  }

}
