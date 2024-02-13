<?php include 'includes/header.php';

// si tenemos las clases Detalles y Clientes en una carpeta externa común
// y estamos colocando namespace App en esas clases


use App\Clientes;
use App\Detalles;

function mi_autoload($clase) {
    //echo $clase;
    $partes = explode(''\\'', $clase)
    require __DIR__.'clases/' . $partes[1] . 'php';
}

spl_autoload_register('mi_autoload');



$detalles = new Detalles();
$clientes = new Clientes();



include 'includes/footer.php';
