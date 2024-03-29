<?php

namespace Drupal\formularios_pruebas\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Formularios pruebas routes.
 */
class FormulariosPruebasController extends ControllerBase {

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
