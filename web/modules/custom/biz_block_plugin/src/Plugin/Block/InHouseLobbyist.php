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
 *   id = "inhouselobbyist_custom_block",
 *   admin_label = @Translation("In-house Lobbyist (Organization) block"),
 *   category = @Translation("Bizont custom block")
 * )
 */
  class InHouseLobbyist extends BlockBase implements BlockPluginInterface{
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
        $update_organization_action= '<div class="organization info-organization">'
      		.   '<div class="col-xs-6 no-padding">'
      		.     '<p><strong>'.t(ORGANIZATION).'</strong></p>'
      		.   '</div>'
      		.   '<div class="col-xs-6 no-padding update-action">'
      		.     '<a class="update-action" href="user/' . \Drupal::currentUser()->id() . '/edit">'.t('Edit organization').'</a>'
      		.   '</div>'
      		. '</div>';
          $content []= array('#type' =>'markup','#markup'  => $update_organization_action);
          $organization_header = '<div class="header-view info-organization">'
      	 	.    '<div class="header-row no-padding row col-xs-12">'
      		.      '<div class="field col no-padding align-self-start col-xs-4">'
      		.        '<p class="field-title">' . t('LEGAL ORGANIZATION') . '</p>' 
      		.        '<p>' . t($organization->field_legal_organization) . '</p>'
      		.      '</div>'
      		.      '<div class="field col no-padding align-self-start col-xs-4">'
      		.        '<p class="field-title">' . t('OPERATING ORGANIZATION') . '</p> '
      		.        '<p>' . t($organization->field_operating_organization) . '</p>'
      		.      '</div>'
      		.      '<div class="field col no-padding align-self-start col-xs-4">'
      		.        '<p class="field-title"></p>'
      		.        '<p></p>'
      		.      '</div>'
      		.    '</div>'
      		.    '<div class="header-row no-padding row col-xs-12">'
      		.      '<div class="field col no-padding align-self-start col-xs-4">'
      		.        '<p class="field-title">' . t('ADDRESS') . '</p> '
      		.        '<p>' . t($organization->field_street_address_address_line1) . '</p>'
      		.      '</div>'
      		.      '<div class="field col no-padding align-self-start col-xs-4">'
      		.        '<p class="field-title">' . t('CITY OR TOWN') . '</p> '
      		.        '<p>' . t($organization->field_street_address_locality) . '</p>'
      		.      '</div>'
      		.    '</div>'
          .    '<div class="header-row no-padding row col-xs-12">'
          .      '<div class="field col no-padding align-self-start col-xs-4">'
      		.        '<p class="field-title">' . t('TERRITORY, PROVINCE OR STATE') . '</p> '
      		.        '<p>' . t($organization->field_street_address_administrative_area) . '</p>'
      		.      '</div>'
      		.      '<div class="field col no-padding align-self-start col-xs-4">'
      		.        '<p class="field-title">' . t('ZIP CODE') . '</p> '
      		.        '<p>' . t($organization->field_street_address_postal_code) . '</p>'
      		.      '</div>'
      		.      '<div class="field col no-padding align-self-start col-xs-4">'
      		.        '<p class="field-title">' . t('COUNTRY') . '</p> '
      		.        '<p>' . t($organization->field_street_address_country_code) . '</p>'
      		.      '</div>'
      		.    '</div>'
      		.  '</div>';
          $content[] = array( '#type' => 'markup', '#markup'  => $organization_header);
      }
      if($header_response && $activities_response) {
        $rows_response = json_decode($activities_response['message']);
        $base_href = '/in-house-in-organization-edit?';
        
        foreach ($rows_response as $key => $data) {
	        foreach($data as $field => $value) { 
		        if ($field != 'actions') {
	            		$base_href .=  $field . '=' . $value . '&'; 
	            }
	        }
	        $data->actions = str_replace('[[custom_href]]', $base_href, $data->actions);
        }
                
        $activity_rows = GeneralFunctions::generateTableRows($header_response, $rows_response); 
      }
      


      
      if(!empty($activity_rows)) {    
        $title_lobbyist = '<div class="header-view">'
        .    '<div class="header-row orange no-padding row col-xs-12">'
        .      '<p>'.t('IN-HOUSE LOBBYIST').' </p>'
      	.    '</div>'
        .  '</div>';
        $content[] = array( '#type' => 'markup', '#markup'  => $title_lobbyist  );
        $content[] = array(
          '#theme' => 'table',
          '#header' => $header_response['header'],
          '#rows' => $activity_rows
        );
      }
      $content[] = array(
        '#type' => 'markup',
        '#markup' => '<a type="button" href="/add_an_in_house_lobbyust_to_your" class="btn btn-primary purple-button">'.t('ADD ADITIONAL IN-HOUSE LOBBYIST TO THE ORGANIZATION').'</a>',
      );
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
  