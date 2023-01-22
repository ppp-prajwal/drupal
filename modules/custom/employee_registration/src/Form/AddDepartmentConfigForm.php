<?php
/**
 * @file
 * Contains \Drupal\employee_registration\Form\AddDepartmentConfigForm.
 */
namespace Drupal\employee_registration\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

class AddDepartmentConfigForm extends ConfigFormBase {

  /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'employee_registration.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'add_department_config_form';
  }

  /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /** 
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);

    $form['department_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Department Name'),
      '#default_value' => $config->get('department_name'),
    ];  

    $form['department_code_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Department Code Name'),
      '#default_value' => $config->get('department_code_name'),
    ];  

    return parent::buildForm($form, $form_state);
  }

  /** 
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $field['department_name'] = $form_state->getValue('department_name');
    $field['department_code_name'] = $form_state->getValue('department_code_name');
    $conn = Database::getConnection();
    $conn->insert('department')->fields($field)->execute();
    parent::submitForm($form, $form_state);
  }
}