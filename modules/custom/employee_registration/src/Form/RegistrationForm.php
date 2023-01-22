<?php
/**
 * @file
 * Contains \Drupal\employee_registration\Form\RegistrationForm.
 */
namespace Drupal\employee_registration\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RegistrationForm extends FormBase {

  /**
   * The current route match.
   *
   * @var \Drupal\Core\Routing\CurrentRouteMatch
   */
  protected $currentRouteMatch;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Instance of Entity Type Manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManage;

  /**
   * The Messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Constructs a new MyController object.
   *
   * @param \Drupal\Core\Routing\CurrentRouteMatch $currentRouteMatch
   *   The current route match.
   * @param \Drupal\Core\Database\Connection $database
   *  The database connection.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The Entity Type Manager.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   */
  public function __construct(CurrentRouteMatch $currentRouteMatch, Connection $database, EntityTypeManagerInterface $entity_type_manager, MessengerInterface $messenger) {
    $this->currentRouteMatch = $currentRouteMatch;
    $this->database = $database;
    $this->entityTypeManager = $entity_type_manager;
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_route_match'),
      $container->get('database'),
      $container->get('entity_type.manager'),
      $container->get('messenger')
    );
  }

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

    $department = $this->currentRouteMatch->getParameter('department');
    $result = $this->database->select('department','d')->fields('d',['department_name','department_code_name'])->execute()->fetchAll(\PDO::FETCH_OBJ);
    foreach($result as $row) {
      if ($department == $row->department_code_name){
        $department_check = 'TRUE';
        break;
      }
      else $department_check = 'FALSE';
    }

    if($department_check == 'TRUE') {
      return $form;
    }
    else return [
      '#markup' => "<b>Please enter valid department code in form url.</b>",
    ];
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

    $nids = $this->entityTypeManager->getStorage('node')->getQuery()->accessCheck(FALSE)->condition('status',1)->condition('type','registration')->execute();
    $nodes = $this->entityTypeManager->getStorage('node')->loadMultiple($nids);
    foreach($nodes as $node) {
      if($form_state->getValue('email_address') == $node->field_email_address->value) {
        $duplicate_email_check = 'TRUE';
      }
      else $duplicate_email_check = 'FALSE';
    }

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
    elseif($amount_of_vegeterians > $total_people) {
      $form_state->setErrorByName('amount_of_vegeterians', $this->t('Amount of vegeterians cannot be greater than total amount of people.'));
    }
    elseif($duplicate_email_check == 'TRUE'){
      $form_state->setErrorByName('email_address', $this->t('Email address already exists.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $department = $this->currentRouteMatch->getParameter('department');
    $result = $this->database->select('department','d')->fields('d',['department_name','department_code_name'])->execute()->fetchAll(\PDO::FETCH_OBJ);
    foreach($result as $row) {
      if ($department == $row->department_code_name){
        $department_name = $row->department_name;
        break;
      }
    }

    $node = Node::create(['type' => 'registration']);
    $node->uid = 1;
    $node->promote = 0;
    $node->sticky = 0;
    $node->title = $form_state->getValue('employee_name');
    $node->field_one_plus = $form_state->getValue('one_plus');
    $node->field_amount_of_kids = $form_state->getValue('amount_of_kids');
    $node->field_amount_of_vegeterians = $form_state->getValue('amount_of_vegeterians');
    $node->field_email_address = $form_state->getValue('email_address');
    $node->field_department_name = $department_name;
    $node->save();

    $this->messenger->addStatus(t('Employee details registered successfully for an event.'));
  }
}
