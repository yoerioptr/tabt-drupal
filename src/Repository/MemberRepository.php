<?php

namespace Drupal\tabt\Repository;

use Drupal\tabt\Entity\Member;
use Drupal\tabt\Entity\MemberInterface;
use Drupal\tabt\Util\Enum\Tabt;

final class MemberRepository extends RepositoryBase implements MemberRepositoryInterface {

  protected function entityTypeId(): string {
    return Tabt::MEMBER;
  }

  public function getMemberByUniqueIndex(int $unique_index): ?MemberInterface {
    $query = $this->baseEntityQuery();
    $query->condition('unique_index', $unique_index);
    $results = $query->execute();

    return !empty($results) ? Member::load(reset($results)) : NULL;
  }

  public function lisMembers(): array {
    return [];
  }

}
