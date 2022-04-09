<?php

namespace Drupal\tabt_sync\Commands;

use Drupal\tabt_sync\Event\Sync\syncVenueEvent;
use Drupal\tabt_sync\Event\Truncate\TruncateVenuesEvent;

final class SyncVenuesCommand extends SyncCommandBase {

  protected function syncEvent(): string {
    return SyncVenueEvent::class;
  }

  protected function truncateEvent(): string {
    return TruncateVenuesEvent::class;
  }

  /**
   * @command tabt:sync:venue
   */
  public function sync(): void {
    parent::sync();
  }

  /**
   * @command tabt:truncate:venue
   */
  public function truncate(): void {
    parent::truncate();
  }

}
