<?php

namespace Drupal\tabt\Repository;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\Query\QueryInterface;

abstract class RepositoryBase {

  private EntityTypeManagerInterface $entityTypeManager;

  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  protected function baseEntityQuery(): QueryInterface {
    return $this->getStorage()->getQuery();
  }

  protected function getStorage(): EntityStorageInterface {
    return $this->entityTypeManager->getStorage($this->entityTypeId());
  }

  abstract protected function entityTypeId(): string;

}
