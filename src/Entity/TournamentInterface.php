<?php

namespace Drupal\tabt\Entity;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\ContentEntityInterface;

interface TournamentInterface extends ContentEntityInterface {

  public function getTournamentId(): string;

  public function getTournamentUniqueId(): int;

  public function getWeekName(): string;

  public function getDate(): ?DrupalDateTime;

  public function getHomeTeam(): ?TeamInterface;

  public function getAwayTeam(): ?TeamInterface;

  public function getScore(): ?string;

  public function getDivision(): DivisionInterface;

  public function getVenue(): ?VenueInterface;

  public function isHomeForfeited(): bool;

  public function isAwayForfeited(): bool;

  public function isHomeWithdrawn(): bool;

  public function isAwayWithdrawn(): bool;

}
