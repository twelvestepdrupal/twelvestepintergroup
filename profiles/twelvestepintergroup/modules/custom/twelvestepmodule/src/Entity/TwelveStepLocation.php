<?php

/**
 * @file
 * Contains \Drupal\twelvestepmodule\Entity\TwelveStepLocation.
 */

namespace Drupal\twelvestepmodule\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\twelvestepmodule\TwelveStepLocationInterface;
use Drupal\user\UserInterface;
use Drupal\address\Plugin\Field\FieldType\AddressField;

/**
 * Defines the Twelve Step Location entity.
 *
 * @inlocation twelvestepmodule
 *
 * @ContentEntityType(
 *   id = "twelvesteplocation",
 *   label = @Translation("Twelve Step Location"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\twelvestepmodule\TwelveStepLocationListBuilder",
 *     "views_data" = "Drupal\twelvestepmodule\Entity\TwelveStepLocationViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\twelvestepmodule\Form\TwelveStepLocationForm",
 *       "add" = "Drupal\twelvestepmodule\Form\TwelveStepLocationForm",
 *       "edit" = "Drupal\twelvestepmodule\Form\TwelveStepLocationForm",
 *       "delete" = "Drupal\twelvestepmodule\Form\TwelveStepLocationDeleteForm",
 *     },
 *     "access" = "Drupal\twelvestepmodule\TwelveStepLocationAccessControlHandler",
 *   },
 *   base_table = "twelvesteplocation",
 *   admin_permission = "administer TwelveStepLocation entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *   },
 *   links = {
 *     "canonical" = "/admin/twelvesteplocation/{twelvesteplocation}",
 *     "edit-form" = "/admin/twelvesteplocation/{twelvesteplocation}/edit",
 *     "delete-form" = "/admin/twelvesteplocation/{twelvesteplocation}/delete"
 *   },
 *   field_ui_base_route = "twelvesteplocation.settings"
 * )
 */
class TwelveStepLocation extends ContentEntityBase implements TwelveStepLocationInterface {
  use EntityChangedTrait;
  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += array(
      'user_id' => \Drupal::currentUser()->id(),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? NODE_PUBLISHED : NODE_NOT_PUBLISHED);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the Twelve Step Location entity.'))
      ->setReadOnly(TRUE);
    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the Twelve Step Location entity.'))
      ->setReadOnly(TRUE);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Twelve Step Location entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDefaultValueCallback('Drupal\node\Entity\Node::getCurrentUserId')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => array(
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ),
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Twelve Step Location entity.'))
      ->setSettings(array(
        'max_length' => 255,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Twelve Step Location is published.'))
      ->setDefaultValue(TRUE);

    $fields['langcode'] = BaseFieldDefinition::create('language')
      ->setLabel(t('Language code'))
      ->setDescription(t('The language code for the Twelve Step Location entity.'));

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

  /**
   * @{inheritdoc}
   */
  public function postSave(EntityStorageInterface $storage, $update = TRUE) {
    if (!$update || $this->isAddressChanged()) {
      geolocationqueue_additem($this, 'field_address', 'field_coordinates');
    }

    parent::postSave($storage, $update);
  }

  /**
   * Return TRUE if the address changed.
   *
   * @return bool
   */
  public function isAddressChanged() {
    if (!$this->original) {
      return TRUE;
    }
    /** var AddressField $original */
    if (!$original = $this->original->get('field_address')->first()) {
      return FALSE;
    }
    /** var AddressField $current */
    if (!$current = $this->get('field_address')->first()) {
      return FALSE;
    }
    if ( $current->address_line1 != $original->address_line1
      || $current->locality != $original->locality
      || $current->administrative_area != $original->administrative_area
      || $current->postal_code != $original->postal_code
      || $current->country != $original->country) {
      return TRUE;
    }
    return FALSE;
  }
}
