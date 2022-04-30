<?php

namespace Drupal\tabt\Util\Enum;

final class Tabt {

  public const DIVISION = 'tabt_division';
  public const MEMBER = 'tabt_member';
  public const TEAM = 'tabt_team';
  public const TOURNAMENT = 'tabt_tournament';
  public const VENUE = 'tabt_venue';

  public static function labels(): array {
    return [
      self::DIVISION => t('Division'),
      self::MEMBER => t('Member'),
      self::TEAM => t('Team'),
      self::TOURNAMENT => t('Tournament'),
      self::VENUE => t('Venue'),
    ];
  }

}
