<?php

namespace Drupal\api_rest_block_bdns_content\Controller;

use Drupal\Core\Controller\ControllerBase;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\api_rest_block_bdns_content\Services\getContentUrl;

/**
 * Returns responses for api_rest_block_bdns routes.
 */
class ApiRestBlockBdnsContentController extends ControllerBase {

  protected $getcontenturl;

  public function __construct(getContentUrl $getcontenturl) {
    $this->getcontenturl = $getcontenturl;
  }

  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('api_rest_block_bdns_content.getcontenturl'),
    );
  }


  /**
   * Builds the response.
   */
  public function build() {

    //$test = $this->getContentUrl('https://www.infosubvenciones.es/bdnstrans/GE/es/api/v2.1/listadoconvocatoria?titulo-contiene=COVID&fecha-desde=12/10/2021&mrr=1');

    //dpm($test); 

    

    $build['content'] = [
      '#prefix' => '<div id="ajax-content">',
      '#markup' => $this->t('It works Content dkdkdkBlock!'),
      '#suffix' => '</div>'
    ];

    return $build;
  

//  $urlbase = 'http://www.infosubvenciones.es/bdnstrans/GE/es/api/v2.1/listadoconvocatoria?';
//  $administracion = [];
  
  }

  /*public function getContentUrl($urlbase, $administracion) {
    $client = \Drupal::httpClient();
    try {
      $request = $client->get($urlbase."&".$administracion);
      $body = $request->getBody();
      $response = $body->getContents();
    }
    catch (RequestException $e) {
      $response = $e;
    }
    return $response;
  }*/

}
