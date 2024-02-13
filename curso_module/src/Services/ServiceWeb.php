<?php

namespace Drupal\curso_module\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;


class ServiceWeb
{
    /**
     * The HTTP client to fetch the feed data with.
     *
     * @var \GuzzleHttp\ClientInterface
     */
    protected $httpClient;


    protected $direccionWeb;

    /**
     * Constructor for MymoduleServiceExample.
     *
     * @param \GuzzleHttp\ClientInterface $http_client
     *   A Guzzle client object.
     */
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

    public function posts($dirweb)
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

}
