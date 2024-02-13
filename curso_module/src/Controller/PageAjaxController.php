<?php

namespace Drupal\curso_module\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for curso module routes.
 */
class PageAjaxController extends ControllerBase
{

  /**
   * Builds the response.
   */
  public function build()
  {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('<div id="content-bloque">It works!</div>'),
    ];

    return $build;
  }
}
