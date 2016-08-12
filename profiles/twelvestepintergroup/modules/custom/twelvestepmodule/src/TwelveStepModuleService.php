<?php

/**
 * @file
 * Contains \Drupal\twelvestepmodule\TwelveStepModuleService.
 */

namespace Drupal\twelvestepmodule;

use Drupal\Core\Extension\ThemeInstaller;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Entity\EntityManager;
use Drupal\block\Entity\Block;

/**
 * Class TwelveStepModuleService.
 *
 * @package Drupal\twelvestepmodule
 */
class TwelveStepModuleService implements TwelveStepModuleServiceInterface {

  /**
   * @var Drupal\Core\Extension\ThemeInstaller
   */
  protected $theme_installer;

  /**
   * @var Drupal\Core\Config\ConfigFactory
   */
  protected $config_factory;

  /**
   * The block storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $storage;

  /**
   * Constructor.
   */
  public function __construct(ConfigFactory $config_factory, EntityManager $entity_manager, ThemeInstaller $theme_installer) {
    $this->config_factory = $config_factory;
    $this->storage = $entity_manager->getStorage('block');
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
    // picked up from twelvesteptheme.
    $block_ids = $this->storage->getQuery()
      ->condition('theme', $default_theme)
      ->condition('region', 'navigation')
      ->execute();

    /** @var $block \Drupal\block\BlockInterface[] */
    $blocks = $this->storage->loadMultiple($block_ids);
    foreach ($blocks as $block) {
      $block->setStatus(FALSE);
      $block->save();
    }

    // Create Announcement blocks.
    $block_defaults = [
      'theme' => $default_theme,
      'weight' => -2,
      'status' => TRUE,
      'region' => 'highlighted',
      'settings' => [
        'label_display' => 0,
      ],
    ];
    Block::create([
      'id' => 'announcements',
      'plugin' => 'views_block:announcements-block_1',
      'visibility' => [
        'request_path' => [
          'id' => 'request_path',
          'pages' => '<front>',
        ],
      ],
    ] + $block_defaults)->save();
    Block::create([
      'id' => 'group-announcements',
      'plugin' => 'views_block:announcements-block_2',
      'visibility' => [
        'request_path' => [
          'id' => 'request_path',
          'pages' => 'twelvestepgroup/*',
        ],
      ],
    ] + $block_defaults)->save();
  }
}
