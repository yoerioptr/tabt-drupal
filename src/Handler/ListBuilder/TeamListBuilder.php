<?php

namespace Drupal\tabt\Handler\ListBuilder;

use Drupal\Core\Entity\EntityListBuilder;
use Drupal\tabt\Entity\TeamInterface;

final class TeamListBuilder extends EntityListBuilder {

  public function buildHeader(): array {
    return [
      'team_id' => $this->t('Team ID'),
      'title' => $this->t('Title'),
    ] + parent::buildHeader();
  }

  public function buildRow(TeamInterface $team): array {
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
