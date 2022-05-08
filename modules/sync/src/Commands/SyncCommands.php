<?php

namespace Drupal\tabt_sync\Commands;

use Drupal\tabt_sync\Exception\NonSyncableTypeException;
use Drupal\tabt_sync\TabtSyncerInterface;
use Drush\Commands\DrushCommands;

final class SyncCommands extends DrushCommands {

  private TabtSyncerInterface $tabtSyncer;

  public function __construct(TabtSyncerInterface $tabtSyncer) {
    parent::__construct();
    $this->tabtSyncer = $tabtSyncer;
  }

  /**
   * @command tabt:sync
   *
   * @param string $type
   */
  public function sync(string $type = 'all'): void {
    if ($type === 'all') {
      $this->tabtSyncer->syncAll();
      return;
    }

    try {
      $this->tabtSyncer->syncSingle($type);
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
      $this->tabtSyncer->truncateAll();
      return;
    }

    try {
      $this->tabtSyncer->truncateSingle($type);
    } catch (NonSyncableTypeException $exception) {
      // TODO: Display error message & logging
    }
  }

}
