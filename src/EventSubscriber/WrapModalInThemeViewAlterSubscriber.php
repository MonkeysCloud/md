<?php

namespace Drupal\tesla_modal\EventSubscriber;

use Drupal\core_event_dispatcher\Event\Entity\EntityViewAlterEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\hook_event_dispatcher\HookEventDispatcherInterface;

/**
 * Class Wrap Modal In Theme View Alter Subscriber.
 */
class WrapModalInThemeViewAlterSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      // Static class constant => method on this class.
      HookEventDispatcherInterface::ENTITY_VIEW_ALTER => 'wrapModalInTheme',
    ];
  }

  /**
   * This method is called whenever an entity is viewed.
   *
   * @param \Drupal\core_event_dispatcher\Event\Entity\EntityViewAlterEvent $event The event object.
   *
   * @return void
   *   void
   */
  public function wrapModalInTheme(EntityViewAlterEvent $event): void {
    $build = &$event->getBuild();
    $entity = $event->getEntity();

    if ($entity->getEntityTypeId() == 'tesla_modal' && $build['#view_mode'] == 'modal') {
      /** @var \Drupal\tesla_modal\Entity\TeslaModalInterface $modal */
      $modal = $entity;

      unset($build['#pre_render']);

      $shouldShowTitleCheckbox = $modal->shouldShowTitle();
      $title = '';

      if ($shouldShowTitleCheckbox) {
        if ($modal->hasField('field_modal_display_title')) {
          $title = $modal->get('field_modal_display_title')->value;
        }
        else {
          $title = $modal->label();
        }
      }

      $build = [
        '#theme' => 'tesla_modal_modal',
        '#modal_title' => $title,
        '#background_color' => strtolower($modal->getThemeName()) == 'dark' ? 'black' : 'white',
        '#modal_id' => $modal->getHtmlId(),
        '#cache' => $build['#cache'],
        '#btn_id' => $modal->getButtonId(),
        '#modal_content' => $build,
      ];
    }
  }

}
