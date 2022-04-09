<?php

namespace Drupal\tabt_sync\Commands;

use Drupal\tabt_sync\Event\Sync\SyncDivisionEvent;
use Drupal\tabt_sync\Event\Truncate\TruncateDivisionsEvent;

final class SyncDivisionsCommand extends SyncCommandBase {

  protected function syncEvent(): string {
    return SyncDivisionEvent::class;
  }

  protected function truncateEvent(): string {
    return TruncateDivisionsEvent::class;
  }

  /**
   * @command tabt:sync:division
   */
  public function sync(): void {
    parent::sync();
  }

  /**
   * @command tabt:truncate:division
   */
  public function truncate(): void {
    parent::truncate();
  }

}
