<?php

namespace Drupal\employee_registration\Plugin\Block;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

use Drupal\Core\Block\BlockBase;

/**
* Provides a block with a simple text.
*
* @Block(
*   id = "registration_count",
*   admin_label = @Translation("Registration Count"),
* )
*/
class RegistrationCount extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManage;

  /**
   * Constructs a RegistrationCount.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param array $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $nids = $this->entityTypeManager->getStorage('node')->getQuery()->accessCheck('FALSE')->condition('status',1)->condition('type','registration')->execute();
    
    return [
      '#markup' => "Registration Count  : ".count($nids),
      '#cache' => [
        'max-age' => 0,
      ]
    ];
  }
}
