<?php

namespace Drupal\tabt_sync\Model;

final class Division extends SyncableItemBase {

  private int $divisionId;

  private int $divisionCategory;

  private string $divisionName;

  public function __construct(
    int $divisionId,
    int $divisionCategory,
    string $divisionName
  ) {
    $this->divisionId = $divisionId;
    $this->divisionCategory = $divisionCategory;
    $this->divisionName = $divisionName;
  }

  public function getDivisionId(): int {
    return $this->divisionId;
  }

  public function getDivisionCategory(): int {
    return $this->divisionCategory;
  }

  public function getDivisionName(): string {
    return $this->divisionName;
  }

}
