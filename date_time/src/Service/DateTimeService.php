<?php

namespace Drupal\date_time\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Get time by Location.
 */
class DateTimeService {

/**
 * Constructs an Utility object.
 *
 * \Drupal\Core\Config\ConfigFactoryInterface $configFactory.
 */
public function __construct(ConfigFactoryInterface $configFactory) {
    $this->config = $configFactory->get('date_time.settings');
}

/**
 * Fetch time using timezone.
 */
  public function getTimeByTimeZone() {
    
    $zone = $this->config->get('timezone');
    $current_time = new DrupalDateTime('now');
    $timestamp = $current_time->getTimestamp();
    $date = \Drupal::service('date.formatter')->format($timestamp, 'custom', 'dS M Y - h:i A', $zone);
    return $date;
  }

}
