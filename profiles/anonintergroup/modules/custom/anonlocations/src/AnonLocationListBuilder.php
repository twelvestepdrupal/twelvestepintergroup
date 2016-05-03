<?php

/**
 * @file
 * Contains \Drupal\anonlocations\AnonLocationListBuilder.
 */

namespace Drupal\anonlocations;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Anonymous 12 Step Location entities.
 *
 * @inlocation anonlocations
 */
class AnonLocationListBuilder extends EntityListBuilder {
  use LinkGeneratorTrait;
  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Anonymous 12 Step Location ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\anonlocations\Entity\AnonLocation */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.anonlocation.edit_form', array(
          'anonlocation' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
