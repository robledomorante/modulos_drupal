<?php

namespace Drupal\api_rest_block_bdns_filter\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Form\FormBuilderInterface;
use GuzzleHttp\Exception\RequestException;
use Drupal\api_rest_block_bdns_content\Services\getContentUrl;

/**
 * Provides an example block.
 *
 * @Block(
 *   id = "api_rest_block_bdns_filter",
 *   admin_label = @Translation("Filter Block"),
 *   category = @Translation("api_rest_block_bdns")
 * )
 */
class FilterBlock extends BlockBase implements ContainerFactoryPluginInterface {

  protected $currentRouteMatch;
  protected $formBuilder;
  protected $getcontenturl;

  public function __construct(array $configuration,
      $plugin_id,
      $plugin_definition,
      RouteMatchInterface $current_route_match,
      FormBuilderInterface $form_builder,
      getContentUrl $getcontenturl) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->currentRouteMatch = $current_route_match;
    $this->formBuilder = $form_builder;
    $this->getcontenturl = $getcontenturl;
  }

  public static function create(ContainerInterface $container,
      array $configuration,
      $plugin_id,
      $plugin_definition) {
    return new static(
        $configuration,
        $plugin_id,
        $plugin_definition,
        $container->get('current_route_match'),
        $container->get('form_builder'),
        $container->get('api_rest_block_bdns_content.getcontenturl')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $form = $this->formBuilder->getForm('Drupal\api_rest_block_bdns_filter\Form\MenuFormSpace');
    return $form;
  }

}
