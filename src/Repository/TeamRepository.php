<?php

namespace Drupal\tabt\Repository;

use Drupal\tabt\Entity\Team;
use Drupal\tabt\Entity\TeamInterface;
use Drupal\tabt\Util\Enum\Tabt;

class TeamRepository extends RepositoryBase implements TeamRepositoryInterface {

  protected function entityTypeId(): string {
    return Tabt::TEAM;
  }

  public function getTeamByTeamId(string $id): ?TeamInterface {
    $query = $this->baseEntityQuery();
    $query->condition('team_id', $id);
    $results = $query->execute();

    return !empty($results) ? Team::load(reset($results)) : NULL;
  }

  public function getTeamByName(string $name): ?TeamInterface {
    $query = $this->baseEntityQuery();
    $query->condition('title', $name);
    $results = $query->execute();

    return !empty($results) ? Team::load(reset($results)) : NULL;
  }

  public function listTeams(): array {
    return Team::loadMultiple();
  }

}
