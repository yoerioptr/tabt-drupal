<?php

namespace Drupal\tabt_sync\DataFetcher;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\tabt\Context\ClubContext;
use Drupal\tabt_sync\Model\Tournament;
use Yoerioptr\TabtApiClient\Entries\TeamMatchesEntry;
use Yoerioptr\TabtApiClient\Repository\MatchRepository;

final class TournamentDataFetcher implements DataFetcherInterface {

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
    $match_entries = $this->matchRepository
      ->listMatchesByClub($this->clubContext->getClub())
      ->getTeamMatchesEntries();

    return array_map(function (TeamMatchesEntry $match): Tournament {
      return new Tournament(
        $match->getMatchId(),
        $match->getWeekName(),
        DrupalDateTime::createFromDateTime($match->getDateTime()),
        $match->getHomeTeam(),
        $match->getAwayTeam(),
        $match->getVenueClub(),
        $match->isHomeForfeited(),
        $match->isAwayForfeited(),
        $match->getDivisionId()
      );
    }, $match_entries);
  }

}
