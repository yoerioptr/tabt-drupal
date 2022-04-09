<?php

namespace Drupal\tabt_sync\Commands;

use Drupal\tabt_sync\Event\Sync\SyncTeamEvent;
use Drupal\tabt_sync\Event\Truncate\TruncateTeamsEvent;

final class SyncTeamsCommand extends SyncCommandBase {

  protected function syncEvent(): string {
    return SyncTeamEvent::class;
  }

  protected function truncateEvent(): string {
    return TruncateTeamsEvent::class;
  }

  /**
   * @command tabt:sync:team
   */
  public function sync(): void {
    parent::sync();
  }

  /**
   * @command tabt:truncate:team
   */
  public function truncate(): void {
    parent::truncate();
  }

}
