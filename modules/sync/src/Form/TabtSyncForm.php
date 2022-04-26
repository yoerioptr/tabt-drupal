<?php

namespace Drupal\tabt_sync\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\tabt_sync\DataFetcher\DataFetcherInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class TabtSyncForm extends FormBase {

  private EventDispatcherInterface $eventDispatcher;

  private string $syncEvent;

  private string $clearEvent;

  private DataFetcherInterface $dataFetcher;

  public function __construct(EventDispatcherInterface $eventDispatcher) {
    $this->eventDispatcher = $eventDispatcher;
  }

  public static function create(ContainerInterface $container) {
    return new TabtSyncForm($container->get('event_dispatcher'));
  }

  public function getFormId(): string {
    return 'tabt_sync_form';
  }

  public function buildForm(
    array $form,
    FormStateInterface $form_state,
    string $sync_event = '',
    string $clear_event = '',
    DataFetcherInterface $data_fetcher = NULL
  ): array {
    $this->syncEvent = $sync_event;
    $this->clearEvent = $clear_event;
    $this->dataFetcher = $data_fetcher;

    if (!empty($this->syncEvent) && class_exists($this->syncEvent)) {
      $form['sync'] = [
        '#type' => 'submit',
        '#value' => $this->t('Sync'),
        '#attributes' => ['class' => ['button', 'button--primary']],
        '#name' => 'sync',
      ];
    }

    if (!empty($this->clearEvent) && class_exists($this->clearEvent)) {
      $form['clear'] = [
        '#type' => 'submit',
        '#value' => $this->t('Clear'),
        '#attributes' => ['class' => ['button', 'button--danger']],
        '#name' => 'clear',
      ];
    }

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state): void {
    switch ($form_state->getTriggeringElement()['#name']) {
      case 'sync':
        foreach ($this->dataFetcher->listItemsToSync() as $item) {
          $this->eventDispatcher->dispatch(new $this->syncEvent($item));
        }
        break;
      case 'clear':
        $this->eventDispatcher->dispatch(new $this->clearEvent());
        break;
    }
  }

}
