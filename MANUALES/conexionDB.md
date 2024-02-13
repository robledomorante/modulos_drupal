# CONEXIÓN A BASE DE DATOS MYSQL / MARIADB CON PHP

## CON MYSQLI

~~~
// Conectar a la BD con Mysqli.
$db = new mysqli('localhost', 'root', '', 'baseDeDatos');

// Creamos el query
$query = "SELECT titulo, imagen from propiedades";

// Lo preparamos
$stmt = $db->prepare($query);

// Lo ejecutamos
$stmt->execute();

// creamos la variable
$stmt->bind_result($titulo, $imagen);

// imprimir el resultado
while($stmt->fetch()):
    var_dump($titulo);
endwhile;
~~~


## CON PDO

~~~
// Conectar a la BD con PDO
$db = new PDO('mysql:host=localhost; dbname=bienesraices_crud', 'root', '');

// Creamos el query
$query = "SELECT titulo, imagen from propiedades";

// Lo preparamos
$stmt = $db->prepare($query);

// Lo ejecutamos
$stmt->execute();

// Obtener los resultados
$resultado = $stmt->fetchAll( PDO::FETCH_ASSOC );

// Iterar
foreach($resultado as $propiedad):
    echo $propiedad['titulo'];
    echo "<br>";
    echo $propiedad['imagen'];
    echo "<br>";
endforeach;

~~~

Estas son las dos formas orientadas a objetos
