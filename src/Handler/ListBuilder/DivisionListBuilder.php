<?php

namespace Drupal\tabt\Handler\ListBuilder;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

final class DivisionListBuilder extends EntityListBuilder {

  public function buildHeader(): array {
    return [
      'division_id' => $this->t('Division ID'),
      'title' => $this->t('Division'),
    ] + parent::buildHeader();
  }

  /**
   * @param \Drupal\tabt\Entity\DivisionInterface $division
   *
   * @return array
   */
  public function buildRow(EntityInterface $division): array {
    return [
      'division_id' => $division->getDivisionId(),
      'title' => $division->label(),
    ] + parent::buildRow($division);
  }

}
