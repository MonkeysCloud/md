<?php

// phpcs:ignoreFile
// This is all Drupal Console generated code.
// Commenting linting doesn't apply.
namespace Drupal\tesla_modal\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Modal entities.
 *
 * @ingroup tesla_modal
 */
interface TeslaModalInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Modal name.
   *
   * @return string
   *   Name of the Modal.
   */
  public function getName();

  /**
   * Sets the Modal name.
   *
   * @param string $name
   *   The Modal name.
   *
   * @return \Drupal\tesla_modal\Entity\TeslaModalInterface
   *   The called Modal entity.
   */
  public function setName($name);

  /**
   * Gets the HTML ID.
   *
   * @return string
   *   The HTML ID.
   */
  public function getHtmlId();

  /**
   * Sets the HTML ID.
   *
   * @param string $html_id
   *   The Modal HTML ID.
   *
   * @return \Drupal\tesla_modal\Entity\TeslaModalInterface
   *   The called Modal entity.
   */
  public function setHtmlId($html_id);

  /**
   * Gets the Button ID.
   *
   * @return string
   *   The button ID.
   */
  public function getButtonId();

  /**
   * Gets the TDS Theme.
   *
   * @return int
   *   The TDS Theme ID.
   */
  public function getTheme();

  /**
   * Gets the TDS Theme.
   *
   * @return \Drupal\tesla_modal\Entity\TeslaModalInterface
   *   The called Modal entity.
   */
  public function getThemeName();

  /**
   * Sets the TDS Theme.
   *
   * @param int $theme
   *   The key for the TDS Theme.
   *
   * @return object
   *   The entity object ($this).
   */
  public function setTheme($theme);

  /**
   * Gets the Modal creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Modal.
   */
  public function getCreatedTime();

  /**
   * Sets the Modal creation timestamp.
   *
   * @param int $timestamp
   *   The Modal creation timestamp.
   *
   * @return \Drupal\tesla_modal\Entity\TeslaModalInterface
   *   The called Modal entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Modal published status indicator.
   *
   * Unpublished Modal are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Modal is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Modal.
   *
   * @param bool $published
   *   TRUE to set this Modal to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\tesla_modal\Entity\TeslaModalInterface
   *   The called Modal entity.
   */
  public function setPublished($published);

  /**
   * Gets the Modal revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Modal revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\tesla_modal\Entity\TeslaModalInterface
   *   The called Modal entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Modal revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Modal revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\tesla_modal\Entity\TeslaModalInterface
   *   The called Modal entity.
   */
  public function setRevisionUserId($uid);

  /**
   * Should the title be displayed?
   *
   * An editor can configure the modal to hide the title. This returns
   * true if the modal's title should be rendered, false otherwise.
   *
   * @return bool
   *   True if the title should be displayed.
   */
  public function shouldShowTitle();
}
