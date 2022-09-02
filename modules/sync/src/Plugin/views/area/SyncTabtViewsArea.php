<?php

namespace Drupal\tabt_sync\Plugin\views\area;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\tabt_sync\Form\TabtSyncForm;
use Drupal\views\Plugin\views\area\AreaPluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @ingroup tabt_views_area_handlers
 *
 * @ViewsArea("tabt_sync")
 */
class SyncTabtViewsArea extends AreaPluginBase {

  protected FormBuilderInterface $formBuilder;

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
  ): static {
    return new static(
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
      str_replace('tabt_', '', $entity_type_id)
    );
  }

  public function access(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'sync tabt entities');
  }

  private function getTargetEntityTypeId(): string {
    foreach ($this->view->getQuery()->getEntityTableInfo() as $table_info) {
      if (strpos($table_info['entity_type'], 'tabt_') === 0) {
        return $table_info['entity_type'];
      }
    }

    $this->messenger()->addWarning($this->t('Sync form is not compatible with this view.'));

    return '';
  }

}
