<?php

namespace Drupal\tabt_sync;

use Drupal\Core\Batch\BatchBuilder;
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

final class TabtSyncer {

  public const TYPE_DIVISION = 'division';
  public const TYPE_MEMBER = 'member';
  public const TYPE_TEAM = 'team';
  public const TYPE_VENUE = 'venue';
  public const TYPE_TOURNAMENT = 'tournament';

  public static function syncAll(): void {
    $types = array_keys(self::syncEventMapping());

    $batch_builder = self::getBatchBuilder();
    $batch_builder->addOperation([self::class, 'syncSingle'], $types);

    batch_set($batch_builder->toArray());
  }

  public static function syncSingle(string $type): void {
    $event = self::syncEventMapping()[$type] ?? NULL;
    if (is_null($event) || !\Drupal::hasService("tabt_sync.data_fetcher.$type")) {
      throw new NonSyncableTypeException();
    }

    /** @var \Drupal\tabt_sync\DataFetcher\DataFetcherInterface $data_fetcher */
    $data_fetcher = \Drupal::service("tabt_sync.data_fetcher.$type");
    foreach ($data_fetcher->listItemsToSync() as $syncable_entry) {
      \Drupal::service('event_dispatcher')->dispatch(new $event($syncable_entry));
    }
  }

  public static function truncateAll(): void {
    $types = array_keys(self::truncateEventMapping());

    $batch_builder = self::getBatchBuilder();
    $batch_builder->addOperation([self::class, 'truncateSingle'], $types);

    batch_set($batch_builder->toArray());
  }

  public static function truncateSingle(string $type): void {
    $event = self::truncateEventMapping()[$type] ?? NULL;
    if (is_null($event)) {
      throw new NonSyncableTypeException();
    }

    \Drupal::service('event_dispatcher')->dispatch(new $event);
  }

  private static function syncEventMapping(): array {
    return [
      self::TYPE_DIVISION => SyncDivisionEvent::class,
      self::TYPE_MEMBER => SyncMemberEvent::class,
      self::TYPE_TEAM => SyncTeamEvent::class,
      self::TYPE_VENUE => SyncVenueEvent::class,
      self::TYPE_TOURNAMENT => SyncTournamentEvent::class,
    ];
  }

  private static function truncateEventMapping(): array {
    return [
      self::TYPE_DIVISION => TruncateDivisionsEvent::class,
      self::TYPE_MEMBER => TruncateMembersEvent::class,
      self::TYPE_TEAM => TruncateTeamsEvent::class,
      self::TYPE_VENUE => TruncateVenuesEvent::class,
      self::TYPE_TOURNAMENT => TruncateTournamentsEvent::class,
    ];
  }

  private static function getBatchBuilder(): BatchBuilder {
    $batch_builder = new BatchBuilder();
    $batch_builder->setTitle(random_bytes(10));

    return $batch_builder;
  }

}
