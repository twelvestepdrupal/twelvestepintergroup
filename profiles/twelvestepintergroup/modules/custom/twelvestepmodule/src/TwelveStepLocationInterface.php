<?php

/**
 * @file
 * Contains \Drupal\twelvestepmodule\TwelveStepLocationInterface.
 */

namespace Drupal\twelvestepmodule;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Twelve Step Location entities.
 *
 * @inlocation twelvestepmodule
 */
interface TwelveStepLocationInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  /**
   * Gets the Twelve Step Location name.
   *
   * @return string
   *   Name of the Twelve Step Location.
   */
  public function getName();

  /**
   * Sets the Twelve Step Location name.
   *
   * @param string $name
   *   The Twelve Step Location name.
   *
   * @return \Drupal\twelvestepmodule\TwelveStepLocationInterface
   *   The called Twelve Step Location entity.
   */
  public function setName($name);

  /**
   * Gets the Twelve Step Location creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Twelve Step Location.
   */
  public function getCreatedTime();

  /**
   * Sets the Twelve Step Location creation timestamp.
   *
   * @param int $timestamp
   *   The Twelve Step Location creation timestamp.
   *
   * @return \Drupal\twelvestepmodule\TwelveStepLocationInterface
   *   The called Twelve Step Location entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Twelve Step Location published status indicator.
   *
   * Unpublished Twelve Step Location are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Twelve Step Location is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Twelve Step Location.
   *
   * @param bool $published
   *   TRUE to set this Twelve Step Location to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\twelvestepmodule\TwelveStepLocationInterface
   *   The called Twelve Step Location entity.
   */
  public function setPublished($published);

}
