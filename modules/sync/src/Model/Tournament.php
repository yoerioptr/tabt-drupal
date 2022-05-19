<?php

namespace Drupal\tabt_sync\Model;

final class Tournament extends SyncableItemBase {

  private string $matchId;

  private string $weekName;

  private ?int $date;

  private string $homeTeam;

  private string $awayTeam;

  private ?string $homeClub;

  private bool $homeForfeited;

  private bool $awayForfeited;

  private int $divisionId;

  private string $homeWithdrawn;

  private string $awayWithdrawn;

  private string $score;

  private ?string $venue;

  private array $rawData;

  public function __construct(
    string $matchId,
    string $weekName,
    ?\DateTime $date,
    string $homeTeam,
    string $awayTeam,
    ?string $homeClub,
    bool $homeForfeited,
    bool $awayForfeited,
    string $homeWithdrawn,
    string $awayWithdrawn,
    int $divisionId,
    string $score,
    ?string $venue,
    array $rawData
  ) {
    $this->matchId = $matchId;
    $this->weekName = $weekName;
    $this->date = !is_null($date) ? $date->getTimestamp() : NULL;
    $this->homeTeam = $homeTeam;
    $this->awayTeam = $awayTeam;
    $this->homeClub = $homeClub;
    $this->homeForfeited = $homeForfeited;
    $this->awayForfeited = $awayForfeited;
    $this->divisionId = $divisionId;
    $this->homeWithdrawn = $homeWithdrawn;
    $this->awayWithdrawn = $awayWithdrawn;
    $this->score = $score;
    $this->venue = $venue;
    $this->rawData = $rawData;
  }

  public function getAwayTeam(): string {
    return $this->awayTeam;
  }

  public function getDate(): ?int {
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
    return $this->divisionId;
  }

  public function getAwayWithdrawn(): string {
    return $this->awayWithdrawn;
  }

  public function getHomeWithdrawn(): string {
    return $this->homeWithdrawn;
  }

  public function getScore(): string {
    return $this->score;
  }

  public function getVenue(): ?string {
    return $this->venue;
  }

  public function getRawData(): array {
    return $this->rawData;
  }

}
