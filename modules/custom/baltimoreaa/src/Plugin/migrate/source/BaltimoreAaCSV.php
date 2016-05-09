<?php

namespace Drupal\baltimoreaa\Plugin\migrate\source;

use Drupal\migrate_source_csv\Plugin\migrate\source\CSV;

/**
 * Source for Baltimore CSV.
 *
 * This file could be defined configuration, but because it is used in multiple
 * migrations, it's defined in here once in code.
 *
 * Here is the configuration that could be used in migration_templates/anon*.yml.
 *
 * @code
 * source:
 *   plugin: csv
 *   path: modules/custom/baltimoreaa/baltimoreaa.csv
 *   header_row_count: 1
 *   column_names:
 *     0:
 *       mID: identifier
 *     1:
 *       mName: meeting name
 *     2:
 *       mAdd1: address line 1
 *     3:
 *       mAdd2: address line 2
 *     4:
 *       mCity: city
 *     5:
 *       mZip: zipcode
 *     6:
 *       mDayNo: numeric day number
 *     7:
 *       mDay: abbreviated day name
 *     8:
 *       mTime: meeting time
 *     9:
 *       mInternational: meeting time in 24 hour clock
 *     10:
 *       mOpen: open/closed
 *     11:
 *       mSmoke: meeting type (Mens, Womens, Nonsmoking, etc)
 *     12:
 *       mType: meeting format (Discussion, Speaker, Big Book, etc)
 *     13:
 *       mNotes: notes
 *     14:
 *       mAccess: handicaped access
 *     15:
 *       mSpecial: coordinates
 * @endcode
 *
 * @MigrateSource(
 *   id = "baltimoreaa_csv"
 * )
 */
class BaltimoreAaCSV extends CSV {

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration) {
    $configuration += [
      'path' => 'modules/custom/baltimoreaa/baltimoreaa.csv',
      'header_row_count' => '1',
      'column_names' => [
        ['mID' => 'identifier'],
        ['mName' => 'meeting name'],
        ['mAdd1' => 'address line 1'],
        ['mAdd2' => 'address line 2'],
        ['mCity' => 'city'],
        ['mZip' => 'zipcode'],
        ['mDayNo' => 'numeric day number'],
        ['mDay' => 'abbreviated day name'],
        ['mTime' => 'meeting time'],
        ['mInternational' => 'meeting time in 24 hour clock'],
        ['mOpen' => 'open/closed'],
        ['mSmoke' => 'meeting type (Mens, Womens, Nonsmoking, etc)'],
        ['mType' => 'meeting format (Discussion, Speaker, Big Book, etc)'],
        ['mNotes' => 'notes'],
        ['mAccess' => 'handicaped access'],
        ['mSpecial' => 'coordinates'],
      ],
    ];
    parent::__construct($configuration, $plugin_id, $plugin_definition, $migration);
  }

}