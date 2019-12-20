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
 *   id = "consultant_single_act_block",
 *   admin_label = @Translation("Consultant Activity block"),
 *   category = @Translation("Bizont custom block")
 * )
 */
  class ConsultantActivity extends BlockBase implements BlockPluginInterface{

    /**
     * {@inheritdoc}
    */
    public function build() {
      $email = \Drupal::currentUser()->getEmail();
      $activity_format_endpoint = \Drupal::config('biz_block_plugin.settings')->get('consultant_activity_format');
      $activity_endpoint = \Drupal::config('biz_block_plugin.settings')->get('consultant_activity');
      $param = \Drupal::request()->query->all();
      $id = "0" ;
      if(isset($param['id'])){
        $id = $param['id'];
      }
      $activity_response = GeneralFunctions::getSubmission($activity_endpoint, $id);
      $activity_data = json_decode($activity_response['message']);
      $activity_data = $activity_data[0];
      $activity_email = isset($activity_data->mail) ? $activity_data->mail : "";
      $url = \Drupal::config('biz_lobbyist_registration.settings')->get('base_url') . '/json/user/' . $activity_email;
      $get_organization = BizWebformController::get_endpoint($url, [], "GET", []);
      if($get_organization['code'] !== 400){
    
        if($email === $activity_email){
          $edit_organization = '<a class="update-action" href="user/' . \Drupal::currentUser()->id() . '/edit">' .   t('Edit organization') .'</a>';
        }
        $organization = json_decode($get_organization["message"])[0];
        $update_organization_action= '<div class="organization info-organization">'
            .   '<div class="col-xs-6 no-padding">'
            .     '<p><strong>' .   t('Consultant lobbyist') .'</strong></p>'
            .   '</div>'
            .   '<div class="col-xs-6 no-padding update-action">'
            .     $edit_organization
            .   '</div>'
            . '</div>';
          $content []= array('#type' =>'markup','#markup'  => $update_organization_action);
          $organization_header = '<div class="header-view info-organization">'
            .    '<div class="header-row no-padding row col-xs-12">'
            .      '<div class="field col no-padding align-self-start col-xs-4">'
            .        '<p class="field-title">' . t('Name') . '</p>' 
            .        '<p>' .  t($organization->consultant_name) . '</p>'
            .      '</div>'
            .      '<div class="field col no-padding align-self-start col-xs-4">'
            .        '<p class="field-title">' . t('Business name') . '</p> '
            .        '<p>' .  t($organization->field_business_name) . '</p>'
            .      '</div>'
            .      '<div class="field col no-padding align-self-start col-xs-4">'
            .        '<p class="field-title"></p>'
            .        '<p></p>'
            .      '</div>'
            .    '</div>'
            .    '<div class="header-row no-padding row col-xs-12">'
            .      '<div class="field col no-padding align-self-start col-xs-4">'
            .        '<p class="field-title">' . t('Address') . '</p> '
            .        '<p>' .  t($organization->field_street_address_address_line1) . '</p>'
            .      '</div>'
            .      '<div class="field col no-padding align-self-start col-xs-4">'
            .        '<p class="field-title">' . t('City or town') . '</p> '
            .        '<p>' .  t($organization->field_street_address_locality) . '</p>'
            .      '</div>'
            .    '</div>'
          .    '<div class="header-row no-padding row col-xs-12">'
          .      '<div class="field col no-padding align-self-start col-xs-4">'
            .        '<p class="field-title">' . t('Territory, province or state') . '</p> '
            .        '<p>' .  t($organization->field_street_address_administrative_area) . '</p>'
            .      '</div>'
            .      '<div class="field col no-padding align-self-start col-xs-4">'
            .        '<p class="field-title">' . t('Zip code') . '</p> '
            .        '<p>' . t( $organization->field_street_address_postal_code) . '</p>'
            .      '</div>'
            .      '<div class="field col no-padding align-self-start col-xs-4">'
            .        '<p class="field-title">' . t('Country') . '</p> '
            .        '<p>' . t($organization->field_street_address_country_code) . '</p>'
            .      '</div>'
            .    '</div>'
            .  '</div>';
          $content[] = array( '#type' => 'markup', '#markup'  => $organization_header);
      }

      if(!empty($activity_data)){
        $base_href = '/consultant-add-activity-edit?';
        foreach($activity_data as $field => $value) { 
            $base_href .=  $field . '=' . $value . '&'; 
        }
        $edit_activity = "";
        if($email === $activity_email){
          $edit_activity = '<a class="update-action" href="{{custom_href}}">' .   t('Edit activity') .'</a>';
          $edit_activity = str_replace('{{custom_href}}', $base_href, $edit_activity);  
        }
        $activity_format_endpoint = str_replace('{{edit_activity_link}}', $edit_activity, $activity_format_endpoint);
        foreach($activity_data as $field => $value) {
          $activity_format_endpoint = str_replace('{{'. $field .'}}', $value, $activity_format_endpoint); 
        }
        $content[] = array('#type' => 'markup', '#markup'  => $activity_format_endpoint);
        return $content;
      }
    }
    
    public function getCacheMaxAge() {
      // If you want to disable caching for this block.
      return 0;
    }
  }
  