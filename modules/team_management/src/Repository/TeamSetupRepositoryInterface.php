<?php

namespace Drupal\tabt_team_management\Repository;

use Drupal\tabt\Entity\MemberInterface;
use Drupal\tabt\Entity\TeamInterface;
use Drupal\tabt\Entity\TournamentInterface;
use Drupal\tabt_team_management\Entity\TeamSetupInterface;

interface TeamSetupRepositoryInterface {

  public function getSetup(TournamentInterface $tournament, TeamInterface $team): ?TeamSetupInterface;

  public function listSetupsByTournament(TournamentInterface $tournament): array;

  public function listSetupsByTeam(TeamInterface $team): array;

  public function listSetupsByMember(MemberInterface $member): array;

  public function listSetupsByMemberAndWeekName(MemberInterface $member, string $week_name): array;

  public function listSetupsByWeekName(string $week_name): array;

}
