<?php

namespace Drupal\tabt_team_management\Helper\PlayerAvailability;

use Drupal\Core\Form\FormStateInterface;
use Drupal\tabt\Entity\Member;
use Drupal\tabt\Entity\MemberInterface;
use Drupal\tabt\Entity\Team;
use Drupal\tabt\Entity\TeamInterface;
use Drupal\tabt\Entity\TournamentInterface;
use Drupal\tabt_team_management\Entity\TeamSetupInterface;
use Drupal\tabt_team_management\Repository\TeamSetupRepositoryInterface;

class PlayerAvailabilityChecker implements PlayerAvailabilityCheckerInterface {

  protected TeamSetupRepositoryInterface $teamSetupRepository;

  public function __construct(TeamSetupRepositoryInterface $teamSetupRepository) {
    $this->teamSetupRepository = $teamSetupRepository;
  }

  public function checkPlayerAvailability(
    MemberInterface $member,
    TournamentInterface $tournament,
    TeamInterface $team
  ): int {
    $setups_containing_player = array_filter(
      $this->teamSetupRepository->listSetupsByMemberAndWeekName($member, $tournament->getWeekName()),
      function (TeamSetupInterface $setup) use ($tournament): bool {
        return $setup->getTournament()->id() !== $tournament->id();
      }
    );

    if (!empty($setups_containing_player)) {
      return PlayerAvailabilityCheckerInterface::OCCUPIED;
    }

    // TODO: Implement PlayerAvailabilityCheckerInterface::CONFLICTED_RANKING check
    // TODO: Implement PlayerAvailabilityCheckerInterface::UNAVAILABLE check

    return PlayerAvailabilityCheckerInterface::AVAILABLE;
  }

  public function validateFormInput(
    array &$form,
    FormStateInterface $form_state,
    TournamentInterface $tournament
  ): void {
    foreach ($form_state->getValues()['teams'] as $team_id => $team) {
      foreach ($team['players'] as $player_index => $player_id) {
        if (is_null($player_id)) {
          continue;
        }

        $member = Member::load($player_id);
        $team = Team::load($team_id);

        if (isset($selected_players[$player_id])) {
          $form_state->setError($form['teams'][$team_id]['players'][$player_index], PlayerAvailabilityMessage::playerDuplicateMessage($member));
          continue;
        }

        switch ($this->checkPlayerAvailability($member, $tournament, $team)) {
          case PlayerAvailabilityCheckerInterface::AVAILABLE:
            $selected_players[$player_id] = $member;
            break;

          case PlayerAvailabilityCheckerInterface::OCCUPIED:
            $form_state->setError($form['teams'][$team_id]['players'][$player_index], PlayerAvailabilityMessage::playerOccupiedMessage($member));
            break;

          //case PlayerAvailabilityCheckerInterface::CONFLICTED_RANKING:
          //  $form_state->setError($form['teams'][$team_id]['players'][$player_index], PlayerAvailabilityMessage::playerRankingConflictMessage($member));
          //  break;

          //case PlayerAvailabilityCheckerInterface::UNAVAILABLE:
          //  $form_state->setError($form['teams'][$team_id]['players'][$player_index], PlayerAvailabilityMessage::playerUnavailableMessage($member));
          //  break;
        }
      }
    }
  }

}
