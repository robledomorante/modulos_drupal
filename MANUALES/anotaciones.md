# ANOTACIONES

## RUTAS AMIGABLES

### CONFIGURACIÓN DE .HTACCESS

En el archivo .htaccess tenemos las siguientes directivas:

~~~

Options All -Indexes
RewriteEngine on

RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]

~~~

### DEFINICIÓN DE LA RUTA AMIGABLE

La primera línea lo que indica es que no podamos leer los directorios directamente desde las URLs.
La última línea los que prepara el sistema para la ruta amigables. Por lo tanto todo lo que venga despues de index.php? nos lo mete el la variable "url".

~~~
http://tienda.test/controlador/metodo/parametro
* Controlador
* Método
* Parametro

~~~

Para capturar o recuperar la variable url lo hacemos a través del métido GET.

~~~
$url = $_GET['url'];
~~~

Para que funcione correctamente, ya que si sólo dejamos la raiz del servidor nos devuelve un error por estar la variable vacía, lo que hacemos es introducir una condición para resolver el problema

~~~
$url = !empty($_GET['url']) ? $_GET['url'] : 'home/home';
~~~

### CONFIGURACIÓN DE LA RUTAS AMIGABLES

La configuración para que funcione la ruta amigable sería la siguiente

~~~
/** Configuración de la Rutas amigables **/
	$url = !empty($_GET['url']) ? $_GET['url'] : 'home/home';
	$arraUrl = explode("/", $url);
	$controller = $arraUrl[0];
	$method = $arraUrl[0];
	$params = "";

	if (!empty($arraUrl[1]))
	{
		if ($arraUrl[1] != "")
		{
			$method = $arraUrl[1];
		}
	}

	if (!empty($arraUrl[2]))
	{
		if ($arraUrl[2] != "")
		{
			for ($i = 2; $i < count($arraUrl); $i++) {
			    $params .= $arraUrl[$i] . ',';
			}
			// Elimina la última , del array Params
			$params = trim($params, ',');
			echo $params;
		}
	}

	var_dump($arraUrl);

	echo "controlador: ${controller} - Método ${method} - Parametros ${params}"

~~~

** Para la dirección http://tienda.test/controlador/metodo/param1/param2 el resultado sería:
~~~
controlador: controlador - Método metodo - Parametros param1,param2,
~~~

** Para la dirección http://tienda.test/desarrollo/usuario/Pedro/pedro@info.com el resultado sería:
~~~
controlador: desarrollo - Método: usuario - Parametros: Pedro,pedro@info.com,
~~~

## CLASES Y HERENCIAS

### ESTRUCTURA DE LOS ARCHIVOS PARA EL EJEMPLO.

PRUEBAS
	LIBS
		Automovil.php
		Bicicleta.php
		Transporte.php
	index.php

* Planteamiento: La clase padre va a ser Transporte.php y la que van a heredar son Bicicleta y Automovil.
Para ver toda la información, cargamos todas las clases a través de un autoload en el index y luego creamos los objetos de las clases.

* INDEX.PHP
Tenemos el autocargador para que incluya todas las clases que necesitemos.


~~~
<?php 

	/** Auto cargador de clases **/
	function cargadorClases($clases){
		var_dump($clases);
		require_once "Libs/{$clases}.php";
	}

	spl_autoload_register('cargadorClases');


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Pruebas</title>
</head>
<body>
	<div><h1>Pruebas de herencias</h1></div>

	<?php

		$bicicleta = new Bicicleta();
		$bicicleta->setRuedas(2);
		$bicicleta->setCapacidad(1);
		echo $bicicleta->getInfo();
		echo "<br><br>¿Cuantas ruedas tengo? {$bicicleta->getRuedas()}";

		echo "<br><hr><br>";

		$automovil = new Automovil();
		$automovil->setRuedas(4);
		$automovil->setCapacidad(5);
		$automovil->setTransmision(2);
		echo $automovil->getInfo();




	?>

	
</body>
</html>


~~~

* Clase Principal: Transporte.php.

~~~
<?php

class Transporte 
{
	protected $ruedas;
	protected $capacidad;

	public function __construct()
	{
		$this->pruedas = "";
		$this->capacidad = "";
	}

	public function getInfo():string 
	{
		return "El Transporte tiene ". $this->ruedas . " ruedas y una capacidad de " .$this->capacidad. " personas";
	}

    /**
     * @return mixed
     */
    public function getRuedas()
    {
        return $this->ruedas;
    }

