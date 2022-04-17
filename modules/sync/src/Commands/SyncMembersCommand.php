<?php

namespace Drupal\tabt_sync\Commands;

use Drupal\tabt_sync\DataFetcher\MemberDataFetcher;
use Drupal\tabt_sync\Event\Sync\SyncMemberEvent;
use Drupal\tabt_sync\Event\Truncate\TruncateMembersEvent;
use Drupal\tabt_sync\Model\Member;
use Drush\Commands\DrushCommands;
use Symfony\Component\Console\Helper\ProgressBar;
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
    $this->writeln('Fetching API data');
    $members = $this->dataFetcher->listItemsToSync();

    $this->writeln('Processing API data');
    $progress_bar = new ProgressBar($this->output, count($members));

    $progress_bar->start();
    array_walk($members, function (Member $member) use ($progress_bar): void {
      $this->eventDispatcher->dispatch(new SyncMemberEvent($member));
      $progress_bar->advance();
    });

    $progress_bar->finish();
    $this->writeln('');
  }

  /**
   * @command tabt:truncate:member
   */
  public function truncate(): void {
    // TODO: Confirm action
    $this->eventDispatcher->dispatch(new TruncateMembersEvent());
  }

}
