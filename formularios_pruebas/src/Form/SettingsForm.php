<?php

namespace Drupal\formularios_pruebas\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Egulias\EmailValidator\EmailValidator;

/**
 * Configure Formularios pruebas settings for this site.
 */
class SettingsForm extends ConfigFormBase
{

  protected $emailValidator; // validar email

  public function __construct(EmailValidator $email_validator)
  {
    $this->emailValidator = $email_validator;
  }

  // ver los servicios en core.services.yml  - web/core/
  // los servicios están en core/lib/Drupal/Core 
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('email.validator'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'formularios_pruebas_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames()
  {
    return ['formularios_pruebas.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {

    $config = $this->config('formularios_pruebas.settings');

    $form['formularios_pruebas_message'] = [
      '#type' => 'textfield',
      '#title' => $this->t('message'),
      '#default_value' => $config->get('message'),
    ];

    $form['formularios_pruebas_email'] = [
      '#type' => 'email',
      '#title' => $this->t('User email'),
      '#default_value' => $config->get('email'),
      '#description' => $this->t('Your email.'),
      '#required' => TRUE,
    ];

    $form['formularios_pruebas_direccion'] = [
      '#type' => 'textarea',
      '#title' => 'direccion',
      '#cols' => 60,
      '#rows' => 5,
      '#default_value' => $config->get('direccion'),
      '#required' => FALSE,
    ];

    $form['formularios_pruebas_postal'] = [
      '#type' => 'textfield',
      '#title' => $this->t('postal'),
      '#default_value' => $config->get('postal'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {

    $email = $form_state->getValue('formularios_pruebas_email');

    // Validación de email por el método elegido
    if (!$this->emailValidator->isValid($email)) {
      $form_state->setErrorByName('user_email', $this->t('%email is not a valid email address.', ['%email' => $email]));
      parent::validateForm($form, $form_state);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $this->config('formularios_pruebas.settings')
      ->set('message', $form_state->getValue('formularios_pruebas_message'))
      ->set('email', $form_state->getValue('formularios_pruebas_email'))
      ->set('direccion', $form_state->getValue('formularios_pruebas_direccion'))
      ->set('postal', $form_state->getValue('formularios_pruebas_postal'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}
