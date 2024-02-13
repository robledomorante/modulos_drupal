<?php

namespace Drupal\curso_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Messenger\MessengerInterface; // clase para la gestión de los mensajes de advertencia, warning, etc
use Drupal\node\NodeInterface;
use Drupal\node\Entity\node;
use Drupal\curso_module\Services\Repetir; // Servicio - clase personalizada
use Drupal\curso_module\Services\ServiceWeb; // Servicio web json - clase personalizada
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException; // Clase de gestión de excepciones
use Drupal\user\UserInterface; // clase para la gestión del usuario
use Drupal\Core\Datetime\DateFormatterInterface; // clase para dar formato a las fechas
// Clases para enlaces internos
use Drupal\Core\Url;
use Drupal\Core\Link;
use GuzzleHttp\RetryMiddleware;

/**
 * Returns responses for curso module routes.
 */
class CursoModuleController extends ControllerBase
{

  /**  */
  protected $messenger;

  protected $repetir;

  protected $serviceweb;

  protected $dateFormatter;


  /**
   * Builds the response.
   */
  public function __construct(
    MessengerInterface $messenger,
    Repetir $repetir,
    ServiceWeb $serviceweb,
    DateFormatterInterface $date_formatter
  ) {
    $this->messenger = $messenger;
    $this->repetir = $repetir;
    $this->serviceweb = $serviceweb;
    $this->dateFormatter = $date_formatter;
  }

  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('messenger'),
      $container->get('curso_module.repetir'),
      $container->get('curso_module.serviceweb'),
      $container->get('date.formatter'),

    );
  }

  /**
   * Pasar un nodo como parámetro
   */
  public function pasarnode(NodeInterface $node)
  {
    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('The title of node is  ' . $node->getTitle()),

    ];
    return $build;
  }

  /** API DE LAS RECETAS: CATEGORIAS */
  public function categorias()
  {

    $build = [
      '#theme' => 'categoria_recetas',
      '#attached' => [
        'library' => [
          'curso_module/curso_recetas.css',

        ],
      ],
      '#categorias' => [],
    ];

    $resultado = [];

    $direccion = "https://www.themealdb.com/api/json/v1/1/categories.php";

    $resultado = $this->serviceweb->posts($direccion);
    $categorias = $resultado["categories"];

    $url = Url::fromUri('base:/curso/recetas/1');
    $string = $url->toString();
    //$link = Link::fromTextAndUrl($this->t('Ver Categoría'), $url);
    $link = $string;

    $build['#link'] = $link;

    foreach ($categorias as $categoria) {
      //$data = self::idpost($post['url']);
      $url = Url::fromUri('base:/curso/recetas/' . $categoria['strCategory']);
      $urlReceta = $url->toString();
      $build['#categorias'][] = [
        'id' => $categoria['idCategory'],
        'categoria' => $categoria['strCategory'],
        'imgCategoria' => $categoria['strCategoryThumb'],
        'urlReceta' => $urlReceta,

      ];
    }

    //dpm($build);

    return $build;
  }

  /** API DE LAS RECETAS: RECETAS */
  public function comidas($categoria)
  {


    $build = [
      '#theme' => 'comidas_recetas',
      '#attached' => [
        'library' => [
          'curso_module/curso_recetas.css',

        ],
      ],
      '#comidas' => [],
      '#categoria' => '',
    ];

    $resultado = [];

    $direccion = "www.themealdb.com/api/json/v1/1/filter.php?c=" . $categoria;

    $resultado = $this->serviceweb->posts($direccion);

    //dpm($resultado);

    if ($resultado != FALSE) {
      $comidas = $resultado["meals"];

      $build['#categoria'] = $categoria;

      foreach ($comidas as $comida) {
        //$data = self::idpost($post['url']);
        $url = Url::fromUri('base:/curso/comida/' . $comida['idMeal']);
        $urlReceta = $url->toString();
        $build['#comidas'][] = [
          'id' => $comida['idMeal'],
          'titulo' => mb_substr($comida['strMeal'], 0, 10, 'UTF-8'),
          'imgComida' => $comida['strMealThumb'],
          'urlReceta' => $urlReceta,

        ];
      }
    }else{
      $build['#error'] = 'Ha habido un error den la conexión o en la dirección introducida';
    }





    return $build;
  }

  public function receta($id)
  {
    $build = [
      '#theme' => 'receta',
      '#attached' => [
        'library' => [
          'curso_module/curso_recetas.css',

        ],
      ],
      '#receta' => [],
    ];

    $resultado = [];

    $direccion = "www.themealdb.com/api/json/v1/1/lookup.php?i=" . $id;

    // Ejemplo del json generdo
    // https://www.themealdb.com/api/json/v1/1/lookup.php?i=52959

    $resultado = $this->serviceweb->posts($direccion);

    $recetas = $resultado["meals"][0];



    $ingredientes = self::ingredientes($recetas);
    //$medidas = $this->serviceweb->ingredientes($recetas, 'strMeasure');

    $build['#receta'] = [
      'id' => $recetas['idMeal'],
      'titulo' => $recetas['strMeal'],
      'bebida' => $recetas['strDrinkAlternate'],
      'categoria' => $recetas['strCategory'],
      'area' => $recetas['strArea'],
      'instrucciones' => $recetas['strInstructions'],
      'imgComida' => $recetas['strMealThumb'],
      'tags' => $recetas['strTags'],
      'ingredientes' => $ingredientes,

    ];



    //dpm($build);

    return $build;
  }

  /** API DE LOS POKEMON */
  public function pokemon()
  {

    // Plantilla y resultados. Adjuntamos una hoja de css
    $build = [
      '#theme' => 'pokemon',
      '#attached' => [
        'library' => [
          'curso_module/curso_module.css',

        ],
      ],
      '#posts' => [],
    ];



    // Recogemos en un array todo el API de pokemon
    $resultado = [];

    $direccion = "https://pokeapi.co/api/v2/pokemon";

    $resultado = $this->serviceweb->posts($direccion);

    $posts = $resultado["results"];

    foreach ($posts as $post) {
      $data = self::idpost($post['url']);
      $build['#posts'][] = [
        'name' => $post['name'],
        'url' => $post['url'],
        'id' => $data['id'],
        'picture' => $data['picture'],
      ];
    }

    // añadimos el posts a la renderización de la plantilla

    // $build["#posts"] = $posts;

    //dpm($build);

    return $build;
  }

  public function pagparam($pagina)
  {
    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('Ruta con parametro cuya página es ' . $pagina),

    ];
    return $build;
  }

  public function obtenernode($id)
  {
    // cargamos el nodo
    $nid = $id;
    $node = Node::load($nid);

    // Obtenemos el título del nodo
    $titulo = $node->getTitle();
    $etiqueta = $node->label();
    $tipo = $node->bundle();

    /** @var Repetir $repetir */
    //$repetir = \Drupal::service('curso_module.repetir');

    $resultado = $this->repetir->repetir('curso ', 5);

    $descripcion = $this->t('El nodo con el id %id es el nodo con el título %titulo', ['%id' => $id, '%titulo' => $titulo]);

    // $build['content'] = [
    //   '#type' => 'item',
    //   '#markup' => $this->t(
    //     'El nodo con el id %id es el nodo con el título %titulo',
    //     ['%id' => $id, '%titulo' => $titulo ]
    //   ),
    // ];
    // return $build;
    return [
      '#theme' => 'curso_plantilla',
      '#etiqueta' => ['etiqueta' => $etiqueta, 'titulo' => $titulo],
      '#descripcion' => $descripcion,
      '#tipo' => $tipo,
      '#servicio' => $resultado,
    ];
  }

  public function home()
  {
    /**
     * Ejemplos de mensaje. El interface de mensaje está inyectado.
     */
    $this->messenger->addMessage($this->t('With this message, I have inserted a message through the interface injections.'));
    $this->messenger->addWarning($this->t('Danger message other than the first'));

    /**
     * Nos devuelve un mensaje renderizado
     * podemos hacerlo con un:
     *    - markup
     *    - plain_text
     * Ejemplos de renderizado: 
     * https://github.com/drmfraterni/D9BD/blob/master/modulos/forcontu_theming/src/Controller/ForcontuThemingController.php
     */

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('We are entering the drupal!'),

    ];

    return $build;
  }

  public function calculator($num1, $num2)
  {
    $this->messenger->addMessage($this->t('We are performing all the 
    operations on two numbers, the first is @num1 and the second is @num2', ['@num1' => $num1, '@num2' => $num2]));

    $suma = $num1 + $num2;
    $multiplica = $num1 * $num2;
    $resta = $num1 > $num2 ? ($num1 - $num2) : ($num2 - $num1);

    return [
      '#theme' => 'curso_calculator',
      '#operadores' => [
        'num1' => $num1,
        'num2' => $num2,
      ],
      '#operacion' => [
        'suma' => $suma,
        'multiplica' => $multiplica,
        'resta' => $resta
      ],

    ];
  }

  public function calculadora($num1, $num2)
  {
    //a) comprobamos que los valores facilitados sean numéricos
    //y si no es así, lanzamos una excepción
    if (!is_numeric($num1) || !is_numeric($num2)) {
      throw new BadRequestHttpException($this->t('No numeric arguments specified.'));
    }

    //b) Los resultados se mostrarán en formato lista HTML (ul).
    //Cada elemento de la lista se añade a un array
    $list[] = $this->t(
      "@num1 + @num2 = @sum",
      [
        '@num1' => $num1,
        '@num2' => $num2,
        '@sum' => $num1 + $num2
      ]
    );
    $list[] = $this->t(
      "@num1 - @num2 = @difference",
      [
        '@num1' => $num1,
        '@num2' => $num2,
        '@difference' => $num1 - $num2
      ]
    );
    $list[] = $this->t(
      "@num1 x @num2 = @product",
      [
        '@num1' => $num1,
        '@num2' => $num2,
        '@product' => $num1 * $num2
      ]
    );
    //c) Evitar error de división por cero
    if ($num2 != 0)
      $list[] = $this->t(
        "@num1 / @num2 = @division",
        [
          '@num1' => $num1,
          '@num2' => $num2,
          '@division' => $num1 / $num2
        ]
      );
    else
      $list[] = $this->t(
        "@num1 / @num2 = undefined (division by zero)",
        array('@num1' => $num1, '@num2' => $num2)
      );

    //d) Se transforma el array $list en una lista HTML (ul)
    $output['forcontu_pages_calculator'] = [
      '#theme' => 'item_list',
      '#items' => $list,
      '#title' => $this->t('Operations:'),
    ];

    //e) Se devuelve el array renderizable con la salida.
    return $output;
  }

  public function user(UserInterface $user)
  {
    $list[] = $this->t("Username: @username", ['@username' => $user->getAccountName()]);
    $list[] = $this->t(
      "Email: @email",
      ['@email' => $user->getEmail()]
    );
    $list[] = $this->t(
      "Roles: @roles",
      ['@roles' => implode(', ', $user->getRoles())]
    );
    $list[] = $this->t("Last accessed time: @lastaccess", array('@lastaccess' =>
    $this->dateFormatter->format($user->getLastAccessedTime(), 'short')));
    $output['forcontu_pages_user'] = [
      '#theme' => 'item_list',
      '#items' => $list,
      '#title' => $this->t('User data:'),
    ];
    return $output;
  }

  public function links()
  {
    //link to /admin/structure/blocks
    $url1 = Url::fromRoute('block.admin_display');
    $link1 = Link::fromTextAndUrl($this->t('Go to the Block administration page'), $url1);
    $list[] = $link1;
    // Enlace contenido dentro de un texto.
    // método toString(). para convertir el enlace en una cadena
    $list[] = $this->t(
      'This text contains a link to %enlace. Just convert it to String to use it into a text.',
      ['%enlace' => $link1->toString()]
    );
    // Enlace a la página de inicio del sitio (<front>).
    $url3 = Url::fromRoute('<front>');
    $link3 = Link::fromTextAndUrl($this->t('Go to Front page'), $url3);
    $list[] = $link3;
    /**
     * Enlace a un Nodo
     * 'entity.node.canonical': Se corresponde con la página principal del nodo, /node/{node}
     * 'entity.node.edit_form': Se corresponde con el formulario de edición del nodo, /node/{node}/edit
     * 'entity.node.delete_form': Se corresponde con la página de eliminación del nodo, /node/{node}/delete
     */
    $url4 = Url::fromRoute('entity.node.canonical', ['node' => 1]);
    $link4 = Link::fromTextAndUrl($this->t('Link to node/1'), $url4);
    $list[] = $link4;

    // Enlace a una edición de nodo
    $url5 = Url::fromRoute('entity.node.edit_form', ['node' => 1]);
    $link5 = Link::fromTextAndUrl($this->t('Link to edit node/1'), $url5);
    $list[] = $link5;

    // enlace a una URL externa 
    $url6 = Url::fromUri('https://www.forcontu.com');
    $link6 = Link::fromTextAndUrl($this->t('Link to www.forcontu.com'), $url6);
    $list[] = $link6;

    // Enlace a una URL interna no registrada

    $url7 = Url::fromUri('internal:/core/themes/bartik/css/layout.css');
    $link7 = Link::fromTextAndUrl($this->t('Link to layout.css'), $url7);
    $list[] = $link7;

    // Añadir atributos a un enlace externo
    $url8 = Url::fromUri('https://www.drupal.org');
    $link_options = [
      'attributes' => [
        'class' => [
          'external-link',
          'list'
        ],
        'target' => '_blank',
        'title' => 'Go to drupal.org',
      ],
    ];
    $url8->setOptions($link_options);
    $link8 = Link::fromTextAndUrl($this->t('Link to drupal.org'), $url8);
    $list[] = $link8;


    $output['forcontu_pages_links'] = [
      '#theme' => 'item_list',
      '#items' => $list,
      '#title' => $this->t('Examples of links:'),
    ];
    return $output;
  }

  protected function idpost($url)
  {
    // dpm($url);
    $idpokemon = explode('/', $url);
    $id = intval($idpokemon[6]);
    $picture = 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/dream-world/' . $id . '.svg';

    $data = [];
    $data['id'] = $id;
    $data['picture'] = $picture;
    // dpm($data);
    return $data;
  }

  protected function ingredientes($receta)
    {
        $componentes = [];


        for ($i = 1; $i < 20; $i++) {
            if ($receta["strIngredient" . $i] !== "") {
                $componentes[] = $receta["strIngredient" . $i] . " - " . $receta["strMeasure" . $i];
            }
        }


        return $componentes;
    }
}
