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
    $header['id'] = $this->t('ID');
    $header['name'] = $this->t('Name');
    $header['address'] = $this->t('Address');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\anonlocations\Entity\AnonLocation $entity */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.anonlocation.edit_form', array(
          'anonlocation' => $entity->id(),
        )
      )
    );
    $row['address'] = self::renderField($entity, 'field_address');
    return $row + parent::buildRow($entity);
  }

  /**
   * Render the field.
   *
   * @todo: is there a better way to do this?
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   * @param $field_name
   *
   * @return string
   */
  static protected function renderField(EntityInterface $entity, $field_name) {
    $output = $entity->get($field_name)->view(['label' => 'hidden']);
    return drupal_render($output);
  }

}
