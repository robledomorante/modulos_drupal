<?php

namespace Drupal\indices_upload\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\curso_module\Services\ServiceWeb; // Servicio web json - clase personalizada
use Drupal\Core\Messenger\MessengerInterface; // clase para la gestión de los mensajes de advertencia, warning, etc
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Returns responses for Indices Upload routes.
 */
class IndicesUploadController extends ControllerBase
{

  protected $messenger;

  protected $serviceweb;

  /**
   * The date formatter service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  public function __construct(MessengerInterface $messenger, ServiceWeb $serviceweb, DateFormatterInterface $date_formatter)
  {
    $this->messenger = $messenger;
    $this->serviceweb = $serviceweb;
    $this->dateFormatter = $date_formatter;
  }

  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('messenger'),
      $container->get('curso_module.serviceweb'),
      $container->get('date.formatter'),
    );
  }

  /**
   * Builds the response.
   */
  public function actualizacion()
  {

    // ruta para guardar los archivos
    $config = $this->config('indices_upload.settings');
    

    $resultado = [];

    $documentos = [];

    // dirección de provincia ruta: https://datos.gob.es/apidata/nti/territory/Province.json
    $dProvincia = $config->get('province');
    $rProvincia = $this->serviceweb->posts($dProvincia);

    /**
     * Todos los datos de la api
     */

    $datosProvincia = $rProvincia['result']['items'];
    $documentos['provincia'] = json_encode($datosProvincia);

    // CREAR UN ARCHIVO JSON    
    $nuevojson = self::fechaCreacionIndice();
    $ruta =$config->get('ruta').$nuevojson;    

    $mensaje = self::leerArchivo($documentos['provincia'] , $ruta);
    // MENSAJE PARA LA CREACIÓN DE JSON
    $this->messenger()->addMessage($mensaje, 'custom');

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t($mensaje),
    ];
    
    

    return $build;
  }

  public static function leerArchivo ($documento, $ruta) {    

    $fh = fopen($ruta, "w") or die('An error occurred opening the file');
    fwrite($fh, $documento) or die('Could not write to file');
    fclose($fh);
    return 'escrito sin problemas';
  }

  public static function fechaCreacionIndice () {

    $now = new DrupalDateTime('now');
    $now = $now->getTimestamp();
    $now = date('Ymd_H-i-s', $now);

    $nuevojson = "indice_$now.json";

    return $nuevojson;

  }
}
