<?php

namespace Drupal\tabt_sync\Model;

final class Division extends SyncableItemBase {

  private int $divisionId;

  private int $divisionCategory;

  private string $divisionName;

  private array $rawData;

  public function __construct(
    int $divisionId,
    int $divisionCategory,
    string $divisionName,
    array $rawData
  ) {
    $this->divisionId = $divisionId;
    $this->divisionCategory = $divisionCategory;
    $this->divisionName = $divisionName;
    $this->rawData = $rawData;
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

  public function getRawData(): array {
    return $this->rawData;
  }

}
