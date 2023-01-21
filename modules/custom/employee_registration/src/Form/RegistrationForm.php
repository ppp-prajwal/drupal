<?php
/**
 * @file
 * Contains \Drupal\employee_registration\Form\RegistrationForm.
 */
namespace Drupal\employee_registration\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class RegistrationForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'registration_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['employee_name'] = array(
      '#type' => 'textfield',
      '#title' => t('Employee Name'),
      '#required' => TRUE,
    );
    $form['one_plus'] = array (
      '#type' => 'radios',
      '#title' => ('One Plus'),
      '#options' => array(
        'Yes' =>t('Yes'),
        'No' =>t('No'),
      ),
    );
    $form['amount_of_kids'] = array(
      '#type' => 'textfield',
      '#title' => t('Amount of Kids'),
      '#required' => TRUE,
    );
    $form['amount_of_vegeterians'] = array(
      '#type' => 'textfield',
      '#title' => t('Amount of Vegeterians'),
      '#required' => TRUE,
    );
    $form['email_address'] = array(
      '#type' => 'email',
      '#title' => t('Email Address'),
      '#required' => TRUE,
    );
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }
}
