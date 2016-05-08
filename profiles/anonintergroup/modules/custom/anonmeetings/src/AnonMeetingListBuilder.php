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
    $header['when'] = $this->t('When');
    $header['where'] = $this->t('Where');
    $header['format'] = $this->t('Format');
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
    // @todo: is there a better way to do this?
    $row['when'] = drupal_render($entity->get('field_time')->view(['label' => 'hidden']));
    $row['where'] = drupal_render($entity->get('field_location')->view(['label' => 'hidden']));
    $row['format'] = drupal_render($entity->get('field_format')->view(['label' => 'hidden']));
    return $row + parent::buildRow($entity);
  }

}
