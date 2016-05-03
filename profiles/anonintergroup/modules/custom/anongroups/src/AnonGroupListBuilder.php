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
    $header['id'] = $this->t('ID');
    $header['name'] = $this->t('Name');
    $header['phone'] = $this->t('Phone');
    $header['website'] = $this->t('Website');
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
    // @todo: is there a better way to do this?
    $row['phone'] = drupal_render($entity->get('field_default_phone')->view(['label' => 'hidden']));
    $row['website'] = drupal_render($entity->get('field_website')->view(['label' => 'hidden']));

    return $row + parent::buildRow($entity);
  }

}
