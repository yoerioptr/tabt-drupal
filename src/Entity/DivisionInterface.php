<?php

namespace Drupal\tabt\Entity;

use Drupal\Core\Entity\ContentEntityInterface;

interface DivisionInterface extends ContentEntityInterface {

  public function getDivisionId(): int;

  public function getDivisionCategory(): int;

  public function getDivisionName(): string;

}
