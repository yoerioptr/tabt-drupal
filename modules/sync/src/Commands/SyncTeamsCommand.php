<?php

namespace Drupal\tabt_sync\Commands;

use Drupal\tabt_sync\DataFetcher\TeamDataFetcher;
use Drupal\tabt_sync\Event\Sync\SyncTeamEvent;
use Drupal\tabt_sync\Event\Truncate\TruncateTeamsEvent;
use Drupal\tabt_sync\Model\Team;
use Drush\Commands\DrushCommands;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class SyncTeamsCommand extends DrushCommands {

  private EventDispatcherInterface $eventDispatcher;

  private TeamDataFetcher $dataFetcher;

  public function __construct(
    EventDispatcherInterface $eventDispatcher,
    TeamDataFetcher $dataFetcher
  ) {
    parent::__construct();
    $this->dataFetcher = $dataFetcher;
    $this->eventDispatcher = $eventDispatcher;
  }

  /**
   * @command tabt:sync:team
   */
  public function sync(): void {
    $teams = $this->dataFetcher->listItemsToSync();

    array_walk($teams, function (Team $team): void {
      $this->eventDispatcher->dispatch(new SyncTeamEvent($team));
    });
  }

  /**
   * @command tabt:truncate:team
   */
  public function truncate(): void {
    $this->eventDispatcher->dispatch(new TruncateTeamsEvent());
  }

}
