<?php

namespace Drupal\tabt\Entity;

use Drupal\Core\Entity\ContentEntityInterface;

interface TeamInterface extends ContentEntityInterface {

  public function getTeamId(): string;

  public function isExternal(): bool;

}
