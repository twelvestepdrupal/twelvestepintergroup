<?php

/**
 * @file
 * Contains \Drupal\anonmeetings\Entity\AnonMeeting.
 */

namespace Drupal\anonmeetings\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for Anonymous 12 Step Meeting entities.
 */
class AnonMeetingViewsData extends EntityViewsData implements EntityViewsDataInterface {
  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['anonmeeting']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('Anonymous 12 Step Meeting'),
      'help' => $this->t('The Anonymous 12 Step Meeting ID.'),
    );

    return $data;
  }

}
