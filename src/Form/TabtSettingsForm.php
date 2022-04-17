<?php

namespace Drupal\tabt\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\tabt\Event\TruncateDivisionsEvent;
use Drupal\tabt\Event\TruncateMembersEvent;
use Drupal\tabt\Event\TruncateTeamsEvent;
use Drupal\tabt\Event\TruncateTournamentsEvent;
use Drupal\tabt\Event\TruncateVenuesEvent;
use Drupal\tabt\Repository\MemberRepositoryInterface;
use Drupal\tabt\Util\Enum\Config;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Yoerioptr\TabtApiClient\Entries\ClubEntry;
use Yoerioptr\TabtApiClient\Repository\ClubRepository;

final class TabtSettingsForm extends ConfigFormBase {

  public const FORM_ID = 'tabt_settings_form';

  private ClubRepository $clubRepository;

  private EventDispatcherInterface $eventDispatcher;

  public function __construct(
    ConfigFactoryInterface $config_factory,
    ClubRepository $clubRepository,
    EventDispatcherInterface $eventDispatcher
  ) {
    parent::__construct($config_factory);
    $this->clubRepository = $clubRepository;
    $this->eventDispatcher = $eventDispatcher;
  }

  public static function create(ContainerInterface $container): self {
    return new TabtSettingsForm(
      $container->get('config.factory'),
      $container->get('tabt.repository.client.club'),
      $container->get('event_dispatcher')
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

    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $config = $this->config(Config::TABT_SETTINGS);
    $config->set('club', $form_state->getValue('club'));
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

}
