<?php

namespace Drupal\tabt_team_management\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\tabt\Entity\MemberInterface;
use Drupal\tabt\Entity\TeamInterface;
use Drupal\tabt\Entity\TournamentInterface;

interface TeamSetupInterface extends ContentEntityInterface {

  public function getId(): int;

  public function getUuid(): string;

  public function getTournament(): TournamentInterface;

  public function getTeam(): TeamInterface;

  public function getPlayers(): array;

  public function removePlayer(MemberInterface $member): void;

}
