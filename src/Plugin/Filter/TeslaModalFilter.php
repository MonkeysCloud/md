<?php

namespace Drupal\tesla_modal\Plugin\Filter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a Tesla Link Modal filter.
 *
 * @Filter(
 *   id = "tesla_link_modal",
 *   title = @Translation("Tesla link modal decorator"),
 *   description = @Translation("Includes modals based on data attributes."),
 *   settings = {
 *     "title" = TRUE,
 *   },
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_TRANSFORM_REVERSIBLE
 * )
 */
class TeslaModalFilter extends FilterBase implements ContainerFactoryPluginInterface {

  /**
   * Constant to store unique plugin id.
   */
  const PLUGIN_ID = 'tesla_link_modal';

  /**
   * Our logger channel (i.e. tesla_modal).
   *
   * @var \Drupal\Core\Logger\LoggerChannelInterface
   */
  protected $logger;

  /**
   * Modal manager service.
   *
   * @var \Drupal\tesla_modal\TeslaModalManagerInterface
   */
  protected $modalManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = new static($configuration, $plugin_id, $plugin_definition);
    $instance->logger = $container->get('logger.channel.tesla_modal');
    $instance->modalManager = $container->get('tesla_modal.manager');
    return $instance;
  }

  /**
   * Performs the filter processing.
   *
   * @param string $text     The text string to be filtered.
   * @param string $langcode The language code of the text to be filtered.
   *
   * @return \Drupal\filter\FilterProcessResult
   *   The filtered text, wrapped in a FilterProcessResult object, and possibly
   *   with associated assets, cache-ability metadata and placeholders.
   *
   * @see \Drupal\filter\FilterProcessResult
   */
  public function process($text, $langcode) {
    $result = new FilterProcessResult($text);

    if (strpos($text, 'data-tcl-modal-id') !== FALSE) {
      $dom = Html::load($text);
      $xpath = new \DOMXPath($dom);

      /** @var \DOMElement $element */
      foreach ($xpath->query('//a[@data-tcl-modal-id]') as $element) {
        try {
          // Get the data attribute.
          $modalId = $element->getAttribute('data-tcl-modal-id') ?? FALSE;

          if (is_numeric($modalId)) {
            [, $modalHtmlId] = $this->modalManager->registerModalUsage($modalId);

            if (!empty($modalHtmlId)) {
              $element->setAttribute('href', '#');
              $element->setAttribute('onclick', 'return false;');
              $element->removeAttribute('data-tcl-modal-id');
              $element->setAttribute('data-tds-open-modal', $modalHtmlId);
            }
          }
        }
        catch (\Exception $e) {
          $this->logger->debug('There was a problem decorating the link @link with the modal attribute. Error message: @message', [
            '@link' => $dom->saveHtml($element),
            '@message' => $e->getMessage(),
          ]);
        }
      }

      $result->setProcessedText(Html::serialize($dom));
    }

    return $result;
  }

}
