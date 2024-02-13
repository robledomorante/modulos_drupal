<?php

namespace Drupal\indices_upload\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Indices Upload settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'indices_upload_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['indices_upload.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // rutas de actualización
    $form['archivos_actualizacion'] = [
      '#type' => 'details',
      '#title' => $this->t('Direcciones de las APIS'),
      '#description' => $this->t('Direcciones de las APIS que tenemos que actualizar.'),
      '#group' => 'APIS',
    ];
    $form['archivos_actualizacion']['province'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Province'),
      '#default_value' => $this->config('indices_upload.settings')->get('province'),
    ];
    $form['ruta'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Directorio de archivos Json'),
      '#default_value' => $this->config('indices_upload.settings')->get('ruta'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    /*if ($form_state->getValue('ruta') != 'ruta') {
      $form_state->setErrorByName('ruta', $this->t('The value is not correct.'));
    }*/
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('indices_upload.settings')
      ->set('province', $form_state->getValue('province'))
      ->set('ruta', $form_state->getValue('ruta'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
