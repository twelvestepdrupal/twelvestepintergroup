<?php

/**
 * @file
 * Contains \Drupal\twelvestepmodule\TwelveStepGroupListBuilder.
 */

namespace Drupal\twelvestepmodule;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Twelve Step Group entities.
 *
 * @ingroup twelvestepmodule
 */
class TwelveStepGroupListBuilder extends EntityListBuilder {
  use LinkGeneratorTrait;
  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('ID');
    $header['name'] = $this->t('Name');
    $header['where'] = $this->t('Where');
    $header['phone'] = $this->t('Phone');
    $header['website'] = $this->t('Website');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\twelvestepmodule\Entity\TwelveStepGroup */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.twelvestepgroup.edit_form', array(
          'twelvestepgroup' => $entity->id(),
        )
      )
    );
    $row['where'] = self::renderField($entity, 'field_default_location');
    $row['phone'] = self::renderField($entity, 'field_default_phone');
    $row['website'] = self::renderField($entity, 'field_website');

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
