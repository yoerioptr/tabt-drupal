<?php

namespace Drupal\tabt\Repository;

use Drupal\tabt\Entity\Division;
use Drupal\tabt\Entity\DivisionInterface;
use Drupal\tabt\Util\Enum\Tabt;

class DivisionRepository extends RepositoryBase implements DivisionRepositoryInterface {

  protected function entityTypeId(): string {
    return Tabt::DIVISION;
  }

  public function getDivisionByTabtId(int $tabt_id): ?DivisionInterface {
    $query = $this->baseEntityQuery();
    $query->condition('division_id', $tabt_id);
    $results = $query->execute();

    return !empty($results) ? Division::load(reset($results)) : NULL;
  }

  public function listDivisions(): array {
    return Division::loadMultiple();
  }

}
