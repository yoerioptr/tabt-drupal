<?php

namespace Drupal\tabt\Handler\ListBuilder;

use Drupal\Core\Entity\EntityListBuilder;
use Drupal\tabt\Entity\MemberInterface;

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

  public function buildRow(MemberInterface $member): array {
    return [
      'position' => $member->getPosition(),
      'unique_index' => $member->getUniqueIndex(),
      'first_name' => $member->getFirstName(),
      'last_name' => $member->getLastName(),
      'ranking' => $member->getRanking(),
    ] + parent::buildRow($member);
  }

}
