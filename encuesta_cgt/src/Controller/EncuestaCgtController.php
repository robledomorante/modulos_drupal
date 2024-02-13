<?php

namespace Drupal\encuesta_cgt\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Encuesta cgt routes.
 */
class EncuestaCgtController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    return $build;
  }

}
