<?php

/**
 * @file
 * Contains \Drupal\anongroups\AnonGroupAccessControlHandler.
 */

namespace Drupal\anongroups;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Anonymous 12 Step Group entity.
 *
 * @see \Drupal\anongroups\Entity\AnonGroup.
 */
class AnonGroupAccessControlHandler extends EntityAccessControlHandler {
  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished anonymous 12 step group entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published anonymous 12 step group entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit anonymous 12 step group entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete anonymous 12 step group entities');
    }

    return AccessResult::allowed();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add anonymous 12 step group entities');
  }

}
