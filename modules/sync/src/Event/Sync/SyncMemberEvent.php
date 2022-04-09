<?php

namespace Drupal\tabt_sync\Event\Sync;

use Drupal\Component\EventDispatcher\Event;
use Drupal\tabt_sync\Model\Member;

final class SyncMemberEvent extends Event {

  private Member $member;

  public function __construct(Member $member) {
    $this->member = $member;
  }

  public function getMember(): Member {
    return $this->member;
  }

}
