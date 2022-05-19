<?php

namespace Drupal\tabt_sync\Subscriber\Sync;

use Drupal\tabt\Entity\Division;
use Drupal\tabt\Repository\DivisionRepositoryInterface;
use Drupal\tabt_sync\Event\Sync\SyncDivisionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class SyncDivisionEventSubscriber implements EventSubscriberInterface {

  private DivisionRepositoryInterface $divisionRepository;

  public function __construct(DivisionRepositoryInterface $divisionRepository) {
    $this->divisionRepository = $divisionRepository;
  }

  public function syncDivision(SyncDivisionEvent $event): void {
    $source = $event->getDivision();

    $division = $this->divisionRepository->getDivisionByTabtId($source->getDivisionId()) ?? Division::create();
    $division->set('title', $source->getDivisionName());
    $division->set('division_id', $source->getDivisionId());
    $division->set('division_category', $source->getDivisionCategory());
    $division->set('raw_data', json_encode($source->getRawData(), TRUE));

    $division->save();
  }

  public static function getSubscribedEvents(): array {
    return [SyncDivisionEvent::class => 'syncDivision'];
  }

}
