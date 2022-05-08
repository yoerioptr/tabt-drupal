<?php

namespace Drupal\tabt_sync\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\tabt_sync\Exception\NonSyncableTypeException;
use Drupal\tabt_sync\TabtSyncerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class TabtSyncForm extends FormBase {

  private string $sync_type;

  private TabtSyncerInterface $tabtSyncer;

  public function __construct(TabtSyncerInterface $tabtSyncer) {
    $this->tabtSyncer = $tabtSyncer;
  }

  public static function create(ContainerInterface $container): TabtSyncForm {
    return new TabtSyncForm($container->get('tabt_sync.syncer'));
  }

  public function getFormId(): string {
    return 'tabt_sync_form';
  }

  public function buildForm(
    array $form,
    FormStateInterface $form_state,
    string $sync_type = ''
  ): array {
    $this->sync_type = $sync_type;

    $form['sync'] = [
      '#type' => 'submit',
      '#value' => $this->t('Sync'),
      '#attributes' => ['class' => ['button', 'button--primary']],
      '#name' => 'sync',
    ];

    $form['clear'] = [
      '#type' => 'submit',
      '#value' => $this->t('Clear'),
      '#attributes' => ['class' => ['button', 'button--danger']],
      '#name' => 'clear',
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state): void {
    try {
      switch ($form_state->getTriggeringElement()['#name']) {
        case 'sync':
          $this->tabtSyncer->syncSingle($this->sync_type);
          break;

        case 'clear':
          $this->tabtSyncer->truncateSingle($this->sync_type);
          break;
      }
    } catch (NonSyncableTypeException $exception) {
      // TODO: Display error message & logging
    }
  }

}
