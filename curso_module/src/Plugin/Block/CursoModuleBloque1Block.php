<?php

namespace Drupal\curso_module\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a curso_module_bloque_1 block.
 *
 * @Block(
 *   id = "curso_module_curso_module_bloque_1",
 *   admin_label = @Translation("curso_module_bloque_1"),
 *   category = @Translation("Curso")
 * )
 */
class CursoModuleBloque1Block extends BlockBase
{

  /**
   * {@inheritdoc}
   */
  public function build()
  {
    $form = \Drupal::formBuilder()->getForm('\Drupal\curso_module\Form\BlockAjaxForm');
    return $form;
  }
}
