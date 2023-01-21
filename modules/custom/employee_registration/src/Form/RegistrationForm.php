<?php
/**
 * @file
 * Contains \Drupal\employee_registration\Form\RegistrationForm.
 */
namespace Drupal\employee_registration\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

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
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $amount_of_vegeterians = $form_state->getValue('amount_of_vegeterians');
    $amount_of_kids = $form_state->getValue('amount_of_kids');
    if($form_state->getValue('one_plus') == "Yes") {
      $one_plus = 1;
    }
    else $one_plus = 0;
    $total_people = $amount_of_kids + $one_plus + 1;

    if(!preg_match('/^[a-zA-Z\'\-]+$/', $form_state->getValue('employee_name'))) {
      $form_state->setErrorByName('employee_name', $this->t('Only alphabets are allowed.'));
    }
    elseif(!preg_match('/^[0-9]+$/', $form_state->getValue('amount_of_kids'))) {
      $form_state->setErrorByName('amount_of_kids', $this->t('Only numbers are allowed.'));
    }
    elseif(!preg_match('/^[0-9]+$/', $form_state->getValue('amount_of_vegeterians'))) {
      $form_state->setErrorByName('amount_of_vegeterians', $this->t('Only numbers are allowed.'));
    }
    elseif(!preg_match('/^.{2,40}\@.{2,50}\..{2,5}\z/', $form_state->getValue('email_address'))) {
      $form_state->setErrorByName('email_address', $this->t('Please enter a valid email address.'));
    }
    if($amount_of_vegeterians > $total_people) {
      $form_state->setErrorByName('amount_of_vegeterians', $this->t('Amount of vegeterians cannot be greater than total amount of people.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $node = Node::create(['type' => 'registration']);
    $node->uid = 1;
    $node->promote = 0;
    $node->sticky = 0;
    $node->title = $form_state->getValue('employee_name');
    $node->field_one_plus = $form_state->getValue('one_plus');
    $node->field_amount_of_kids = $form_state->getValue('amount_of_kids');
    $node->field_amount_of_vegeterians = $form_state->getValue('amount_of_vegeterians');
    $node->field_email_address = $form_state->getValue('email_address');
    $node->save();
  }
}
