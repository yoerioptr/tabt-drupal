<?php

namespace Drupal\tabt_sync\DataFetcher;

use Drupal\tabt\Context\ClubContext;
use Drupal\tabt\Context\SeasonContext;
use Drupal\tabt_sync\Model\Division;
use Yoerioptr\TabtApiClient\Repository\DivisionRepository;
use Yoerioptr\TabtApiClient\Repository\MatchRepository;

final class DivisionDataFetcher implements DataFetcherInterface {

  use RawDataTrait;

  private ClubContext $clubContext;

  private SeasonContext $seasonContext;

  private MatchRepository $matchRepository;

  private DivisionRepository $divisionRepository;

  public function __construct(
    ClubContext $clubContext,
    SeasonContext $seasonContext,
    MatchRepository $matchRepository,
    DivisionRepository $divisionRepository
  ) {
    $this->clubContext = $clubContext;
    $this->seasonContext = $seasonContext;
    $this->matchRepository = $matchRepository;
    $this->divisionRepository = $divisionRepository;
  }

  public function listItemsToSync(): array {
    $match_entries = $this->matchRepository->listMatchesBy([
      'Club' => $this->clubContext->getClub(),
      'Season' => $this->seasonContext->getSeason(),
    ])->getTeamMatchesEntries();

    $divisions = [];
    foreach ($match_entries as $match_entry) {
      $division_id = $match_entry->getDivisionId();
      if (isset($divisions[$division_id])) {
        continue;
      }

      $response = $this->divisionRepository->listDivisionRankingByDivisionId($division_id);

      $divisions[$division_id] = new Division(
        $division_id,
        $match_entry->getDivisionCategory(),
        $response->getDivisionName(),
        $this->getRawData($match_entry)
      );
    }

    return $divisions;
  }

}
