<?php

/**
 * @file
 * Contains \Drupal\twelvestepmodule\TwelveStepLocationListBuilder.
 */

namespace Drupal\twelvestepmodule;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Twelve Step Location entities.
 *
 * @inlocation twelvestepmodule
 */
class TwelveStepLocationListBuilder extends EntityListBuilder {
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
    /** @var \Drupal\twelvestepmodule\Entity\TwelveStepLocation $entity */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.twelvesteplocation.edit_form', array(
          'twelvesteplocation' => $entity->id(),
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
