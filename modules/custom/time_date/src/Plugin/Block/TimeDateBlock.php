<?php

namespace Drupal\time_date\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\time_date\Services\TimeDateServices;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Show time date in a block.
 *
 * @Block(
 *   id = "time_date",
 *   admin_label = @Translation("Time Date"),
 *   category = @Translation("Time Date")
 * )
 */
class TimeDateBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\time_date\TimeDateServices;
   */
  protected $get_timedate;

  /**
   * @var \Drupal\Core\Config\ConfigFactoryInterface;
   */
  protected $config;

  /**
   * {@inheritdoc}
   */
  public function build() {

    $country = $this->config->get('time_date.settings')->get('country');
    $city = $this->config->get('time_date.settings')->get('city');
    $date_time = $this->get_timedate->getTimeDate();
    $date_time_sep = explode(" - ",$date_time);
    $date = date('l, j F o', strtotime($date_time_sep[0]));
    $time = date('g:i a', strtotime($date_time_sep[1]));
    $location = 'Time in '.$city.', '.$country.'.';

    $data[] = [
      'date'=>$date,
      'time'=>$time,
      'location'=>$location,
    ];

    $build[] = [
      '#items'=>$data,
      '#cache'=>[
        'max-age'=>0
      ],
    ];

    return $build;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('time_date.get_timedate'),
      $container->get('config.factory'),
    );
  }

  /**
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param \Drupal\time_date\TimeDateServices $get_timedate
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, TimeDateServices $get_timedate, ConfigFactoryInterface $config) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->get_timedate = $get_timedate;
    $this->config = $config;
  }

}