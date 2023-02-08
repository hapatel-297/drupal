<?php

namespace Drupal\date_time\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\date_time\Service\DateTimeService;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a 'Date Time' block.
 *
 * @Block(
 *  id = "date_time",
 *  admin_label = @Translation("Datetime By Location"),
 * )
 */
class TimeBlock extends BlockBase implements ContainerFactoryPluginInterface {
  
  /**
   * @var \Drupal\date_time\Service\DateTimeService $dateTimeService
   */
  protected $dateTimeService;

  /**
   * @var \Drupal\Core\Config\ConfigFactoryInterface $configfactory
   */
  protected $configfactory;

  /**
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param \Drupal\date_time\Service\DateTimeService $dateTimeService
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configfactory
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, DateTimeService $dateTimeService, ConfigFactoryInterface $configfactory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->dateTimeService = $dateTimeService;
    $this->config = $configfactory->get('date_time.settings');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('date_time.time_by_location'),
      $container->get('config.factory')
    );
  }
  
  /**
   * {@inheritdoc}
   */
  public function build() {

    $city = $this->config->get('city');
    $country = $this->config->get('country');
    $time = $this->dateTimeService->getTimeByTimeZone();

    $build = [
        '#theme' => 'time_block',
        '#city' => $city,
        '#country' => $country,
        '#time' => $time,
    ];

    /*return [
        '#markup' => "Time block",
    ];*/

    $renderer = \Drupal::service('renderer');
    $renderer->addCacheableDependency($build, $config);

    return $build;
  }

}