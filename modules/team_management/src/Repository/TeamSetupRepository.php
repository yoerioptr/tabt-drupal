<?php

namespace Drupal\tabt_team_management\Repository;

use Drupal\tabt\Entity\MemberInterface;
use Drupal\tabt\Entity\TeamInterface;
use Drupal\tabt\Entity\TournamentInterface;
use Drupal\tabt\Repository\RepositoryBase;
use Drupal\tabt_team_management\Entity\TeamSetup;
use Drupal\tabt_team_management\Entity\TeamSetupInterface;

final class TeamSetupRepository extends RepositoryBase implements TeamSetupRepositoryInterface {

  public function getSetup(TournamentInterface $tournament, TeamInterface $team): ?TeamSetupInterface {
    $query = $this->baseEntityQuery();
    $query->condition('tournament.target_id', $tournament->id());
    $query->condition('team.target_id', $team->id());
    $results = $query->execute();

    return !empty($results)
      ? TeamSetup::load(reset($results))
      : NULL;
  }

  public function listSetupsByMember(MemberInterface $member): array {
    $query = $this->baseEntityQuery();
    $query->condition('members.target_id', $member->id());
    $results = $query->execute();

    return !empty($results)
      ? array_combine($results, TeamSetup::loadMultiple($results))
      : [];
  }

  public function listSetupsByMemberAndWeekName(MemberInterface $member, string $week_name): array {
    $query = $this->baseEntityQuery();
    $query->condition('tournament.entity:tabt_tournament.week_name', $week_name);
    $query->condition('members.target_id', $member->id());
    $results = $query->execute();

    return !empty($results)
      ? array_combine($results, TeamSetup::loadMultiple($results))
      : [];
  }

  public function listSetupsByTeam(TeamInterface $team): array {
    $query = $this->baseEntityQuery();
    $query->condition('team.target_id', $team->id());
    $results = $query->execute();

    return !empty($results)
      ? array_combine($results, TeamSetup::loadMultiple($results))
      : [];
  }

  public function listSetupsByTournament(TournamentInterface $tournament): array {
    $query = $this->baseEntityQuery();
    $query->condition('tournament.target_id', $tournament->id());
    $results = $query->execute();

    return !empty($results)
      ? array_combine($results, TeamSetup::loadMultiple($results))
      : [];
  }

  public function listSetupsByWeekName(string $week_name): array {
    $query = $this->baseEntityQuery();
    $query->condition('tournament.entity:tabt_tournament.week_name', $week_name);
    $results = $query->execute();

    return !empty($results)
      ? array_combine($results, TeamSetup::loadMultiple($results))
      : [];
  }

  protected function entityTypeId(): string {
    return 'tabt_team_setup';
  }

}
