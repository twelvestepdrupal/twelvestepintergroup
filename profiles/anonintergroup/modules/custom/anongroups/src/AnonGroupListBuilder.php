<?php

/**
 * @file
 * Contains \Drupal\anongroups\AnonGroupListBuilder.
 */

namespace Drupal\anongroups;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Anonymous 12 Step Group entities.
 *
 * @ingroup anongroups
 */
class AnonGroupListBuilder extends EntityListBuilder {
  use LinkGeneratorTrait;
  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Anonymous 12 Step Group ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\anongroups\Entity\AnonGroup */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.anongroup.edit_form', array(
          'anongroup' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
