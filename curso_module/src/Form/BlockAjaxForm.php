<?php

namespace Drupal\curso_module\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

/**
 * Provides a curso module form.
 */
class BlockAjaxForm extends FormBase
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
    return 'curso_module_block_ajax';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {

    $node_storage = $this->entityTypeManager->getStorage('node');
    $node = $node_storage->load(1);
    $uuid = $node->bundle();
    $terms = $this->entityTypeManager->getStorage('taxonomy_term')->loadTree('pruebas');
    $opciones = [];
    foreach ($terms as $term) {
      //$opciones[[$term->tid]] = [$term->name];
      // var_dump($term->tid->value); //return tid of term
      // var_dump($term->name->value); //return title of term
      //array_push($opciones, array($term->tid => $term->name));

      $opciones[$term->tid] = $term->name;
      
    }

    // dpm($opciones);

    //dpm($opciones);
    $form['example_select'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Various Options by Checkbox'),
      '#options' => $opciones,
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



    /*$form['example_select'] = [
      '#type' => 'select',
      '#title' => $this->t('Example select field'),
      '#options' =>$opciones,
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
    ];*/

    return $form;
  }

  public function myAjaxCallback(array &$form, FormStateInterface $form_state)
  {


    $formField = $form_state->getValues();
    // Cogemos el valor del campo del formulario
    $idTax = $formField['example_select'];
    dpm($idTax);
    // Cargamos la taxonomía a través del id que nos llega del campo del formulario  
    $term = $this->entityTypeManager->getStorage('taxonomy_term')->loadMultiple($idTax);
    
    
    $captura = '';
    foreach ($term as $key => $value) {
      $captura .='<li>'. $value->name->value .'</li>';      
    }

    // Cargamos la respuesta de AJAX
    $response = new AjaxResponse();
    // See: https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Selectors .
    $Selector = '#content-bloque';
    // The content that will be inserted in the matched element(s), either a render array or an HTML string.
    $content = '<p>la opción elegida es: <ul> ' .  $captura. '</ul></p>';
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
