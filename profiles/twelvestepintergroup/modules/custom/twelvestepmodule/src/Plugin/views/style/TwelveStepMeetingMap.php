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
      }
      else {
        // Remember this row as the first use of this location.
        $map[$location_id] = $delta;
        $aggregated_row = $row;
      }

      // Aggregate the row's time and format values.
      $aggregated_row->twelvestepmeeting__entities[] = clone $row->_entity;
    }

    $build = parent::render();

    return $build;
  }
}
