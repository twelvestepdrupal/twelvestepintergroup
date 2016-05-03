<?php

/**
 * @file
 * Contains anonlocation.page.inc..
 *
 * Page callback for Anonymous 12 Step Location entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for Anonymous 12 Step Location templates.
 *
 * Default template: anonlocation.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_anonlocation(array &$variables) {
  // Fetch AnonLocation Entity Object.
  $anonlocation = $variables['elements']['#anonlocation'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
