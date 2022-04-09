<?php

namespace Drupal\tabt_sync\Model;

use Drupal\Core\Datetime\DrupalDateTime;

final class Tournament extends SyncableItemBase {

  private string $matchId;

  private string $weekName;

  private DrupalDateTime $date;

  private string $homeTeam;

  private string $awayTeam;

  private ?string $homeClub;

  private bool $homeForfeited;

  private bool $awayForfeited;

  private int $division_id;

  public function __construct(
    string $matchId,
    string $weekName,
    DrupalDateTime $date,
    string $homeTeam,
    string $awayTeam,
    ?string $homeClub,
    bool $homeForfeited,
    bool $awayForfeited,
    int $division_id
  ) {
    $this->matchId = $matchId;
    $this->weekName = $weekName;
    $this->date = $date;
    $this->homeTeam = $homeTeam;
    $this->awayTeam = $awayTeam;
    $this->homeClub = $homeClub;
    $this->homeForfeited = $homeForfeited;
    $this->awayForfeited = $awayForfeited;
    $this->division_id = $division_id;
  }

  public function getAwayTeam(): string {
    return $this->awayTeam;
  }

  public function getDate(): DrupalDateTime {
    return $this->date;
  }

  public function getHomeClub(): string {
    return $this->homeClub;
  }

  public function getHomeTeam(): ?string {
    return $this->homeTeam;
  }

  public function getMatchId(): string {
    return $this->matchId;
  }

  public function getWeekName(): string {
    return $this->weekName;
  }

  public function isAwayForfeited(): bool {
    return $this->awayForfeited;
  }

  public function isHomeForfeited(): bool {
    return $this->homeForfeited;
  }

  public function getDivisionId(): int {
    return $this->division_id;
  }

}
