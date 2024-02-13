<?php

namespace Drupal\api_rest_block_bdns_content\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for api_rest_block_bdns routes.
 */
class ApiRestBlockBdnsContentController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works Controller Content Block!'),
    ];

    return $build;
  }

}
