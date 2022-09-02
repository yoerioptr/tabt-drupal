<?php

namespace Drupal\tabt_team_management\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\tabt\Entity\Member;
use Drupal\tabt\Entity\MemberInterface;
use Drupal\tabt\Util\Enum\Tabt;
use Drupal\tabt_team_management\Repository\TeamSetupRepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @Block(
 *   id = "member_tournaments",
 *   admin_label = @Translation("Member tournaments"),
 *   category = "TabT",
 * )
 */
class MemberTournaments extends BlockBase implements ContainerFactoryPluginInterface {

  protected TeamSetupRepositoryInterface $teamSetupRepository;

  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    TeamSetupRepositoryInterface $teamSetupRepository
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->teamSetupRepository = $teamSetupRepository;
  }

  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition
  ): static {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('tabt.repository.team_setup')
    );
  }

  public function build(): array {
    $member = $this->getMember();

    return !is_null($member) ? [
      '#type' => 'view',
      '#name' => 'tabt_member_tournaments',
      '#display_id' => 'block',
      '#arguments' => [$member->id()],
    ] : [];
  }

  public function buildConfigurationForm(array $form, FormStateInterface $form_state): array {
    $form = parent::buildConfigurationForm($form, $form_state);

    $form['member'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Member'),
      '#target_type' => Tabt::MEMBER,
      '#required' => TRUE,
      '#default_value' => $this->getMember(),
    ];

    return $form;
  }

  public function submitConfigurationForm(array &$form, FormStateInterface $form_state): void {
    $this->configuration['member'] = $form_state->getValue('member');
  }

  private function getMember(): ?MemberInterface {
    return !is_null($this->configuration['member'])
      ? Member::load($this->configuration['member'])
      : NULL;
  }

}
