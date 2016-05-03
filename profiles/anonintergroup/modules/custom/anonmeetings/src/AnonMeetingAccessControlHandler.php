<?php

/**
 * @file
 * Contains \Drupal\anonmeetings\AnonMeetingAccessControlHandler.
 */

namespace Drupal\anonmeetings;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Anonymous 12 Step Meeting entity.
 *
 * @see \Drupal\anonmeetings\Entity\AnonMeeting.
 */
class AnonMeetingAccessControlHandler extends EntityAccessControlHandler {
  /**
   * {@inheritdoc}
   */
  protected function checkAccess(AnonMeetingInterface $entity, $operation, AccountInterface $account) {
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished anonymous 12 step meeting entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published anonymous 12 step meeting entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit anonymous 12 step meeting entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete anonymous 12 step meeting entities');
    }

    return AccessResult::allowed();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add anonymous 12 step meeting entities');
  }

}
