<?php

namespace Drupal\tabt\Context;

final class ClubContext extends ConfigContext {

  public function getClub(): string {
    return $this->getConfigValue('club');
  }

}