    /**
     * @param mixed $ruedas
     *
     * @return self
     */
    public function setRuedas($ruedas)
    {
        $this->ruedas = $ruedas;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCapacidad()
    {
        return $this->capacidad;
    }

    /**
     * @param mixed $capacidad
     *
     * @return self
     */
    public function setCapacidad($capacidad)
    {
        $this->capacidad = $capacidad;

        return $this;
    }
}

~~~

* Clases que heredan de transporte:
Podemos observar que en ambas clases extends - extiende de la clase transporte.

** Bicicleta.
~~~
<?php

class Bicicleta extends Transporte
{

}

~~~

** Automovil.
~~~
<?php

<?php

class Automovil extends Transporte
{
	protected $transmision;

	public function __construct () {
		//hereda el constructor de la clase principal
		parent::__construct();
		$this->transmision = 1;
	}

	public function getInfo():string 
	{
		return "El Transporte tiene ". $this->ruedas . " ruedas y una capacidad de " .$this->capacidad. " personas y " . $this->transmision . " transmision";
	}

    /**
     * @return mixed
     */
    public function getTransmision()
    {
        return $this->transmision;
    }

    /**
     * @param mixed $transmision
     *
     * @return self
     */
    public function setTransmision($transmision)
    {
        $this->transmision = $transmision;

        return $this;
    }
}

~~~


## MODELO MVC

### ESTRUCTURA DEL MODELO.

* index.php. Este archivo es el que recibe la url y lo descompone en tres elementos:
** controlador
** métido
** parametros
Si en la url no aparece ningún método nos poner por defecto la ruta home/home.

* Dentro del index está el autoload que es el que carga todas las clases que necesitamos para la carga dinámica de los contorladores y de los métodos además de los parámetros. Las clases las toma del directorio Libraries/Core.

* De la misma manera que para los controladores y métodos lo hacemos desde index.php, los modelos los va a tomar del archivo Libraries/Core/Controllers, que a su vez lo cargamos en el index.php de la raiz a través del autoload.

* Por lo tanto todos los archivos de la carpeta Controllers van a heredar del Libraries/Core/Controllers y así ver si tiene modelos que cargar cada uno de los controladores.







## SANIZITAR DATOS EN LA BASE DE DATOS - PHP
### FUNCIONES PARA LA SANITACIÓN DE LOS DATOS

* Funciones para sanitizado de datos en PDO - MYSQL:
~~~

protected static $columnasDB = ['id', 'titulo', 'precio', 'imagen', 'descripcion', 'habitaciones', 'wc', 'estacionamiento', 'creado', 'vendedorId'];

    public function atributos() 
    {
        $atributos = [];
        foreach (self::$columnasDB as $columna) {
            /* Para ignorar el atributo id */
            if($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;

    }

    public function sanitizarAtributos()
    {
        $atributos = $this->atributos();
        $sanitizado = [];

        foreach ($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }

        return $sanitizado;

    }

    // Para activar el método
     $atributos = $this->sanitizarAtributos();

~~~

* Sanitizado de datos que viene en un formulario y estamos trabajando con mysqli.

~~~

	$titulo = mysqli_real_escape_string( $db,  $_POST['titulo'] );
    $precio = mysqli_real_escape_string( $db,  $_POST['precio'] );
    $descripcion = mysqli_real_escape_string( $db,  $_POST['descripcion'] );
    $habitaciones = mysqli_real_escape_string( $db,  $_POST['habitaciones'] );
    $wc = mysqli_real_escape_string( $db,  $_POST['wc'] );
    $estacionamiento = mysqli_real_escape_string( $db,  $_POST['estacionamiento'] );
    $vendedorId = mysqli_real_escape_string( $db,  $_POST['vendedor'] );
    $creado = date('Y/m/d');
~~~

* Importante tambien ver sanitización de datos con filtros de php.
** Referencia: https://www.php.net/manual/es/filter.filters.sanitize.php
** Ejemplos:

	~~~
	FILTER_SANITIZE_EMAIL: "email" - Elimina todos los caracteres menos letras, dígitos y !#$%&'*+-=?^_`{|}~@.[].

	FILTER_SANITIZE_NUMBER_INT: "number_int" - Elimina todos los caracteres excepto dígitos y los signos de suma y resta.

	~~~

## FUNCION JOIN - php
Esta función nos permite pasar un array a un string.
PHP join () es una función incorporada que devuelve la cadena de los elementos de una matriz. La función join () es un alias de una función implode () . El parámetro separador de join () es opcional. Sin embargo, se recomienda utilizar siempre los dos parámetros para la compatibilidad con versiones anteriores.
~~~
join(separator,array)
~~~

Ejemplo:

$data = ['Google', 'Youtube', 'Facebook', 'Amazon'];
echo join(", ",$data);
resultado:

Ejemplo: Google, Youtube, Facebook, Amazon

