<?php

// phpcs:ignoreFile
// This is all Drupal Console generated code.
// Commenting linting doesn't apply.
namespace Drupal\tesla_modal;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
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
class TeslaModalStorage extends SqlContentEntityStorage implements TeslaModalStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(TeslaModalInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {tesla_modal_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {tesla_modal_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(TeslaModalInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {tesla_modal_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('tesla_modal_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
