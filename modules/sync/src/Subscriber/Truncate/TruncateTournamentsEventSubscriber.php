<?php

namespace Drupal\tabt_sync\Subscriber\Truncate;

use Drupal\tabt\Entity\TournamentInterface;
use Drupal\tabt_sync\Event\Truncate\TruncateTournamentsEvent;
use Drupal\tabt\Repository\TournamentRepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class TruncateTournamentsEventSubscriber implements EventSubscriberInterface {

  private TournamentRepositoryInterface $tournamentRepository;

  public function __construct(TournamentRepositoryInterface $tournamentRepository) {
    $this->tournamentRepository = $tournamentRepository;
  }

  public function removeAllTournaments(): void {
    $tournaments = $this->tournamentRepository->listTournaments();
    array_walk($tournaments, function (TournamentInterface $tournament): void {
      $tournament->delete();
    });
  }

  public static function getSubscribedEvents(): array {
    return [TruncateTournamentsEvent::class => 'removeAllTournaments'];
  }

}
