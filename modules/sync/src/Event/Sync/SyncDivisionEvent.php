<?php

namespace Drupal\tabt_sync\Event\Sync;

use Drupal\Component\EventDispatcher\Event;
use Drupal\tabt_sync\Model\Division;

final class SyncDivisionEvent extends Event {

  private Division $division;

  public function __construct(Division $division) {
    $this->division = $division;
  }

  public function getDivision(): Division {
    return $this->division;
  }

}
