<?php

/**
 * @file
 * Contains \Drupal\twelvestepmodule\Entity\TwelveStepMeeting.
 */

namespace Drupal\twelvestepmodule\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for Twelve Step Meeting entities.
 */
class TwelveStepMeetingViewsData extends EntityViewsData implements EntityViewsDataInterface {
  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['twelvestepmeeting']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('Twelve Step Meeting'),
      'help' => $this->t('The Twelve Step Meeting ID.'),
    );

    return $data;
  }

}
