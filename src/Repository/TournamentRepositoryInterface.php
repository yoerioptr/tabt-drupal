<?php

namespace Drupal\tabt\Repository;

use Drupal\tabt\Entity\DivisionInterface;
use Drupal\tabt\Entity\TeamInterface;
use Drupal\tabt\Entity\TournamentInterface;

interface TournamentRepositoryInterface {

  public function getTournamentByTournamentId(string $tournament_id): ?TournamentInterface;

  /**
   * @return \Drupal\tabt\Entity\TournamentInterface[]
   */
  public function listTournaments(): array;

  /**
   * @param \Drupal\tabt\Entity\DivisionInterface $division
   *
   * @return \Drupal\tabt\Entity\TournamentInterface[]
   */
  public function listTournamentsByDivision(DivisionInterface $division): array;

  /**
   * @param \Drupal\tabt\Entity\TeamInterface $team
   *
   * @return \Drupal\tabt\Entity\TournamentInterface[]
   */
  public function listTournamentsByTeam(TeamInterface $team): array;

}
