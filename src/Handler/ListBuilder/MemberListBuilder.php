<?php

namespace Drupal\tabt\Handler\ListBuilder;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

final class MemberListBuilder extends EntityListBuilder {

  public function buildHeader(): array {
    return [
      'position' => $this->t('Position'),
      'unique_index' => $this->t('Unique index'),
      'first_name' => $this->t('First name'),
      'last_name' => $this->t('Last name'),
      'ranking' => $this->t('Ranking'),
    ] + parent::buildHeader();
  }

  /**
   * @param \Drupal\tabt\Entity\MemberInterface $member
   *
   * @return array
   */
  public function buildRow(EntityInterface $member): array {
    return [
      'position' => $member->getPosition(),
      'unique_index' => $member->getUniqueIndex(),
      'first_name' => $member->getFirstName(),
      'last_name' => $member->getLastName(),
      'ranking' => $member->getRanking(),
    ] + parent::buildRow($member);
  }

}
