<?php

/**
 * @file
 * Contains \Drupal\anonlocations\Entity\AnonLocation.
 */

namespace Drupal\anonlocations\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for Anonymous 12 Step Location entities.
 */
class AnonLocationViewsData extends EntityViewsData implements EntityViewsDataInterface {
  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['anonlocation']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('Anonymous 12 Step Location'),
      'help' => $this->t('The Anonymous 12 Step Location ID.'),
    );

    return $data;
  }

}
