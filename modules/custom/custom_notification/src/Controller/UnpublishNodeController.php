<?php

namespace Drupal\custom_notification\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Node;
use Drupal\Core\Url;

/**
 * Defines HelloController class.
 */
class UnpublishNodeController extends ControllerBase
{

    /**
     * Display the markup.
     *
     * @return array
     *   Return markup array.
     */
    public function content()
    {
        $parameters = \Drupal::routeMatch()->getParameters();
        $val = $parameters->get('node');
        $url = Url::fromRoute('custom_notification.manager');
        $node = \Drupal\node\Entity\Node::load($val);
        $node->setPublished(false);
        $node->save();
        return $this->redirect($url->getRouteName());
    }

}
