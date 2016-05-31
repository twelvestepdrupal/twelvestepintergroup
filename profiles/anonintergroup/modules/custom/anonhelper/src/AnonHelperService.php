<?php

/**
 * @file
 * Contains \Drupal\anonhelper\AnonHelperService.
 */

namespace Drupal\anonhelper;

use Drupal\Core\Extension\ThemeInstaller;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Entity\EntityManager;

/**
 * Class AnonHelperService.
 *
 * @package Drupal\anonhelper
 */
class AnonHelperService implements AnonHelperServiceInterface {

  /**
   * @var Drupal\Core\Extension\ThemeInstaller
   */
  protected $theme_installer;

  /**
   * @var Drupal\Core\Config\ConfigFactory
   */
  protected $config_factory;

  /**
   * @var Drupal\Core\Entity\EntityManager
   */
  protected $entity_manager;

  /**
   * Constructor.
   */
  public function __construct(ConfigFactory $config_factory, EntityManager $entity_manager, ThemeInstaller $theme_installer) {
    $this->config_factory = $config_factory;
    $this->entity_manager = $entity_manager;
    $this->theme_installer = $theme_installer;
  }

  /**
   * Set the default theme.
   *
   * @param $default_theme
   */
  function changeTheme($default_theme) {
    // Change the default theme.
    $this->theme_installer->install([$default_theme], FALSE);
    $this->config_factory->getEditable('system.theme')
      ->set('default', $default_theme)
      ->save(TRUE);

    // Remove navigation header blocks.
    // @todo: why do we need to do this? Shouldn't the default blocks be
    // picked up from anontheme.
    $blocks = $this->entity_manager->getStorage('block')->loadMultiple($block_ids);
    foreach ($blocks as $block) {
      /** @var $block \Drupal\block\BlockInterface */
      if ($block->getTheme() == $default_theme && $block->getRegion() == 'navigation') {
        $block->setStatus(FALSE);
        $block->save();
      }
    }
  }

}
