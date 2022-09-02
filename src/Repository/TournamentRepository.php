<?php

namespace Drupal\tabt\Repository;

use Drupal\tabt\Entity\DivisionInterface;
use Drupal\tabt\Entity\TeamInterface;
use Drupal\tabt\Entity\Tournament;
use Drupal\tabt\Entity\TournamentInterface;
use Drupal\tabt\Util\Enum\Tabt;

class TournamentRepository extends RepositoryBase implements TournamentRepositoryInterface {

  protected function entityTypeId(): string {
    return Tabt::TOURNAMENT;
  }

  public function getTournamentByTournamentId(string $tournament_id): ?TournamentInterface {
    $query = $this->baseEntityQuery();
    $query->condition('tournament_id', $tournament_id);
    $results = $query->execute();

    return !empty($results) ? Tournament::load(reset($results)) : NULL;
  }

  public function listTournaments(): array {
    return Tournament::loadMultiple();
  }

  public function listTournamentsByDivision(DivisionInterface $division): array {
    $query = $this->baseEntityQuery();
    $query->condition('division', $division);
    $results = $query->execute();

    return Tournament::loadMultiple($results);
  }

  public function listTournamentsByTeam(TeamInterface $team): array {
    $query = $this->baseEntityQuery();
    $team_condition_group = $query->orConditionGroup();
    $team_condition_group->condition('home_team', $team);
    $team_condition_group->condition('away_team', $team);
    $query->condition($team_condition_group);
    $results = $query->execute();

    return Tournament::loadMultiple($results);
  }

}
