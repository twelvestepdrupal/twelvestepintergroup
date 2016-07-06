<?php

namespace Drupal\twelvestepmodule\Plugin\views\row;

use Drupal\rest\Plugin\views\row\DataEntityRow;
use Drupal\twelvestepmodule\Entity\TwelveStepMeetingInterface;
use Drupal\weeklytime\Plugin\Field\FieldType\WeeklyTimeField;
use Drupal\Core\Field\FieldItemList;

/**
 * Plugin which displays entities as raw data.
 *
 * @ingroup views_row_plugins
 *
 * @ViewsRow(
 *   id = "meeting_guide",
 *   title = @Translation("Meeting guide"),
 *   help = @Translation("Use meetings entities as row data in meeting guide API format, see https://meetingguide.org/api."),
 *   display_types = {"data"}
 * )
 */
class MeetingGuideRow extends DataEntityRow {

  /**
   * {@inheritdoc}
   */
  public function render($row) {
    $output = [];
    
    /** @var TwelveStepMeeting $meeting */
    $meeting = $this->getEntityTranslation($row->_entity, $row);
    $output['name'] = $meeting->getName();
//  $output['slug'] = $meeting->path->value;
    $output['updated'] = format_date($meeting->changed->value);
//  $output['url'] = $meeting->url->value;

    /** @var FieldItemList $field_format */
    $field_format = $meeting->get('field_format');
    $output['types'] = array_map(function($item) {
      return $item['value'];
    }, $field_format->getValue());

    /** @var FieldItemList $field_time */
    $field_time = $meeting->get('field_time');
    $output['time'] = $field_time->time;
    $output['days'] = [];
    foreach (array_keys(WeeklyTimeField::dayOptions()) as $day) {
      if ($field_time->{$day}) {
        $output['days'][] = $day;
      }
    }

    $location_id = $meeting->get('field_location')->target_id;
    if ($location_id) {
      /** @var TwelveStepLocation $location */
      $location = entity_load('twelvesteplocation', $location_id);
      $output['location'] = $location->getName();
//    $output['location_slug'] = $location->path->value;
      /** @var FieldItemList $field_address */
      $field_address = $location->get('field_address')->first();
      if ($field_address) {
        $output['address'] = $field_address->address_line1;
        $output['city'] = $field_address->locality;
        $output['state'] = $field_address->administrative_area;
        $output['postal_code'] = $field_address->postal_code;
        $output['country'] = $field_address->country_code;
      }

      /** @var FieldItemList $field_coordinates */
      $field_coordinates = $location->get('field_coordinates')->first();
      if ($field_coordinates) {
        $output['latitude'] = $field_coordinates->lat;
        $output['longitude'] = $field_coordinates->lng;
      }
    }

    return $output;
  }

}
