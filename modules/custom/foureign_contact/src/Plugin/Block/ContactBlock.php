<?php

namespace Drupal\foureign_contact\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\FormBuilder;

/**
 * Provides a 'ContactBlock' block.
 *
 * @Block(
 *  id = "contact_block",
 *  admin_label = @Translation("Contact form block"),
 *  category = @Translation("4eign"),
 * )
 */
class contactBlock extends BlockBase implements ContainerFactoryPluginInterface {
  
  /**
   * Drupal\Core\Form\FormBuilder definition.
   *
   * @var \Drupal\Core\Form\FormBuilder
   */
  protected $formBuilder;
  /**
   * Constructs a new ContactBlock object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param string $plugin_definition
   *   The plugin implementation definition.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    FormBuilder $form_builder
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->formBuilder = $form_builder;
  }
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('form_builder')
    );
  }
  
  /**
   * {@inheritdoc}
   */
  public function build() {
    $uuid = $this->configuration['uuid'];
    $contact_form = $this->formBuilder->getForm('Drupal\foureign_contact\Form\ContactForm');
    $block_config = $this->configuration;
    $build = array(
      '#theme' => 'foureign_contact_block',
      '#uuid' => $uuid,
      '#form' => $contact_form,
      '#config' => $this->configuration,
    );
    
    $build['#attached']['drupalSettings']['contactBlockConfig'][$uuid] = $block_config;
    
    $build['#cache']['max-age'] = 0;
    
    return $build;
  }
  
}
