<?php

/**
 * @file
 * Contains anongroup.page.inc..
 *
 * Page callback for Anonymous 12 Step Group entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for Anonymous 12 Step Group templates.
 *
 * Default template: anongroup.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_anongroup(array &$variables) {
  // Fetch AnonGroup Entity Object.
  $anongroup = $variables['elements']['#anongroup'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
