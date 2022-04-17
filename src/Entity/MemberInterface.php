<?php

namespace Drupal\tabt\Entity;

use Drupal\Core\Entity\ContentEntityInterface;

interface MemberInterface extends ContentEntityInterface {

  public function getPosition(): int;

  public function getUniqueIndex(): int;

  public function getRankingIndex(): int;

  public function getFirstName(): string;

  public function getLastName(): string;

  public function getRanking(): string;

}
