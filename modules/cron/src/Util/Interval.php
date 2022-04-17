<?php

namespace Drupal\tabt_cron\Util;

final class Interval {

  public const HOURLY = 'h';
  public const DAILY = 'd';
  public const WEEKLY = 'w';
  public const CUSTOM = 'c';

  public static function options(): array {
    return [
      self::HOURLY => t('Hourly'),
      self::DAILY => t('Daily'),
      self::WEEKLY => t('Weekly'),
      self::CUSTOM => t('Custom'),
    ];
  }

  public static function isValidInterval(string $interval): bool {
    return TRUE;
  }

}
