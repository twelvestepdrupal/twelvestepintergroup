<?php

/**
 * @file
 * Contains \Drupal\anongroups\AnonGroupInterface.
 */

namespace Drupal\anongroups;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Anonymous 12 Step Group entities.
 *
 * @ingroup anongroups
 */
interface AnonGroupInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {
  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Anonymous 12 Step Group name.
   *
   * @return string
   *   Name of the Anonymous 12 Step Group.
   */
  public function getName();

  /**
   * Sets the Anonymous 12 Step Group name.
   *
   * @param string $name
   *   The Anonymous 12 Step Group name.
   *
   * @return \Drupal\anongroups\AnonGroupInterface
   *   The called Anonymous 12 Step Group entity.
   */
  public function setName($name);

  /**
   * Gets the Anonymous 12 Step Group creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Anonymous 12 Step Group.
   */
  public function getCreatedTime();

  /**
   * Sets the Anonymous 12 Step Group creation timestamp.
   *
   * @param int $timestamp
   *   The Anonymous 12 Step Group creation timestamp.
   *
   * @return \Drupal\anongroups\AnonGroupInterface
   *   The called Anonymous 12 Step Group entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Anonymous 12 Step Group published status indicator.
   *
   * Unpublished Anonymous 12 Step Group are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Anonymous 12 Step Group is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Anonymous 12 Step Group.
   *
   * @param bool $published
   *   TRUE to set this Anonymous 12 Step Group to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\anongroups\AnonGroupInterface
   *   The called Anonymous 12 Step Group entity.
   */
  public function setPublished($published);

}
