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
      ->save();
    parent::submitForm($form, $form_state);
  }

}