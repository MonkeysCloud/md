<?php

// phpcs:ignoreFile
// This is all Drupal Console generated code.
// Commenting linting doesn't apply.
namespace Drupal\tesla_modal\Entity;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\layout_builder\Plugin\DataType\SectionData;
use Drupal\layout_builder\Plugin\Field\FieldType\LayoutSectionItem;
use Drupal\layout_builder\SectionComponent;
use Drupal\user\UserInterface;

/**
 * Defines the Modal entity.
 *
 * @ingroup tesla_modal
 *
 * @ContentEntityType(
 *   id = "tesla_modal",
 *   label = @Translation("Modal"),
 *   bundle_label = @Translation("Modal type"),
 *   handlers = {
 *     "storage" = "Drupal\tesla_modal\TeslaModalStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\tesla_modal\TeslaModalListBuilder",
 *     "views_data" = "Drupal\tesla_modal\Entity\TeslaModalViewsData",
 *     "translation" = "Drupal\tesla_modal\TeslaModalTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\tesla_modal\Form\TeslaModalForm",
 *       "add" = "Drupal\tesla_modal\Form\TeslaModalForm",
 *       "edit" = "Drupal\tesla_modal\Form\TeslaModalForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *     "access" = "Drupal\tesla_modal\TeslaModalAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\tesla_modal\TeslaModalHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "tesla_modal",
 *   data_table = "tesla_modal_field_data",
 *   revision_table = "tesla_modal_revision",
 *   revision_data_table = "tesla_modal_field_revision",
 *   translatable = TRUE,
 *   admin_permission = "administer modal entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "vid",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *     "theme" = "theme",
 *     "html_id" = "html_id",
 *     "hide_title" = "hide_title",
 *   },
 *   revision_metadata_keys = {
 *     "revision_user" = "revision_user",
 *     "revision_created" = "revision_created",
 *     "revision_log_message" = "revision_log_message"
 *   },
 *   links = {
 *     "canonical" = "/admin/content/tesla_modal/{tesla_modal}",
 *     "add-page" = "/admin/content/tesla_modal/add",
 *     "add-form" = "/admin/content/tesla_modal/add/{tesla_modal_type}",
 *     "edit-form" = "/admin/content/tesla_modal/{tesla_modal}/edit",
 *     "delete-form" = "/admin/content/tesla_modal/{tesla_modal}/delete",
 *     "version-history" = "/admin/content/tesla_modal/{tesla_modal}/revisions",
 *     "revision" = "/admin/content/tesla_modal/{tesla_modal}/revisions/{tesla_modal_revision}/view",
 *     "revision_revert" = "/admin/content/tesla_modal/{tesla_modal}/revisions/{tesla_modal_revision}/revert",
 *     "revision_delete" = "/admin/content/tesla_modal/{tesla_modal}/revisions/{tesla_modal_revision}/delete",
 *     "translation_revert" = "/admin/content/tesla_modal/{tesla_modal}/revisions/{tesla_modal_revision}/revert/{langcode}",
 *     "collection" = "/admin/content/tesla_modal",
 *   },
 *   bundle_entity_type = "tesla_modal_type",
 *   field_ui_base_route = "entity.tesla_modal_type.edit_form"
 * )
 */
class TeslaModal extends RevisionableContentEntityBase implements TeslaModalInterface {

  use EntityChangedTrait;
  use StringTranslationTrait;

  /**
   * The TDS Themes.
   *
   * @var array
   */
  protected $tdsThemes = [];

  /**
   * {@inheritdoc}
   */
  public function __construct(array $values, $entity_type, $bundle = FALSE, $translations = []) {
    parent::__construct($values, $entity_type, $bundle, $translations);
    $this->tdsThemes = [
      0 => $this->t('Light'),
      1 => $this->t('Dark'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function urlRouteParameters($rel) {
    $uri_route_parameters = parent::urlRouteParameters($rel);

    if ($rel === 'revision_revert' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }
    elseif ($rel === 'revision_delete' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }

    return $uri_route_parameters;
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);

    foreach (array_keys($this->getTranslationLanguages()) as $langcode) {
      $translation = $this->getTranslation($langcode);

      // If no owner has been set explicitly, make the anonymous user the owner.
      if (!$translation->getOwner()) {
        $translation->setOwnerId(0);
      }
    }

    // If no revision author has been set explicitly, make the tesla_modal owner the
    // revision author.
    if (!$this->getRevisionUser()) {
      $this->setRevisionUserId($this->getOwnerId());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    $tags = [];

    if ($this->hasField('layout_builder__layout')) {
      $layout = $this->get('layout_builder__layout');

      /** @var \Drupal\layout_builder\Plugin\Field\FieldType\LayoutSectionItem $layout_section_item */
      foreach ($layout as $layout_section_item) {
        /** @var SectionData $section */
        $section = $layout_section_item->get('section')->getValue();

        /** @var SectionComponent $section_component */
        foreach ($section->getComponents() as $section_component) {
          /** @var CacheableDependencyInterface $block */
          $block = $section_component->getPlugin();
          $tags = Cache::mergeTags($tags, $block->getCacheTags());
        }
      }
    }
    return Cache::mergeTags(parent::getCacheTags(), $tags);
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getHtmlId() {
    return $this->get('html_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setHtmlId($html_id) {
    $this->set('html_id', $html_id);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getButtonId() {
    return $this->getHtmlId() . '_btn';
  }

  /**
   * {@inheritdoc}
   */
  public function getTheme() {
    return $this->get('theme')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getThemeName() {
   return isset($this->tdsThemes[$this->getTheme()]) ? $this->tdsThemes[$this->getTheme()] : '';
  }

  /**
   * {@inheritdoc}
   */
  public function setTheme($theme) {
    $this->set('theme', $theme);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? TRUE : FALSE);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Modal entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setDescription(t('The title of the Modal entity.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -6,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['hide_title'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Hide Modal Title'))
      ->setDescription(t('Hide the Modal title on Modal display.'))
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setRevisionable(TRUE)
      ->setTranslatable(FALSE);

    $fields['theme'] = BaseFieldDefinition::create('list_integer')
      ->setLabel(t('Modal Theme'))
      ->setDescription(t('The TDS Modal Theme to use.'))
      ->setDefaultValue(0)
      ->setSettings([
        'allowed_values' => [
          0 => 'Light',
          1 => 'Dark',
        ],
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRevisionable(TRUE)
      ->setRequired(TRUE)
      ->setTranslatable(FALSE);

    $fields['html_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Modal HTML ID'))
      ->setDescription(t('The HTML ID for the Modal. It must be a unique ID that starts with a letter(a-zA-Z) and thereafter can contain any combination of letters, numbers(0-9), dashes(-) & underscores(_).'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -6,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Modal is published.'))
      ->setRevisionable(TRUE)
      ->setDefaultValue(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -3,
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['revision_translation_affected'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Revision translation affected'))
      ->setDescription(t('Indicates if the last edit of a translation belongs to current revision.'))
      ->setReadOnly(TRUE)
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE);

    return $fields;
  }
  /**
   * {@inheritdoc}
   */
  public function shouldShowTitle() {
    return !$this->get('hide_title')->value;
  }
}
