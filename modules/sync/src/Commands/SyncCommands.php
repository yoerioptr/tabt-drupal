<?php

namespace Drupal\tabt_sync\Commands;

use Drupal\tabt_sync\Exception\NonSyncableTypeException;
use Drupal\tabt_sync\TabtSyncer;
use Drush\Commands\DrushCommands;

final class SyncCommands extends DrushCommands {

  /**
   * @command tabt:sync
   *
   * @param string $type
   */
  public function sync(string $type = 'all'): void {
    if ($type === 'all') {
      TabtSyncer::syncAll();
      return;
    }

    try {
      TabtSyncer::syncSingle($type);
    } catch (NonSyncableTypeException $exception) {
      // TODO: Display error message & logging
    }
  }

  /**
   * @command tabt:truncate
   *
   * @param string $type
   */
  public function truncate(string $type = 'all'): void {
    if ($type === 'all') {
      TabtSyncer::truncateAll();
      return;
    }

    try {
      TabtSyncer::truncateSingle($type);
    } catch (NonSyncableTypeException $exception) {
      // TODO: Display error message & logging
    }
  }

}
