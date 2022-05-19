<?php

namespace Drupal\tabt_sync\Subscriber\Sync;

use Drupal\tabt\Entity\Team;
use Drupal\tabt\Repository\DivisionRepositoryInterface;
use Drupal\tabt\Repository\TeamRepositoryInterface;
use Drupal\tabt_sync\Event\Sync\SyncTeamEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class SyncTeamEventSubscriber implements EventSubscriberInterface {

  private TeamRepositoryInterface $teamRepository;

  private DivisionRepositoryInterface $divisionRepository;

  public function __construct(
    TeamRepositoryInterface $teamRepository,
    DivisionRepositoryInterface $divisionRepository
  ) {
    $this->teamRepository = $teamRepository;
    $this->divisionRepository = $divisionRepository;
  }

  public function syncTeam(SyncTeamEvent $event): void {
    $source = $event->getTeam();

    $team = $this->teamRepository->getTeamByTeamId($source->getTeamId()) ?? Team::create();
    $team->set('title', $source->getTeam());
    $team->set('team_id', $source->getTeamId());
    $team->set('division', $this->divisionRepository->getDivisionByTabtId($source->getDivisionId()));
    $team->set('external', $source->isExternal());
    $team->set('raw_data', json_encode($source->getRawData(), TRUE));

    $team->save();
  }

  public static function getSubscribedEvents(): array {
    return [SyncTeamEvent::class => 'syncTeam'];
  }

}
