<?php
namespace Drupal\biz_dashboard\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure example settings for this site.
 */
class DashboardSettingsForm extends ConfigFormBase {

  /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'biz_dashboard.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'biz_dashboard_admin_settings';
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
    
    $form['get_consultant_activities_url_api'] = [
      '#type' => 'textfield',
      '#title' => $this->t('URL API for get consultant activities'),
      '#default_value' => $config->get('get_consultant_activities_url_api'),
    ];  
    $form['get_in_house_activities_url_api'] = [
      '#type' => 'textfield',
      '#title' => $this->t('URL API for get in-house activities'),
      '#default_value' => $config->get('get_in_house_activities_url_api'),
    ]; 
     
    $form['get_in_house_lobbyist_into_organization_url_api'] = [
      '#type' => 'textfield',
      '#title' => $this->t('URL API for get in-house lobbyist in the organization'),
      '#default_value' => $config->get('get_in_house_lobbyist_in_organization_url_api'),
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
      ->set('get_consultant_activities_url_api', $form_state->getValue('get_consultant_activities_url_api'))
      ->set('get_in_house_activities_url_api', $form_state->getValue('get_in_house_activities_url_api'))
      ->set('get_in_house_lobbyist_into_organization_url_api', $form_state->getValue('get_in_house_lobbyist_into_organization_url_api'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}