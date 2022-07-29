<?php

namespace Drupal\tabt\Context;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\ImmutableConfig;
use Drupal\tabt\Util\Enum\Config;

abstract class ConfigContext {

  private ImmutableConfig $config;

  public function __construct(ConfigFactoryInterface $configFactory) {
    $this->config = $configFactory->get(Config::TABT_SETTINGS);
  }

  protected function getConfigValue(string $property): mixed {
    return $this->config->get($property) ?? NULL;
  }

}
