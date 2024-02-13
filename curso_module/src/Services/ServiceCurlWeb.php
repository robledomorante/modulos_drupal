<?php

namespace Drupal\curso_module\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Service description.
 */

class ServiceCurlWeb
{


  protected $url;

  protected $data;


  /**
   * Method description.
   */
  public function guardarDocumentoCurl($url, $archivo)
  {
    $this->url = $url;

    $ch = curl_init($this->url);

    $ruta = 'sites/default/files/json/' . $archivo . '.json';

    $fp = fopen($ruta, "w");

    curl_setopt_array($ch, array(
      CURLOPT_TIMEOUT => 3600,
      CURLOPT_FILE => $fp
    ));

    

    //curl_setopt($ch, CURLOPT_FILE, $fp);
    //curl_setopt($ch, CURLOPT_HEADER, 0);

    curl_exec($ch);
    curl_close($ch);

    $codigo = curl_getinfo($ch, $opt = CURLINFO_HTTP_CODE );
     

    fclose($fp);
    return $codigo;
  }

  public function servicecurl($url, $data)
  {
    $this->url = $url;
    // array con los datos del JSON
    $this->data = $data;



    //create a new cURL resource
    $ch = curl_init($this->url);

    //setup request to send json via POST

    //$payload = json_encode($this->data);

    //return response instead of outputting
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Send request data using POST method
    //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

    //attach encoded JSON string to the POST fields
    //curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

    //set the content type to application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));



    //execute the POST request
    $result = curl_exec($ch);


    // close cURL resource
    curl_close($ch);


    // $control = "esto es una prueba de como podemos hacer esto";

    return $result;
  }
}
