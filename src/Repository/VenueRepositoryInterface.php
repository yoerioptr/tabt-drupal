<?php

namespace Drupal\tabt\Repository;

interface VenueRepositoryInterface {

  /**
   * @return \Drupal\tabt\Entity\VenueInterface[]
   */
  public function listVenues(): array;

}
