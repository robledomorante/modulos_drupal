<?php

namespace Drupal\curso_module\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a blockajaxnode block.
 *
 * @Block(
 *   id = "curso_module_blockajaxnode",
 *   admin_label = @Translation("BlockAjaxNode"),
 *   category = @Translation("Curso")
 * )
 */
class BlockajaxnodeBlock extends BlockBase
{

  /**
   * {@inheritdoc}
   */
  public function build()
  {

    $form = \Drupal::formBuilder()->getForm('\Drupal\curso_module\Form\NodeAjaxForm');
    return $form;
  }
}
