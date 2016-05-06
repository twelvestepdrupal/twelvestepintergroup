<?php

namespace Drupal\baltimoreaa\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
//use Drupal\Core\Entity\EntityStorageInterface;
//use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
//use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\MigrateExecutableInterface;
//use Drupal\migrate\Plugin\MigrateProcessInterface;
//use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;
//use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @MigrateProcessPlugin(
 *   id = "baltimoreaa_process"
 * )
 */
class BaltimoreAaProcess extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    file_put_contents('/tmp/doug.txt', print_r([$value, $row, $destination_property], 1), FILE_APPEND);
  }

  // @todo:
  /**
  source:
  - mSmoke
  - mType
  - mOpen
  - mAccess
  map:
  mOpen:
  O: open
  C: closed
  mType:
  D: discussion
  O: open
  Discussion: discussion
  Speaker: speaker
  Step: stepstudy
  Beginners: beginners
  Literature: literature
  Discusssion: discussion
  Discuussion: discussion
  'Step Study': stepstudy
  'Big Book': bigbook
  Traditions: traditions
  BB: bigbook
  mSmoke:
  M: mens
  W: womens
  NSM: nonsmoking
  YP: youngpeoples
  mAccess:
  H: handicap
   */

}
