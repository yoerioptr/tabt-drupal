<?php

namespace Drupal\tabt_team_management\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\tabt\Entity\Member;
use Drupal\tabt\Entity\Team;
use Drupal\tabt\Entity\TeamInterface;
use Drupal\tabt\Entity\TournamentInterface;
use Drupal\tabt_team_management\Entity\TeamSetup;
use Drupal\tabt_team_management\Helper\PlayerAvailability\PlayerAvailabilityCheckerInterface;
use Drupal\tabt_team_management\Repository\TeamSetupRepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class TournamentTeamManagementForm extends FormBase {

  private ?TournamentInterface $tournament = NULL;

  private PlayerAvailabilityCheckerInterface $playerAvailabilityChecker;

  private TeamSetupRepositoryInterface $teamSetupRepository;

  public function __construct(
    PlayerAvailabilityCheckerInterface $playerAvailabilityChecker,
    TeamSetupRepositoryInterface $teamSetupRepository
  ) {
    $this->playerAvailabilityChecker = $playerAvailabilityChecker;
    $this->teamSetupRepository = $teamSetupRepository;
  }

  public static function create(ContainerInterface $container): self {
    return new self(
      $container->get('tabt.helper.player_availability_checker'),
      $container->get('tabt.repository.team_setup')
    );
  }

  public function getFormId(): string {
    return 'tournament_team_management';
  }

  public function buildForm(
    array $form,
    FormStateInterface $form_state,
    TournamentInterface $tournament = NULL
  ): array {
    $this->tournament = $tournament;

    $form['#tree'] = TRUE;
    foreach ([$this->tournament->getHomeTeam(), $this->tournament->getAwayTeam()] as $team) {
      if (!$team instanceof TeamInterface) {
        continue;
      }

      if (!is_null($setup = $this->teamSetupRepository->getSetup($tournament, $team))) {
        $players = $setup->getPlayers();
      }

      $form['teams'][$team->id()] = [
        '#type' => 'fieldset',
        '#title' => $team->label(),
        '#access' => !$team->isExternal(),
      ];

      // TODO: Find a way to dynamically declare the amount of players
      for ($i = 0; $i < 4; $i++) {
        $form['teams'][$team->id()]['players'][$i] = [
          '#type' => 'player_autocomplete',
          '#tournament' => $tournament,
          '#team' => $team,
          '#default_value' => $players[$i] ?? NULL,
        ];
      }
    }

    $form['actions']['save'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#attributes' => ['class' => ['button', 'button--primary']],
      '#name' => 'save',
    ];

    $form['actions']['clear'] = [
      '#type' => 'submit',
      '#value' => $this->t('Clear'),
      '#attributes' => ['class' => ['button', 'button--danger']],
      '#name' => 'clear',
    ];

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state): void {
    $this->playerAvailabilityChecker->validateFormInput($form, $form_state, $this->tournament);
    parent::validateForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state): void {
    if ($form_state->getTriggeringElement()['#name'] === 'save') {
      foreach ($form_state->getValues()['teams'] as $team_id => $values) {
        $setup = $this->teamSetupRepository->getSetup($this->tournament, Team::load($team_id)) ?? TeamSetup::create();
        $setup->set('tournament', $this->tournament);
        $setup->set('team', Team::load($team_id));
        $setup->set('members', Member::loadMultiple(array_filter($values['players'])));
        $setup->save();
      }

      $this->messenger()->addMessage($this->t('The setup has been saved.'));
    }

    if ($form_state->getTriggeringElement()['#name'] === 'clear') {
      /** @var \Drupal\tabt_team_management\Entity\TeamSetupInterface $setup */
      foreach ($this->teamSetupRepository->listSetupsByTournament($this->tournament) as $setup) {
        $setup->delete();
      }

      $this->messenger()->addMessage($this->t('The setup has been cleared.'));
    }
  }

}
