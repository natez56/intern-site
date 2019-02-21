<?php

namespace Drupal\custom_notification\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Provides a 'Hello' Block.
 *
 * @Block(
 *   id = "notification_block",
 *   admin_label = @Translation("Notifications"),
 *   category = @Translation("Notification Block"),
 * )
 */
class NotificationBlock extends BlockBase
{

    /** @var string Config settings */
    const SETTINGS = 'custom_notification.settings.yml';

    /**
     * {@inheritdoc}
     */
    public function getCacheMaxAge()
    {
        return 0;
    }

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        // Get config settings from notification settings on site.
        $notificationIsEnabled = \Drupal::config(static::SETTINGS)->get('checkbox');

        if ($notificationIsEnabled) {
            // Get start time from user selected settings on site.
            $notificationStart = \Drupal::config(static::SETTINGS)->get('start');

            // Get end time from user selected settings on site.
            $notificationEnd = \Drupal::config(static::SETTINGS)->get('end');
            $nodeType = 'notification';

            // Set to query both published and unpublished
            $publishedStatus = [false, true];
            $entityType = 'node';
            $view_mode = 'teaser';

            $query = \Drupal::entityQuery($entityType);

            $query->condition('status', $publishedStatus, 'IN');
            $query->condition('type', $nodeType);

            $nodeIds = $query->execute();

            // If no notification content exists display this message.
            if (sizeof($nodeIds) < 1) {
                return [
                    '#type' => 'markup',
                    '#markup' => $this->t('You have no notifications at this time.'),
                ];
            }

            // Gets an array of notification objects.
            $nodeArray = (
                \Drupal::entityTypeManager()->getStorage($entityType)
                    ->loadMultiple($nodeIds)
            );

            // Check created date of nodes to ensure they are within dates
            // set by user on site under notification settings.
            $blockContentArray = [];
            foreach ($nodeArray as $node) {
                if ($node->isPublished()) {
                    $timeStamp = $node->get('created')->value;
                    $createdDate = DrupalDateTime::createFromTimestamp($timeStamp, 'UTC');

                    $startDate = new DrupalDateTime($notificationStart);
                    $endDate = new DrupalDateTime($notificationEnd);

                    if ($createdDate > $startDate && $createdDate < $endDate) {
                        array_push($blockContentArray, $node);
                    }
                }
            }

            rsort($blockContentArray);

            // First two elements of reversed sorted array correspond to
            // the two most recent notifications.
            if (sizeof($blockContentArray) > 2) {
                $blockContentArray = array_slice($blockContentArray, 0, 2);
            }

            $view_builder = \Drupal::entityTypeManager()->getViewBuilder($entityType);

            $build = $view_builder->viewMultiple($blockContentArray, $view_mode);

            return $build;
        } else {
            return [
                '#type' => 'markup',
                '#markup' => $this->t('Hello, World!'),
            ];
        }

    }

}
