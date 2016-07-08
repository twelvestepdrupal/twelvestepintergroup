<?php

/**
 * Plugin display TwelveStepMeeting fields, plus aggregated time and format.
 *
 * @ingroup views_row_plugins
 *
 * @ViewsRow(
 *   id = "twelvestepmeeting",
 *   title = @Translation("Twelve Step Meeting Fields"),
 *   help = @Translation("Twelve Step Meetings Fields with aggregated time and format from the Twelve Step Meeting Map style."),
 *   display_types = {"normal"}
 * )
 */
namespace Drupal\twelvestepmodule\Plugin\views\row;

use Drupal\views\Plugin\views\row\Fields;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Component\Utility\SortArray;

/**
 * The TwelveStepMeeting 'fields' row plugin.
 *
 * This extends the Fields plugin by adding a combined time and format field
 * when the TwelveStepMeetingMap is used.
 *
 * The field item delta's are matched to create a single value. For example,
 *
 *     field_time + field_format    = time_and_format
 * [0] "9am"      + "discussion"    = "9am discussion"
 * [1] "2pm"      + "bigbook, open" = "2pm bigbook, open"
 * [2] "8pm"      + "closed, mens"  = "8pm closed, mens"
 *
 * @ingroup views_row_plugins
 *
 * @ViewsRow(
 *   id = "fields_twelvestepmeeting",
 *   title = @Translation("Fields - Twelve Step Meeting"),
 *   help = @Translation("Displays the Twelve Step Meeting fields with aggregated time and format from the Twelve Step Meeting Map style."),
 *   theme = "views_view_fields",
 *   display_types = {"normal"}
 * )
 *
 * @todo: create a views field instead?
 */
class TwelveStepMeetingRow extends Fields {

  /**
   * {@inheritdoc}
   */
  public function render($row) {
    if (isset($row->twelvestepmeeting__entities)) {
      // Hide the fields from the parent rendering.
      $row->_entity->set('field_time', NULL);
      $row->_entity->set('field_format', NULL);

      // Add time and format one row at a time.
      $items = [];
      foreach ($row->twelvestepmeeting__entities as $entity) {
        // @todo: do this with theming and not by rendering the fields.
        $output = self::renderField($entity, 'field_time') . ' ' . self::renderField($entity, 'field_format');
        $output = preg_replace('/\s+/', ' ', strip_tags($output));
        // Use the array index to weight.
        $weight = $entity->get('field_time')->get(0)->getTimeValue();
        while (isset($items[$weight])) {
          $weight += 0.0001;
        }
        $items[$weight] = $output;
      }
      // @todo: use uasort() #weight and SortArray::sortByWeightElement().
      ksort($items, SORT_NUMERIC);
      $build['time_and_format'] = [
        '#theme' => 'item_list',
        '#items' => $items,
        '#weight' => 1,
      ];
    }

    $build['fields'] = parent::render($row);

    return $build;
  }

  /**
   * Render the field.
   *
   * @todo: is there a better way to do this?
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   * @param $field_name
   *
   * @return string
   */
  static protected function renderField(EntityInterface $entity, $field_name) {
    $output = $entity->get($field_name)->view(['label' => 'hidden']);
    return drupal_render($output);
  }

}
