<?php

namespace Drupal\tabt_sync;

interface TabtSyncerInterface {

  public function syncAll(): void;

  public function syncSingle(string $type): void;

  public function truncateAll(): void;

  public function truncateSingle(string $type): void;

}
