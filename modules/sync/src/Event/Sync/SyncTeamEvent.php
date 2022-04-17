<?php

namespace Drupal\tabt_sync\Event\Sync;

use Drupal\Component\EventDispatcher\Event;
use Drupal\tabt_sync\Model\Team;

final class SyncTeamEvent extends Event {

  private Team $tournament;

  public function __construct(Team $tournament) {
    $this->tournament = $tournament;
  }

  public function getTeam(): Team {
    return $this->tournament;
  }

}
