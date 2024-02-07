<?php

// phpcs:ignoreFile
// This is all Drupal Console generated code.
// Commenting linting doesn't apply.
namespace Drupal\tesla_modal\Form;

use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for deleting a Modal revision.
 *
 * @ingroup tesla_modal
 */
class TeslaModalRevisionDeleteForm extends ConfirmFormBase {


  /**
   * The Modal revision.
   *
   * @var \Drupal\tesla_modal\Entity\TeslaModalInterface
   */
  protected $revision;

  /**
   * The Modal storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $TeslaModalStorage;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * Constructs a new TeslaModalRevisionDeleteForm.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $entity_storage
   *   The entity storage.
   * @param \Drupal\Core\Database\Connection $connection
   *   The database connection.
   */
  public function __construct(EntityStorageInterface $entity_storage, Connection $connection) {
    $this->TeslaModalStorage = $entity_storage;
    $this->connection = $connection;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $entity_manager = $container->get('entity_type.manager');
    return new static(
      $entity_manager->getStorage('tesla_modal'),
      $container->get('database')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'tesla_modal_revision_delete_confirm';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return t('Are you sure you want to delete the revision from %revision-date?', ['%revision-date' => \Drupal::service('date.formatter')->format($this->revision->getRevisionCreationTime())]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.tesla_modal.version_history', ['tesla_modal' => $this->revision->id()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $tesla_modal_revision = NULL) {
    $this->revision = $this->TeslaModalStorage->loadRevision($tesla_modal_revision);
    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->TeslaModalStorage->deleteRevision($this->revision->getRevisionId());

    $this->logger('content')->notice('Modal: deleted %title revision %revision.', ['%title' => $this->revision->label(), '%revision' => $this->revision->getRevisionId()]);
    MessengerInterface::addMessage(t('Revision from %revision-date of Modal %title has been deleted.', ['%revision-date' => \Drupal::service('date.formatter')->format($this->revision->getRevisionCreationTime()), '%title' => $this->revision->label()]));
    $form_state->setRedirect(
      'entity.tesla_modal.canonical',
       ['tesla_modal' => $this->revision->id()]
    );
    if ($this->connection->query('SELECT COUNT(DISTINCT vid) FROM {tesla_modal_field_revision} WHERE id = :id', [':id' => $this->revision->id()])->fetchField() > 1) {
      $form_state->setRedirect(
        'entity.tesla_modal.version_history',
         ['tesla_modal' => $this->revision->id()]
      );
    }
  }

}
