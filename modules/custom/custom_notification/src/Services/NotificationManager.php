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
    private $entityTypeManager;
    private $notificationArray;
    private $config;
    const SETTINGS = 'custom_notification.settings.yml';

    /**
     * Constructs a new CustomService object.
     */
    public function __construct(EntityTypeManager $entityTypeManager,
        ConfigFactory $config) {
        $this->entityTypeManager = $entityTypeManager;
        $notificationIds = $entityTypeManager
            ->getStorage('node')
            ->getQuery()
            ->condition('status', 1)
            ->condition('type', 'notification')
            ->execute();

        $this->notificationArray = $entityTypeManager
            ->getStorage('node')
            ->loadMultiple($notificationIds);

        $this->sortByDate();
        $this->config = $config;
    }

    /**
     * Get notifications enabled information.
     */
    public function isNotificationSettingEnabled()
    {
        return $this->config->get(static::SETTINGS)->get('checkbox');
    }

    /**
     * Get config notification start time settings.
     */
    public function getConfigStartDate()
    {
        return $this->config->get(static::SETTINGS)->get('start');
    }

    /**
     * Get config notification end time settings.
     */
    public function getConfigEndDate()
    {
        return $this->config->get(static::SETTINGS)->get('end');
    }

    /**
     * Get latest updated notification that is published within specified range.
     * If range is not included then return most recent of all notifications.
     */
    public function getLatestNotification($start = null, $end = null)
    {
        if ($start || $end) {
            $validNotifications = $this->getNotificationsByDate($start, $end);

            return end($validNotifications);
        }

        return end($this->notificationArray);

    }
    /**
     * Returns the number of published notifications.
     */
    public function getNotificationCount($start = null, $end = null)
    {
        if ($start || $end) {
            $validNotifications = $this->getNotificationsByDate($start, $end);
            return count($validNotifications);
        }

        return count($this->notificationArray);
    }

    /**
     * Returns array of nodes by updated date and published.
     */
    public function getRecentThreeNotifications($start = null, $end = null)
    {
        $this->sortByDate();
        if ($start || $end) {
            $validNotifications = $this->getNotificationsByDate($start, $end);

            return array_slice($validNotifications, -3, 3);
        }

        return array_slice($this->notificationArray, -3, 3);
    }

    /**
     * Sorts array by date.
     */
    private function sortByDate()
    {
        usort($this->notificationArray, [$this, 'cmpDate']);
    }

    /**
     * Helper function for usort to sort by date.
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
     */
    private function getNotificationsByDate($start = null, $end = null)
    {
        $validNotifications = [];

        if ($start) {
            $start = new DrupalDateTime($start);
        }

        if ($end) {
            $end = new DrupalDateTime($end);
        }

        foreach ($this->notificationArray as $notification) {
            $createdDate = DrupalDateTime::createFromTimestamp($notification->get('created')->value);

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
