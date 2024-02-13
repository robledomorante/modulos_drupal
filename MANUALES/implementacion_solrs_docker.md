# IMPLEMENTACIÓN DE SOLR EN DOCKER

## INSTALACIÓN DE SOLR CON DOCKER:

* docker-compose.yml

~~~
version: '3'
services:
  solr:
    image: solr
    ports:
     - "8983:8983"
    volumes:
      - data:/var/solr
    command:
      - solr-precreate
      - gettingstarted
volumes:
  data:

~~~


* Lanzamos el docker


~~~
docker-compose up -d

~~~

* Ejemplo en el virtualbox Ubuntu:

~~~
david@david-VirtualBox:~/proyectos/imagenes-docker/solrdata$ docker-compose up -d
Starting solrdata_solr_1 ... done

david@david-VirtualBox:~/proyectos/imagenes-docker/solrdata$ docker-compose ps -a


     Name                    Command               State                    Ports                  
---------------------------------------------------------------------------------------------------
solrdata_solr_1   docker-entrypoint.sh solr- ...   Up      0.0.0.0:8983->8983/tcp,:::8983->8983/tcp


david@david-VirtualBox:~/proyectos/imagenes-docker/solrdata$ docker ps -a

CONTAINER ID   IMAGE       COMMAND                  CREATED         STATUS                     PORTS                                       NAMES
c9f01d672c04   solr:8      "docker-entrypoint.s…"   7 weeks ago     Up 25 seconds              0.0.0.0:8983->8983/tcp, :::8983->8983/tcp   solrdata_solr_1
f37db7f0ffa0   solr:8      "docker-entrypoint.s…"   7 weeks ago     Exited (143) 7 weeks ago                                               solr_demo
8c2bdf02d37b   wordpress   "docker-entrypoint.s…"   19 months ago   Exited (0) 18 months ago                                               wp
c9ce46a9d655   mysql:5.7   "docker-entrypoint.s…"   19 months ago   Exited (0) 18 months ago                                               wp-mysql
~~~

~~~
david@david-VirtualBox:~/proyectos/drupal9$ docker ps -a
CONTAINER ID   IMAGE       COMMAND                  CREATED         STATUS                     PORTS                                       NAMES
c9f01d672c04   solr:8      "docker-entrypoint.s…"   4 days ago      Up 12 hours                0.0.0.0:8983->8983/tcp, :::8983->8983/tcp   solrdata_solr_1
~~~


* En el dockerfile tememos el apartado de command que podemos cambiar el gettingstarted por dev, desarrollo y cualquier nombre que queramos que se llame el servidor o el Core admin de Solr para indexar nuestro drupal.

Ejemplo: http://drupal9.local:8983/solr/#/

Vemos que funciona el servidor de forma correcta.

## INSTALACIÓN DEL MÓDULO DE DRUPAL SEARCH API
https://www.drupal.org/project/search_api_solr
composer require 'drupal/search_api_solr:^4.2'


# ERRORES DE CONFIGURACIÓN DE LA VISTAS.

Para sincronizar la configuración del módulo Solr de drupal con el servidor de Solr tenemos que copiar la siguiente directiva. En mi caso es es _DEFAULT, a tener en cuenta para luego configurar las VISTAS en el drupal.


docker cp /david/proyectos/drupal9/web/modules/contrib/search_api_solr/solr-conf/6.x/. container_solr:/opt/solr/server/solr/test_core/conf

CONTAINER ID   IMAGE       COMMAND                  CREATED         STATUS                     PORTS                                       NAMES
c9f01d672c04   solr:8      "docker-entrypoint.s…"   4 days ago      Up 12 hours                0.0.0.0:8983->8983/tcp, :::8983->8983/tcp   solrdata_solr_1

Ejemplos:

* Copiar de local a contenedor.
docker cp ./some_file CONTAINER:/work
* Copiar filas de contenedor a dirección local
docker cp CONTAINER:/var/logs/ /tmp/app_logs
* Copiar un fila desde el conenedor to stdout. Please note cp command produces a tar stream
docker cp CONTAINER:/var/logs/app.log - | tar x -O | grep "ERROR"

https://www.searchstax.com/docs/searchstax-cloud-drupal-8/



## ERRORES DE JAVASCRIPT Y CSS 

Example, change uasort($css, 'static::sort') to uasort($css, static::sort(...))
Example, change uasort($javascript, 'static::sort') to uasort($javascript, static::sort(...))
Example, change uasort($entities, 'static::sort') to uasort($entities, static::sort(...))

