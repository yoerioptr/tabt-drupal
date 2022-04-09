<?php

namespace Drupal\tabt_sync\Subscriber\Sync;

use Drupal\tabt\Entity\Tournament;
use Drupal\tabt\Repository\DivisionRepositoryInterface;
use Drupal\tabt\Repository\TeamRepositoryInterface;
use Drupal\tabt\Repository\TournamentRepositoryInterface;
use Drupal\tabt_sync\Event\Sync\SyncTournamentEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class SyncTournamentEventSubscriber implements EventSubscriberInterface {

  private TournamentRepositoryInterface $tournamentRepository;

  private TeamRepositoryInterface $teamRepository;

  private DivisionRepositoryInterface $divisionRepository;

  public function __construct(
    TournamentRepositoryInterface $tournamentRepository,
    TeamRepositoryInterface $teamRepository,
    DivisionRepositoryInterface $divisionRepository
  ) {
    $this->tournamentRepository = $tournamentRepository;
    $this->teamRepository = $teamRepository;
    $this->divisionRepository = $divisionRepository;
  }

  public function syncTournament(SyncTournamentEvent $event): void {
    $source = $event->getTournament();

    $tournament = $this->tournamentRepository->getTournamentByTournamentId($source->getMatchId()) ?? Tournament::create();
    $tournament->set('title', "{$source->getMatchId()} - {$source->getHomeTeam()} - {$source->getAwayTeam()}");
    $tournament->set('tournament_id', $source->getMatchId());
    $tournament->set('home_forfeited', $source->isHomeForfeited());
    $tournament->set('away_forfeited', $source->isAwayForfeited());
    $tournament->set('home_team', $this->teamRepository->getTeamByName($source->getHomeTeam()));
    $tournament->set('away_team', $this->teamRepository->getTeamByName($source->getAwayTeam()));
    $tournament->set('date', $source->getDate());
    $tournament->set('division', $this->divisionRepository->getDivisionByTabtId($source->getDivisionId()));
    $tournament->set('week_name', $source->getWeekName());

    $tournament->save();
  }

  public static function getSubscribedEvents(): array {
    return [SyncTournamentEvent::class => 'syncTournament'];
  }

}
