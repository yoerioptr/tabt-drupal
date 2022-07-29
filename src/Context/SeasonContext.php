<?php

namespace Drupal\tabt\Context;

use Drupal\Core\Config\ConfigFactoryInterface;
use Yoerioptr\TabtApiClient\Repository\SeasonRepository;

final class SeasonContext extends ConfigContext {

  public const SEASON_LATEST = 'latest';

  private SeasonRepository $seasonRepository;

  public function __construct(
    ConfigFactoryInterface $configFactory,
    SeasonRepository $seasonRepository
  ) {
    parent::__construct($configFactory);
    $this->seasonRepository = $seasonRepository;
  }

  public function getSeason(): int {
    $season = $this->getConfigValue('season');

    return $season === self::SEASON_LATEST
      ? $this->seasonRepository->listSeasons()->getCurrentSeason()
      : (int) $season;
  }

}
