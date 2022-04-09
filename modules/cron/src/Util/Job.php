<?php

namespace Drupal\tabt_cron\Util;

final class Job {

  public const TOURNAMENT = 'tournament';
  public const MEMBER = 'member';

  public static function options(): array {
    return [
      self::TOURNAMENT => t('Tournaments'),
      self::MEMBER => t('Members'),
    ];
  }

}
