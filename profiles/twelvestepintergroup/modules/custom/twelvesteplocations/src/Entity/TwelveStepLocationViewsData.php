<?php

/**
 * @file
 * Contains \Drupal\twelvesteplocations\Entity\TwelveStepLocation.
 */

namespace Drupal\twelvesteplocations\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for Twelve Step Location entities.
 */
class TwelveStepLocationViewsData extends EntityViewsData implements EntityViewsDataInterface {
  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['twelvesteplocation']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('Twelve Step Location'),
      'help' => $this->t('The Twelve Step Location ID.'),
    );

    return $data;
  }

}
