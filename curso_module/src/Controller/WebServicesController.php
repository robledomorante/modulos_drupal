<?php

namespace Drupal\curso_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\ClientInterface;
use Drupal\curso_module\Services\ServiceCurlWeb; // Servicio - clase personalizada
use Drupal\Core\Messenger\MessengerInterface;






/**
 * Returns responses for curso module routes.
 */
class WebServicesController extends ControllerBase
{

  /**
   * Guzzle\Client instance.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  protected $servicecurl;

  protected $messenger;

  /**
   * {@inheritdoc}
   */
  public function __construct(ClientInterface $http_client, ServiceCurlWeb $servicecurlweb, MessengerInterface $messenger)
  {
    $this->httpClient = $http_client;
    $this->servicecurl = $servicecurlweb;
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('http_client'),
      $container->get('curso_module.servicecurlweb'),
      $container->get('messenger'),
    );
  }

  /**
   * Posts route callback.
   *
   * @param int $limit
   *   The total number of posts we want to fetch.
   * @param string $sort
   *   The sorting order.
   *
   * @return array
   *   A render array used to show the Posts list.
   */
  public function posts()
  {
    $build = [
      '#theme' => 'curso_postslist',
      '#posts' => [],
    ];

    /*$request = $this->httpClient->request('GET', 'https://datosabiertos.ayto-arganda.es/dataset/1901a9d0-b250-4e9d-88a7-285aefe2c76b/resource/f7686945-2e03-4279-a51b-0128761f5064/download/eventos-deportivos.-abril-2023.json', [
      'limit' => $limit,
      'sort' => $sort,
    ]);*/

    // https://www.drupal.org/docs/contributed-modules/http-client-manager/introduction

    $request = $this->httpClient->request('GET', 'https://datosabiertos.ayto-arganda.es/dataset/1901a9d0-b250-4e9d-88a7-285aefe2c76b/resource/f7686945-2e03-4279-a51b-0128761f5064/download/eventos-deportivos.-abril-2023.json');

    //dpm($request);

    if ($request->getStatusCode() != 200) {
      $this->messenger->addWarning($this->t('Cannot connect to page chosen to load API data'));
      return $build;
    }

    $posts = json_decode($request->getBody()->getContents(), true);



    //dpm($posts[0]["TIPO"]);

    foreach ($posts as $post) {
      $build['#posts'][] = [
        'tipo' => $post['TIPO'],
        'fecha' => $post['FECHA'],
        'hora' => $post['HORA'],
        'instalacion' => $post['INSTALACIÓN'],
        'entidad' => $post['ENTIDAD'],
        'actividad' => $post['ACTIVIDAD'],
      ];
    }

    //dpm($build);


    return $build;
  }

  public function conexioncurl()
  {
    /*$url = "https://www.themealdb.com/api/json/v1/1/categories.php";

  


    $data = [
      'idCategory' => 'idCategory',
      'strCategory' => 'strCategory',
      'strCategoryThumb' => 'strCategoryThumb',
      'strCategoryDescription' => 'strCategoryDescription',
    ];
    
    $prueba = $this->servicecurl->servicecurl($url, $data);*/
    $url = "https://www.themealdb.com/api/json/v1/1/categories.php";

    $codigo = $this->servicecurl->guardarDocumentoCurl($url, "categorias");

    if ($codigo == 200) {
      $documento = 'El documento se ha descargado de forma correcta';
      $this->messenger()->addMessage('Descarga Correcta del documento', 'custom');
    }else{
      $documento = 'Se ha producido un error en la descarga con el código: '.$codigo;
      $this->messenger()->addError('Error en la descarga código: '.$codigo);
    }
    

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('<div id="content-bloque">'.$documento.'</div>'),
    ];

    
    return $build;

    //$this->servicecurl->servicecurl($url, $method, $api_key);
  }
}
