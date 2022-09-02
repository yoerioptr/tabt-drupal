<?php

namespace Drupal\tabt\Repository;

use Drupal\tabt\Entity\Venue;
use Drupal\tabt\Entity\VenueInterface;
use Drupal\tabt\Util\Enum\Tabt;

class VenueRepository extends RepositoryBase implements VenueRepositoryInterface {

  protected function entityTypeId(): string {
    return Tabt::VENUE;
  }

  public function getVenueByName(string $name): ?VenueInterface {
    $query = $this->baseEntityQuery();
    $query->condition('title', $name);
    $results = $query->execute();

    return !empty($results) ? Venue::load(reset($results)) : NULL;
  }

  public function listVenues(): array {
    return Venue::loadMultiple();
  }

}
