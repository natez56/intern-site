<?php

namespace Drupal\custom_notification\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Notification' Block.
 *
 * @Block(
 *   id = "notification_block",
 *   admin_label = @Translation("Notifications"),
 *   category = @Translation("Notification Block"),
 * )
 */
class NotificationBlock extends BlockBase implements ContainerFactoryPluginInterface
{
    /**
     * @var $config \Drupal\Core\Config\ConfigFactory
     */
    protected $config;
    protected $notificationManager;

    /** @var string Config settings */
    const SETTINGS = 'custom_notification.settings.yml';

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param array $configuration
     * @param string $plugin_id
     * @param mixed $plugin_definition
     *
     * @return static
     */
    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
    {
        return new static(
            $configuration,
            $plugin_id,
            $plugin_definition
            // $container->get('custom_notification.notification_manager')
        );
    }

    /**
     * @param array $configuration
     * @param string $plugin_id
     * @param mixed $plugin_definition
     * @param \Drupal\Core\Config\ConfigFactory $config
     * @param Drupal\custom_notification\Services\NotificationManager
     */
    public function __construct(array $configuration, $plugin_id, $plugin_definition)
    {
        parent::__construct($configuration, $plugin_id, $plugin_definition);
        $this->notificationManager = \Drupal::service('custom_notification.notification');
    }

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
        $entityType = 'node';
        $view_mode = 'teaser';

        if ($this->notificationManager->isNotificationSettingEnabled()) {
            $start = $this->notificationManager->getConfigStartDate();
            $end = $this->notificationManager->getConfigEndDate();
            $blockContentArray = $this->notificationManager->getRecentThreeNotifications($start, $end);
            $blockContentArray = array_reverse($blockContentArray);

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
