<?php

namespace Drupal\tabt_sync\Commands;

use Drupal\tabt_sync\DataFetcher\DataFetcherInterface;
use Drush\Commands\DrushCommands;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class SyncCommandBase extends DrushCommands {

  private EventDispatcherInterface $eventDispatcher;

  private DataFetcherInterface $dataFetcher;

  public function __construct(
    EventDispatcherInterface $eventDispatcher,
    DataFetcherInterface $dataFetcher
  ) {
    parent::__construct();
    $this->eventDispatcher = $eventDispatcher;
    $this->dataFetcher = $dataFetcher;
  }

  public function sync(): void {
    $event_class = $this->syncEvent();
    foreach ($this->dataFetcher->listItemsToSync() as $item) {
      $this->eventDispatcher->dispatch(new $event_class($item));
    }
  }

  public function truncate(): void {

  }

  abstract protected function syncEvent(): string;

  abstract protected function truncateEvent(): string;

}
