<?php

namespace Drupal\curso_module\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Url;

/**
 * Provides a curso module form.
 */
class NodeAjaxForm extends FormBase
{

  protected $entityTypeManager;

  public function __construct(EntityTypeManagerInterface $entityTypeManager)
  {
    $this->entityTypeManager = $entityTypeManager;
  }

  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'curso_module_node_ajax';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {

    // cargamos todos los nodos del drupal
    $nodeStorage = $this->entityTypeManager->getStorage('node');
    // Filtarmos por nodos artículos y sólo vamos a listar 5
    $ids = $nodeStorage->getQuery()
      ->condition('status', 1)
      ->condition('type', 'article') // type = bundle id (machine name)
      ->sort('created', 'DES') // sorted by time of creation
      ->pager(5) // limit 15 items
      ->execute();

    $articles = $nodeStorage->loadMultiple($ids);
    $article = [];

    foreach ($articles as $key => $art) {
      $article[$art->nid->value] = $art->title->value;
    }

    //dpm($article);


    $form['all_article'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('last articles published'),
      '#options' => $article,
      '#ajax' => [
        'callback' => '::myAjaxCallback', // don't forget :: when calling a class method.
        //'callback' => [$this, 'myAjaxCallback'], //alternative notation
        'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
        'event' => 'change',
        //'wrapper' => 'content-bloque', // This element is updated with this AJAX callback.
        'progress' => [
          'type' => 'throbber',
          'message' => $this->t('Verifying entry...'),
        ],
      ]
    ];

    return $form;
  }

  public function myAjaxCallback(array &$form, FormStateInterface $form_state)
  {


    $formField = $form_state->getValues();
    // Cogemos el valor del campo del formulario
    $idTax = $formField['all_article'];
    // Cargamos el nodo a través del id que nos llega del campo del formulario  
    $nodes = $this->entityTypeManager->getStorage('node')->loadMultiple($idTax);
    
    $captura = '';
    foreach ($nodes as $node) {
      // datos que necesitamos, título, cuerpo y url del artículo
      $nUrl = $node->toUrl()->toString();
      $nTit = $node->getTitle();
      $nbody = substr($node->get("body")->getValue()[0]["value"], 0, 325);
      //Montamos la estructura que nos va aparecer
      $captura .='<h3><a href="'.$nUrl.'">' . $nTit . '</a></h3>'; 
      $captura .= '<div><p>' . $nbody . ' (...)</p></div>';
          
    }

    // Cargamos la respuesta de AJAX
    $response = new AjaxResponse();
    // See: https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Selectors .
    $Selector = '#content-bloque';
    // The content that will be inserted in the matched element(s), either a render array or an HTML string.
    
    $content =  $captura;
   
    // (Optional) An array of JavaScript settings to be passed to any attached behaviours.
    //$settings = ['my-setting' => 'setting',];

    //$response->addCommand(new HtmlCommand($Selector, $content, $settings));
    $response->addCommand(new HtmlCommand($Selector, $content));
    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    if (mb_strlen($form_state->getValue('message')) < 10) {
      $form_state->setErrorByName('message', $this->t('Message should be at least 10 characters.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $this->messenger()->addStatus($this->t('The message has been sent.'));
    $form_state->setRedirect('<front>');
  }
}
