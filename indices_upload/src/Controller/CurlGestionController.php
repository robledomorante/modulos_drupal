<?php

namespace Drupal\indices_upload\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\curso_module\Services\ServiceCurlWeb; // Servicio - clase personalizada
use Drupal\Core\Messenger\MessengerInterface;

/**
 * Returns responses for Indices Upload routes.
 */
class CurlGestionController extends ControllerBase
{

  /**
   * Guzzle\Client instance.
   *
   * @var \GuzzleHttp\ClientInterface
   */

  protected $servicecurl;

  protected $messenger;

  /**
   * {@inheritdoc}
   */
  public function __construct(ServiceCurlWeb $servicecurlweb, MessengerInterface $messenger)
  {
    $this->servicecurl = $servicecurlweb;
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('curso_module.servicecurlweb'),
      $container->get('messenger'),
    );
  }

  /**
   * Builds the response.
   */
  public function descargaDocumentos()
  {

    
    //$direccion =  $this->config('indices_upload.settings')->get('province');
    $direccion = 'https://des.iepnb.es/pgrest/v_normativa_solr';
    $rutaDescarga = $this->config('indices_upload.settings')->get('ruta');
    $nombre = "v_normativa";

    $descarga = $this->servicecurl->descargaDocCurl($direccion, $rutaDescarga, $nombre);

    $list[] = $this->t("Direccion a descargar: {$direccion}");
    $list[] = $this->t("Ruta donde se descarga: {$rutaDescarga}");
    $list[] = $this->t("Nombre del archivo: {$nombre}");
    $list[] = $this->t("Código de descarga: {$direccion}");
    
    $output['indices_upload_descarga'] = [
      '#theme' => 'item_list',
      '#items' => $list,
      '#title' => $this->t('Descarga del Archivo: '.$nombre),
    ];

  
    return $output;
  }
}
