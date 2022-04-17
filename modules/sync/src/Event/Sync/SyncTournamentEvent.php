<?php

namespace Drupal\tabt_sync\Event\Sync;

use Drupal\Component\EventDispatcher\Event;
use Drupal\tabt_sync\Model\Tournament;

final class SyncTournamentEvent extends Event {

  private Tournament $tournament;

  public function __construct(Tournament $tournament) {
    $this->tournament = $tournament;
  }

  public function getTournament(): Tournament {
    return $this->tournament;
  }

}
