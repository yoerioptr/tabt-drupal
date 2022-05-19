<?php

namespace Drupal\tabt_sync\Subscriber\Sync;

use Drupal\tabt\Entity\Venue;
use Drupal\tabt\Repository\VenueRepositoryInterface;
use Drupal\tabt_sync\Event\Sync\SyncVenueEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\tabt_sync\Model\Venue as VenueSource;

final class SyncVenueEventSubscriber implements EventSubscriberInterface {

  private VenueRepositoryInterface $venueRepository;

  public function __construct(VenueRepositoryInterface $venueRepository) {
    $this->venueRepository = $venueRepository;
  }

  public function syncVenue(SyncVenueEvent $event): void {
    $source = $event->getVenue();

    $venue = $this->venueRepository->getVenueByName($source->getName()) ?? Venue::create();
    $venue->set('title', $source->getName());
    $venue->set('address', $this->buildAddress($source));
    $venue->set('description', $source->getComment());
    $venue->set('phone', $this->buildPhone($source));
    $venue->set('raw_data', json_encode($source->getRawData(), TRUE));

    $venue->save();
  }

  public static function getSubscribedEvents(): array {
    return [SyncVenueEvent::class => 'syncVenue'];
  }

  private function buildAddress(VenueSource $venue): string {
    return "{$venue->getStreet()} {$venue->getTown()}";
  }

  private function buildPhone(VenueSource $venue): string {
    return $venue->getPhone();
  }

}
