<?php

namespace Drupal\tabt\Repository;

use Drupal\tabt\Util\Enum\Tabt;

final class VenueRepository extends RepositoryBase implements VenueRepositoryInterface {

  protected function entityTypeId(): string {
    return Tabt::VENUE;
  }

  public function listVenues(): array {
    return [];
  }

}
