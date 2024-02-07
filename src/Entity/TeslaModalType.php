<?php

// phpcs:ignoreFile
// This is all Drupal Console generated code.
// Commenting linting doesn't apply.
namespace Drupal\tesla_modal\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Modal type entity.
 *
 * @ConfigEntityType(
 *   id = "tesla_modal_type",
 *   label = @Translation("Modal type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\tesla_modal\TeslaModalTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\tesla_modal\Form\TeslaModalTypeForm",
 *       "edit" = "Drupal\tesla_modal\Form\TeslaModalTypeForm",
 *       "delete" = "Drupal\tesla_modal\Form\TeslaModalTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\tesla_modal\TeslaModalTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "tesla_modal_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "tesla_modal",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/tesla_modal_type/{tesla_modal_type}",
 *     "add-form" = "/admin/structure/tesla_modal_type/add",
 *     "edit-form" = "/admin/structure/tesla_modal_type/{tesla_modal_type}/edit",
 *     "delete-form" = "/admin/structure/tesla_modal_type/{tesla_modal_type}/delete",
 *     "collection" = "/admin/structure/tesla_modal_type"
 *   },
 *   config_export = {
 *     "id",
 *     "label"
 *   }
 * )
 */
class TeslaModalType extends ConfigEntityBundleBase implements TeslaModalTypeInterface {

  /**
   * The Modal type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Modal type label.
   *
   * @var string
   */
  protected $label;

}
