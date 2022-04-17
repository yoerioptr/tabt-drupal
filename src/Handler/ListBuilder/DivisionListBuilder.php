<?php

namespace Drupal\tabt\Handler\ListBuilder;

use Drupal\Core\Entity\EntityListBuilder;
use Drupal\tabt\Entity\DivisionInterface;

final class DivisionListBuilder extends EntityListBuilder {

  public function buildHeader(): array {
    return [
      'division_id' => $this->t('Division ID'),
      'title' => $this->t('Division'),
    ] + parent::buildHeader();
  }

  public function buildRow(DivisionInterface $division): array {
    return [
      'division_id' => $division->getDivisionId(),
      'title' => $division->label(),
    ] + parent::buildRow($division);
  }

}
