<?php

namespace Drupal\tabt_team_management\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\tabt\Entity\TeamInterface;
use Drupal\tabt\Entity\TournamentInterface;
use Drupal\tabt_team_management\Helper\PlayerAvailability\PlayerAvailabilityCheckerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class TournamentTeamManagementForm extends FormBase {

  private ?TournamentInterface $tournament = NULL;

  private PlayerAvailabilityCheckerInterface $playerAvailabilityChecher;

  public function __construct(PlayerAvailabilityCheckerInterface $playerAvailabilityChecher) {
    $this->playerAvailabilityChecher = $playerAvailabilityChecher;
  }

  public static function create(ContainerInterface $container): self {
    return new self($container->get('tabt_team_management.helper.player_availability_checker'));
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

      $form['teams'][$team->id()] = [
        '#type' => 'fieldset',
        '#title' => $team->label(),
        '#access' => !$team->isExternal(),
      ];

      // TODO: Find a way to dynamically declare the amount of players
      for ($i = 0; $i < 4; $i++) {
        $form['teams'][$team->id()]['players'][$i] = [
          '#type' => 'entity_autocomplete',
          '#target_type' => 'tabt_member',
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
    $this->playerAvailabilityChecher->validateFormInput($form, $form_state, $this->tournament);
    parent::validateForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state): void {
  }

}
