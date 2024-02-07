<?php
// phpcs:ignoreFile
// This is a copy of a Core file.
namespace Drupal\tesla_modal\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Drupal\Core\Routing\RoutingEvents;
use Symfony\Component\Routing\RouteCollection;

/**
 * Subscriber for Modal routes.
 */
class TeslaModalRouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    $route_names = [
      "entity.tesla_modal.canonical",
      "layout_builder.overrides.tesla_modal.view",
    ];
    foreach ($route_names as $route_name) {
      /* @var $edit_route \Symfony\Component\Routing\Route */
      if ($edit_route = $collection->get($route_name)) {
        $edit_route->setOption('_admin_route', FALSE);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events = parent::getSubscribedEvents();
    // Should run after AdminRouteSubscriber so the routes can inherit admin
    // status of the edit routes on entities. Therefore priority -210.
    $events[RoutingEvents::ALTER] = ['onAlterRoutes', -210];
    return $events;
  }

}
