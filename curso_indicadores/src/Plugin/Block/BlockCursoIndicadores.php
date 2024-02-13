<?php

namespace Drupal\curso_indicadores\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\curso_module\Services\ServiceWeb; // Servicio web json - clase personalizada
use Drupal\Core\Messenger\MessengerInterface; // clase para la gestión de los mensajes de advertencia, warning, etc
use Drupal\Core\Url;
use Drupal\Core\Link;

/**
 * Provides a curos indicadores block block.
 *
 * @Block(
 *   id = "curso_indicadores_block",
 *   admin_label = @Translation("Curos indicadores block"),
 *   category = @Translation("Curso")
 * )
 */
class BlockCursoIndicadores extends BlockBase implements ContainerFactoryPluginInterface {

  protected $messenger;           // servicio de la base de datos
  protected $serviceweb;        // Servicio de Usuario


  public function __construct(array $configuration, $plugin_id, $plugin_definition, MessengerInterface $messenger, ServiceWeb $serviceweb) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->messenger = $messenger;
    $this->serviceweb = $serviceweb;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
        $configuration,
        $plugin_id,
        $plugin_definition,
        $container->get('messenger'),
        $container->get('curso_module.serviceweb'),
    );
  }



  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'addressAPI' => $this->t('https://des.iepnb.es/pgrest/v_normativa_solr'),
      'tab' => $this->t('indicador10'),
      'observations' =>$this->t('primera observación'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['addressAPI'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Address API'),
      '#default_value' => $this->configuration['addressAPI'],
    ];
    $form['tab'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Tab'),
      '#default_value' => $this->configuration['tab'],
    ];
    $form['observations'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Observaciones'),
      '#default_value' => $this->configuration['observations'],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['addressAPI'] = $form_state->getValue('addressAPI');
    $this->configuration['tab'] = $form_state->getValue('tab');
    $this->configuration['observations'] = $form_state->getValue('observations');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $elementos = [];

    $address = $this->configuration['addressAPI']; 
    $tab = $this->configuration['tab']; 
    $observations = $this->configuration['observations']; 

    $elementos['address'] = $address;
    $elementos['tab'] = $tab;
    $elementos['observations'] = $observations;


   

  

    $message = $this->t('The API address is: %address, the label for the tab is %tab, las observaciones de bloque son: %observations', ['%address' => $address, '%tab' => $tab, '%observations' =>$observations]);
    $this->messenger()->addMessage($message);

    $build['content'] = [
      '#theme' => 'indicadoresApi',
      '#elementos' => $elementos,
    ];
    return $build;
  }

}
