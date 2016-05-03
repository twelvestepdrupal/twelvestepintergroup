<?php

/**
 * @file
 * Contains \Drupal\anongroups\Entity\AnonGroup.
 */

namespace Drupal\anongroups\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for Anonymous 12 Step Group entities.
 */
class AnonGroupViewsData extends EntityViewsData implements EntityViewsDataInterface {
  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['anongroup']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('Anonymous 12 Step Group'),
      'help' => $this->t('The Anonymous 12 Step Group ID.'),
    );

    return $data;
  }

}
