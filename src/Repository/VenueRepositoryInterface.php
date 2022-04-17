<?php

namespace Drupal\tabt\Repository;

use Drupal\tabt\Entity\VenueInterface;

interface VenueRepositoryInterface {

  public function getVenueByName(string $name): ?VenueInterface;

  /**
   * @return \Drupal\tabt\Entity\VenueInterface[]
   */
  public function listVenues(): array;

}
