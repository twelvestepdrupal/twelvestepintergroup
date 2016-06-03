<?php

/**
 * @file
 * Contains twelvestepmeeting.page.inc..
 *
 * Page callback for Twelve Step Meeting entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for Twelve Step Meeting templates.
 *
 * Default template: twelvestepmeeting.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_twelvestepmeeting(array &$variables) {
  // Fetch TwelveStepMeeting Entity Object.
  $twelvestepmeeting = $variables['elements']['#twelvestepmeeting'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
