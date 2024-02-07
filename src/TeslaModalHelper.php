<?php

namespace Drupal\tesla_modal;

use Symfony\Component\DomCrawler\Crawler;

/**
 * TeslaModalHelper.
 */
class TeslaModalHelper {

  /**
   * FilterHtml.
   *
   * @param TeslaModalManagerInterface $modalManager     The modal manager.
   * @param string $text The text to filter.
   *
   * @return string
   *   The filtered text.
   */
  public static function filterHtml(TeslaModalManagerInterface $modalManager, string $text): string {
    $result = $text;
    if (strpos($text, 'data-tcl-modal-id') !== FALSE) {
      $crawler = new Crawler($text);
      $anchors = $crawler->filter('a[data-tcl-modal-id]');
      foreach ($anchors as $element) {
        $modalId = $element->getAttribute('data-tcl-modal-id') ?? FALSE;
        if (is_numeric($modalId)) {
          [, $modalHtmlId] = $modalManager->registerModalUsage($modalId);

          if (!empty($modalHtmlId)) {
            $element->setAttribute('href', '#');
            $element->setAttribute('onclick', 'return false;');
            $element->removeAttribute('data-tcl-modal-id');
            $element->setAttribute('data-tds-open-modal', $modalHtmlId);
          }
        }
      }
      $result = $crawler->filterXpath('//body')->html();
    }
    return $result;
  }

}
