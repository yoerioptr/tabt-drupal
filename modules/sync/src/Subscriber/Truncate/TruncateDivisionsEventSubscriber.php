<?php

namespace Drupal\tabt_sync\Subscriber\Truncate;

use Drupal\tabt\Entity\DivisionInterface;
use Drupal\tabt_sync\Event\Truncate\TruncateDivisionsEvent;
use Drupal\tabt\Repository\DivisionRepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class TruncateDivisionsEventSubscriber implements EventSubscriberInterface {

  private DivisionRepositoryInterface $divisionRepository;

  public function __construct(DivisionRepositoryInterface $divisionRepository) {
    $this->divisionRepository = $divisionRepository;
  }

  public function removeAllDivisions(): void {
    $divisions = $this->divisionRepository->listDivisions();
    array_walk($divisions, function (DivisionInterface $division): void {
      $division->delete();
    });
  }

  public static function getSubscribedEvents(): array {
    return [TruncateDivisionsEvent::class => 'removeAllDivisions'];
  }

}
