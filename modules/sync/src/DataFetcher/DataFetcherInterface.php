<?php

namespace Drupal\tabt_sync\DataFetcher;

interface DataFetcherInterface {

  /**
   * @return \Drupal\tabt_sync\Model\SyncableItemInterface[]
   */
  public function listItemsToSync(): array;

}
