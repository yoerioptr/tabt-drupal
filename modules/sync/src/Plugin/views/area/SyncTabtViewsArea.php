<?php

namespace Drupal\tabt_sync\Plugin\views\area;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\tabt\Util\Enum\Tabt;
use Drupal\tabt_sync\DataFetcher\DataFetcherInterface;
use Drupal\tabt_sync\Event\Sync\SyncDivisionEvent;
use Drupal\tabt_sync\Event\Sync\SyncMemberEvent;
use Drupal\tabt_sync\Event\Sync\SyncTeamEvent;
use Drupal\tabt_sync\Event\Sync\SyncTournamentEvent;
use Drupal\tabt_sync\Event\Sync\SyncVenueEvent;
use Drupal\tabt_sync\Event\Truncate\TruncateDivisionsEvent;
use Drupal\tabt_sync\Event\Truncate\TruncateMembersEvent;
use Drupal\tabt_sync\Event\Truncate\TruncateTeamsEvent;
use Drupal\tabt_sync\Event\Truncate\TruncateTournamentsEvent;
use Drupal\tabt_sync\Event\Truncate\TruncateVenuesEvent;
use Drupal\tabt_sync\Form\TabtSyncForm;
use Drupal\views\Plugin\views\area\AreaPluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @ingroup tabt_views_area_handlers
 *
 * @ViewsArea("tabt_sync")
 */
final class SyncTabtViewsArea extends AreaPluginBase {

  private FormBuilderInterface $formBuilder;

  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    FormBuilderInterface $formBuilder
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->formBuilder = $formBuilder;
  }

  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition
  ): SyncTabtViewsArea {
    return new SyncTabtViewsArea(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('form_builder')
    );
  }

  public function render($empty = FALSE): array {
    $entity_type_id = $this->getTargetEntityTypeId();
    if (empty($entity_type_id)) {
      return [];
    }

    return $this->formBuilder->getForm(
      TabtSyncForm::class,
      $this->getSyncEvent($entity_type_id),
      $this->getClearEvent($entity_type_id),
      $this->getDataFetcher($entity_type_id)
    );
  }

  private function getSyncEvent(string $entity_type_id): string {
    switch ($entity_type_id) {
      case Tabt::DIVISION:
        return SyncDivisionEvent::class;
      case Tabt::MEMBER:
        return SyncMemberEvent::class;
      case Tabt::TEAM:
        return SyncTeamEvent::class;
      case Tabt::TOURNAMENT:
        return SyncTournamentEvent::class;
      case Tabt::VENUE:
        return SyncVenueEvent::class;
      default:
        return '';
    }
  }

  private function getClearEvent(string $entity_type_id): string {
    switch ($entity_type_id) {
      case Tabt::DIVISION:
        return TruncateDivisionsEvent::class;
      case Tabt::MEMBER:
        return TruncateMembersEvent::class;
      case Tabt::TEAM:
        return TruncateTeamsEvent::class;
      case Tabt::TOURNAMENT:
        return TruncateTournamentsEvent::class;
      case Tabt::VENUE:
        return TruncateVenuesEvent::class;
      default:
        return '';
    }
  }

  private function getDataFetcher(string $entity_type_id): ?DataFetcherInterface {
    switch ($entity_type_id) {
      case Tabt::DIVISION:
        return \Drupal::service('tabt_sync.data_fetcher.division');
      case Tabt::MEMBER:
        return \Drupal::service('tabt_sync.data_fetcher.member');
      case Tabt::TEAM:
        return \Drupal::service('tabt_sync.data_fetcher.team');
      case Tabt::TOURNAMENT:
        return \Drupal::service('tabt_sync.data_fetcher.tournament');
      case Tabt::VENUE:
        return \Drupal::service('tabt_sync.data_fetcher.venue');
      default:
        return NULL;
    }
  }

  public function access(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'sync tabt entities');
  }

  private function getTargetEntityTypeId(): string {
    $entity_type_id = '';
    foreach ($this->view->getQuery()->getEntityTableInfo() as $table_info) {
      $entity_type_id = $table_info['entity_type'];
      break;
    }

    if (strpos($entity_type_id, 'tabt_') !== 0) {
      \Drupal::messenger()->addWarning($this->t('Sync form is not compatible with this view.'));
      return '';
    }

    return $entity_type_id;
  }

}
