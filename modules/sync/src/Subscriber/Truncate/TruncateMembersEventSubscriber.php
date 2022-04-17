<?php

namespace Drupal\tabt_sync\Subscriber\Truncate;

use Drupal\tabt\Entity\MemberInterface;
use Drupal\tabt_sync\Event\Truncate\TruncateMembersEvent;
use Drupal\tabt\Repository\MemberRepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class TruncateMembersEventSubscriber implements EventSubscriberInterface {

  private MemberRepositoryInterface $memberRepository;

  public function __construct(MemberRepositoryInterface $memberRepository) {
    $this->memberRepository = $memberRepository;
  }

  public function removeAllMembers(): void {
    $members = $this->memberRepository->lisMembers();
    array_walk($members, function (MemberInterface $member): void {
      $member->delete();
    });
  }

  public static function getSubscribedEvents(): array {
    return [TruncateMembersEvent::class => 'removeAllMembers'];
  }

}
