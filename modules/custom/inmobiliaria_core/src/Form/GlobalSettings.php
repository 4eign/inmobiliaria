<?php

namespace Drupal\inmobiliaria_core\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Datetime\Entity\DateFormat;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;

/**
 * Class GlobalSettings.
 *
 * @package Drupal\inmobiliaria_core\Form
 */
class GlobalSettings extends ConfigFormBase {
  
  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'inmobiliaria_core.settings',
    ];
  }
  
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'inmobiliaria_core_settings';
  }
  
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('inmobiliaria_core.settings');
    
    $form["#tree"] = true;
    $form['bootstrap'] = [
      '#type' => 'vertical_tabs',
      '#prefix' => '<h2><small>' . t('Configuración global') . '</small></h2>',
      '#weight' => -10,
      '#default_tab' => $config->get('active_tab'),
    ];
    
    $group = "code";
    
    $form[$group] = [
      '#type' => 'details',
      '#title' => $this->t('Código embebido'),
      '#group' => 'bootstrap'
    ];
  
    $form[$group]['script_head'] = array(
      '#type' => 'textarea',
      '#title' => $this->t("Contenido del script a incluir en el head"),
      '#description' => $this->t("Contenido del script a incluir en el head de la pagina"),
      '#default_value' => isset($config->get($group)['script_head']) ? $config->get($group)['script_head'] : '',
      '#rows' => 10,
    );
  
    $form[$group]['script_bottom'] = array(
      '#type' => 'textarea',
      '#title' => $this->t("Contenido del script a incluir en el bottom de la pagina"),
      '#description' => $this->t("Contenido del script a incluir en el bottom de la pagina"),
      '#default_value' => isset($config->get($group)['script_bottom']) ? $config->get($group)['script_bottom'] : '',
      '#rows' => 10,
    );
  
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }
  
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    
    $this->config('inmobiliaria_core.settings')
      ->set('code',$form_state->getValue('code') )
      ->save();
    
    return;
  }
  
}
