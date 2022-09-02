<?php

namespace Drupal\tabt\Handler\ListBuilder;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

class TournamentListBuilder extends EntityListBuilder {

  public function buildHeader(): array {
    return [
      'week_name' => $this->t('Week'),
      'tournament_id' => $this->t('Tournament ID'),
      'date' => $this->t('Date'),
      'home_team' => $this->t('Home team'),
      'away_team' => $this->t('Visitors'),
      'score' => $this->t('Score'),
    ] + parent::buildHeader();
  }

  /**
   * @param \Drupal\tabt\Entity\TournamentInterface $tournament
   *
   * @return array
   */
  public function buildRow(EntityInterface $tournament): array {
    return [
      'week_name' => $tournament->getWeekName(),
      'tournament_id' => $tournament->getTournamentId(),
      'date' => $tournament->getDate() ? $tournament->getDate()->format('d-m-Y H:i') : '',
      'home_team' => $tournament->getHomeTeam() ? $tournament->getHomeTeam()->label() : '',
      'away_team' => $tournament->getAwayTeam() ? $tournament->getAwayTeam()->label() : '',
      'score' => $tournament->getScore(),
    ] + parent::buildRow($tournament);
  }

  protected function getEntityIds(): array {
    $query = $this->getStorage()->getQuery();
    $query->sort('week_name');

    if ($this->limit) {
      $query->pager($this->limit);
    }

    return $query->execute();
  }

}
