<?php

/**
 * @file
 * Contains twelvestepgroup.page.inc..
 *
 * Page callback for Twelve Step Group entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for Twelve Step Group templates.
 *
 * Default template: twelvestepgroup.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_twelvestepgroup(array &$variables) {
  // Fetch TwelveStepGroup Entity Object.
  $twelvestepgroup = $variables['elements']['#twelvestepgroup'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
