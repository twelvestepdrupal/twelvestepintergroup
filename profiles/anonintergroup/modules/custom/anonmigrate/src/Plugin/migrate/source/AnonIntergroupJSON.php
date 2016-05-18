<?php

namespace Drupal\anonmigrate\Plugin\migrate\source;

use Drupal\anonmigrate\Plugin\migrate\source\AnonIntergroupSourceTrait;
use Drupal\weeklytime\Plugin\Field\FieldType\WeeklyTimeField;
use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;
use Drupal\Component\Serialization\Json;
use Drupal\migrate\Row;

/**
 * Generic source for AnonIntergroup JSON.
 *
 * @code
 * source:
 *   plugin: anonintergroup_json
 *   keys:
 *     id: [ address, city, state, postal_code, country ]
 *     day: day
 *   path: path/to/json/meetings.json
 * @endcode
 *
 * @MigrateSource(
 *   id = "anonintergroup_json"
 * )
 */
class AnonIntergroupJSON extends SourcePluginBase {

  use AnonIntergroupSourceTrait;

  protected $iterator;

  /**
   * {@inheritdoc}
   */
  public function __toString() {
    return 'AnonIntergroupJSON::' . $this->configuration['path'];
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
//  if (!isset($this->iterator)) {
//    $this->initSource();
//  }
    return array_keys($this->iterator->current());
  }

  /**
   * @inheritdoc}
   */
  public function initSource() {
    // Read the entire file into memory.
    // @todo: read this in a more modern way.
    // @todo: allow URL style paths.
    $data = file_get_contents($this->configuration['path']);
    $rows = new \ArrayObject(Json::decode($data));
    $this->iterator = $rows->getIterator();
  }

  /**
   * @inheritdoc}
   */
  public function nextSourceRow() {
    // Get the current row and move to the next one.
    if (!$row = $this->iterator->current()) {
      return NULL;
    }
    $this->iterator->next();

    // Replace 'null' in the source data with the empty string.
    array_map(function($v) {
      return is_string($v) && $v == 'null' ? '' : $v;
    }, $row);

    return $row;
  }

}
