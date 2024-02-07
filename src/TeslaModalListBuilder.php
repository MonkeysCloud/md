<?php

// phpcs:ignoreFile
// This is all Drupal Console generated code.
// Commenting linting doesn't apply.
namespace Drupal\tesla_modal;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Modal entities.
 *
 * @ingroup tesla_modal
 */
class TeslaModalListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['name'] = $this->t('Name');
    $header['type'] = $this->t('Modal Type');
    $header['id'] = $this->t('Modal ID');
    $header['theme'] = $this->t('Modal Theme');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\tesla_modal\Entity\TeslaModal */
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.tesla_modal.edit_form',
      ['tesla_modal' => $entity->id()]
    );
    $row['type'] = $entity->bundle();
    $row['id'] = $entity->getHtmlId();
    $row['theme'] = $entity->getThemeName();
    return $row + parent::buildRow($entity);
  }

}
