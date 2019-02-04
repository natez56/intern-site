<?php

namespace Drupal\internsd8\Plugin\Block;

use Drupal\Core\Block\BlockBase;

class RecentPostBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return array(
      '#markup' => $this->t('Hello, World!'),
    );
  }

}