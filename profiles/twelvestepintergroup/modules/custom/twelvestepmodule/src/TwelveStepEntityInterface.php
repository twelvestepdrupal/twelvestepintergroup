<?php

/**
 * @file
 * Contains \Drupal\twelvestepmodule\TwelveStepEntityInterface.
 */

namespace Drupal\twelvestepmodule;

use Drupal\Core\Session\AccountInterface;

interface TwelveStepEntityInterface {

  /**
   * Returns if the user can update the entity.
   *
   * @return bool
   *   TRUE if the User can update the entity.
   */
  public function isAccessUpdate(AccountInterface $account);

}
