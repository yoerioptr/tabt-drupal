<?php

namespace Drupal\tabt_sync\DataFetcher;

use Drupal\tabt\Context\ClubContext;
use Drupal\tabt_sync\Model\Member;
use Yoerioptr\TabtApiClient\Entries\MemberEntry;
use Yoerioptr\TabtApiClient\Repository\MemberRepository;

final class MemberDataFetcher implements DataFetcherInterface {

  use RawDataTrait;

  private ClubContext $clubContext;

  private MemberRepository $memberRepository;

  public function __construct(
    ClubContext $clubContext,
    MemberRepository $memberRepository
  ) {
    $this->clubContext = $clubContext;
    $this->memberRepository = $memberRepository;
  }

  public function listItemsToSync(): array {
    $member_entries = $this->memberRepository
      ->listMembersByClub($this->clubContext->getClub())
      ->getMemberEntries();

    return array_map(function (MemberEntry $member): Member {
      return new Member(
        $member->getPosition(),
        $member->getUniqueIndex(),
        $member->getFirstName(),
        $member->getLastName(),
        $member->getRanking(),
        $member->getRankingIndex(),
        $this->getRawData($member)
      );
    }, $member_entries);
  }

}
