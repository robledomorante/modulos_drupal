<?php

namespace Drupal\curso_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\curso_module\Services\ServiceWeb; // Servicio web json - clase personalizada
use Drupal\Core\Messenger\MessengerInterface; // clase para la gestión de los mensajes de advertencia, warning, etc
use Drupal\Core\Url;
use Drupal\Core\Link;

/**
 * Returns responses for curso module routes.
 */
class ApiEstadoController extends ControllerBase
{

  protected $messenger;

  protected $serviceweb;

  public function __construct(MessengerInterface $messenger, ServiceWeb $serviceweb)
  {
    $this->messenger = $messenger;
    $this->serviceweb = $serviceweb;
  }

  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('messenger'),
      $container->get('curso_module.serviceweb'),
    );
  }


  /**
   * Builds the response.
   */
  public function provincias($page, $pageSize)
  {

    //dpm($page);
    //dpm($pageSize);

    $build = [];

    $resultado = [];

    $direccion = "https://datos.gob.es/apidata/nti/territory/Province?_pageSize=$pageSize&_page=$page&_sort=label";
    $cadenaPag = "https://datos.gob.es/apidata/nti/territory/Province?_pageSize=$pageSize&_page=$page";
    $ordenDireccion = "http://datos.gob.es/recurso/sector-publico/territorio/Autonomia/Galicia";
    $orden = explode("https://datos.gob.es/es/recurso/sector-publico/territorio/Autonomia/", $ordenDireccion);



    $resultado = $this->serviceweb->posts($direccion);

    /**
     * Todos los datos de la api
     */

    $datosGenerales = $resultado['result'];
    $paginacion = [];
    $provincias = $resultado['result']['items'];

    $prov = [];

    $cabeceras = ['_about', 'autonomia', 'label', 'sameAs'];

    $ver = self::verProvincias($provincias);


    foreach ($ver as $value) {
      $filas[] = [
        'data' => [
          self::constLink($value['_about'], 'Sobre ' . $value['label']),
          self::constLink($value['autonomia'], 'Sobre ' . self::verAutonomia($value['autonomia'])),
          $value['label'],
          //$value['pais'],
          self::constLink($value['sameAs'], 'Más información provincia de ' . $value['label']),
          //$value['type'],
        ]
      ];
    }

    $tabla['table'] = [
      '#type' => 'table',
      '#header' => $cabeceras,
      '#rows' => $filas,
    ];

    $build['table'] = $tabla;


    /**
     * Paginación de la API
     */


    $paginacion['itemsPerPage'] = $resultado['result']['itemsPerPage'];
    $paginacion['next'] = empty($resultado['result']['next']) ? Null : $resultado['result']['next'];
    //$paginacion['next'] = $resultado['result']['next'];
    $paginacion['page'] = $resultado['result']['page'];

    $paginacion['prev'] = empty($resultado['result']['prev']) ? Null : $resultado['result']['prev'];
    $paginacion['startIndex'] = $resultado['result']['startIndex'];
    $paginacion['type'] = $resultado['result']['type'];

    

    $paginaActual = $this->paginado($cadenaPag, '_pageSize=');
    $paginaPrev = $this->paginado($paginacion['prev'], '=');
    $paginaNext = $this->paginado($paginacion['next'], '=');

    /**
     * PAGINADOR CON LOS ENLACES
     */
    $list = [];

    $bs = Url::fromRoute('curso_module.apiestado');
    $base = $bs->toString();

    $url1 = Url::fromUri('base:'.$base.'/'.$paginaPrev );
    $urlp1 = Link::fromTextAndUrl('Prev', $url1);

    $url2 = Url::fromUri('base:'.$base.'/'.$paginaNext );
    $urlp2 = Link::fromTextAndUrl('Next', $url2);

    $list = [$urlp1, $urlp2];

    dpm($urlp1);
    dpm($urlp2);

    $build['links'] = [
      '#theme' => 'item_list',
      '#items' => $list,
      '#title' => $this->t('paginador:'),
    ];

    return $build;
  }

  public function verProvincias($provincias)
  {
    $ficha = [];
    $num = 0;
    foreach ($provincias as $value) {
      $ficha[$num]["_about"] = $value["_about"];
      $ficha[$num]["autonomia"] = $value["autonomia"];
      $ficha[$num]["label"] = $value["label"];
      $ficha[$num]["pais"] = $value["pais"];
      $ficha[$num]["sameAs"] = $value["sameAs"];
      $ficha[$num]["type"] = $value["type"];
      $num++;
    }

    return $ficha;
  }

  public function verAutonomia($direccion)
  {
    $autonomia = [];
    $autonomia = explode("http://datos.gob.es/recurso/sector-publico/territorio/Autonomia/", $direccion);
    return $autonomia[1];
  }

  public function constLink($direccion, $texto)
  {
    $url = Url::fromUri($direccion);
    $link = Link::fromTextAndUrl($this->t($texto), $url);

    return $link;
  }

  public function paginado($cadena, $caracter)
  {

    if ($cadena == '') {
      $num = 0;
      return $num;
    }
    $rest = strstr($cadena, $caracter);
    $num = substr($rest, 1, 1);
    
    return $num;
  }
}
