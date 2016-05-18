<?php

namespace Drupal\anonmigrate\Plugin\migrate\source;

use Drupal\anonmigrate\Plugin\migrate\source\AnonIntergroupSourceTrait;
use Drupal\weeklytime\Plugin\Field\FieldType\WeeklyTimeField;
use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;
use Drupal\migrate\Row;

/**
 * Generic source for AnonIntergroup CSV.
 *
 * @code
 * source:
 *   plugin: anonintergroup_csv
 *   keys:
 *     id: [ mAdd1, mAdd2, mCity, mZip ]
 *     day: mDayNo
 *   path: path/to/csv/meetings.csv
 * @endcode
 *
 * @MigrateSource(
 *   id = "anonintergroup_csv"
 * )
 */
class AnonIntergroupCSV extends SourcePluginBase {

  use AnonIntergroupSourceTrait;

  protected $file;
  protected $header;
  protected $headerCount;

  /**
   * {@inheritdoc}
   */
  public function __toString() {
    return 'AnonIntergroupCSV::' . $this->configuration['path'];
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    return $this->header;
  }

  /**
   * @inheritdoc}
   */
  public function initSource() {
    // Open the file and read the header.
    $this->file = new \SplFileObject($this->configuration['path']);
    $this->file->rewind();
    $this->header = $this->file->fgetcsv();
    $this->headerCount = count($this->header);
  }

  /**
   * @inheritdoc}
   */
  public function nextSourceRow() {
    while ($row = $this->file->fgetcsv()) {
      if (count($row) == $this->headerCount) {
        // Create the data from the header and this row's value.
        return array_combine($this->header, $row);
      }
    }
    return NULL;
  }
  
}
