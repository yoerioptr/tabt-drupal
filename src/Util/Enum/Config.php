<?php

namespace Drupal\tabt\Util\Enum;

final class Config {

  public const TABT_SETTINGS = 'tabt.settings';

  public static function getConstants(): array {
    return (new \ReflectionClass(self::class))->getConstants();
  }

}
