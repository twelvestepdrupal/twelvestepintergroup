<?php

/**
 * @file
 * Contains \Drupal\twelvesteplocations\TwelveStepLocationAccessControlHandler.
 */

namespace Drupal\twelvesteplocations;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Twelve Step Location entity.
 *
 * @see \Drupal\twelvesteplocations\Entity\TwelveStepLocation.
 */
class TwelveStepLocationAccessControlHandler extends EntityAccessControlHandler {
  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished twelve step location entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published twelve step location entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit twelve step location entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete twelve step location entities');
    }

    return AccessResult::allowed();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add twelve step location entities');
  }

}
