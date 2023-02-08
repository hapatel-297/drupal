<?php

namespace Drupal\date_time\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Datetime\DateFormatter;

/**
 * Get time by Location.
 */
class DateTimeService {

  /**
   * The Date Fromatter.
   *
   * @var Drupal\Core\Datetime\DateFormatter $date_formatter
   */
  protected $date_formatter;

/**
 * Constructs an Utility object.
 *
 * \Drupal\Core\Config\ConfigFactoryInterface $configfactory
 * \Drupal\Core\Datetime\DateFormatter $date_formatter.
 */
public function __construct(ConfigFactoryInterface $configfactory, DateFormatter $date_formatter) {
    $this->config = $configfactory->get('date_time.settings');
    $this->date_formatter = $date_formatter;
}

/**
 * Fetch time using timezone.
 */
  public function getTimeByTimeZone() {
    
    $zone = $this->config->get('timezone');
    $current_time = new DrupalDateTime('now');
    $timestamp = $current_time->getTimestamp();
    $date = $this->date_formatter->format($timestamp, 'custom', 'dS M Y - h:i A', $zone);
    return $date;
  }

}
