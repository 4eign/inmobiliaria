<?php

namespace Drupal\foureign_contact\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Mail\MailManager;

/**
 * Class ContactForm.
 */
class ContactForm extends FormBase {

  /**
   * Drupal\Core\Mail\MailManager definition.
   *
   * @var \Drupal\Core\Mail\MailManager
   */
  protected $pluginManagerMail;
  
  /**
   * Constructs a new ContactForm object.
   * @param \Drupal\Core\Mail\MailManager $plugin_manager_mail
   */
  public function __construct(
    MailManager $plugin_manager_mail
  ) {
    $this->pluginManagerMail = $plugin_manager_mail;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.mail')
    );
  }


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'foureign_contact_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    
    $form['messages_container'] = array(
      '#type' => 'container',
      '#attributes' => [
        'id' => 'messages-container',
      ],
    );
  
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nombre'),
      '#attributes' =>array('placeholder' => t('Nombre')),
      '#maxlength' => 64,
    ];
    
    $form['phone_number'] = [
      '#type' => 'tel',
      '#title' => $this->t('Teléfono'),
      '#attributes' =>array('placeholder' => t('Teléfono')),
      '#maxlength' => 64,
    ];
    
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Correo electrónico'),
      '#attributes' =>array('placeholder' => t('Correo')),
      '#maxlength' => 64,
    ];
    
    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Mensaje'),
      '#attributes' =>array('placeholder' => t('Mensaje')),
      '#maxlength' => 300,
    ];
  
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Enviar'),
      '#ajax' => [
        'callback' => '::sendInformationCallback',
        'wrapper' => 'messages-container',
      ],
    ];

    return $form;
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
    //log values
    \Drupal::logger('luis')->error('entro al submit handler');
    
    // Display result.
    return $form;
    
  }
  
  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array
   */
  public function sendInformationCallback(array &$form, FormStateInterface $form_state) {
  
    try{
      $module = 'foureign_contact';
      $key = 'test';
      $to = \Drupal::config('system.site')->get('mail');
      
      $langcode = \Drupal::languageManager()->getCurrentLanguage()->getId();
      
      $params['name'] = $form_state->getValue('name');
      $params['email'] = $form_state->getValue('email');
      $params['phone_number'] = $form_state->getValue('phone_number');
      $params['description'] = $form_state->getValue('description');
      
      $send = true;
      
      $result = $this->pluginManagerMail->mail($module, $key, $to, $langcode, $params, NULL, $send);
      if ($result['result'] !== true) {
        \Drupal::logger('luis')->error('error enviando mensaje @value', ['@value' => (array)$result]);
        $this->messenger()->addError(t('There was a problem sending your message and it was not sent.'));
      }
      else {
        \Drupal::logger('luis')->error('Mensaje enviado correctamente, @value', ['@value' => (array)$result]);
        $this->messenger()->addStatus('Su mensaje ha sido enviado correctamente');
        /*
        $response = new AjaxResponse();
        $response->addCommand(new HtmlCommand(
          '#messages-container',
          '<div class="alert alert-success" role="alert">Su mensaje ha sido enviado correctamente
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>')
        );
        return $response;
        */
        $form_state->setRebuild();
        return $form;
        
        /*
        $message = new \stdClass();
        $message->mid = 1;
        $message->subject = 'este es el subject';
        $message->content = 'Mensaje enviado correctamente.';
        $response = new AjaxResponse();
        $response->addCommand(new DisplayMessageCommand($message));
        return $response;
        */
      }
    }catch(\Exception $e){
      \Drupal::logger('luis')->error('error en callback: @error', ['@error' =>  (array)$e->getMessage()]);
    }
  
    //return $form;
    //echo "successMessage('hello');";
    //echo "<script> successMessage('hello2'); </script>";
    
  }

}
