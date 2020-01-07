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
 *   id = "consultant_act_block",
 *   admin_label = @Translation("Consultant Activities block"),
 *   category = @Translation("Bizont custom block")
 * )
 */
  class ConsultantActivities extends BlockBase implements BlockPluginInterface{

    /**
     * {@inheritdoc}
    */
    public function build() {
      $email = \Drupal::currentUser()->getEmail();
      $header_activities_endpoind = \Drupal::config('biz_block_plugin.settings')->get('header_activities');
      $activities_endpoind = \Drupal::config('biz_block_plugin.settings')->get('consultant_activities');
      
      $header_response = GeneralFunctions::getHeadersTable($header_activities_endpoind, 'header-orange');
      $activities_response = GeneralFunctions::getAllData($activities_endpoind);  
      $activity_rows = [];
      $activity_row = [];
      if($header_response && $activities_response){
        $rows_response = json_decode($activities_response['message']);
        $activity_rows = GeneralFunctions::generateTableRows($header_response, $rows_response); 
      }
      $notifications = '<div class=" organization info-organization purple-header new-notifications-header">'
            .   '<div class="col-xs-12">'
            .     '<p><strong>' .t('Notifications').'</strong></p>'
            .   '</div>'
            . '</div>'
            .'<div class=" new-notifications">'
            .   '<div class="col-xs-12">'
            .     '<p>You don’t have notifications.</p>'
            .   '</div>'
            . '</div>';
      $content[] = array('#type' => 'markup', '#markup' => $notifications);
      if(!empty($activity_rows)){
        $content[] = array(
          '#theme' => 'custom_table',
          '#header' => $header_response['header'],
          '#rows' => $activity_rows,
          '#empty'=> "You don't have activities.",
          '#caption' => t('Lobbying activities you are associated with'),
          '#attributes' => ['id' => 'consultant-activities', 'class' => 'table-orange-header general-lobbyist-tables']
        );
      }
      $content[] = array(
        '#type' => 'markup',
        '#markup' => '<a type="button" href="/consultant-account-home/consultant-add-activity" class="btn btn-primary purple-button">ADD A LOBBYING ACTIVITY</a>',
      );
      $messages = '<div class="  organization info-organization purple-header new-messages-header">'
            .   '<div class="col-xs-12">'
            .     '<p><strong>New messages</strong></p>'
            .   '</div>'
            . '</div>'
            .'<div class=" new-messages">'
            .   '<div class="col-xs-12">'
            .     '<p>You don’t have new messages.</p>'
            .   '</div>'
            . '</div>';
      $content[] = array('#type' => 'markup', '#markup' => $messages);
      return $content;
    }
    public function getCacheMaxAge() {
      // If you want to disable caching for this block.
      return 0;
    }
  }
  