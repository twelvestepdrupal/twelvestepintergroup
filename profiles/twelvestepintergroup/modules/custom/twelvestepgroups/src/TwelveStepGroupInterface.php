<?php

/**
 * @file
 * Contains \Drupal\twelvestepgroups\TwelveStepGroupInterface.
 */

namespace Drupal\twelvestepgroups;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Twelve Step Group entities.
 *
 * @ingroup twelvestepgroups
 */
interface TwelveStepGroupInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {
  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Twelve Step Group name.
   *
   * @return string
   *   Name of the Twelve Step Group.
   */
  public function getName();

  /**
   * Sets the Twelve Step Group name.
   *
   * @param string $name
   *   The Twelve Step Group name.
   *
   * @return \Drupal\twelvestepgroups\TwelveStepGroupInterface
   *   The called Twelve Step Group entity.
   */
  public function setName($name);

  /**
   * Gets the Twelve Step Group creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Twelve Step Group.
   */
  public function getCreatedTime();

  /**
   * Sets the Twelve Step Group creation timestamp.
   *
   * @param int $timestamp
   *   The Twelve Step Group creation timestamp.
   *
   * @return \Drupal\twelvestepgroups\TwelveStepGroupInterface
   *   The called Twelve Step Group entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Twelve Step Group published status indicator.
   *
   * Unpublished Twelve Step Group are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Twelve Step Group is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Twelve Step Group.
   *
   * @param bool $published
   *   TRUE to set this Twelve Step Group to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\twelvestepgroups\TwelveStepGroupInterface
   *   The called Twelve Step Group entity.
   */
  public function setPublished($published);

}
