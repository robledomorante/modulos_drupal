<?php

namespace Drupal\api_rest_block_bdns_content\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;

class getContentUrl
{



    protected $httpClient;


    protected $direccionWeb;


    public function __construct(ClientInterface $http_client)
    {
        $this->httpClient = $http_client;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container)
    {
        return new static(
            $container->get('http_client')
        );
    }

    public function getContent($dirweb)
    {
        $this->direccionWeb = $dirweb;

        try {
            $request = $this->httpClient->request('GET', $this->direccionWeb);
            $posts = json_decode($request->getBody()->getContents(), true);
        } catch (GuzzleException $error) {
            \Drupal::messenger()->addMessage(t('Failed to connect to the given address'));
            return FALSE;
        }

        return $posts;
    }

    public function contenidoGeneral($form, $form_state, $orden, $guardarCache)
    {
        $element = $form_state->getTriggeringElement();

        dpm($element['#value']);
       
        $values = $form_state->getValues();

        // Filtro Título convocatoria
        $tituloconv = $values['tituloconv'];

        // Filtro Administración
        $aestadolist = $values['aestadolist'];
        $cautonomaslist = $values['cautonomaslist'];

        if ($aestadolist) {
            array_push($orden, $aestadolist);
        }
        if ($cautonomaslist) {
            array_push($orden, $cautonomaslist);
        }


        $adminest = $aestadolist ? $aestadolist : $cautonomaslist;


        // $adminest 

        dpm("cid: " . $adminest);

        // Filtro mrr
        $mrr = $values['mrr'];

        // Filtro Fecha desde
        $fechadesde = $values['fecha-desde'];
        $fecha = explode('-', $fechadesde);
        $fecha = array_reverse($fecha);
        $fechadesde = implode('/', $fecha);

        // Filtro Fecha hasta
        $fechahasta = $values['fecha-hasta'];
        $fecha = explode('-', $fechahasta);
        $fecha = array_reverse($fecha);
        $fechahasta = implode('/', $fecha);

        // Filtro Instrumentos de Ayuda
        $iayuda = $values['iayudalist'];

        // Filtro Finalidad del gasto
        $finalidad = $values['finalidadlist'];


        $cid = 'bdns_cache:' . $fechadesde . '-' . $fechahasta . '-' . $mrr . '-' . $adminest . '-' . $iayuda . '-' . $finalidad . '-' . $tituloconv;
        $data = NULL;
        $from_cache = FALSE;

        if ($cache = $guardarCache->get($cid)) {
            $data = $guardarCache->data;
            $from_cache = TRUE;
        } else {
            dpm("cache: " . $adminest);
            $data = $this->querybdns($fechadesde, $fechahasta, $mrr, $adminest, $iayuda, $finalidad, $tituloconv);
            $guardarCache->set($cid, $data);
        }



        return $data;
    }

    public function querybdns($fechadesde, $fechahasta, $mrr, $adminest, $iayuda, $finalidad, $tituloconvocatoria)
    {      


        $urlbase = 'http://www.infosubvenciones.es/bdnstrans/GE/es/api/v2.1/listadoconvocatoria?';

        $direccion = $urlbase . '&fecha-desde=' . $fechadesde . '&fecha-hasta=' . $fechahasta . '&mrr=' . $mrr . '&administracion=' . $adminest
            . '&instrumento=' . $iayuda . '&finalidad=' . $finalidad . '&titulo-contiene=' . $tituloconvocatoria . '&page-size=10';

        $resultado = $this->getContent($direccion);

        $pintres = '<div id="ajax-content">';

        dpm($direccion);

        $convocatorias = $resultado[0]['convocatorias'];

        $convoca = [];
        // Listado convocatorias por titulo

        if ($convocatorias) {

            // vemos todas las convocatorias que tenemos y contruimos un array con ello.
            $convoca = self::allConvocatorias($convocatorias);
            // pintamos las convocatorias
            $pintres .= '<div class="convocatorias">';
            $pintres .= self::pintarConvocatorias($convoca);
            $pintres .= '</div>';
        } else {
            $pintres .= '<p class="sin-resultados">No hay resultados</p>';
        }

        return $pintres;

        
    }

    private function allConvocatorias($convocatorias)
    {
        $conv = [];
        for ($i = 0; $i < count($convocatorias); $i++) {
            $conv[$i]['organo'] = $convocatorias['convocatoria - orden ' . ($i + 1)]["desc-organo"];
            $conv[$i]['titulo'] = $convocatorias['convocatoria - orden ' . ($i + 1)]["titulo"];
            $conv[$i]['descripcion'] = $convocatorias['convocatoria - orden ' . ($i + 1)]["descripcionBR"];
            $conv[$i]['enlace'] = $convocatorias['convocatoria - orden ' . ($i + 1)]["URLespBR"];
            $conv[$i]['convocatoria'] = $convocatorias['convocatoria - orden ' . ($i + 1)]["permalink-convocatoria"];
            $conv[$i]['concesiones'] = $convocatorias['convocatoria - orden ' . ($i + 1)]["permalink-concesiones"];
        }

        return $conv;
    }

    private function pintarConvocatorias($convoca)
    {
        $pintres = "";
        for ($i = 0; $i < count($convoca); $i++) {
            $pintres .= '<div class="convoca">';
            $pintres .= '<h3 class="convoca__titulo">TÍTULO: ' . $convoca[$i]['titulo'] . '</h3>';
            $pintres .= '<p class="convoca__organo">ÓRGANO: ' . $convoca[$i]['organo'] . '</p>';
            $pintres .= '<p class="convoca__descripcion">DESCRIPCIÓN: ' . $convoca[$i]['descripcion'] . '</p>';
            $pintres .= '<p class="convoca__enlace">ENLACE: <a href="' . $convoca[$i]['enlace'] . '">' . $convoca[$i]['enlace'] . '</a></p>';
            $pintres .= '<p class="convoca__convocatoria">CONVOCATORIA: <a href="' . $convoca[$i]['convocatoria'] . '">' . $convoca[$i]['convocatoria'] . '</a></p>';
            $pintres .= '<p class="convoca__convocatoria">CONCESIONES: <a href="' . $convoca[$i]['concesiones'] . '">' . $convoca[$i]['concesiones'] . '</a></p>';
            $pintres .= '</div>';
        }

        return $pintres;
    }
}
