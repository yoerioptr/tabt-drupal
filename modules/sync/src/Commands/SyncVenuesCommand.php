<?php

namespace Drupal\tabt_sync\Commands;

use Drupal\tabt_sync\DataFetcher\VenueDataFetcher;
use Drupal\tabt_sync\Event\Sync\SyncVenueEvent;
use Drupal\tabt_sync\Event\Truncate\TruncateVenuesEvent;
use Drupal\tabt_sync\Model\Venue;
use Drush\Commands\DrushCommands;
use Symfony\Component\Console\Helper\ProgressBar;
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
    $this->writeln('Fetching API data');
    $venues = $this->dataFetcher->listItemsToSync();

    $this->writeln('Processing API data');
    $progress_bar = new ProgressBar($this->output, count($venues));
    $progress_bar->start();

    array_walk($venues, function (Venue $venue): void {
      $this->eventDispatcher->dispatch(new SyncVenueEvent($venue));
    });

    $progress_bar->finish();
    $this->writeln('');
  }

  /**
   * @command tabt:truncate:venue
   */
  public function truncate(): void {
    // TODO: Confirm action
    $this->eventDispatcher->dispatch(new TruncateVenuesEvent());
  }

}
