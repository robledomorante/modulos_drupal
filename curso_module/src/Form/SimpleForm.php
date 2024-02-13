<?php

namespace Drupal\curso_module\Form;

// Clases para trabajar con formularios
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
//para poder acceder al contenedor de servicios
use Symfony\Component\DependencyInjection\ContainerInterface;
//para acceder al servicio database
use Drupal\Core\Database\Connection;
//para acceder al servicio current_user
use Drupal\Core\Session\AccountInterface;
// Validador de email
use Egulias\EmailValidator\EmailValidator;


/**
 * Provides a curso module form.
 */
class SimpleForm extends FormBase
{

  protected $database;
  protected $currentUser;
  protected $emailValidator;

  public function __construct(
    Connection $database,
    AccountInterface $current_user,
    EmailValidator $email_validator
  ) {
    $this->database = $database;
    $this->currentUser = $current_user;
    $this->emailValidator = $email_validator;
  }
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('database'),
      $container->get('current_user'),
      $container->get('email.validator')

    );
  }


  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'curso_module_simple';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {

    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#description' => $this->t('The title must be at least 15 characters long.'),
      '#required' => TRUE,
    ];

    $form['color'] = [
      '#type' => 'select',
      '#title' => $this->t('Color'),
      '#options' => [
        0 => $this->t('Black'),
        1 => $this->t('Red'),
        2 => $this->t('Blue'),
        3 => $this->t('Green'),
        4 => $this->t('Orange'),
        5 => $this->t('White'),
      ],
      '#default_value' => 2,
      '#description' => $this->t('Choose a color.'),
    ];

    $form['username'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Username'),
      '#description' => $this->t('Your username.'),
      '#default_value' => $this->currentUser->getAccountName(),
      '#required' => TRUE,
    ];

    $form['user_email'] = [
      '#type' => 'email',
      '#title' => $this->t('User email'),
      '#description' => $this->t('Your email.'),
      '#required' => TRUE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Send'),
    ];

    return $form;
  }

  

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    // mb_strlen — Obtiene la longitud de una cadena de caracteres
    if (mb_strlen($form_state->getValue('title')) < 15) {
      $form_state->setErrorByName('title', $this->t('Message should be at least 15 characters.'));
    }

    $email = $form_state->getValue('user_email');
    if (!$this->emailValidator->isValid($email)) {
      $form_state->setErrorByName('user_email', $this->t(
        '%email is not a valid email address.',
        ['%email' => $email]
      ));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $title = $form_state->getValue('title');
    $color = $form_state->getValue('color');
    $username = $form_state->getValue('username');
    $user_email = $form_state->getValue('user_email');

    // INSERTANDO LOS DATOS DEL FORMULARIO EN LA BASE DE DATOS
    // TABLA CREADA EN CURSO_MODULE.INSTALL

    $this->database->insert('curso_forms_simple')
      ->fields([
        'title' => $title,
        'color' => $color,
        'username' => $username,
        'email' => $user_email,
        'uid' => $this->currentUser->id(),
        // vemos desde que ip ha hecho la petición
        'ip' => \Drupal::request()->getClientIP(),
        'timestamp' => REQUEST_TIME,
      ])
      ->execute();
    
    // Introducimos la entrada en los log de Drupal
    $this->logger('forcontu_forms')->notice(
      'New Simple Form entry from user %username inserted: %title.',
      [
        '%username' => $form_state->getValue('username'),
        '%title' => $form_state->getValue('title'),
      ]
    );

    // Introducimos un mensaje cuando termina de enviar el formulario.
    $this->messenger()->addStatus($this->t('The message has been sent.'));
    // Redirigimos después de enviar otra vez al formulario limpio
    $form_state->setRedirect('curso_module.simple');
  }
}
