<?php

namespace Drupal\curso_indicadores\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for curso_indicadores routes.
 */
class CursoIndicadoresController extends ControllerBase {

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
