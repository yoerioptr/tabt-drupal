<?php

namespace Drupal\tabt_team_management\Repository;

use Drupal\tabt\Repository\RepositoryBase;

final class TeamSetupRepository extends RepositoryBase implements TeamSetupRepositoryInterface {

  protected function entityTypeId(): string {
    return 'tabt_team_setup';
  }

}
