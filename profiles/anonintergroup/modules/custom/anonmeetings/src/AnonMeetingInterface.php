<?php

/**
 * @file
 * Contains \Drupal\anonmeetings\AnonMeetingInterface.
 */

namespace Drupal\anonmeetings;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Anonymous 12 Step Meeting entities.
 *
 * @ingroup anonmeetings
 */
interface AnonMeetingInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {
  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Anonymous 12 Step Meeting name.
   *
   * @return string
   *   Name of the Anonymous 12 Step Meeting.
   */
  public function getName();

  /**
   * Sets the Anonymous 12 Step Meeting name.
   *
   * @param string $name
   *   The Anonymous 12 Step Meeting name.
   *
   * @return \Drupal\anonmeetings\AnonMeetingInterface
   *   The called Anonymous 12 Step Meeting entity.
   */
  public function setName($name);

  /**
   * Gets the Anonymous 12 Step Meeting creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Anonymous 12 Step Meeting.
   */
  public function getCreatedTime();

  /**
   * Sets the Anonymous 12 Step Meeting creation timestamp.
   *
   * @param int $timestamp
   *   The Anonymous 12 Step Meeting creation timestamp.
   *
   * @return \Drupal\anonmeetings\AnonMeetingInterface
   *   The called Anonymous 12 Step Meeting entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Anonymous 12 Step Meeting published status indicator.
   *
   * Unpublished Anonymous 12 Step Meeting are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Anonymous 12 Step Meeting is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Anonymous 12 Step Meeting.
   *
   * @param bool $published
   *   TRUE to set this Anonymous 12 Step Meeting to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\anonmeetings\AnonMeetingInterface
   *   The called Anonymous 12 Step Meeting entity.
   */
  public function setPublished($published);

}
