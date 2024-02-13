<?php

namespace  Drupal\api_rest_block_bdns_filter\Form;

use Drupal\views\Views;
use Drupal\Core\Form\FormBase;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Ajax\CssCommand;
use Drupal\page_manager\Entity\Page;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\iepnb_menu_ajax\Controller\ControllerRest;
use Drupal\iepnb_menu_ajax\Controller\ViewController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\iepnb_menu_ajax\Controller\ControllerCodification;
use Drupal\api_rest_block_bdns_content\Services\getContentUrl;
use Drupal\Core\Cache\CacheBackendInterface;

use Drupal\iepnb_graphs\Controller\ControllerRest as ControllerRestChart;

/**
 *
 */
class MenuFormSpace extends FormBase
{

  /**
   * @var \Drupal\Core\Language\LanguageManager
   */
  protected $entityTypeManager;

  protected $languageManager;

  protected $getcontenturl;

  protected $cache;

  protected $orden = [];

  /**
   *
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    LanguageManagerInterface $language_manager,
    getContentUrl $getcontenturl,
    CacheBackendInterface $cache
  ) {
    $this->entityTypeManager = $entity_type_manager;
    $this->languageManager = $language_manager;
    $this->getcontenturl = $getcontenturl;
    $this->cache = $cache;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    // Instantiates this form class.
    return new static(
      // Load the service required to construct this class.
      $container->get('entity_type.manager'),
      $container->get('language_manager'),
      $container->get('api_rest_block_bdns_content.getcontenturl'),
      $container->get('cache.bdns')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'filter_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {




    $form['tituloconv'] = [
      '#type' => 'textfield',
      '#title' => t('Título convocatoría'),
      '#attributes' => [
        'placeholder' => 'Escriba el título de la convocatoria',
      ],
      '#ajax' => [
        'callback' => '::validateCallback',
        'effect' => 'fade',
        'event' => 'change',
        'progress' => [
          'type' => 'throbber',
          'message' => NULL,
        ]
      ],
    ];
    $form['mrr'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Mecanismo de Recuperación y Resiliencia'),
      '#default_value' => 1,
      '#ajax' => [
        'callback' => '::validateCallback',
        'effect' => 'fade',
        'event' => 'change',
        'progress' => [
          'type' => 'throbber',
          'message' => NULL,
        ]
      ],
    ];
    $form['fechas'] = [
      '#type' => 'details',
      '#title' => $this->t('Fecha de registro'),
      '#description' => $this->t('Fecha de registro'),
    ];
    $form['fechas']['fecha-desde'] = [
      '#type' => 'date',
      '#title' => $this->t('Desde'),
      '#date_part_order' => ['day', 'month', 'year'],
      '#ajax' => [
        'callback' => '::validateCallback',
        'effect' => 'fade',
        'event' => 'change',
        'progress' => [
          'type' => 'throbber',
          'message' => NULL,
        ]
      ],
    ];
    $form['fechas']['fecha-hasta'] = [
      '#type' => 'date',
      '#title' => $this->t('Hasta'),
      '#ajax' => [
        'callback' => '::validateCallback',
        'effect' => 'fade',
        'event' => 'change',
        'progress' => [
          'type' => 'throbber',
          'message' => NULL,
        ]
      ],
    ];
    $form['administracion'] = [
      '#type' => 'details',
      '#title' => $this->t('Administración'),
      '#description' => $this->t('Elige una administración.'),
    ];
    $form['administracion']['admin'] = [
      '#type' => 'radios',
      '#title' => $this->t('Ámbito de la administración'),
      '#options' => [
        'estado' => $this->t('ESTADO'),
        'comunidad' => $this->t('COMUNIDADES AUTÓNOMAS'),
      ],
      '#ajax' => [
        'callback' => '::elegirAdministracion',
        'effect' => 'fade',
        'event' => 'change',
        'progress' => [
          'type' => 'throbber',
          'message' => NULL,
        ]
      ],

    ];
    $form['aestado'] = [
      '#type' => 'details',
      '#title' => $this->t('A del Estado'),
      '#attributes' => [
        'id' => 'aestado',
      ],
      '#description' => $this->t('Admnistración del Estado.'),
    ];
    $form['aestado']['aestadolist'] = [
      '#type' => 'radios',
      '#title' => $this->t('A. de Estado'),
      '#options' => [
        'C23' => $this->t('MINISTERIO DE AGRICULTURA, PESCA Y ALIMENTACIÓN'),
        'C27' => $this->t('MINISTERIO DE ASUNTOS ECONÓMICOS Y TRANSFORMACIÓN DIGITAL'),
        'C12' => $this->t('MINISTERIO DE ASUNTOS EXTERIORES, UNIÓN EUROPEA Y COOPERACIÓN'),
        'C31' => $this->t('MINISTERIO DE CIENCIA E INNOVACIÓN'),
        'C33' => $this->t('MINISTERIO DE CONSUMO'),
        'C29' => $this->t('MINISTERIO DE CULTURA Y DEPORTE'),
        'C14' => $this->t('MINISTERIO DE DEFENSA'),
        'C34' => $this->t('MINISTERIO DE DERECHOS SOCIALES Y AGENDA 2030'),
        'C18' => $this->t('MINISTERIO DE EDUCACIÓN Y FORMACIÓN PROFESIONAL'),
        'C15' => $this->t('MINISTERIO DE HACIENDA Y FUNCIÓN PÚBLICA'),
        'C22' => $this->t('MINISTERIO DE IGUALDAD'),
        'C21' => $this->t('MINISTERIO DE INCLUSIÓN, SEGURIDAD SOCIAL Y MIGRACIONES'),
        'C20' => $this->t('MINISTERIO DE INDUSTRIA, COMERCIO Y TURISMO'),
        'C13' => $this->t('MINISTERIO DE JUSTICIA'),
        'C25' => $this->t('MINISTERIO DE LA PRESIDENCIA, RELACIONES CON LAS CORTES Y MEMORIA DEMOCRÁTICA'),
        'C28' => $this->t('MINISTERIO DE POLÍTICA TERRITORIAL'),
        'C26' => $this->t('MINISTERIO DE SANIDAD'),
        'C19' => $this->t('MINISTERIO DE TRABAJO Y ECONOMÍA SOCIAL'),
        'C17' => $this->t('MINISTERIO DE TRANSPORTES, MOVILIDAD Y AGENDA URBANA'),
        'C32' => $this->t('MINISTERIO DE UNIVERSIDADES'),
        'C16' => $this->t('MINISTERIO DEL INTERIOR'),
        'C30' => $this->t('MINISTERIO PARA LA TRANSICIÓN ECOLÓGICA Y EL RETO DEMOGRÁFICO'),
      ],
      '#ajax' => [
        'callback' => '::validateCallback',
        'effect' => 'fade',
        'event' => 'change',
        'progress' => [
          'type' => 'throbber',
          'message' => NULL,
        ]
      ],
    ];
    $form['cautonomas'] = [
      '#type' => 'details',
      '#title' => $this->t('CC Autonomas'),
      '#attributes' => [
        'id' => 'cautonomas',
      ],
      '#description' => $this->t('Comunidades Autónomas'),
    ];
    $form['cautonomas']['cautonomaslist'] = [
      '#type' => 'radios',
      '#title' => $this->t('CC Autonomas'),
      '#options' => [
        'A01' => $this->t('ANDALUCÍA'),
        'A02' => $this->t('ARAGÓN'),
        'A05' => $this->t('CANARIAS'),
        'A06' => $this->t('CANTABRIA'),
        'A07' => $this->t('CASTILLA Y LEÓN'),
        'A08' => $this->t('CASTILLA-LA MANCHA'),
        'A09' => $this->t('CATALUÑA'),
        'A18' => $this->t('CIUDAD AUTÓNOMA DE CEUTA'),
        'A19' => $this->t('CIUDAD AUTÓNOMA DE MELILLA'),
        'A13' => $this->t('COMUNIDAD DE MADRID'),
        'A15' => $this->t('COMUNIDAD FORAL DE NAVARRA'),
        'A10' => $this->t('COMUNITAT VALENCIANA'),
        'A11' => $this->t('EXTREMADURA'),
        'A12' => $this->t('GALICIA'),
        'A04' => $this->t('ILLES BALEARS'),
        'A17' => $this->t('LA RIOJA'),
        'A03' => $this->t('PRINCIPADO DE ASTURIAS'),
        'A14' => $this->t('REGIÓN DE MURCIA'),
      ],
      '#ajax' => [
        'callback' => '::validateCallback',
        'effect' => 'fade',
        'event' => 'change',
        'progress' => [
          'type' => 'throbber',
          'message' => NULL,
        ]
      ],
    ];
    $form['iayuda'] = [
      '#type' => 'details',
      '#title' => $this->t('Intrumentos de Ayuda'),
      '#description' => $this->t('Intrumentos de Ayuda'),
    ];
    $form['iayuda']['iayudalist'] = [
      '#type' => 'radios',
      '#title' => $this->t('I Ayudas'),
      '#options' => [
        'APORTACIÓN DE FINANCIACIÓN RIESGO' => $this->t('APORTACIÓN DE FINANCIACIÓN RIESGO'),
        'GARANTÍA' => $this->t('GARANTÍA'),
        'OTROS INSTRUMENTOS DE AYUDA' => $this->t('OTROS INSTRUMENTOS DE AYUDA'),
        'PRÉSTAMOS' => $this->t('PRÉSTAMOS'),
        'SUBVENCIÓN y ENTREGA DINERARIA SIN CONTRAPRESTACIÓN ' => $this->t('SUBVENCIÓN y ENTREGA DINERARIA SIN CONTRAPRESTACIÓN'),
        'VENTAJA FISCAL' => $this->t('VENTAJA FISCAL'),
      ],
      '#ajax' => [
        'callback' => '::validateCallback',
        'effect' => 'fade',
        'event' => 'change',
        'progress' => [
          'type' => 'throbber',
          'message' => NULL,
        ]
      ],
    ];
    $form['finalidad'] = [
      '#type' => 'details',
      '#title' => $this->t('Finalidad (política de gasto)'),
      '#description' => $this->t('Finalidad (política de gasto)'),
    ];
    $form['finalidad']['finalidadlist'] = [
      '#type' => 'radios',
      '#title' => $this->t('Finalidad'),
      '#options' => [
        'ACCESO A LA VIVIENDA Y FOMENTO DE LA EDIFICACIÓN' => $this->t('ACCESO A LA VIVIENDA Y FOMENTO DE LA EDIFICACIÓN'),
        'AGRICULTURA, PESCA Y ALIMENTACIÓN' => $this->t('AGRICULTURA, PESCA Y ALIMENTACIÓN'),
        'COMERCIO, TURISMO Y PYMES' => $this->t('COMERCIO, TURISMO Y PYMES'),
        'OOPERACIÓN INTERNACIONAL PARA EL DESARROLLO Y CULTURAL' => $this->t('COOPERACIÓN INTERNACIONAL PARA EL DESARROLLO Y CULTURAL'),
        'CULTURA' => $this->t('CULTURA'),
        'DEFENSA' => $this->t('DEFENSA'),
        'DESEMPLEO' => $this->t('DESEMPLEO'),
        'EDUCACIÓN' => $this->t('EDUCACIÓN'),
        'FOMENTO DEL EMPLEO' => $this->t('FOMENTO DEL EMPLEO'),
        'INDUSTRIA Y ENERGÍA' => $this->t('INDUSTRIA Y ENERGÍA'),
        'INFRAESTRUCTURAS' => $this->t('INFRAESTRUCTURAS'),
        'INVESTIGACIÓN, DESARROLLO E INNOVACIÓN' => $this->t('INVESTIGACIÓN, DESARROLLO E INNOVACIÓN'),
        'JUSTICIA' => $this->t('JUSTICIA'),
        'OTRAS ACTUACIONES DE CARÁCTER ECONÓMICO' => $this->t('OTRAS ACTUACIONES DE CARÁCTER ECONÓMICO'),
        'OTRAS PRESTACIONES ECONÓMICAS' => $this->t('OTRAS PRESTACIONES ECONÓMICAS'),
        'SANIDAD' => $this->t('SANIDAD'),
        'SEGURIDAD CIUDADANA E INSTITUCIONES PENITENCIARIAS' => $this->t('SEGURIDAD CIUDADANA E INSTITUCIONES PENITENCIARIAS'),
        'SERVICIOS SOCIALES Y PROMOCIÓN SOCIAL' => $this->t('SERVICIOS SOCIALES Y PROMOCIÓN SOCIAL'),
        'SUBVENCIONES AL TRANSPORTE' => $this->t('SUBVENCIONES AL TRANSPORTE'),
      ],
      '#ajax' => [
        'callback' => '::validateCallback',
        'effect' => 'fade',
        'event' => 'change',
        'progress' => [
          'type' => 'throbber',
          'message' => NULL,
        ]
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {

    return TRUE;
  }

  /**
   *
   */

  public function elegirAdministracion(array &$form, FormStateInterface $form_state)
  {
    //$element = $form_state->getTriggeringElement();
    $values = $form_state->getValues();
    $eligeAdmin = $values['admin'];

    //dpm($eligeAdmin);

    $ajax_response = new AjaxResponse();

    if ($eligeAdmin == 'estado') {
      //dpm('estamos dentro');
      $ajax_response->addCommand(new InvokeCommand('#cautonomas', 'hide'));
      $ajax_response->addCommand(new InvokeCommand('#aestado', 'show'));
    } else {
      $ajax_response->addCommand(new InvokeCommand('#aestado', 'hide'));
      $ajax_response->addCommand(new InvokeCommand('#cautonomas', 'show'));
    };




    return $ajax_response;
  }


  public function validateCallback(array &$form, FormStateInterface $form_state)
  {
    $ajax_response = new AjaxResponse();
    $pintres = $this->getcontenturl->contenidoGeneral($form, $form_state, $this->orden, $this->cache);
    $ajax_response->addCommand(new ReplaceCommand('#ajax-content', $pintres));
    return $ajax_response;
    
  }



  

  
}
