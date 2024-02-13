# MASTER EXPERTO EN DRUPAL 9 SITE BUILDING

## DRUSH - COMANDOS

Instalar Drush al proyecto de Drupal 9. Asegurar que tenemos permisos del a carpeta web/sites/default

~~~
$ composer require drush/drush
~~~

Vaciar el caché de Drupal

~~~
$ drush cr
~~~

Descargar un módulo de Drupal

~~~
$ drush dl módulo
~~~

Instalar un módulo de Drupal

~~~
$ drush en módulo
~~~
Ejemplo de Admin Toolbar:

~~~

$ composer require drupal/admin_toolbar
$ drush en admin_toolbar admin_toolbar_tools
$ drush cr
~~~

Listar los módulos de Drupal

~~~
$ drush pml
~~~

Instalación de módulos con drush 
Por ejemplo, para instalar Admin Toolbar y el submódulo Admin Toolbar Tools, ejecutaremos este 
comando:
 
~~~
$ drush en admin_toolbar admin_toolbar_tools
$ drush cr
~~~

Desinstalar un módulo de Drupal

~~~
$ drush pmu proyecto
$ drush pmu admin_toolbar
~~~

Eliminar los archivos del módulo
Una vez desinstalado, ya podemos eliminar el módulo de la instalación de Drupal. Tenemos que hacerlo 
desde Composer: 
$ composer remove drupal/proyect

