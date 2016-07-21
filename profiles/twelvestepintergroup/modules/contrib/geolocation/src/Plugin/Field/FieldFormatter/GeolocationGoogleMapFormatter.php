<?php

namespace Drupal\geolocation\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\geolocation\GoogleMapsDisplayTrait;

/**
 * Plugin implementation of the 'geolocation_latlng' formatter.
 *
 * @FieldFormatter(
 *   id = "geolocation_map",
 *   module = "geolocation",
 *   label = @Translation("Geolocation Google Map"),
 *   field_types = {
 *     "geolocation"
 *   }
 * )
 */
class GeolocationGoogleMapFormatter extends FormatterBase {

  use GoogleMapsDisplayTrait;

  /**
   * {@inheritdoc}
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    $settings = [];
    $settings['title'] = '';
    $settings += parent::defaultSettings();
    $settings['info_text'] = '';
    $settings += self::getGoogleMapDefaultSettings();

    return $settings;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $settings = $this->getSettings();

    $element['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Hover title'),
      '#description' => $this->t('The hover title is a tool tip that will be displayed when the mouse is paused over the map marker.'),
      '#default_value' => $settings['title'],
    ];

    $element += $this->getGoogleMapsSettingsForm($settings);

    $element['info_text'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Info text'),
      '#description' => $this->t('This text will be displayed in an "Info'
        . ' window" above the map marker. The "Info window" will be displayed by'
        . ' default unless the "Automatically show info text" format setting'
        . ' is unchecked. Leave blank if you do not wish to display an "Info'
        . ' window". See "REPLACEMENT PATTERNS" below for available replacements.'),
      '#default_value' => $settings['info_text'],
    ];

    $element['replacement_patterns'] = [
      '#type' => 'details',
      '#title' => 'Replacement patterns',
      '#description' => $this->t('The following replacement patterns are available for the "Info text" and the "Hover title" settings.'),
    ];
    $element['replacement_patterns']['native'] = [
      '#markup' => $this->t('<h4>Geolocation field data:</h4><ul><li>Latitude (%lat) or (:lat)</li><li>Longitude (%lng) or (:lng)</li></ul>'),
    ];
    // Add the token UI from the token module if present.
    $element['replacement_patterns']['token_help'] = [
      '#theme' => 'token_tree_link',
      '#prefix' => $this->t('<h4>Tokens:</h4>'),
      '#token_types' => [$this->fieldDefinition->getTargetEntityTypeId()],
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $settings = $this->getSettings();

    $summary = [];
    $summary[] = $this->t('Hover Title: @type', ['@type' => $settings['title']]);
    $summary = array_merge($summary, $this->getGoogleMapsSettingsSummary($settings));
    $summary[] = $this->t('Info Text: @type', [
      '@type' => current(explode(chr(10), wordwrap($settings['info_text'], 30))),
    ]);

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    // Add formatter settings to the drupalSettings array.
    $field_settings = $this->getGoogleMapsSettings($this->getSettings()) + $this->getSettings();
    $elements = [];
    // This is a list of tokenized settings that should have placeholders
    // replaced with contextual values.
    $tokenized_settings = [
      'info_text',
      'title',
    ];

    foreach ($items as $delta => $item) {
      // @todo: Add token support to the geolocaiton field exposing sub-fields.
      // Get token context.
      $token_context = [
        'field' => $items,
        $this->fieldDefinition->getTargetEntityTypeId() => $items->getEntity(),
      ];

      $uniqueue_id = uniqid("map-canvas-");

      $elements[$delta] = [
        '#type' => 'markup',
        '#markup' => '<div id="' . $uniqueue_id . '" class="geolocation-google-map"></div>',
        '#attached' => [
          'library' => ['geolocation/geolocation.formatter.googlemap'],
          'drupalSettings' => [
            'geolocation' => [
              'maps' => [
                $uniqueue_id => [
                  'id' => "{$uniqueue_id}",
                  'lat' => (float) $item->lat,
                  'lng' => (float) $item->lng,
                  'settings' => $field_settings,
                ],
              ],
              'google_map_api_key' => \Drupal::config('geolocation.settings')->get('google_map_api_key'),
            ],
          ],
        ],
      ];

      // Replace placeholders with token values.
      $item_settings = &$elements[$delta]['#attached']['drupalSettings']['geolocation']['maps'][$uniqueue_id]['settings'];
      array_walk($tokenized_settings, function ($v) use (&$item_settings, $token_context, $item) {
        $item_settings[$v] = \Drupal::token()->replace($item_settings[$v], $token_context);
        // TODO: Drupal does not like variables handed to t().
        $item_settings[$v] = $this->t($item_settings[$v], [
          ':lat' => (float) $item->lat,
          '%lat' => (float) $item->lat,
          ':lng' => (float) $item->lng,
          '%lng' => (float) $item->lng,
        ]);
      });

    }
    return $elements;
  }

}
