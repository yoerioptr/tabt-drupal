<?php

namespace Drupal\tabt_sync\Commands;

use Drupal\tabt_sync\DataFetcher\DivisionDataFetcher;
use Drupal\tabt_sync\Event\Sync\SyncDivisionEvent;
use Drupal\tabt_sync\Event\Truncate\TruncateDivisionsEvent;
use Drupal\tabt_sync\Model\Division;
use Drush\Commands\DrushCommands;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class SyncDivisionsCommand extends DrushCommands {

  private EventDispatcherInterface $eventDispatcher;

  private DivisionDataFetcher $dataFetcher;

  public function __construct(
    EventDispatcherInterface $eventDispatcher,
    DivisionDataFetcher $dataFetcher
  ) {
    parent::__construct();
    $this->dataFetcher = $dataFetcher;
    $this->eventDispatcher = $eventDispatcher;
  }

  /**
   * @command tabt:sync:division
   */
  public function sync(): void {
    $divisions = $this->dataFetcher->listItemsToSync();

    array_walk($divisions, function (Division $division): void {
      $this->eventDispatcher->dispatch(new SyncDivisionEvent($division));
    });
  }

  /**
   * @command tabt:truncate:division
   */
  public function truncate(): void {
    $this->eventDispatcher->dispatch(new TruncateDivisionsEvent());
  }

}
