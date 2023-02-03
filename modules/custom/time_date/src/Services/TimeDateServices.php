<?php

/**
* @file providing the service that provide time and date based on timezone.
*
*/

namespace  Drupal\time_date\Services;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Config\ConfigFactory;

class TimeDateServices {

  /**
   * @var \Drupal\Core\Config\ConfigFactory;
   */
  private $config;

  /**
   * @param @param \Drupal\Core\Config\ConfigFactory $config
   */
  public function __construct(ConfigFactory $config) {
    $this->config = $config;
  }

  public function getTimeDate() {
    $config = $this->config->get('time_date.settings');
    $timezone = $config->get('timezone');
    $date = new DrupalDateTime("now", new \DateTimeZone($timezone));
    $formatted_date = $date->format('jS M o - h:i A');
    return $formatted_date;
  }

}
