<?php

namespace Drupal\tabt_sync\DataFetcher;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\tabt\Context\ClubContext;
use Drupal\tabt_sync\Model\Tournament;
use Yoerioptr\TabtApiClient\Entries\TeamMatchesEntry;
use Yoerioptr\TabtApiClient\Repository\MatchRepository;

final class VenueDataFetcher implements DataFetcherInterface {

  private ClubContext $clubContext;

  private MatchRepository $matchRepository;

  public function __construct(
    ClubContext $clubContext,
    MatchRepository $matchRepository
  ) {
    $this->clubContext = $clubContext;
    $this->matchRepository = $matchRepository;
  }

  public function listItemsToSync(): array {
    return [];
  }

}
