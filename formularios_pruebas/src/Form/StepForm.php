<?php

namespace Drupal\formularios_pruebas\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a Formularios pruebas form.
 */
class StepForm extends FormBase
{

  /**
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  public function __construct(MessengerInterface $messenger)
  {
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    // Instantiates this form class.
    return new static(
      // Load the service required to construct this class.
      $container->get('messenger')
    );
  }



  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'formularios_pruebas_step';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    if ($form_state->has('page') && $form_state->get('page') == 2) {
      return self::formPageTwo($form, $form_state);
    }

    $form_state->set('page', 1);

    $form['description'] = [
      '#type' => 'item',
      '#title' => $this->t('Page @page', ['@page' => $form_state->get('page')]),
    ];

    $form['first_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('First Name'),
      '#default_value' => $form_state->getValue('first_name', ''),
      '#required' => TRUE,
    ];

    $form['last_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Last Name'),
      '#default_value' => $form_state->getValue('last_name', ''),
    ];


    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['next'] = [
      '#type' => 'submit',
      '#button_type' => 'primary',
      '#value' => $this->t('Next'),
      '#submit' => ['::submitPageOne'],
      '#validate' => ['::validatePageOne'],
    ];

    return $form;
  }

  public function validatePageOne(array &$form, FormStateInterface $form_state)
  {
    $title = $form_state->getValue('first_name');
    if (strlen($title) < 5) {
      $form_state->setErrorByName('first_name', $this->t('The first name must be at least 5 characters long.'));
    }
  }

  /**
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitPageOne(array &$form, FormStateInterface $form_state)
  {
    $form_state
      ->set('page_values', [
        'first_name' => $form_state->getValue('first_name'),
        'last_name' => $form_state->getValue('last_name'),
      ])
      ->set('page', 2)
      ->setRebuild(TRUE);
  }

  /**
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @return array
   *   The render array defining the elements of the form.
   */
  public function formPageTwo(array &$form, FormStateInterface $form_state)
  {

   
    $form['description'] = [
      '#type' => 'item',
      '#title' => $this->t('Page @page', ['@page' => $form_state->get('page')]),
    ];

    $form['color'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Favorite color'),
      '#required' => TRUE,
      '#default_value' => $form_state->getValue('color', ''),
    ];
    $form['back'] = [
      '#type' => 'submit',
      '#value' => $this->t('Back'),
      '#submit' => ['::pageTwoBack'],
      '#limit_validation_errors' => [],
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#button_type' => 'primary',
      '#value' => $this->t('Submit'),
    ];
    return $form;
  }

  /**
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function pageTwoBack(array &$form, FormStateInterface $form_state)
  {
    $form_state
      ->setValues($form_state->get('page_values'))
      ->set('page', 1)
      ->setRebuild(TRUE);
  }

  /**
   * @param array $form
   * @param FormStateInterface $form_state
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    // validate form
  }

  /**
   * @param array $form
   * @param FormStateInterface $form_state
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {

    $page_values = $form_state->get('page_values');

    // dpm($page_values['first_name']);

    $fields['first_name'] = $page_values['first_name'];
    $fields['last_name'] = $page_values['last_name'];
    $fields['color'] = $form_state->getValue('color');

    // dpm($fields);

    $this->messenger()->addStatus($this->t('The message has been sent. Username: %username %lastname with Color: %color', 
    ['%username' => $fields['first_name'],
     '%lastname' => $fields['last_name'],
     '%color' => $fields['color']  
    ]));
  }
}
