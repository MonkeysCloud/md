<?php

namespace Drupal\tesla_modal;

use Drupal\Core\Cache\MemoryCache\MemoryCacheInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Security\TrustedCallbackInterface;
use Drupal\tesla_modal\Entity\TeslaModal;

/**
 * Provides the Tesla Modal manager.
 */
class TeslaModalManager implements TeslaModalManagerInterface, TrustedCallbackInterface {

  /**
   * The registered modals for the page being rendered.
   *
   * @var array
   */
  protected $registeredInUseModals = [];

  /**
   * The used modals for the page being rendered.
   *
   * @var array
   */
  protected $usedModals = [];

  /**
   * Modal storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $modalStorage;

  /**
   * The memory cache.
   *
   * @var \Drupal\Core\Cache\MemoryCache\MemoryCacheInterface
   */
  protected MemoryCacheInterface $memoryCache;

  /**
   * TeslaModalManager constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager Type manager.
   * @param \Drupal\Core\Cache\MemoryCache\MemoryCacheInterface $memory_cache The memory cache.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException Plugin definition issue.
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException Plugin availability issue.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, MemoryCacheInterface $memory_cache) {
    $this->modalStorage = $entity_type_manager->getStorage('tesla_modal');
    $this->memoryCache = $memory_cache;
  }

  /**
   * Retrieves all the modals.
   *
   * @return \Drupal\tesla_modal\Entity\TeslaModalInterface[]
   *   An associative array of all modals.
   */
  public function getModals() {
    $modalsCacheEntry = $this->memoryCache->get(__CLASS__ . __METHOD__);

    if ($modalsCacheEntry) {
      $modals = $modalsCacheEntry->data;
    }
    else {
      $ids = $this->modalStorage->getQuery()
        ->sort('name')
        ->execute();

      $modals = TeslaModal::loadMultiple($ids);
      $this->memoryCache->set(__CLASS__ . __METHOD__, $modals);
    }

    return $modals;
  }

  /**
   * Registers that a particular modal is used on the page.
   *
   * @param string $modal_id Modal id
   *   The internal id of the modal.
   *
   * @return array
   *   The first value of the array is an id suitable
   *   to place on a button. The second value is the HTML
   *   id of the modal that is being referenced.
   */
  public function registerModalUsage(string $modal_id) {
    if ($modal = TeslaModal::load($modal_id)) {
      $buttonId = $modal->getButtonId();
      $this->registeredInUseModals[$modal_id] = $buttonId;
      return [$buttonId, $modal->getHtmlId()];
    }
  }

  /**
   * Get Modal by $htmlId.
   *
   * @param string $htmlId
   *   The html id of the modal.
   *
   * @return int|string|null
   *   The modal id or NULL if not found.
   */
  public function getModalIdByHtmlId(string $htmlId) {
    $modals = $this->getModals();
    foreach ($modals as $modal) {
      if ($modal->getHtmlId() === $htmlId) {
        return $modal->id();
      }
    }
    return NULL;
  }

  /**
   * Returns render arrays for the registered modals.
   *
   * @return array
   *   A render array.
   */
  public function renderModals() {
    $build = [];

    if (count($this->registeredInUseModals) > 0) {
      $build['#pre_render'][] = [$this, 'build'];
    }

    return $build;
  }

  /**
   * {@inheritDoc}
   */
  public function reset() {
    $this->registeredInUseModals = [];
  }

  /**
   * Builds and returns the renderable array for this block plugin.
   *
   * @param array $build
   *   The original build array.
   *
   * @return array
   *   A renderable array representing the content of the block.
   */
  public function build(array $build) {
    if (count($this->registeredInUseModals) > 0) {
      $modals = TeslaModal::loadMultiple(array_keys($this->registeredInUseModals));

      /** @var \Drupal\Core\Entity\EntityViewBuilder $modalViewBuilder */
      $modalViewBuilder = \Drupal::entityTypeManager()->getViewBuilder('tesla_modal');

      /**
       * @var integer $modalId
       * @var \Drupal\tesla_modal\Entity\TeslaModalInterface $modal
       */
      foreach ($modals as $modalId => $modal) {
        if (!in_array($modalId, $this->usedModals)) {
          // Render the modal and add it to the build array.
          $modalBuild = $modalViewBuilder->view($modal, 'modal');

          // Add cache tags for this specific modal.
          $modalCacheTags = $modal->getCacheTags();
          if (!empty($modalCacheTags)) {
            $modalBuild['#cache']['tags'] = $modalCacheTags;
          }

          $build[] = $modalBuild;
          $this->usedModals[] = $modalId;
        }
      }
      $this->reset();
    }

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public static function trustedCallbacks() {
    return ['build'];
  }

}
