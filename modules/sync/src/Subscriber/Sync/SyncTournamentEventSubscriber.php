<?php

namespace Drupal\tabt_sync\Subscriber\Sync;

use Drupal\tabt\Entity\Tournament;
use Drupal\tabt\Repository\DivisionRepositoryInterface;
use Drupal\tabt\Repository\TeamRepositoryInterface;
use Drupal\tabt\Repository\TournamentRepositoryInterface;
use Drupal\tabt\Repository\VenueRepositoryInterface;
use Drupal\tabt_sync\Event\Sync\SyncTournamentEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\tabt_sync\Model\Tournament as TournamentSource;

final class SyncTournamentEventSubscriber implements EventSubscriberInterface {

  private TournamentRepositoryInterface $tournamentRepository;

  private TeamRepositoryInterface $teamRepository;

  private DivisionRepositoryInterface $divisionRepository;

  private VenueRepositoryInterface $venueRepository;

  public function __construct(
    TournamentRepositoryInterface $tournamentRepository,
    TeamRepositoryInterface $teamRepository,
    DivisionRepositoryInterface $divisionRepository,
    VenueRepositoryInterface $venueRepository
  ) {
    $this->tournamentRepository = $tournamentRepository;
    $this->teamRepository = $teamRepository;
    $this->divisionRepository = $divisionRepository;
    $this->venueRepository = $venueRepository;
  }

  public function syncTournament(SyncTournamentEvent $event): void {
    $source = $event->getTournament();

    $tournament = $this->tournamentRepository->getTournamentByTournamentId($source->getMatchId()) ?? Tournament::create();
    $tournament->set('title', $this->buildTitle($source));
    $tournament->set('tournament_id', $source->getMatchId());
    $tournament->set('home_forfeited', $source->isHomeForfeited());
    $tournament->set('away_forfeited', $source->isAwayForfeited());
    $tournament->set('home_withdrawn', $source->getHomeWithdrawn());
    $tournament->set('away_withdrawn', $source->getAwayWithdrawn());
    $tournament->set('home_team', $this->teamRepository->getTeamByName($source->getHomeTeam()));
    $tournament->set('away_team', $this->teamRepository->getTeamByName($source->getAwayTeam()));
    $tournament->set('date', $source->getDate());
    $tournament->set('division', $this->divisionRepository->getDivisionByTabtId($source->getDivisionId()));
    $tournament->set('week_name', $source->getWeekName());
    $tournament->set('score', $this->buildScore($source));
    $tournament->set('venue', $this->venueRepository->getVenueByName($source->getVenue() ?? ''));

    $tournament->save();
  }

  public static function getSubscribedEvents(): array {
    return [SyncTournamentEvent::class => 'syncTournament'];
  }

  private function buildTitle(TournamentSource $tournament): string {
    return "{$tournament->getMatchId()} - {$tournament->getHomeTeam()} - {$tournament->getAwayTeam()}";
  }

  private function buildScore(TournamentSource $tournament): string {
    $score = preg_replace("/[^0-9-]/", "", (string) $tournament->getScore());
    if (empty($score)) {
      $score = '0-0';
    }

    return implode(' - ', explode('-', $score));
  }

}
