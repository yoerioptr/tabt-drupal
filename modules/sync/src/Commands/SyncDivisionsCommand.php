<?php

namespace Drupal\tabt_sync\Commands;

use Drupal\tabt_sync\DataFetcher\DivisionDataFetcher;
use Drupal\tabt_sync\Event\Sync\SyncDivisionEvent;
use Drupal\tabt_sync\Event\Truncate\TruncateDivisionsEvent;
use Drupal\tabt_sync\Model\Division;
use Drush\Commands\DrushCommands;
use Symfony\Component\Console\Helper\ProgressBar;
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
    $this->writeln('Fetching API data');
    $divisions = $this->dataFetcher->listItemsToSync();

    $this->writeln('Processing API data');
    $progress_bar = new ProgressBar($this->output, count($divisions));
    $progress_bar->start();

    array_walk($divisions, function (Division $division) use ($progress_bar): void {
      $this->eventDispatcher->dispatch(new SyncDivisionEvent($division));
      $progress_bar->advance();
    });

    $progress_bar->finish();
    $this->writeln('');
  }

  /**
   * @command tabt:truncate:division
   */
  public function truncate(): void {
    // TODO: Confirm action
    $this->eventDispatcher->dispatch(new TruncateDivisionsEvent());
  }

}
