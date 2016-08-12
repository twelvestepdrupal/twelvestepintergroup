<?php

/**
 * @file
 * Contains \Drupal\twelvestepmodule\TwelveStepMeetingInterface.
 */

namespace Drupal\twelvestepmodule;

use Drupal\twelvestepmodule\TwelveStepEntityInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Twelve Step Meeting entities.
 *
 * @ingroup twelvestepmodule
 */
interface TwelveStepMeetingInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface, TwelveStepEntityInterface {

  /**
   * Gets the Twelve Step Meeting name.
   *
   * @return string
   *   Name of the Twelve Step Meeting.
   */
  public function getName();

  /**
   * Sets the Twelve Step Meeting name.
   *
   * @param string $name
   *   The Twelve Step Meeting name.
   *
   * @return \Drupal\twelvestepmodule\TwelveStepMeetingInterface
   *   The called Twelve Step Meeting entity.
   */
  public function setName($name);

  /**
   * Gets the Twelve Step Meeting creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Twelve Step Meeting.
   */
  public function getCreatedTime();

  /**
   * Sets the Twelve Step Meeting creation timestamp.
   *
   * @param int $timestamp
   *   The Twelve Step Meeting creation timestamp.
   *
   * @return \Drupal\twelvestepmodule\TwelveStepMeetingInterface
   *   The called Twelve Step Meeting entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Twelve Step Meeting published status indicator.
   *
   * Unpublished Twelve Step Meeting are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Twelve Step Meeting is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Twelve Step Meeting.
   *
   * @param bool $published
   *   TRUE to set this Twelve Step Meeting to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\twelvestepmodule\TwelveStepMeetingInterface
   *   The called Twelve Step Meeting entity.
   */
  public function setPublished($published);

}
