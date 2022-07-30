<?php

namespace Drupal\tabt_team_management\Helper\PlayerAvailability;

use Drupal\Core\Form\FormStateInterface;
use Drupal\tabt\Entity\MemberInterface;
use Drupal\tabt\Entity\TeamInterface;
use Drupal\tabt\Entity\TournamentInterface;

interface PlayerAvailabilityCheckerInterface {

  public const AVAILABLE = 1;
  public const OCCUPIED = 2;
  public const CONFLICTED_RANKING = 3;
  public const UNAVAILABLE = 4;

  public function checkPlayerAvailability(
    MemberInterface $member,
    TournamentInterface $tournament,
    TeamInterface $team
  ): int;

  public function validateFormInput(
    array &$form,
    FormStateInterface $form_state,
    TournamentInterface $tournament
  ): void;

}
