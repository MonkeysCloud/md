<?php

// phpcs:ignoreFile
// This is all Drupal Console generated code.
// Commenting linting doesn't apply.
namespace Drupal\tesla_modal\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Modal edit forms.
 *
 * @ingroup tesla_modal
 */
class TeslaModalForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\tesla_modal\Entity\TeslaModal */
    $form = parent::buildForm($form, $form_state);

    if (!$this->entity->isNew()) {
      $form['new_revision'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Create new revision'),
        '#default_value' => FALSE,
        '#weight' => 10,
      ];
    }

    $entity = $this->entity;

    $form['advanced'] = [
      '#type' => 'vertical_tabs',
      '#attributes' => [
        'class' => ['entity-meta'],
      ],
    ];
    $form['info'] = [
      '#type' => 'details',
      '#group' => 'advanced',
      '#title' => $this->t('Modal Info'),
      '#open' => TRUE,
      '#attributes' => [
        'class' => ['node-form-options'],
      ],
    ];
    $form['status']['#group'] = 'info';
    $form['user_id']['#group'] = 'info';
    $form['revision_log_message']['#group'] = 'info';


    // Setup the add/edit form to match the node add/edit form.
    $form['#theme'] = ['node_edit_form'];
    $form['#attached']['library'][] = 'seven/node-form';

    $form['advanced']['#type'] = 'container';
    $form['meta']['#type'] = 'container';
    $form['meta']['#access'] = TRUE;
    $form['meta']['changed']['#wrapper_attributes']['class'][] = 'container-inline';
    $form['meta']['author']['#wrapper_attributes']['class'][] = 'container-inline';

    $form['revision_information']['#type'] = 'container';
    $form['revision_information']['#group'] = 'meta';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;

    // Save as a new revision if requested to do so.
    if (!$form_state->isValueEmpty('new_revision') && $form_state->getValue('new_revision') != FALSE) {
      $entity->setNewRevision();

      // If a new revision is created, save the current user as revision author.
      $entity->setRevisionCreationTime( \Drupal::time()->getRequestTime());
      $entity->setRevisionUserId(\Drupal::currentUser()->id());
    }
    else {
      $entity->setNewRevision(FALSE);
    }

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Modal.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Modal.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.tesla_modal.canonical', ['tesla_modal' => $entity->id()]);
  }

}
