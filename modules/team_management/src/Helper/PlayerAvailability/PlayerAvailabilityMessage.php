<?php

namespace Drupal\tabt_team_management\Helper\PlayerAvailability;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\tabt\Entity\MemberInterface;

class PlayerAvailabilityMessage {

  public static function playerDuplicateMessage(MemberInterface $member): TranslatableMarkup {
    return t('%player cannot participate twice in the same tournament.', ['%player' => $member->label()]);
  }

  public static function playerOccupiedMessage(MemberInterface $member): TranslatableMarkup {
    return t('%player is occupied and unable to participate.', ['%player' => $member->label()]);
  }

  public static function playerRankingConflictMessage(MemberInterface $member): TranslatableMarkup {
    return t('%player has a conflicting ranking index.', ['%player' => $member->label()]);
  }

  public static function playerUnavailableMessage(MemberInterface $member): TranslatableMarkup {
    return t('%player is unavailable.', ['%player' => $member->label()]);
  }

}
