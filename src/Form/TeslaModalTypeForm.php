<?php

// phpcs:ignoreFile
// This is all Drupal Console generated code.
// Commenting linting doesn't apply.
namespace Drupal\tesla_modal\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;

/**
 * Class TeslaModalTypeForm.
 */
class TeslaModalTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $tesla_modal_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $tesla_modal_type->label(),
      '#description' => $this->t("Label for the Modal type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $tesla_modal_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\tesla_modal\Entity\TeslaModalType::load',
      ],
      '#disabled' => !$tesla_modal_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $tesla_modal_type = $this->entity;
    $status = $tesla_modal_type->save();

    switch ($status) {
      case SAVED_NEW:
        MessengerInterface::addMessage($this->t('Created the %label Modal type.', [
          '%label' => $tesla_modal_type->label(),
        ]));
        break;

      default:
        MessengerInterface::addMessage($this->t('Saved the %label Modal type.', [
          '%label' => $tesla_modal_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($tesla_modal_type->toUrl('collection'));
  }

}
