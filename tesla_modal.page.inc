<?php

/**
 * @file
 * Contains tesla_modal.page.inc.
 *
 * Page callback for Modal entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Modal templates.
 *
 * @param array $variables The tesla-modal variables.
 *
 * @return void
 *   void
 */
function template_preprocess_tesla_modal(array &$variables) {
  // Fetch TeslaModal Entity Object.
  $tesla_modal = $variables['elements']['#tesla_modal'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
