<?php

namespace Drupal\tabt\Repository;

use Drupal\tabt\Entity\MemberInterface;

interface MemberRepositoryInterface {

  public function getMemberByUniqueIndex(int $unique_index): ?MemberInterface;

  /**
   * @return \Drupal\tabt\Entity\MemberInterface[]
   */
  public function lisMembers(): array;

}
