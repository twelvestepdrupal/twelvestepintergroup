<?php

/**
 * @file
 * Contains \Drupal\twelvestepmodule\TwelveStepGroupAccessControlHandler.
 */

namespace Drupal\twelvestepmodule;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Twelve Step Group entity.
 *
 * @see \Drupal\twelvestepmodule\Entity\TwelveStepGroup.
 */
class TwelveStepGroupAccessControlHandler extends EntityAccessControlHandler {
  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished twelve step group entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published twelve step group entities');

      case 'update':
        if ($entity->isAccessUpdate($account)) {
          return AccessResult::allowed()->cachePerPermissions()->cachePerUser()->addCacheableDependency($node);
        }
        return AccessResult::allowedIfHasPermission($account, 'edit twelve step group entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete twelve step group entities');
    }

    return AccessResult::allowed();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add twelve step group entities');
  }

}
