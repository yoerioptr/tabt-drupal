<?php

namespace Drupal\tabt_sync\Subscriber\Truncate;

use Drupal\tabt\Entity\TeamInterface;
use Drupal\tabt_sync\Event\Truncate\TruncateTeamsEvent;
use Drupal\tabt\Repository\TeamRepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class TruncateTeamsEventSubscriber implements EventSubscriberInterface {

  private TeamRepositoryInterface $teamRepository;

  public function __construct(TeamRepositoryInterface $teamRepository) {
    $this->teamRepository = $teamRepository;
  }

  public function removeAllTeams(): void {
    $team = $this->teamRepository->listTeams();
    array_walk($team, function (TeamInterface $team): void {
      $team->delete();
    });
  }

  public static function getSubscribedEvents(): array {
    return [TruncateTeamsEvent::class => 'removeAllTeams'];
  }

}
