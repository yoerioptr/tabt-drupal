<?php

namespace Drupal\tabt_sync\DataFetcher;

use Drupal\tabt\Context\ClubContext;
use Drupal\tabt_sync\Model\Tournament;
use Yoerioptr\TabtApiClient\Entries\TeamMatchesEntry;
use Yoerioptr\TabtApiClient\Repository\MatchRepository;

class TournamentDataFetcher implements DataFetcherInterface {

  use RawDataTrait;

  protected ClubContext $clubContext;

  protected MatchRepository $matchRepository;

  public function __construct(
    ClubContext $clubContext,
    MatchRepository $matchRepository
  ) {
    $this->clubContext = $clubContext;
    $this->matchRepository = $matchRepository;
  }

  public function listItemsToSync(): array {
    $match_entries = $this->matchRepository
      ->listMatchesByClub($this->clubContext->getClub())
      ->getTeamMatchesEntries();

    return array_map(function (TeamMatchesEntry $match): Tournament {
      return new Tournament(
        $match->getMatchId(),
        $match->getWeekName(),
        $match->getDateTime(),
        $match->getHomeTeam(),
        $match->getAwayTeam(),
        $match->getVenueClub(),
        $match->isHomeForfeited(),
        $match->isAwayForfeited(),
        $match->getHomeWithdrawn(),
        $match->getAwayWithdrawn(),
        $match->getDivisionId(),
        $match->getScore(),
        !is_null($match->getVenueEntry()) ? $match->getVenueEntry()->getName() : NULL,
        $this->getRawData($match)
      );
    }, $match_entries);
  }

}
