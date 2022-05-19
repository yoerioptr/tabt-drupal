<?php

namespace Drupal\tabt_sync\Subscriber\Sync;

use Drupal\tabt\Entity\Member;
use Drupal\tabt\Repository\MemberRepositoryInterface;
use Drupal\tabt_sync\Event\Sync\SyncMemberEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class SyncMemberEventSubscriber implements EventSubscriberInterface {

  private MemberRepositoryInterface $memberRepository;

  public function __construct(MemberRepositoryInterface $memberRepository) {
    $this->memberRepository = $memberRepository;
  }

  public function syncMember(SyncMemberEvent $event): void {
    $source = $event->getMember();

    $member = $this->memberRepository->getMemberByUniqueIndex($source->getUniqueIndex()) ?? Member::create();
    $member->setSyncing(TRUE);
    $member->set('title', "{$source->getUniqueIndex()} - {$source->getLastName()} {$source->getFirstName()}");
    $member->set('position', $source->getPosition());
    $member->set('unique_index', $source->getUniqueIndex());
    $member->set('first_name', $source->getFirstName());
    $member->set('last_name', $source->getLastName());
    $member->set('ranking', $source->getRanking());
    $member->set('ranking_index', $source->getRankingIndex());
    $member->set('raw_data', json_encode($source->getRawData(), TRUE));

    $member->save();
  }

  public static function getSubscribedEvents(): array {
    return [SyncMemberEvent::class => 'syncMember'];
  }

}
