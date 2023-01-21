<?php

namespace Drupal\employee_registration\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
* Provides a block with a simple text.
*
* @Block(
*   id = "registration_count",
*   admin_label = @Translation("Registration Count"),
* )
*/
class RegistrationCount extends BlockBase {
  
  /**
   * {@inheritdoc}
   */
  public function build() {
    $nids = \Drupal::entityQuery('node')->accessCheck('FALSE')->condition('status',1)->condition('type','registration')->execute();
    
    return [
      '#markup' => "Registration Count  : ".count($nids),
      '#cache' => [
        'max-age' => 0,
      ]
    ];
  }
}
