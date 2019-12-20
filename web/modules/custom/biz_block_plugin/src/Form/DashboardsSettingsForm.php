<?php
namespace Drupal\biz_block_plugin\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure example settings for this site.
 */
class DashboardsSettingsForm extends ConfigFormBase {
    /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'biz_block_plugin.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'biz_block_plugin_admin_settings';
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
    
    $form['header_activities'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Header for Activities Table'),
      '#default_value' => $config->get('header_activities'),
    ];

    $form['in_house_activities'] = [
      '#type' => 'textfield',
      '#title' => $this->t('In-house Activities'),
      '#default_value' => $config->get('in_house_activities'),
    ];
    
    $form['in_house_activity'] = [
      '#type' => 'textfield',
      '#title' => $this->t('In-house Activity'),
      '#default_value' => $config->get('in_house_activity'),
    ];
    $form['in_house_activity_format'] = [
      '#type' => 'textarea',
      '#title' => $this->t('In-house Activity Format'),
      '#default_value' => $config->get('in_house_activity_format'),
    ];
    $form['header_in_house_lobbying'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Header for Lobbyist Table'),
      '#default_value' => $config->get('header_in_house_lobbying'),
    ];
    
    $form['in_house_lobbying_list'] = [
      '#type' => 'textfield',
      '#title' => $this->t('In-house Lobbying(List)'),
      '#default_value' => $config->get('in_house_lobbying_list'),
    ];
    
    $form['in_house_lobbying_single'] = [
      '#type' => 'textfield',
      '#title' => $this->t('In-house Lobbying(Single)'),
      '#default_value' => $config->get('in_house_lobbying_single'),
    ]; 
    
    $form['header_consultant_activities'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Header for Consultant Activities'),
      '#default_value' => $config->get('header_consultant_activities'),
    ]; 
    
    $form['consultant_activities'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Consultant Activities'),
      '#default_value' => $config->get('consultant_activities'),
    ]; 
    $form['consultant_activity'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Consultant Activity'),
      '#default_value' => $config->get('consultant_activity'),
    ];
    $form['consultant_activity_format'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Consultant Activity Format'),
      '#default_value' => $config->get('consultant_activity_format'),
    ]; 
    $form['current_user_info'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Current user'),
      '#default_value' => $config->get('current_user_info'),
    ]; 
    $form['all_activities'] = [
      '#type' => 'textfield',
      '#title' => $this->t('All Activities'),
      '#default_value' => $config->get('all_activities'),
    ]; 
    $form['header_all_activities'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Header for All Activities table'),
      '#default_value' => $config->get('header_all_activities'),
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
      ->set('header_activities', $form_state->getValue('header_activities'))
      ->set('in_house_activities', $form_state->getValue('in_house_activities'))
      ->set('in_house_activity', $form_state->getValue('in_house_activity'))
      ->set('header_in_house_lobbying', $form_state->getValue('header_in_house_lobbying'))
      ->set('in_house_lobbying_list', $form_state->getValue('in_house_lobbying_list'))
      ->set('in_house_lobbying_single', $form_state->getValue('in_house_lobbying_single'))
      ->set('header_consultant_activities', $form_state->getValue('header_consultant_activities')) 
      ->set('consultant_activities', $form_state->getValue('consultant_activities'))
      ->set('consultant_activity', $form_state->getValue('consultant_activity'))
      ->set('consultant_activity_format', $form_state->getValue('consultant_activity_format'))
      ->set('in_house_activity_format', $form_state->getValue('in_house_activity_format'))
      ->set('current_user_info', $form_state->getValue('current_user_info'))
      ->set('all_activities', $form_state->getValue('all_activities'))
      ->set('header_all_activities', $form_state->getValue('header_all_activities'))
      ->save();
    parent::submitForm($form, $form_state);
  }
}