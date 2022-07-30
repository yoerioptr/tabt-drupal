<?php

namespace Drupal\tabt_sync\DataFetcher;

use Drupal\tabt\Context\ClubContext;
use Drupal\tabt_sync\Model\Team;
use Yoerioptr\TabtApiClient\Entries\ClubEntry;
use Yoerioptr\TabtApiClient\Entries\TeamEntry;
use Yoerioptr\TabtApiClient\Repository\ClubRepository;
use Yoerioptr\TabtApiClient\Repository\MatchRepository;

final class TeamDataFetcher implements DataFetcherInterface {

  use RawDataTrait;

  private array $clubTeams = [];

  private ClubContext $clubContext;

  private ClubRepository $clubRepository;

  private MatchRepository $matchRepository;

  public function __construct(
    ClubContext $clubContext,
    ClubRepository $clubRepository,
    MatchRepository $matchRepository
  ) {
    $this->clubContext = $clubContext;
    $this->clubRepository = $clubRepository;
    $this->matchRepository = $matchRepository;
  }

  public function listItemsToSync(): array {
    $teams = [];

    /** @var \Drupal\tabt_sync\Model\Team $team */
    foreach ($this->getTeamsForClub($this->clubContext->getClub()) as $team) {
      if (!isset($teams[$team->getTeamId()])) {
        $teams[$team->getTeamId()] = $team;
      }
    }

    $division_ids = array_unique(array_map(function (Team $team): int {
      return $team->getDivisionId();
    }, $teams));

    /** @var \Drupal\tabt_sync\Model\Team $team */
    foreach ($this->getTeamsForDivisions($division_ids) as $team) {
      if (!isset($teams[$team->getTeamId()])) {
        $teams[$team->getTeamId()] = $team;
      }
    }

    return $teams;
  }

  private function getTeamsForDivisions(array $division_ids): array {
    $match_entries = $this->matchRepository
      ->listMatchesByClub($this->clubContext->getClub())
      ->getTeamMatchesEntries();

    $teams = [];
    foreach ($match_entries as $match_entry) {
      foreach ([$match_entry->getHomeClub(), $match_entry->getAwayClub()] as $club) {
        $teams = array_merge($teams, $this->getTeamsForClub($club, $division_ids));
      }
    }

    return $teams;
  }

  private function getTeamsForClub(string $club, ?array $division_ids = NULL): array {
    if (!empty($this->clubTeams[$club])) {
      return $this->clubTeams[$club];
    }

    $club_entry = $this->getClubEntry($club);
    if (is_null($club_entry)) {
      return [];
    }

    $team_entries = $this->clubRepository
      ->listTeamsByClub($club)
      ->getTeamEntries();

    if (!is_null($division_ids)) {
      $team_entries = array_filter($team_entries, function (TeamEntry $team) use ($division_ids): bool {
        return in_array($team->getDivisionId(), $division_ids);
      });
    }

    $this->clubTeams[$club] = array_map(function (TeamEntry $team) use ($club_entry): Team {
      return new Team(
        $team->getTeamId(),
        "{$club_entry->getName()} {$team->getTeam()}",
        $team->getDivisionId(),
        $this->clubContext->getClub() !== $club_entry->getUniqueIndex(),
        $this->getCombinedRawData($team, $club_entry)
      );
    }, $team_entries);

    return $this->clubTeams[$club];
  }

  private function getClubEntry(string $club): ?ClubEntry {
    foreach ($this->clubRepository->listClubs()->getClubEntries() as $club_entry) {
      if ($club_entry->getUniqueIndex() === $club) {
        return $club_entry;
      }
    }

    return NULL;
  }

  private function getCombinedRawData(TeamEntry $team_entry, ClubEntry $club_entry): array {
    $raw_data = $this->getRawData($team_entry);
    $raw_data['club'] = $this->getRawData($club_entry);

    return $raw_data;
  }

}
