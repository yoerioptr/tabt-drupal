<?php

namespace Drupal\tabt_sync;

use Drupal\tabt_sync\Event\Sync\SyncDivisionEvent;
use Drupal\tabt_sync\Event\Sync\SyncMemberEvent;
use Drupal\tabt_sync\Event\Sync\SyncTeamEvent;
use Drupal\tabt_sync\Event\Sync\SyncTournamentEvent;
use Drupal\tabt_sync\Event\Sync\SyncVenueEvent;
use Drupal\tabt_sync\Event\Truncate\TruncateDivisionsEvent;
use Drupal\tabt_sync\Event\Truncate\TruncateMembersEvent;
use Drupal\tabt_sync\Event\Truncate\TruncateTeamsEvent;
use Drupal\tabt_sync\Event\Truncate\TruncateTournamentsEvent;
use Drupal\tabt_sync\Event\Truncate\TruncateVenuesEvent;
use Drupal\tabt_sync\Exception\NonSyncableTypeException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class TabtSyncer implements TabtSyncerInterface {

  public const TYPE_DIVISION = 'division';
  public const TYPE_MEMBER = 'member';
  public const TYPE_TEAM = 'team';
  public const TYPE_VENUE = 'venue';
  public const TYPE_TOURNAMENT = 'tournament';

  private EventDispatcherInterface $eventDispatcher;

  public function __construct(EventDispatcherInterface $eventDispatcher) {
    $this->eventDispatcher = $eventDispatcher;
  }

  public function syncAll(): void {
    $types = array_keys($this->syncEventMapping());
    array_walk($types, [$this, 'syncSingle']);
  }

  public function syncSingle(string $type): void {
    $event = $this->syncEventMapping()[$type] ?? NULL;
    if (is_null($event) || !\Drupal::hasService("tabt_sync.data_fetcher.$type")) {
      throw new NonSyncableTypeException();
    }

    /** @var \Drupal\tabt_sync\DataFetcher\DataFetcherInterface $data_fetcher */
    $data_fetcher = \Drupal::service("tabt_sync.data_fetcher.$type");
    foreach ($data_fetcher->listItemsToSync() as $syncable_entry) {
      $this->eventDispatcher->dispatch(new $event($syncable_entry));
    }
  }

  public function truncateAll(): void {
    $types = array_keys($this->syncEventMapping());
    array_walk($types, [$this, 'truncateSingle']);
  }

  public function truncateSingle(string $type): void {
    $event = $this->truncateEventMapping()[$type] ?? NULL;
    if (is_null($event)) {
      throw new NonSyncableTypeException();
    }

    $this->eventDispatcher->dispatch(new $event);
  }

  private function syncEventMapping(): array {
    return [
      self::TYPE_DIVISION => SyncDivisionEvent::class,
      self::TYPE_MEMBER => SyncMemberEvent::class,
      self::TYPE_TEAM => SyncTeamEvent::class,
      self::TYPE_VENUE => SyncVenueEvent::class,
      self::TYPE_TOURNAMENT => SyncTournamentEvent::class,
    ];
  }

  private function truncateEventMapping(): array {
    return [
      self::TYPE_DIVISION => TruncateDivisionsEvent::class,
      self::TYPE_MEMBER => TruncateMembersEvent::class,
      self::TYPE_TEAM => TruncateTeamsEvent::class,
      self::TYPE_VENUE => TruncateVenuesEvent::class,
      self::TYPE_TOURNAMENT => TruncateTournamentsEvent::class,
    ];
  }

}
