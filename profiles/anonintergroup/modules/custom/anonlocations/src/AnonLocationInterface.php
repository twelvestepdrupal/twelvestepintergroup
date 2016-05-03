<?php

/**
 * @file
 * Contains \Drupal\anonlocations\AnonLocationInterface.
 */

namespace Drupal\anonlocations;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Anonymous 12 Step Location entities.
 *
 * @inlocation anonlocations
 */
interface AnonLocationInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {
  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Anonymous 12 Step Location name.
   *
   * @return string
   *   Name of the Anonymous 12 Step Location.
   */
  public function getName();

  /**
   * Sets the Anonymous 12 Step Location name.
   *
   * @param string $name
   *   The Anonymous 12 Step Location name.
   *
   * @return \Drupal\anonlocations\AnonLocationInterface
   *   The called Anonymous 12 Step Location entity.
   */
  public function setName($name);

  /**
   * Gets the Anonymous 12 Step Location creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Anonymous 12 Step Location.
   */
  public function getCreatedTime();

  /**
   * Sets the Anonymous 12 Step Location creation timestamp.
   *
   * @param int $timestamp
   *   The Anonymous 12 Step Location creation timestamp.
   *
   * @return \Drupal\anonlocations\AnonLocationInterface
   *   The called Anonymous 12 Step Location entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Anonymous 12 Step Location published status indicator.
   *
   * Unpublished Anonymous 12 Step Location are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Anonymous 12 Step Location is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Anonymous 12 Step Location.
   *
   * @param bool $published
   *   TRUE to set this Anonymous 12 Step Location to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\anonlocations\AnonLocationInterface
   *   The called Anonymous 12 Step Location entity.
   */
  public function setPublished($published);

}
