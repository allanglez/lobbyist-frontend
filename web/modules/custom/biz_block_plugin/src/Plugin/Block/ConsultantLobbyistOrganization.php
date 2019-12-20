<?php
  namespace Drupal\biz_block_plugin\Plugin\Block;
  
  use Drupal\Core\Block\BlockBase;
  use Drupal\Core\Block\BlockPluginInterface;
  use Drupal\Core\Form\FormBuilderInterface;
  use Drupal\Core\Form\FormStateInterface;
  use Drupal\Core\Access\AccessResult;
  use Drupal\Core\Cache\Cache;
  use Drupal\biz_webforms\BizWebformController;
  use Drupal\biz_block_plugin\Controller\GeneralFunctions;

/**
 * Provides a custom block.
 *
 * @Block(
 *   id = "info_consultant_org_custom_block",
 *   admin_label = @Translation("Consultant Lobbyist (Organization) block"),
 *   category = @Translation("Bizont custom block")
 * )
 */
  class ConsultantLobbyistOrganization extends BlockBase implements BlockPluginInterface{
    /**
     * {@inheritdoc}
     */

     /*
    public function access(AccountInterface $account, $return_as_object = FALSE) {
      return \Drupal\Core\Access\AccessResult::allowedIf($account->isAuthenticated());
    }

    /**
     * {@inheritdoc}
    */
    public function build() {
      $content = [];
      $email = \Drupal::currentUser()->getEmail();
      $lobbyist_endpoind = \Drupal::config('biz_block_plugin.settings')->get('in_house_lobbying_list');
      $header_lobbyist_endpoind = \Drupal::config('biz_block_plugin.settings')->get('header_in_house_lobbying');
      $header_response = GeneralFunctions::getHeadersTable($header_lobbyist_endpoind);
      $activities_response = GeneralFunctions::getAllData($lobbyist_endpoind); 
      
      $url = \Drupal::config('biz_lobbyist_registration.settings')->get('base_url') . '/json/user/' . \Drupal::currentUser()->getEmail();
      $get_organization = BizWebformController::get_endpoint($url, [], "GET", []);
      if($get_organization['code'] !== 400){
        $organization = json_decode($get_organization["message"])[0];
        $update_organization_action= '<div class="organization info-organization orange-header">'
      		.   '<div class="col-xs-6 no-padding">'
      		.     '<p><strong>'.t('Consultant information').'</strong></p>'
      		.   '</div>'
      		.   '<div class="col-xs-6 no-padding update-action">'
      		.     '<a class="update-action" href="user/' . \Drupal::currentUser()->id() . '/edit">'.t('Edit organization').'</a>'
      		.   '</div>'
      		. '</div>';
          $content []= array('#type' =>'markup','#markup'  => $update_organization_action);
          $organization_header = '<div class="header-view info-organization acount-body-info">'
      	 	.    '<div class="header-row no-padding row col-xs-12">'
      		.      '<div class="field col no-padding align-self-start col-xs-4">'
      		.        '<p class="field-title">'.t('Name') . '</p>' 
      		.        '<p>'.t($organization->consultant_name). '</p>'
      		.      '</div>'
      		.      '<div class="field col no-padding align-self-start col-xs-4">'
      		.        '<p class="field-title">'.t('Business name') . '</p> '
      		.        '<p>' . t($organization->field_business_name . '</p>'
      		.      '</div>'
      		.      '<div class="field col no-padding align-self-start col-xs-4">'
      		.        '<p class="field-title"></p>'
      		.        '<p></p>'
      		.      '</div>'
      		.    '</div>'
      		.    '<div class="header-row no-padding row col-xs-12">'
      		.      '<div class="field col no-padding align-self-start col-xs-4">'
      		.        '<p class="field-title">' . t('Address') . '</p> '
      		.        '<p>'.t($organization->field_street_address_address_line1) . '</p>'
      		.      '</div>'
      		.      '<div class="field col no-padding align-self-start col-xs-4">'
      		.        '<p class="field-title">' . t('City or town') . '</p> '
      		.        '<p>' . t($organization->field_street_address_locality) . '</p>'
      		.      '</div>'
      		.    '</div>'
          .    '<div class="header-row no-padding row col-xs-12">'
          .      '<div class="field col no-padding align-self-start col-xs-4">'
      		.        '<p class="field-title">' . t('Territory, province or state') . '</p> '
      		.        '<p>'.t($organization->field_street_address_administrative_area) . '</p>'
      		.      '</div>'
      		.      '<div class="field col no-padding align-self-start col-xs-4">'
      		.        '<p class="field-title">'.t('Zip code') . '</p> '
      		.        '<p>'.t($organization->field_street_address_postal_code) . '</p>'
      		.      '</div>'
      		.      '<div class="field col no-padding align-self-start col-xs-4">'
      		.        '<p class="field-title">'.t('Country') . '</p> '
      		.        '<p>'.t( $organization->field_street_address_country_code) . '</p>'
      		.      '</div>'
      		.    '</div>'
      		.  '</div>';
          $content[] = array( '#type' => 'markup', '#markup'  => $organization_header);
      }
      return $content;
    }
  
    /**
     * {@inheritdoc}
     */
     /*
    public function blockForm($form, FormStateInterface $form_state) {
      $form = parent::blockForm($form, $form_state);
  
      // Retrieve existing configuration for this block.
      $config = $this->getConfiguration();
  
      // Add a form field to the existing block configuration form.
      $form['fax_number'] = array(
        '#type' => 'textfield',
        '#title' => t('Fax number'),
        '#default_value' => isset($config['fax_number']) ? $config['fax_number'] : '',
      );
      
      return $form;
    }
    */
  
    /**
     * {@inheritdoc}
     */
     /*
    public function blockSubmit($form, FormStateInterface $form_state) {
      // Save our custom settings when the form is submitted.
      $this->setConfigurationValue('fax_number', $form_state->getValue('fax_number'));
    }
  */
  
    /**
     * {@inheritdoc}
     */
/*
    public function blockValidate($form, FormStateInterface $form_state) {
      $fax_number = $form_state->getValue('fax_number');
  
      if (!is_numeric($fax_number)) {
        $form_state->setErrorByName('fax_number', t('Needs to be an integer'));
      }
    }
*/

    public function getCacheMaxAge() {
      // If you want to disable caching for this block.
      return 0;
    }

  
  }
  