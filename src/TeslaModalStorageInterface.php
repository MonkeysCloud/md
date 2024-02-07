<?php

// phpcs:ignoreFile
// This is all Drupal Console generated code.
// Commenting linting doesn't apply.
namespace Drupal\tesla_modal;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\tesla_modal\Entity\TeslaModalInterface;

/**
 * Defines the storage handler class for Modal entities.
 *
 * This extends the base storage class, adding required special handling for
 * Modal entities.
 *
 * @ingroup tesla_modal
 */
interface TeslaModalStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Modal revision IDs for a specific Modal.
   *
   * @param \Drupal\tesla_modal\Entity\TeslaModalInterface $entity
   *   The Modal entity.
   *
   * @return int[]
   *   Modal revision IDs (in ascending order).
   */
  public function revisionIds(TeslaModalInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Modal author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Modal revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\tesla_modal\Entity\TeslaModalInterface $entity
   *   The Modal entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(TeslaModalInterface $entity);

  /**
   * Unsets the language for all Modal with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
