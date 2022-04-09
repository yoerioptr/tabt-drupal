<?php

namespace Drupal\tabt_sync\Commands;

use Drupal\tabt_sync\Event\Sync\SyncMemberEvent;
use Drupal\tabt_sync\Event\Truncate\TruncateMembersEvent;

final class SyncMembersCommand extends SyncCommandBase {

  protected function syncEvent(): string {
    return SyncMemberEvent::class;
  }

  protected function truncateEvent(): string {
    return TruncateMembersEvent::class;
  }

  /**
   * @command tabt:sync:member
   */
  public function sync(): void {
    parent::sync();
  }

  /**
   * @command tabt:truncate:member
   */
  public function truncate(): void {
    parent::truncate();
  }

}
