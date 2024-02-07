<?php

namespace Drupal\tesla_modal;

/**
 * Provides the definition of the Tesla Modal Manager.
 */
interface TeslaModalManagerInterface {

  /**
   * Retrieves all the modals.
   *
   * @return \Drupal\tesla_modal\Entity\TeslaModalInterface[]
   *   An associative array of all modals.
   */
  public function getModals();

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
  public function registerModalUsage(string $modal_id);

  /**
   * Returns render arrays for the registered modals.
   *
   * @return array
   *   A render array.
   */
  public function renderModals();

  /**
   * Reset the modal manager.
   *
   * @return void
   *   Returns nothing.
   */
  public function reset();

  /**
   * Builds and returns the renderable array for this block plugin.
   *
   * @param array $build
   *   The original build array.
   *
   * @return array
   *   A renderable array representing the content of the block.
   */
  public function build(array $build);

}
