<?php

namespace Drupal\api_rest_block_bdns_filter\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for api_rest_block_bdns_filter routes.
 */
class ApiRestBlockBdnsControllerFilter extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works Controller Filter Block!'),
    ];

    return $build;
  }

}
