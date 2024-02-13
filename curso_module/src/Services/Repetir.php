<?php

namespace Drupal\curso_module\Services;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;

class Repetir {

    /** @var MessengerInterface */

    private $messenger;

    /** @var EntityTypeManagerIntface */

    private $entityTypeManager;

    public function __construct(MessengerInterface $messenger, EntityTypeManagerInterface $entityTypeManager)
    {
        $this->messenger = $messenger;
        $this->entityTypeManager = $entityTypeManager;
    }

    public function repetir($palabra, $cantidad = 3) {


        $cargaNodo = $this->entityTypeManager->getStorage('node')->load(1);
        $this->messenger->addError('Esto es un error del servicio repetir');
        $this->messenger->addMessage('El id: ' .$cargaNodo->id() . ' y la etiqueta ' . $cargaNodo->bundle());
        //dpm($cargaNodo->id());
        return str_repeat($palabra, $cantidad);
    }
}