Ver módulos para actualizar actualización
$composer outdated drupal/*



## COMPOSER EN DRUPAL

Como instalar Drupal con composer
https://www.drupal.org/docs/develop/using-composer/using-composer-to-install-drupal-and-manage-dependencies

Instalación el Drupal como proyecto.
~~~
$ cd public_html/sb/
$ composer create-project drupal/recommended-project sb1
$ composer create-project drupal/recommended-project sb2
$ chmod u+w web/sites/default
~~~

Instalación en Drupal de un theme
~~~
$ cd public_html/sb/
$ composer require drupal/proyecto
Añade el proyecto al composer.json:
$ composer require drupal/proyecto --no-update
Realiza la instalación o actualización del proyecto y sus dependencias:
$ composer update drupal/proyecto --with-dependencies

Ejemplo:
$ composer require drupal/d8w3css
~~~

Los temas contribuidos instalados con Composer se almacenarán en web/themes/contrib: 
~~~
proyecto_web/
 ├── composer.json
 ├── composer.lock
 ├── vendor
 └── web
 └── themes
 ├── contrib
 └── custom
~~~

Instalación en Drupal de módulos

Desde la carpeta principal (public_html/sb/sb1), lanzaremos este comando para añadir el módulo al 
sitio, sustituyendo 'proyecto' por el nombre de sistema del módulo: 
$ composer require drupal/proyecto

Si queremos instalar el módulo evitando una actualización del sistema, podemos ejecutar estos 
comandos: 
a) Añade el proyecto a composer.json:
~~~ 
$ composer require drupal/proyecto --no-update
~~~
b) Realiza la instalación o actualización del proyecto y sus dependencias:
~~~ 
$ composer update drupal/proyecto --with-dependencies
~~~
Los módulos contribuidos instalados con Composer se almacenarán en web/modules/contrib: 
~~~
proyecto_web/
 ├── composer.json
 ├── composer.lock
 ├── vendor
 └── web
    └── modules
        ├── contrib
        └── custom
~~~


# MÓDULOS CONTRIB

## MÓDULOS GENERALES
https://www.drupal.org/project/back_to_top      //Botón para ir arriba en la página web
https://www.drupal.org/project/replicate        //replica un contenido
https://www.drupal.org/project/replicate_ui
https://www.drupal.org/project/masquerade       //suplanta roles
$ composer require 'drupal/masquerade:^2.0@beta'

## MÓDULOS DE TAXONOMIA
* https://www.drupal.org/project/taxonomy_menu
$ composer require 'drupal/taxonomy_menu:^3.5'
$ composer require drupal/taxonomy_menu
* https://www.drupal.org/project/taxonomy_unique
https://www.drupal.org/project/hierarchical_taxonomy_menu
$ composer require 'drupal/hierarchical_taxonomy_menu:^1.42'
$ composer require drupal/hierarchical_taxonomy_menu

## MÓDULOS PARA LA EDICIÓN DEL TEXTO - CKEDITOR
https://www.drupal.org/project/editor_file
https://www.drupal.org/project/editor_advanced_link
https://www.drupal.org/project/allowed_formats


## MÓDULOS DE CAMPOS
https://www.drupal.org/project/field_group
$ composer require drupal/field_group
$ composer require 'drupal/jquery_ui_accordion:^1.1'
$ druah

## MÓDULOS IMAGEN
https://www.drupal.org/project/image_effects   //Ampliación de efectos para los estilos de imagen
Lo utilizamos para la marcas de agua.
$ composer require 'drupal/image_effects:^3.2'
$ composer require drupal/image_effects

https://www.drupal.org/project/video_embed_field

## MÓDULOS VISUALIZACIÓN
https://www.drupal.org/project/colorbox
$ composer require 'drupal/colorbox:^1.8'
$ composer require drupal/colorbox
$ cd public_html/sb/
$ composer require drupal/colorbox
$ drush en colorbox
$ drush colorbox-plugin
[notice] The colorbox library has been successfully downloaded to
/home/drupal/public_html/sb/sb1/web/libraries/colorbox.
$ drush cr

Ejemplo de instalación de colorbox
### INSTALACIÓN DE COLORBOX
davirobl@forcontu.com:~/public_html/sb/sb2 $ composer require drupal/colorbox
davirobl@forcontu.com:~/public_html/sb/sb2 $ drush cr
davirobl@forcontu.com:~/public_html/sb/sb2 $ drush en colorbox
davirobl@forcontu.com:~/public_html/sb/sb2 $ drush colorbox-plugin
 [notice] The colorbox library has been successfully downloaded to home/davirobl/public_html/sb/sb2/web/libraries/colorbox.
davirobl@forcontu.com:~/public_html/sb/sb2 $ drush cr

## MÓDULO DE MANTENIMIENTO
$ composer require 'drupal/readonlymode:^1.1'
$ composer require drupal/readonlymode




https://www.drupal.org/project/pathauto   // Patrones para las url automáticas.
Ejemplo:
~~~
$ composer require drupal/pathauto
$ drush en pathauto
~~~

## MIGRACIÓN, COPIAS DE SEGURIDAD Y ACTUALIZACIONES

https://www.drupal.org/project/backup_migrate
~~~
$ composer require drupal/backup_migrate
$ composer require 'drupal/backup_migrate:^5.0'
~~~

## PARAGRAPHS
https://www.drupal.org/project/paragraphs
~~~
$ composer require 'drupal/paragraphs:^1.13'
$ composer require drupal/paragraphs
~~~
Dependencias:
~~~
$ composer require drupal/search_api drupal/entity_browser
$ composer require 'drupal/entity_usage:^2.0@beta'
~~~
Instalar todos los módulos con drush
~~~
$ drush en paragraphs paragraphs_demo paragraphs_library paragraphs_type_permissions
$ drush cr
~~~
* Módulos adiciones:
** https://www.drupal.org/project/classy_paragraphs
~~~
$ composer require 'drupal/classy_paragraphs:^1.0@RC'
$ composer require drupal/classy_paragraphs
~~~

** https://www.drupal.org/project/reference_table_formatter
~~~
$ composer require 'drupal/reference_table_formatter:^1.1'
$ composer require drupal/reference_table_formatter
~~~

** https://www.drupal.org/project/bootstrap_paragraphs
~~~
$ composer require 'drupal/bootstrap_paragraphs:^2.0@beta'
$ composer require drupal/bootstrap_paragraphs
~~~

** https://www.drupal.org/project/viewsreference
~~~
$ composer require 'drupal/viewsreference:^1.7'
$ composer require drupal/viewsreference
~~~

** https://www.drupal.org/project/bootstrap_library
~~~
composer require 'drupal/bootstrap_library:^2.0'
Bajar la librería de https://getbootstrap.com/docs/5.1/getting-started/download/
Ver todos los tipos de paragrahs Bootstrat https://bp.jimbir.ch/paragraph-types/all
~~~

## FORMULARIOS - WEBFORM

https://www.drupal.org/project/webform
~~~
$ composer require 'drupal/webform:^6.1'
$ composer require drupal/webform
$ composer require 'drupal/devel:^4.1'
$ composer require drupal/devel
~~~

* Instalar librerías que necesita webform
~~~
$ drush webform:libraries:download
~~~
Nos lo instala en /librerias/
 
* Instalar libería - jquery_ui_datepicker
https://www.drupal.org/project/jquery_ui_datepicker
~~~
$ composer require 'drupal/jquery_ui_datepicker:^1.2'
~~~

* librería antibot
~~~
$ composer require 'drupal/antibot:^2.0'
$ composer require drupal/antibot
~~~

* Expresiones regulares
https://www.w3schools.com/js/js_regexp.asp
Ejemplo: Patrón ^[aeiou][a-z]*
Sólo puedo comenzar por vocal y luego sólo puede ser alfabético, no admite números.

* Protección contra spam
https://www.drupal.org/project/captcha
~~~
$ composer require 'drupal/captcha:^1.2'
$ composer require drupal/captcha
~~~

https://www.drupal.org/project/recaptcha
~~~
$ composer require 'drupal/recaptcha:^3.0'
$ composer require drupal/recaptcha
~~~

https://www.drupal.org/project/honeypot
~~~
$ composer require 'drupal/honeypot:^2.0'
$ composer require drupal/honeypot
~~~

# LAYOUT BUILDER
https://www.drupal.org/docs/8/core/modules/layout-builder/additional-modules

# PANELS.

El ecosistema de Panels se compone de los siguientes módulos:
* Layout Discovery. 
* Page Manager. 
* Panels. 

## MÓDULOS PARA EL PANELS
Panels (con Page Manager) se diferencia principalmente en que permite construir páginas complejas,
que incluyen el paso de argumentos, la integración de vistas

* https://www.drupal.org/project/page_manager
~~~
$ composer require 'drupal/page_manager:^4.0@beta'
$ composer require drupal/page_manager
~~~

* https://www.drupal.org/project/ctools
~~~
$ composer require 'drupal/ctools:^3.7'
$ composer require drupal/ctools
~~~

* https://www.drupal.org/project/panels
~~~
$ composer require 'drupal/panels:^4.6'
$ composer require drupal/panels
~~~

* https://www.drupal.org/project/panelizer
~~~
$ composer require 'drupal/panelizer:^5.0@beta'
$ composer require drupal/panelizer
~~~


## MÓDULOS PARA DISPLAY SUITE
Display Suite se centra en la presentación de cualquier entidad

https://www.drupal.org/project/ds
~~~
$ composer require 'drupal/ds:^3.13'
$ composer require drupal/ds
~~~
los módulos de display suite y panels se pueden complementar bien.

## MÓDULO SOLUCIÓN DE ERRORES CON ENTIDADES
https://www.drupal.org/project/devel_entity_updates
Y luego ejecutando el comando: 
drush entup

# MÓDULOS MULTIMEDIA
* núcleo de Drupal, media y media library
* Módulos adicionales - https://www.drupal.org/project/media_entity:
** Credenciales de Facebook
https://www.drupal.org/project/media_entity_facebook
https://developers.facebook.com/docs/instagram-basic-display-api/getting-started
$ composer require 'drupal/media_entity_facebook:^3.0@beta'
** Instagram
$ composer require 'drupal/media_entity_instagram:^3.0'
** Twitter
https://www.drupal.org/project/media_entity_twitter
$ composer require 'drupal/media_entity_twitter:^2.7'


# MÓDULOS AMPLIAN VISTAS (VIEWS)

## WEIGHT
* https://www.drupal.org/project/weight
~~~
$ composer require 'drupal/weight:^3.3'
$ composer require drupal/weight
~~~

* https://www.drupal.org/project/views_accordion
~~~
$ composer require 'drupal/views_accordion:^2.0'
~~~

PEC1
$ cd public_html/sb/
$ composer create-project drupal/recommended-project sb2
$ chmod u+w web/sites/default o chmod 775 web/sites/default
permisos del a carpeta web/sites/default
$ composer require drush/drush

informe de estado con los errores:
$ 
$settings['trusted_host_patterns'] = [
  '^.+\.forcontu\.com$',
];

$ composer require drupal/admin_toolbar
$ drush en admin_toolbar admin_toolbar_tools
$ drush cr


# TWIG

## Comentarios
Para escribir comentarios en el código, utilizaremos {# #}
~~~
{# nota: todo este código es un comentario y no se ejecutará
aunque incluyamos variables como {{ title }}
#}
~~~
## Imprimir variables
a) Ejemplo de variable simple:
~~~
{{ title }}
~~~
b) Variable con atributos (objeto en PHP):
~~~
{{ foo.bar }}
~~~
c) Variable con elementos (array en PHP):
~~~
{{ foo['bar'] }}
~~~
d) Si el nombre del atributo contiene caracteres especiales (como por ejemplo un guión – que
podría ser interpretado como "menos"), podemos utilizar la función attribute():
~~~
{{ attribute(foo, 'data-bar') }}
~~~

## Asignar variables
Es posible utilizar variables auxiliares dentro de la plantilla. Utilizaremos la etiqueta set, entre {% y %}
(en lugar de {{ }}).
~~~
{% set foo = 'bar' %}
{% set foo = [1, 2] %}
~~~
Las variables se pueden imprimir o utilizar en otras expresiones.

## Filtros

Los filtros se utilizan para modificar las variables de alguna forma. Por ejemplo, un filtro puede ser pasar
una cadena a mayúsculas (upper). Primero se escribe la variable sobre la que se aplicará el filtro, y
luego el nombre del filtro separado por el símbolo pipe |. Se pueden concatenar varios filtros, separados
por |, que se aplicarán a la variable en orden.

~~~
{% set foo = 'welcome' %}
{{ foo|upper }}
{# La salida será: WELCOME #}
{{ 'welcome'|upper }}
{# también se puede utilizar el filtro directamente sobre
una cadena, obteniéndose el mismo resultado
#}
~~~

Algunos de los filtros disponibles son:
* upper. Pasa a mayúsculas todas las letras de una cadena.
* lower. Pasa a minúsculas todas las letras de una cadena.
~~~
{{ 'WELCOME'|lower }}
{# Resultado: welcome #}
~~~

* capitalize. Pasa a mayúsculas solo la primera letra de una cadena.

~~~
{{ 'welcome home'|capitalize }}
{# Resultado: Welcome home #}
~~~

* title. Pasa a mayúsculas la primera letra de cada palabra de la cadena.

~~~
{{ 'welcome home'|title }}
{# Resultado: Welcome Home #}
~~~

* date. Permite dar formato a una fecha. Funciona como la función date de PHP.

~~~
{{ "now"|date("d/m/Y") }}
{# Resultado: 13/02/2016 #}
~~~

* join. Une los elementos de un array en una cadena. Por defecto se utiliza una cadena vacía
como separador (sin separación), pero se puede especificar cualquier otro caracter.

~~~
{{ [1, 2, 3]|join }}
{# Resultado: 123 #}
{{ [1, 2, 3]|join(',') }}
{# Resultado: 1,2,3 #}
~~~

* sort. Ordena un array.

~~~
{% set foo = [3, 2, 4, 1]|sort %}
{# El contenido de la variable for será: [1, 2, 3, 4] #}
~~~

* length. Devuelve el número de elementos de un array o el número de caracteres de una
cadena.

~~~
{% set count = users|length %}
{# Almacena en la variable count el número de elementos de user #}
~~~

El listado completo de filtros se puede consultar en:
https://twig.symfony.com/doc/2.x/filters/index.html

## Estructuras de control: for
El for se puede utilizar como un foreach:

~~~
<h1>Users</h1>
<ul>
{% for user in users %}
<li>{{ user.name }}</li>
{% endfor %}
</ul>
{# Imprime una lista HTML de nombres de usuario (atributo name)
<h1>Users</h1>
<ul>
<li>frangil</li>
<li>laurafornie</li>
<li>userfoo</li>
</ul>
#}
~~~
O como un for clásico, estableciendo un rango de ejecución:
~~~
{% for i in range(0, 3) %}
{{ i }},
{% endfor %}
{# Imprime 0, 1, 2, 3 #}
~~~

## Estructuras de control: if
Permite evaluar una condición para realizar o no un conjunto de acciones.

~~~
{% if title|length > 0 %}
<h1>{{ title }}</h1>
{% endif %}
{# Imprime el valor de título (y las etiquetas h1) solo si
la variable título no está vacía.
#}
~~~

## Funciones

Twig dispone de un conjunto de funciones que podemos utilizar en las plantillas.
Por ejemplo, la función range, que ya utilizamos para establecer el rango de valores de la estructura
for anterior, devuelve un listado de números entre el mínimo y el máximo indicados. También permite
establecer el salto entre valores (tercer parámetro).

~~~
{% for i in range(0, 6, 2) %}
{{ i }},
{% endfor %}
{# Imprime 0, 2, 4, 6 #}
La función max devuelve el valor máximo de entre los facilitados.
{{ max(1, 3, 2) }}
{# Imprime 3 #}
~~~

La función max devuelve el valor máximo de entre los facilitados.

~~~
{{ max(1, 3, 2) }}
{# Imprime 3 #}
El conjunto completo de funciones disponibles por defecto se pueden consultar en:
https://twig.symfony.com/doc/2.x/functions/index.html
~~~


## COPIAS DE SEGURIDAD

* Copia de seguridad del sitio web

~~~
Comprimir archivo
cd public_html/sb
tar -cvzf backup.sb1.files.YYYYMMDD.tgz ./sb1

Descomprimir archivo
cd public_html/sb
tar -xvzf backup.sb1.files.YYYYMMDD.tgz
~~~
* Copia de seguridad de la BBDD.

~~~
Exportarla base de datos.
Copia de seguridad de la base de datos
mysqldump -u usuario -p basedatos > backup.sb1.database.YYYYMMDD.sql

Importar la base de datos.
Nota: elimina primero las tablas de la base de datos. Puedes hacerlo desde phpMyAdmin.
mysql -u usuario -p basedatos < backup.sb1.database.YYYYMMDD.sql
~~~

Más información: https://www.drupal.org/docs/7/backing-up-and-migrating-a-site/back-up-your-site-using-the-command-line

# ACTUALIZACIÓN DE DRUPAL

## ACTUALIZACIÓN DEL CORE Y MÓDULOS
Primero nos ubicaremos en la carpeta principal del sitio:
$ cd public_html/sb/sb1

Para comprobar si el núcleo o los módulos están desactualizados, además de verlo en los
informes estudiados, podemos lanzar el comando:
$ composer outdated drupal/*

Para actualizar tanto el núcleo como los módulos, podemos lanzar el comando:
$ composer update
Sin embargo, si hay muchas actualizaciones pendientes, corremos el riesgo de que se produzca algún
fallo. Por eso, se recomienda actualizar paso a paso.

Para actualizar solo el núcleo de Drupal, usaremos el comando:
$ composer update drupal/core-recommended --with-dependencies

Para actualizar un módulo en particular, usaremos el comando (por ejemplo, Pathauto):
$  composer update drupal/pathauto --with-dependencie


## ACTUALIZACIÓN DE LA BASE DE DATOS
$ drush updb
$ drush cr

# CONTROLLER

## Ejemplo de controlador donde le pasamos dos parámetros que van a ir a un formulario

En el ROUTING tenemos lo siguiente.
~~~
retiro_test.pasarvariables:
  path: '/pasar/{variable1}/{variable2}'
  defaults:
    _title: 'Pasar Variables'
    _controller: '\Drupal\retiro_test\Controller\RetiroTestController::pasar'
  requirements:
    _permission: 'access content'
~~~

En el controlador enganchamos directamente con el formulario y le pasamos dos variables
~~~
  public function pasar ($variable1, $variable2) {

    $args = [
      'cliente'    => $variable1,
      'producto'   => $variable2
    ];
    

    $texto = "CLIENTE: " . $args['cliente'] . " PRODUCTO. " . $args['producto'];

    //$form = $this->formBuilder()->getForm('Drupal\retiro_test\Form\PruebaForm', $args);
    $form = \Drupal::formBuilder()->getForm('Drupal\retiro_test\Form\PruebaForm', $args);

    // Utiliza la plantilla por defecto
    $build = [];

    $markup = ['#markup' => 'Esta es la página del formulario',];

    $build[] = $form;
    $build[] = $markup;

    return $build;

    // Utiliza una plantilla que queremos
    /*return [
      '#theme' => 'retiro_test_prueba',
      '#texto' => $this->t('Formulario de Protección de Datos'),
      '#formulario' => $form,
      ];*/

    
  } 
~~~









# COMANDOS DRUSH

## COMANDOS GENERALES
$ drush cr                 			para limpiar caché
$ drush en [módulo]        			para artivar un módulo
$ drush en muprespa_pruebas			ejemplo de activación de módulos.
$ drush pm-uninstall [módulo]			para desinstalar un módulo.
$ drush pm-uninstall muprespa_pruebas		ejemplo de desinstalación de módulos.
$ drush pmu

## MÓDULOS
drush
$ drush en [module] Ejemplo: drush en migrate_upgrade -> instalar módulo
$ drush dl [module] -> descargar módulo
$ drush pml -> LISTAR MÓDULOS
$ drush pmu [module] -> desinstalar
$ drush dis -> DESHABILITAR MÓDULO
$ drush en -> HABILITAR MÓDULO

## MIGRACIÓN

$ drush migrate-import bz_usuarios
$ drush migrate-import bz_productos --update
$ drush ms -> listar todas la migraciones

## ACTUALIZAR DRUPAL CON DRUSH
https://www.carloscarrascal.com/blog/actualizar-drupal-con-drush-desde-la-linea-de-comandos
$ drush up drupal



