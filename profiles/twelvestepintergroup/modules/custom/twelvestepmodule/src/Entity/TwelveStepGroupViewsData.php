<?php

/**
 * @file
 * Contains \Drupal\twelvestepmodule\Entity\TwelveStepGroup.
 */

namespace Drupal\twelvestepmodule\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for Twelve Step Group entities.
 */
class TwelveStepGroupViewsData extends EntityViewsData implements EntityViewsDataInterface {
  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['twelvestepgroup']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('Twelve Step Group'),
      'help' => $this->t('The Twelve Step Group ID.'),
    );

    return $data;
  }

}
