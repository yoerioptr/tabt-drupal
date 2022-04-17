<?php

namespace Drupal\tabt_sync\Model;

final class Team extends SyncableItemBase {

  private string $team;

  private string $team_id;

  private int $division_id;

  private bool $isExternal;

  public function __construct(
    string $team_id,
    string $team,
    int $division_id,
    bool $isExternal
  ) {
    $this->team_id = $team_id;
    $this->team = $team;
    $this->division_id = $division_id;
    $this->isExternal = $isExternal;
  }

  public function getTeamId(): string {
    return $this->team_id;
  }

  public function getTeam(): string {
    return $this->team;
  }

  public function getDivisionId(): int {
    return $this->division_id;
  }

  public function isExternal(): bool {
    return $this->isExternal;
  }

}
