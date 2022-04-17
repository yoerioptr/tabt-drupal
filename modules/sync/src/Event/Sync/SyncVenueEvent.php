<?php

namespace Drupal\tabt_sync\Event\Sync;

use Drupal\Component\EventDispatcher\Event;
use Drupal\tabt_sync\Model\Venue;

final class SyncVenueEvent extends Event {

  private Venue $tournament;

  public function __construct(Venue $tournament) {
    $this->tournament = $tournament;
  }

  public function getVenue(): Venue {
    return $this->tournament;
  }

}
