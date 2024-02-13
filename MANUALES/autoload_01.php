<?php include 'includes/header.php';

// si tenemos las clases Detalles y Clientes en una carpeta externa común
// y estamos colocando namespace App en esas clases


function mi_autoload($clase) {
    //echo $clase;
    $partes = explode(''\\'', $clase)
    require __DIR__.'clases/' . $partes[1] . 'php';
}

spl_autoload_register('mi_autoload');


class Clientes {

    public funtion __construct()
    {
        echo "Esta clase está dentro del archivo el otro cliente en la carpeta clases";
    }
}
    
$detalles = new App\Detalles();
$clientes = new App\Clientes();
$cliente2 = new Clientes();


include 'includes/footer.php';
