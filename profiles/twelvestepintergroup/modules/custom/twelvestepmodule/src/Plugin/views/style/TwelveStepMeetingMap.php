<?php

/**
 * @file
 * Contains \Drupal\twelvestepmodule\Plugin\views\style\TwelveStepMeetingMap.
 */

namespace Drupal\twelvestepmodule\Plugin\views\style;

use Drupal\geolocation\Plugin\views\style\CommonMap;

/**
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "maps_twelvestepmeeting",
 *   title = @Translation("Geolocation - TwelveStepMeetingMap"),
 *   help = @Translation("Display TwelveStepMeeting geolocations on a common map."),
 *   theme = "views_view_list",
 *   display_types = {"normal"},
 * )
 */
class TwelveStepMeetingMap extends CommonMap {

  /**
   * Overrides \Drupal\views\Plugin\views\display\PathPluginBase::render().
   */
  public function render() {
    $map = [];
    foreach ($this->view->result as $delta => $row) {
      /** @var \Drupal\views\ResultRow $row */
      // @todo: is there a better way to get this value?
      $location_id = $row->twelvesteplocation_twelvestepmeeting__field_location_id;
      if (isset($map[$location_id])) {
        // Remember the row's original entity.
        $original = $row->_entity;

        // Remove this row from the results.
        unset($this->view->result[$delta]);

        // Add this rows values to field_time and field_format.
        $delta = $map[$location_id];
        $aggregated_row = $this->view->result[$delta];
        foreach (['field_time', 'field_format'] as $field_name) {
          $aggregated_items = $aggregated_row->_entity->get($field_name);
          $original_item = $original->get($field_name)->get(0);
          if ($original_item) {
//          $aggregated_items->appendItem($original_item);
          }
        }
      }
      else {
        // Remember this row as the first use of this location.
        $map[$location_id] = $delta;
      }
    }

    $build = parent::render();

    return $build;
  }
}
