<?php

/**
 * @file
 * Contains anonmeeting.page.inc..
 *
 * Page callback for Anonymous 12 Step Meeting entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for Anonymous 12 Step Meeting templates.
 *
 * Default template: anonmeeting.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_anonmeeting(array &$variables) {
  // Fetch AnonMeeting Entity Object.
  $anonmeeting = $variables['elements']['#anonmeeting'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
