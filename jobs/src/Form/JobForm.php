<?php

namespace Drupal\jobs\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the registro entity edit forms.
 */
class JobForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->getEntity();
    $result = parent::save($form, $form_state);

    $message_arguments = ['%label' => $entity->toLink()->toString()];
    $logger_arguments = [
      '%label' => $entity->label(),
      'link' => $entity->toLink($this->t('View'))->toString(),
    ];

    switch ($result) {
      case SAVED_NEW:
        $this->messenger()->addStatus($this->t('New job %label has been created.', $message_arguments));
        $this->logger('Empleo')->notice('Created new job %label', $logger_arguments);
        break;

      case SAVED_UPDATED:
        $this->messenger()->addStatus($this->t('The job %label has been updated.'));
        $this->logger('Empleo')->notice('Updated registro %label.');
        break;
    }

    $form_state->setRedirect('entity.job.canonical', ['job' => $entity->id()]);

    return $result;
  }

}