<?php

namespace Drupal\tabt_sync\Commands;

use Drupal\tabt_sync\DataFetcher\MemberDataFetcher;
use Drupal\tabt_sync\Event\Sync\SyncMemberEvent;
use Drupal\tabt_sync\Event\Truncate\TruncateMembersEvent;
use Drupal\tabt_sync\Model\Member;
use Drush\Commands\DrushCommands;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class SyncMembersCommand extends DrushCommands {

  private EventDispatcherInterface $eventDispatcher;

  private MemberDataFetcher $dataFetcher;

  public function __construct(
    EventDispatcherInterface $eventDispatcher,
    MemberDataFetcher $dataFetcher
  ) {
    parent::__construct();
    $this->dataFetcher = $dataFetcher;
    $this->eventDispatcher = $eventDispatcher;
  }

  /**
   * @command tabt:sync:member
   */
  public function sync(): void {
    $members = $this->dataFetcher->listItemsToSync();

    array_walk($members, function (Member $member): void {
      $this->eventDispatcher->dispatch(new SyncMemberEvent($member));
    });
  }

  /**
   * @command tabt:truncate:member
   */
  public function truncate(): void {
    $this->eventDispatcher->dispatch(new TruncateMembersEvent());
  }

}
