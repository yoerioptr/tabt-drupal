<?php

namespace Drupal\tabt_sync\Commands;

use Drupal\tabt_sync\DataFetcher\TournamentDataFetcher;
use Drupal\tabt_sync\Event\Sync\SyncTournamentEvent;
use Drupal\tabt_sync\Event\Truncate\TruncateTournamentsEvent;
use Drupal\tabt_sync\Model\Tournament;
use Drush\Commands\DrushCommands;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class SyncTournamentsCommand extends DrushCommands {

  private EventDispatcherInterface $eventDispatcher;

  private TournamentDataFetcher $dataFetcher;

  public function __construct(
    EventDispatcherInterface $eventDispatcher,
    TournamentDataFetcher $dataFetcher
  ) {
    parent::__construct();
    $this->dataFetcher = $dataFetcher;
    $this->eventDispatcher = $eventDispatcher;
  }

  /**
   * @command tabt:sync:tournament
   */
  public function sync(): void {
    $this->writeln('Fetching API data');
    $tournaments = $this->dataFetcher->listItemsToSync();

    $this->writeln('Processing API data');
    $progress_bar = new ProgressBar($this->output, count($tournaments));
    $progress_bar->start();

    array_walk($tournaments, function (Tournament $tournament): void {
      $this->eventDispatcher->dispatch(new SyncTournamentEvent($tournament));
    });

    $progress_bar->finish();
    $this->writeln('');
  }

  /**
   * @command tabt:truncate:tournament
   */
  public function truncate(): void {
    // TODO: Confirm action
    $this->eventDispatcher->dispatch(new TruncateTournamentsEvent());
  }

}
