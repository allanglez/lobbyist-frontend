<?php
namespace Drupal\biz_lobbyist_registration\Form;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
* Configure example settings for this site.
*/
class RegistrationSettingsForm extends ConfigFormBase {

  /** 
  * Config settings.
  *
  * @var string
  */
  const SETTINGS = 'biz_lobbyist_registration.settings';

  /** 
  * {@inheritdoc}
  */
  public function getFormId() {
    return 'biz_lobbyist_registration_admin_settings';
  }

  /** 
  * {@inheritdoc}
  */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /** 
  * {@inheritdoc}
  */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);
    
    $form['validate_email_url_api'] = [
      '#type' => 'textfield',
      '#title' => $this->t('URL API for validate email'),
      '#default_value' => $config->get('validate_email_url_api'),
    ];  
    $form['registration_url_api'] = [
      '#type' => 'textfield',
      '#title' => $this->t('URL API for create account'),
      '#default_value' => $config->get('registration_url_api'),
    ]; 
    $form['in_house_lobbyist_welcome_mail'] = [
      '#type' => 'textarea',
      '#title' => $this->t('URL API for create account'),
      '#default_value' => $config->get('in_house_lobbyist_welcome_mail'),
    ];  
    $form['consultant_lobbyist_welcome_mail'] = [
      '#type' => 'textarea',
      '#title' => $this->t('URL API for create account'),
      '#default_value' => $config->get('consultant_lobbyist_welcome_mail'),
    ];

    $form['base_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('BaseURL'),
      '#default_value' => $config->get('base_url'),
    ];  
    $form['json_path'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Json Path for user'),
      '#default_value' => $config->get('json_path'),
    ];  
    $form['add_an_in_house_lobbyust_button_text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Add an in house lobbyust button text'),
      '#default_value' => $config->get('add_an_in_house_lobbyust_button_text'),
    ];  
    
    
    
    return parent::buildForm($form, $form_state);
  }

  /** 
  * {@inheritdoc}
  */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Retrieve the configuration.
    $this->configFactory->getEditable(static::SETTINGS)
      // Set the submitted configuration setting.
      ->set('registration_url_api', $form_state->getValue('registration_url_api'))
      ->set('validate_email_url_api', $form_state->getValue('validate_email_url_api'))
      ->set('in_house_lobbyist_welcome_mail', $form_state->getValue('in_house_lobbyist_welcome_mail'))
      ->set('consultant_lobbyist_welcome_mail', $form_state->getValue('consultant_lobbyist_welcome_mail'))
      ->set('add_an_in_house_lobbyust_button_text', $form_state->getValue('add_an_in_house_lobbyust_button_text'))
      ->set('json_path', $form_state->getValue('json_path'))
      ->set('base_url', $form_state->getValue('base_url'))
      ->save();
    parent::submitForm($form, $form_state);
  }
}