<?php

namespace Drupal\api_rest_block_bdns_content\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use GuzzleHttp\Exception\RequestException;
use Drupal\api_rest_block_bdns_content\Services\getContentUrl;


/**
 * Provides an example block.
 *
 * @Block(
 *   id = "api_rest_block_bdns_content",
 *   admin_label = @Translation("Content Block"),
 *   category = @Translation("api_rest_block_bdns")
 * )
 */
class ContentBlock extends BlockBase implements ContainerFactoryPluginInterface {

  protected $getcontenturl;

  public function __construct(array $configuration,
    $plugin_id,
    $plugin_definition,
    getContentUrl $getcontenturl) {
      parent::__construct($configuration, $plugin_id, $plugin_definition);
      $this->getcontenturl = $getcontenturl;
  }

 
  public static function create(ContainerInterface $container,
      array $configuration,
      $plugin_id,
      $plugin_definition)
  {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('api_rest_block_bdns_content.getcontenturl'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

   // $urlbase = 'http://www.infosubvenciones.es/bdnstrans/GE/es/api/v2.1/listadoconvocatoria?';
  //  $administracion = 'C23';

   // $direccion = $urlbase.'&administracion='.$administracion;

   // $resultado = $this->getcontenturl->getContent($direccion);
   // dpm($resultado);

    $build['content'] = [
      '#prefix' => '<div id="ajax-content">',
      '#markup' => $this->t('It works Content dkdkdkBlock!'),
      '#suffix' => '</div>'
    ];
    return $build;
  }

}
