<?php

namespace Drupal\tabt_sync\Subscriber\Sync;

use Drupal\tabt_sync\Event\Sync\SyncVenueEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class SyncVenueEventSubscriber implements EventSubscriberInterface {

  public function syncVenue(SyncVenueEvent $event): void {
  }

  public static function getSubscribedEvents(): array {
    return [SyncVenueEvent::class => 'syncVenue'];
  }

}
