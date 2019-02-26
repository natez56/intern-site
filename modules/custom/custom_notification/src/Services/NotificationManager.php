<?php

namespace Drupal\custom_notification\Services;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityTypeManager;

/**
 * Class NotificationManager.
 */
class NotificationManager
{
    /**
     * @var $entityTypeManager \Drupal\Core\Entity\EntityTypeManager
     */
    protected $entityTypeManager;

    /**
     * @var $notificationArray \Drupal\custom_notification\Services
     */
    protected $notificationArray;

    /**
     * @var $config \Drupal\Core\Config\ConfigFactory
     */
    protected $config;

    /**
     * @var $drupalDateTime \Drupal\Core\Datetime\DrupalDateTime
     */
    protected $drupalDateTime;

    /** @var string Config settings */
    const SETTINGS = 'custom_notification.settings.yml';

    /**
     * @param \Drupal\Core\Entity\EntityTypeManager $entityTypeManager
     * @param \Drupal\Core\Config\ConfigFactory $config
     * @param \Drupal\Core\DateTime\DrupalDateTime $drupalDateTime
     */
    public function __construct(EntityTypeManager $entityTypeManager,
        ConfigFactory $config, DrupalDateTime $drupalDateTime) {
        $this->entityTypeManager = $entityTypeManager;
        $this->notificationArray = null;
        $this->config = $config;
        $this->drupalDateTime = $drupalDateTime;
    }

    /**
     * Get notifications enabled information.
     *
     * @return bool
     *   True if notifications are enabled.
     */
    public function isNotificationSettingEnabled()
    {
        return $this->config->get(static::SETTINGS)->get('checkbox');
    }

    /**
     * Get config notification start time settings.
     *
     * @return string
     *   The date string representing the cutoff indicating that dates must
     *   be after this date to be displayed.
     */
    public function getConfigStartDate()
    {
        return $this->config->get(static::SETTINGS)->get('start');
    }

    /**
     * Get config notification end time settings.
     *
     * @return string
     *   The date string representing the cutoff for dates to be displayed.
     *   Dates must be lower that this result to be displayed.
     */
    public function getConfigEndDate()
    {
        return $this->config->get(static::SETTINGS)->get('end');
    }

    /**
     * Get latest updated notification that is published within specified range.
     * If range is not included then return most recent of all notifications.
     *
     * @param string $start
     *   Date string
     * @param string $end
     *   Date string
     * @return object
     *   Notification entity object
     */
    public function getLatestNotification($start = null, $end = null)
    {
        if (!$this->notificationArray) {
            $this->createNotificationArray();
        }

        if ($start || $end) {
            $validNotifications = $this->getNotificationsByDate($start, $end);

            return end($validNotifications);
        }

        return end($this->notificationArray);

    }
    /**
     * Returns the number of published notifications.
     *
     * @param string $start
     *   Date string
     * @param string $end
     *   Date string
     * @return int
     *   The number of notifications
     */
    public function getNotificationCount($start = null, $end = null)
    {
        if (!$this->notificationArray) {
            $this->createNotificationArray();
        }

        if ($start || $end) {
            $validNotifications = $this->getNotificationsByDate($start, $end);
            return count($validNotifications);
        }

        return count($this->notificationArray);
    }

    /**
     * Returns array of nodes by updated date and published.
     *
     * @param string $start
     *   Date string
     * @param string $end
     *   Date string
     * @return object[]
     *   Array of notification entity objects.
     */
    public function getRecentThreeNotifications($start = null, $end = null)
    {
        if (!$this->notificationArray) {
            $this->createNotificationArray();
        }

        if ($start || $end) {
            $validNotifications = $this->getNotificationsByDate($start, $end);
            return array_slice($validNotifications, -3, 3);
        }

        return array_slice($this->notificationArray, -3, 3);
    }

    /**
     * Query current notifications.
     *
     * @return object[]
     *   Array of notification objects.
     */
    private function createNotificationArray()
    {
        $notificationIds = $this->entityTypeManager
            ->getStorage('node')
            ->getQuery()
            ->condition('status', 1)
            ->condition('type', 'notification')
            ->execute();

        $this->notificationArray = $this->entityTypeManager
            ->getStorage('node')
            ->loadMultiple($notificationIds);

        $this->sortNotificationsByDateUpdated();
    }

    /**
     * Sorts array by date.
     */
    private function sortNotificationsByDateUpdated()
    {
        usort($this->notificationArray, [$this, 'cmpDate']);
    }

    /**
     * Helper function for usort to sort by date.
     *
     * @param object $a
     *   Notification entity object.
     * @param object $b
     *   Notification entity object.
     * @return int
     *   Int used as the return value that usort will receive.
     */
    private function cmpDate($a, $b)
    {
        $aDate = $a->get('changed')->value;
        $bDate = $b->get('changed')->value;

        if ($aDate == $bDate) {
            return 0;
        }

        return ($aDate < $bDate) ? -1 : 1;
    }

    /**
     * Use config settings to look for valid notifications that have created
     * dates between the config settings dates.
     *
     * @param string $start
     *   Date string
     * @param string $end
     *   Date string
     * @return object[]
     *   Array of notification entity objects.
     */
    private function getNotificationsByDate($start = null, $end = null)
    {
        $validNotifications = [];

        if ($start) {
            $start = new $this->drupalDateTime($start);
        }

        if ($end) {
            $end = new $this->drupalDateTime($end);
        }

        foreach ($this->notificationArray as $notification) {
            $createdDate = $this->drupalDateTime->createFromTimestamp($notification
                    ->get('created')->value);

            if ($start && $end) {
                if ($createdDate > $start && $createdDate < $end) {
                    array_push($validNotifications, $notification);
                }
            } else if ($start) {
                if ($createdDate > $start) {
                    array_push($validNotifications, $notification);
                }
            } else {
                if ($createdDate < $end) {
                    array_push($validNotifications, $notification);
                }
            }

        }

        return $validNotifications;
    }
}
