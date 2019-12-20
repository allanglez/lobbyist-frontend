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
use Symfony\Component\HttpFoundation\RequestStack;
/**
 * Provides a custom block.
 *
 * @Block(
 *   id = "in-house_single_act_block",
 *   admin_label = @Translation("In-House Activity block"),
 *   category = @Translation("Bizont custom block")
 * )
 */
  class InHouseActivity extends BlockBase implements BlockPluginInterface{
        
    public function getCacheMaxAge() {
      // If you want to disable caching for this block.
      return 0;
    } 
    /**
     * {@inheritdoc}
    */
    public function build(){
      $email = \Drupal::currentUser()->getEmail();
      
      $activity_format_endpoind = \Drupal::config('biz_block_plugin.settings')->get('in_house_activity_format');
      $activity_endpoind = \Drupal::config('biz_block_plugin.settings')->get('in_house_activity');
      $param = \Drupal::request()->query->all();
      $id = "0" ;
      if(isset($param['id'])){
        $id = $param['id'];
      }
      $activity_response = GeneralFunctions::getSubmission($activity_endpoind, $id);
      $activity_data = json_decode($activity_response['message']);
      $activity_data= $activity_data[0];
      $activity_email = isset($activity_data->mail) ? $activity_data->mail : "";
      $url = \Drupal::config('biz_lobbyist_registration.settings')->get('base_url') . '/json/user/' . $activity_email;
      $get_organization = BizWebformController::get_endpoint($url, [], "GET", []);
      
      if($get_organization['code'] !== 400){
        $organization = json_decode($get_organization["message"])[0];
        $edit_organization = "";
        if($email === $activity_email){
          $edit_organization = '<a class="update-action" href="user/' . \Drupal::currentUser()->id() . '/edit">' .   t('Edit organization').'</a>';
        }
        
        $update_organization_action= '<div class="organization info-organization orange-header">'
            .   '<div class="col-xs-6 no-padding">'
            .     '<p><strong>' .   t('Organization').'</strong></p>'
            .   '</div>'
            .   '<div class="col-xs-6 no-padding update-action">'
            .    $edit_organization
            .   '</div>'
            . '</div>';
        $content[] = array('#type' => 'markup', '#markup' => $update_organization_action);
        $organization_header = '<div class="header-view info-organization">'
            .    '<div class="header-row no-padding row col-xs-12">'
            .      '<div class="field col no-padding align-self-start col-xs-4">'
            .        '<p class="field-title">' . t('Legal organization') . '</p>' 
            .        '<p>' . t($organization->field_legal_organization) . '</p>'
            .      '</div>'
            .      '<div class="field col no-padding align-self-start col-xs-4">'
            .        '<p class="field-title">' . t('Operating organization') . '</p> '
            .        '<p>' . t($organization->field_operating_organization) . '</p>'
            .      '</div>'
            .      '<div class="field col no-padding align-self-start col-xs-4">'
            .        '<p class="field-title"></p>'
            .        '<p></p>'
            .      '</div>'
            .    '</div>'
            .    '<div class="header-row no-padding row col-xs-12">'
            .      '<div class="field col no-padding align-self-start col-xs-4">'
            .        '<p class="field-title">' . t('Address') . '</p> '
            .        '<p>' . t($organization->field_street_address_address_line1) . '</p>'
            .      '</div>'
            .      '<div class="field col no-padding align-self-start col-xs-4">'
            .        '<p class="field-title">' . t('City or town') . '</p> '
            .        '<p>' . t($organization->field_street_address_locality) . '</p>'
            .      '</div>'
            .    '</div>'
            .    '<div class="header-row no-padding row col-xs-12">'
            .      '<div class="field col no-padding align-self-start col-xs-4">'
            .        '<p class="field-title">' . t('Territory, province or state') . '</p> '
            .        '<p>' . t($organization->field_street_address_administrative_area) . '</p>'
            .      '</div>'
            .      '<div class="field col no-padding align-self-start col-xs-4">'
            .        '<p class="field-title">' . t('Zip code') . '</p> '
            .        '<p>' . t($organization->field_street_address_postal_code) . '</p>'
            .      '</div>'
            .      '<div class="field col no-padding align-self-start col-xs-4">'
            .        '<p class="field-title">' . t('Country') . '</p> '
            .        '<p>' . t($organization->field_street_address_country_code) . '</p>'
            .      '</div>'
            .    '</div>'
            .  '</div>';
          $content[] = array( '#type' => 'markup', '#markup'  => $organization_header);
      }
     
      if(isset($activity_data) && !empty($activity_data)){
	    $base_href = '/in-house-add-activity-edit?';
        foreach($activity_data as $field => $value) { 
            $base_href .=  $field . '=' . $value . '&'; 
        }
        foreach($activity_data as $field => $value){
          $activity_format_endpoind = str_replace('{{'. $field .'}}', $value, $activity_format_endpoind); 
        }
        
        $edit_activity = "";
        if($email === $activity_email){
          $edit_activity = '<a class="update-action" href="{{custom_href}}">' . t('Edit activity') . '</a>';
          $edit_activity = str_replace('{{custom_href}}', $base_href, $edit_activity);
        }
        
        $title_lobbyist = '<div class="header-view purple-header">'
        .    '<div class="header-row col-xs-6">'
        .       '<p>' . t('Lobbying activity') . ' </p>'
        .     '</div>'
        .     '<div class="col-xs-6 no-padding update-action">'
        .      $edit_activity  
        .     '</div>'
        .  '</div>';
/*
        
        $lobbyist_endpoind = \Drupal::config('biz_block_plugin.settings')->get('in_house_lobbying_single');
        $url = \Drupal::config('biz_lobbyist_registration.settings')->get('base_url') . $lobbyist_endpoind . '?_format=json&id='.$activity_data->in_house_lobbyist;
        $lobbyist_response = BizWebformController::get_endpoint($url, [], "GET", []);
        $lobbyist_data = is_array(json_decode($lobbyist_response['message'])) ?  json_decode($lobbyist_response['message'])[0] : NULL;
        
        if(isset($lobbyist_data->last_name) ){
          $in_house_lobbyist_header= '<div class="orange-header lobbyist-info">'
            .   '<div class="col-xs-12 no-padding">'
            .     '<p><strong>In-house lobbyist</strong></p>'
            .   '</div>'
            . '</div>';
          $content[] = array('#type' => 'markup', '#markup'  => $in_house_lobbyist_header);
          $in_house_lobbyist = '<div class="header-view info-organization">'
            .    '<div class="header-row no-padding row col-xs-12">'
            .      '<div class="field col no-padding align-self-start col-xs-4">'
            .        '<p class="field-title">' . 'Name' . '</p>' 
            .        '<p>' . $lobbyist_data->first_name . ' ' .$lobbyist_data->last_name . '</p>'
            .      '</div>'
            .      '<div class="field col no-padding align-self-start col-xs-4">'
            .        '<p class="field-title">' . 'Position' . '</p> '
            .        '<p>' . $lobbyist_data->position . '</p>'
            .      '</div>'
            .    '</div>'
            .  '</div>';
          $content[] = array('#type' => 'markup', '#markup'  => $in_house_lobbyist);
        }
*/
        $content[] = array('#type' => 'markup', '#markup'  => $title_lobbyist);
        $content[] = array('#type' => 'markup', '#markup'  => $activity_format_endpoind);
        return $content;
      }
    }

  }
  