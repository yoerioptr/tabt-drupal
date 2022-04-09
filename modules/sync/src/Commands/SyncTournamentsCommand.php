<?php

namespace Drupal\tabt_sync\Commands;

use Drupal\tabt_sync\Event\Sync\SyncTournamentEvent;
use Drupal\tabt_sync\Event\Truncate\TruncateTournamentsEvent;

final class SyncTournamentsCommand extends SyncCommandBase {

  protected function syncEvent(): string {
    return SyncTournamentEvent::class;
  }

  protected function truncateEvent(): string {
    return TruncateTournamentsEvent::class;
  }

  /**
   * @command tabt:sync:tournament
   */
  public function sync(): void {
    parent::sync();
  }

  /**
   * @command tabt:truncate:tournament
   */
  public function truncate(): void {
    parent::truncate();
  }

}
