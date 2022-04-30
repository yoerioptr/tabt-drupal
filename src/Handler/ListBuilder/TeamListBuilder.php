<?php

namespace Drupal\tabt\Handler\ListBuilder;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

final class TeamListBuilder extends EntityListBuilder {

  public function buildHeader(): array {
    return [
      'team_id' => $this->t('Team ID'),
      'title' => $this->t('Title'),
    ] + parent::buildHeader();
  }

  /**
   * @param \Drupal\tabt\Entity\TeamInterface $team
   *
   * @return array
   */
  public function buildRow(EntityInterface $team): array {
    return [
      'team_id' => $team->getTeamId(),
      'title' => $team->label(),
    ] + parent::buildRow($team);
  }

  protected function getEntityIds(): array {
    $query = $this->getStorage()->getQuery();
    $query->condition('external', FALSE);
    $query->sort($this->entityType->getKey('label'));

    if ($this->limit) {
      $query->pager($this->limit);
    }

    return $query->execute();
  }

}
