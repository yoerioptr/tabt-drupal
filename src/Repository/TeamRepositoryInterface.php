<?php

namespace Drupal\tabt\Repository;

use Drupal\tabt\Entity\TeamInterface;

interface TeamRepositoryInterface {

  public function getTeamByTeamId(string $id): ?TeamInterface;

  public function getTeamByName(string $name): ?TeamInterface;

  /**
   * @return \Drupal\tabt\Entity\TeamInterface[]
   */
  public function listTeams(): array;

}
