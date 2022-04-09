<?php

namespace Drupal\tabt_sync\Commands;

use Drupal\tabt_sync\DataFetcher\VenueDataFetcher;
use Drupal\tabt_sync\Event\Sync\syncVenueEvent;
use Drupal\tabt_sync\Event\Truncate\TruncateVenuesEvent;
use Drupal\tabt_sync\Model\Venue;
use Drush\Commands\DrushCommands;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class SyncVenuesCommand extends DrushCommands {

  private EventDispatcherInterface $eventDispatcher;

  private VenueDataFetcher $dataFetcher;

  public function __construct(
    EventDispatcherInterface $eventDispatcher,
    VenueDataFetcher $dataFetcher
  ) {
    parent::__construct();
    $this->dataFetcher = $dataFetcher;
    $this->eventDispatcher = $eventDispatcher;
  }

  /**
   * @command tabt:sync:venue
   */
  public function sync(): void {
    $venues = $this->dataFetcher->listItemsToSync();

    array_walk($venues, function (Venue $venue): void {
      $this->eventDispatcher->dispatch(new SyncVenueEvent($venue));
    });
  }

  /**
   * @command tabt:truncate:venue
   */
  public function truncate(): void {
    $this->eventDispatcher->dispatch(new TruncateVenuesEvent());
  }

}
