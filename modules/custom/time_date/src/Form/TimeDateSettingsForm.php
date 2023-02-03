<?php

namespace Drupal\time_date\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Sessions core settings for this site.
 */
class TimeDateSettingsForm extends ConfigFormBase {

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'time_date_settings';
    }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['time_date.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['country'] = [
      '#type' => 'textfield',
      '#title' => 'Country',
      '#default_value' => $this->config('time_date.settings')->get('country'),
    ];
    $form['city'] = [
      '#type' => 'textfield',
      '#title' => 'City',
      '#default_value' => $this->config('time_date.settings')->get('city'),
    ];
    $form['timezone'] = [
      '#type' => 'select',
      '#title' => 'Timzone',
      '#default_value' => $this->config('time_date.settings')->get('timezone'),
      '#options' => [
        'America/Chicago' => 'America/Chicago',
        'America/New_York' => 'America/New_York',
        'Asia/Tokyo' => 'Asia/Tokyo',
        'Asia/Dubai' => 'Asia/Dubai',
        'Asia/Kolkata' => 'Asia/Kolkata',
        'Europe/Amsterdam' => 'Europe/Amsterdam',
        'Europe/Oslo' => 'Europe/Oslo',
        'Europe/London' => 'Europe/London',
      ]
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    if(!preg_match('/^[a-z A-Z\'\-]+$/', $form_state->getValue('country'))) {
      $form_state->setErrorByName('country', $this->t('Only alphabets are allowed.'));
    }
    elseif(!preg_match('/^[a-z A-Z\'\-]+$/', $form_state->getValue('city'))) {
      $form_state->setErrorByName('city', $this->t('Only alphabets are allowed.'));
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('time_date.settings')
      ->set('country', $form_state->getValue('country'))
      ->set('city', $form_state->getValue('city'))
      ->set('timezone', $form_state->getValue('timezone'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
