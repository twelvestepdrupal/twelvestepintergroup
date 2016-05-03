<?php

/**
 * @file
 * Contains \Drupal\anonmeetings\AnonMeetingListBuilder.
 */

namespace Drupal\anonmeetings;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Anonymous 12 Step Meeting entities.
 *
 * @ingroup anonmeetings
 */
class AnonMeetingListBuilder extends EntityListBuilder {
  use LinkGeneratorTrait;
  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\anonmeetings\Entity\AnonMeeting */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.anonmeeting.edit_form', array(
          'anonmeeting' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
