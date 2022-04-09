<?php

namespace Drupal\tabt\Context;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\ImmutableConfig;
use Drupal\tabt\Util\Enum\Config;

final class ClubContext {

  private ImmutableConfig $config;

  public function __construct(ConfigFactoryInterface $configFactory) {
    $this->config = $configFactory->get(Config::TABT_SETTINGS);
  }

  public function getClub(): string {
    return $this->config->get('club');
  }

}
