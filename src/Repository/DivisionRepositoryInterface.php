<?php

namespace Drupal\tabt\Repository;

use Drupal\tabt\Entity\DivisionInterface;

interface DivisionRepositoryInterface {

  public function getDivisionByTabtId(int $tabt_id): ?DivisionInterface;

  /**
   * @return \Drupal\tabt\Entity\DivisionInterface[]
   */
  public function listDivisions(): array;

}
