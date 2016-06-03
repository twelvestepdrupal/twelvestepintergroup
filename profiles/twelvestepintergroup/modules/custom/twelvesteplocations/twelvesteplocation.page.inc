<?php

/**
 * @file
 * Contains twelvesteplocation.page.inc..
 *
 * Page callback for Twelve Step Location entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for Twelve Step Location templates.
 *
 * Default template: twelvesteplocation.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_twelvesteplocation(array &$variables) {
  // Fetch TwelveStepLocation Entity Object.
  $twelvesteplocation = $variables['elements']['#twelvesteplocation'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
