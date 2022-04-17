<?php

namespace Drupal\tabt_cron\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\tabt_cron\Util\Interval;
use Drupal\tabt_cron\Util\Job;

final class TabtCronSettingsForm extends ConfigFormBase {

  private const FORM_ID = 'tabt_cron_settings_form';

  protected function getEditableConfigNames(): array {
    return [];
  }

  public function getFormId(): string {
    return self::FORM_ID;
  }

  public function buildForm(array $form, FormStateInterface $form_state): array {
    $this->addCronSettings($form);

    return parent::buildForm($form, $form_state);
  }

  private function addCronSettings(array &$form): void {
    $form['interval'] = [
      '#type' => 'select',
      '#title' => $this->t('Interval'),
      '#options' => Interval::options(),
      '#default_value' => $this->getSetting('interval'),
      '#attributes' => ['name' => 'cron_interval'],
    ];

    $form['custom_interval'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Custom interval'),
      '#states' => ['visible' => [':input[name="cron_interval"]' => ["value" => Interval::CUSTOM]]],
      '#default_value' => $this->getSetting('custom_interval'),
    ];

    $form['jobs'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Jobs'),
      '#options' => Job::options(),
      '#default_value' => $this->getSetting('jobs'),
    ];
  }

  private function getSetting(string $setting) {
    return NULL;
  }

}
