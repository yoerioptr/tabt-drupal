<?php

namespace Drupal\tabt\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\tabt\Context\SeasonContext;
use Drupal\tabt\Util\Enum\Config;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Yoerioptr\TabtApiClient\Entries\ClubEntry;
use Yoerioptr\TabtApiClient\Entries\SeasonEntry;
use Yoerioptr\TabtApiClient\Repository\ClubRepository;
use Yoerioptr\TabtApiClient\Repository\SeasonRepository;

final class TabtSettingsForm extends ConfigFormBase {

  public const FORM_ID = 'tabt_settings_form';

  private ClubRepository $clubRepository;

  private SeasonRepository $seasonRepository;

  public function __construct(
    ConfigFactoryInterface $config_factory,
    ClubRepository $clubRepository,
    SeasonRepository $seasonRepository
  ) {
    parent::__construct($config_factory);
    $this->clubRepository = $clubRepository;
    $this->seasonRepository = $seasonRepository;
  }

  public static function create(ContainerInterface $container): self {
    return new TabtSettingsForm(
      $container->get('config.factory'),
      $container->get('tabt.repository.client.club'),
      $container->get('tabt.repository.client.season'),
    );
  }

  protected function getEditableConfigNames(): array {
    return [Config::TABT_SETTINGS];
  }

  public function getFormId(): string {
    return self::FORM_ID;
  }

  public function buildForm(array $form, FormStateInterface $form_state): array {
    $config = $this->config(Config::TABT_SETTINGS);

    $form['club'] = [
      '#type' => 'select',
      '#title' => $this->t('Club'),
      '#options' => $this->listClubs(),
      '#default_value' => $config->get('club') ?? NULL,
      '#required' => TRUE,
    ];

    $form['season'] = [
      '#type' => 'select',
      '#title' => $this->t('Season'),
      '#options' => $this->listSeasons(),
      '#default_value' => $config->get('season') ?? SeasonContext::SEASON_LATEST,
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $config = $this->config(Config::TABT_SETTINGS);
    $config->set('club', $form_state->getValue('club'));
    $config->set('season', $form_state->getValue('season'));
    $config->save();

    parent::submitForm($form, $form_state);
  }

  private function listClubs(): array {
    $clubs = $this->clubRepository->listClubs()->getClubEntries();

    return array_combine(
      array_map(fn(ClubEntry $club): string => $club->getUniqueIndex(), $clubs),
      array_map(fn(ClubEntry $club): string => "{$club->getUniqueIndex()} - {$club->getName()}", $clubs)
    );
  }

  private function listSeasons(): array {
    $seasons = array_reverse($this->seasonRepository->listSeasons()->getSeasonEntries());

    return [SeasonContext::SEASON_LATEST => $this->t('Latest')] + array_combine(
      array_map(fn(SeasonEntry $season): string => $season->getSeason(), $seasons),
      array_map(fn(SeasonEntry $season): string => $season->getName(), $seasons)
    );
  }

}
