<?php

namespace Drupal\tabt_sync\DataFetcher;

use Drupal\tabt\Context\ClubContext;
use Drupal\tabt_sync\Model\Venue;
use Yoerioptr\TabtApiClient\Entries\TeamMatchesEntry;
use Yoerioptr\TabtApiClient\Repository\MatchRepository;

final class VenueDataFetcher implements DataFetcherInterface {

  use RawDataTrait;

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
    $venues = [];

    $match_entries = $this->matchRepository
      ->listMatchesByClub($this->clubContext->getClub())
      ->getTeamMatchesEntries();

    $match_entries = array_filter($match_entries, function (TeamMatchesEntry $match): bool {
      return !is_null($match->getVenueEntry());
    });

    foreach ($match_entries as $match) {
      if (!isset($venues[$match->getVenueEntry()->getName()])) {
        $venues[$match->getVenueEntry()->getName()] = new Venue(
          $match->getVenueEntry()->getName(),
          $match->getVenueEntry()->getStreet(),
          $match->getVenueEntry()->getTown(),
          $match->getVenueEntry()->getPhone(),
          $match->getVenueEntry()->getComment(),
          $this->getRawData($match->getVenueEntry())
        );
      }
    }

    return array_values($venues);
  }

}
