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
 *   id = "inhouse_activities_block",
 *   admin_label = @Translation("In-house Activities block"),
 *   category = @Translation("Bizont custom block")
 * )
 */
  class InHouseActivities extends BlockBase implements BlockPluginInterface{
    
    public function getCacheMaxAge() {
      return 0;
    }
    /**
     * {@inheritdoc}
    */
    public function build() {
      $email = \Drupal::currentUser()->getEmail();
      $header_activities_endpoind = \Drupal::config('biz_block_plugin.settings')->get('header_activities');
      $activities_endpoind = \Drupal::config('biz_block_plugin.settings')->get('in_house_activities');
      $header_response = GeneralFunctions::getHeadersTable($header_activities_endpoind);
      $activities_response = GeneralFunctions::getAllData($activities_endpoind);  
      $activity_rows = [];
      $activity_row = [];
      $notifications = '<div class="organization info-organization purple-header new-notifications-header">'
            .   '<div class="col-xs-12">'
            .     '<p><strong>'.t('Notifications').'</strong></p>'
            .   '</div>'
            . '</div>'
            .'<div class=" new-notifications">'
            .   '<div class="col-xs-12">'
            .     '<p>'.t('You don’t have notifications.').'</p>'
            .   '</div>'
            . '</div>';
      $content[] = array('#type' => 'markup', '#markup' => $notifications);
      if($header_response && $activities_response){
        $rows_response = json_decode($activities_response['message']);
        $activity_rows = GeneralFunctions::generateTableRows($header_response, $rows_response);
        $title_lobbyist = '<div class="header-view">'
        .    '<div class="header-row orange no-padding row col-xs-12">'
        .      '<p>'.t('Lobbying activities you are associated with').' </p>'
      	.    '</div>'
        .  '</div>';
        $content[] = array( '#type' => 'markup', '#markup'  => $title_lobbyist  );
        $content[] =array(
          '#theme' => 'table',
          '#header' => $header_response['header'],
           '#rows' => $activity_rows
        ); 
        
      }
      $content[] = array(
        '#type' => 'markup',
        '#markup' => '<a type="button" href="/in-house-add-activity" class="btn btn-primary purple-button">'.t('ADD A LOBBYING ACTIVITY').'</a>',
      );
      $messages = '<div class="organization info-organization purple-header new-messages-header">'
            .   '<div class="col-xs-12">'
            .     '<p><strong>'.t('New messages').'</strong></p>'
            .   '</div>'
            . '</div>'
            .'<div class=" new-messages">'
            .   '<div class="col-xs-12">'
            .     '<p>'.t('You don’t have new messages').'.</p>'
            .   '</div>'
            . '</div>';
      $content[] = array('#type' => 'markup', '#markup' => $messages);
      return $content;
      
    }
  }
  