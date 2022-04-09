<?php

namespace Drupal\tabt_sync\Subscriber\Truncate;

use Drupal\tabt\Entity\VenueInterface;
use Drupal\tabt_sync\Event\Truncate\TruncateVenuesEvent;
use Drupal\tabt\Repository\VenueRepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class TruncateVenuesEventSubscriber implements EventSubscriberInterface {

  private VenueRepositoryInterface $venueRepository;

  public function __construct(VenueRepositoryInterface $venueRepository) {
    $this->venueRepository = $venueRepository;
  }

  public function removeAllVenues(): void {
    $venues = $this->venueRepository->listVenues();
    array_walk($venues, function (VenueInterface $venue): void {
      $venue->delete();
    });
  }

  public static function getSubscribedEvents(): array {
    return [TruncateVenuesEvent::class => 'removeAllVenues'];
  }

}