https://www.drupal.org/project/drupal/issues/3335226

## FUENTES
* https://solr.apache.org/guide/solr/latest/deployment-guide/solr-in-docker.html
* http://drupal9.local del virtualbox de ubuntu.
* http://drupal9.local:8983/solr/#/  servidor Solr de indexación
* https://www.drupal.org/project/search_api_solr  Compatibilidad de Solr con Drupal


## SI QUEREMOS OPERAR DENTRO DEL SISTEMA OPERATIVO DEL CONTENEDOR


* Si queremos operar con una terminar dentro del sistema operativo que hemos creado en el contenedor a través de la imagen tenemos que hacerlo de la siguiente manera:

~~~
$ docker exec -ti jenkins-test bash
~~~

* Si queremos ingresar como usuario root

~~~
$docker exec -u root -ti jenkins-test bash
$docker exec -u root -ti solr_solr_1 bash  // para entrar en solr
~~~

## DOCKER 

* Para averiguar la ip de un contenedor:

docker inspect 6a16ae40d5a2 | grep "IPAddress"

### DOCKER-COMPOSE

https://coffeebytes.dev/docker-compose-tutorial-con-comandos-en-gnu-linux/

* Compilar un archivo de servicios.
$ docker-compose build
* Si queremos especificar un archivo docker-compose en específico usamos la opción -f seguida del nombre del archivo.
$ docker-componse -f production.yml build
* Ejecutar docker-compose y sus servicios
$ docker-compose up -d
* Correr un comando dentro de un contenedor en ejecución
$ docker-compose exec app bash
* Detener y remover los servicios
$ docker-compose down
* Reiniciar los servicios
$ docker-compose restart servicio
* Detener los servicios sin removerlos
$ docker-compose stop
* Para ejecutar docker-compose stop a solo a un servicio. Recordar que es el servicio y no el contenedor por ejemplo servicio traefik, zookeeper, mailhog, etc

david@david-VirtualBox:~/proyectos/imagenes-docker/docker4drupal$ docker-compose ps
            Name                           Command                State                     Ports                 
------------------------------------------------------------------------------------------------------------------
docker_drupal10_crond           /docker-entrypoint.sh sudo ...   Up         9000/tcp                              
docker_drupal10_elasticsearch   /usr/local/bin/docker-entr ...   Exit 137                                         
docker_drupal10_mailhog         MailHog                          Up         1025/tcp, 8025/tcp                    
docker_drupal10_mariadb         /docker-entrypoint.sh mysqld     Up         3306/tcp                              
docker_drupal10_nginx           /docker-entrypoint.sh sudo ...   Up         80/tcp                                
docker_drupal10_php             /docker-entrypoint.sh sudo ...   Up         9000/tcp                              
docker_drupal10_pma             /docker-entrypoint.sh apac ...   Up         80/tcp                                
docker_drupal10_solr            /entrypoint.sh solr-foreground   Up         8983/tcp                              
docker_drupal10_traefik         /entrypoint.sh --api.insec ...   Up         0.0.0.0:8000->80/tcp,:::8000->80/tcp  
docker_drupal10_zookeeper       /docker-entrypoint.sh zkSe ...   Up         2181/tcp, 2888/tcp, 3888/tcp, 8080/tcp
david@david-VirtualBox:~/proyectos/imagenes-docker/docker4drupal$ docker-compose stop traefik
Stopping docker_drupal10_traefik ... done
david@david-VirtualBox:~/proyectos/imagenes-docker/docker4drupal$ docker-compose stop zookeeper
Stopping docker_drupal10_zookeeper ... done
david@david-VirtualBox:~/proyectos/imagenes-docker/docker4drupal$ docker-compose stop mailhog
Stopping docker_drupal10_mailhog ... done


$ docker-compose stop servicio
* Iniciar los servicios de docker-compose sin crearlos
$ docker-compose start
* Ejecutar sólo un servicio 
$ docker-compose start servicio
* Correr un comando dentro de un servicio
$ docker-compose run --rm django python manage.py migrate
* Ver los procesos
$ docker-compose ps
* Ver solo un proceso
$ docker-compose ps servicio
* Acceder a los procesos
$ docker-compose -f local.yml top
* Ver los procesos de un único servicio 
$ docker-compose top servicio
* Ver los logs
$ docker-compose -f production.yml logs


## ubuntu server

* Sólo está con docker para crear microservicios.
* usuario: drobledo
* contraseña: Drm***45

* Entrar en SOLR



https://github.com/drmfraterni/imagenes-docker.git
