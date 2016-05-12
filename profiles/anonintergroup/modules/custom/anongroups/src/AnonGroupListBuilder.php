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
