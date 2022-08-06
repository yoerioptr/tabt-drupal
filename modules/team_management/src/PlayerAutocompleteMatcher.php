<?php

namespace Drupal\tabt_team_management;

use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\Tags;
use Drupal\Core\Entity\EntityAutocompleteMatcher;
use Drupal\Core\Entity\EntityReferenceSelection\SelectionPluginManagerInterface;
use Drupal\tabt\Entity\Member;
use Drupal\tabt\Entity\TeamInterface;
use Drupal\tabt\Entity\TournamentInterface;
use Drupal\tabt_team_management\Helper\PlayerAvailability\PlayerAvailabilityCheckerInterface;

final class PlayerAutocompleteMatcher extends EntityAutocompleteMatcher {

  private PlayerAvailabilityCheckerInterface $playerAvailabilityChecker;

  public function __construct(
    SelectionPluginManagerInterface $selection_manager,
    PlayerAvailabilityCheckerInterface $playerAvailabilityChecker
  ) {
    parent::__construct($selection_manager);
    $this->playerAvailabilityChecker = $playerAvailabilityChecker;
  }

  public function getMatches(
    $target_type,
    $selection_handler,
    $selection_settings,
    $string = '',
    TournamentInterface $tournament = NULL,
    TeamInterface $team = NULL
  ): array {
    if (empty($string)) {
      return [];
    }

    $options = $selection_settings + ['target_type' => $target_type, 'handler' => $selection_handler];
    $handler = $this->selectionManager->getInstance($options);

    $match_operator = !empty($selection_settings['match_operator'])
      ? $selection_settings['match_operator']
      : 'CONTAINS';
    $match_limit = isset($selection_settings['match_limit'])
      ? (int) $selection_settings['match_limit']
      : 10;

    foreach ($handler->getReferenceableEntities($string, $match_operator, $match_limit) as $values) {
      foreach ($values as $entity_id => $label) {
        $member = Member::load($entity_id);
        $availability = $this->playerAvailabilityChecker->checkPlayerAvailability($member, $tournament, $team);
        if ($availability !== PlayerAvailabilityCheckerInterface::AVAILABLE) {
          continue;
        }

        $key = "$label ($entity_id)";
        $key = str_replace("\n", '', trim(Html::decodeEntities(strip_tags($key))));
        $key = preg_replace('/\s\s+/', ' ', $key);
        $key = Tags::encode($key);

        $matches[] = ['value' => $key, 'label' => $label];
      }

    }

    return $matches ?? [];
  }

}
