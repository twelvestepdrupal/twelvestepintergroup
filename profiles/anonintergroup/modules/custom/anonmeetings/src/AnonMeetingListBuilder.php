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
    $header['where'] = $this->t('Where');
    $header['when'] = $this->t('When');
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
    $location_id = $entity->get('field_location')->target_id;
    if ($location_id) {
      /** @var AnonLocation $location */
      $location = entity_load('anonlocation', $location_id);
      $row['where'] = self::renderField($location, 'field_address');
    }
    $row['when'] = self::renderField($entity, 'field_time');
    $row['format'] = self::renderField($entity, 'field_format');
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
