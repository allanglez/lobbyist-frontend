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
      
      $header_response = self::test_getHeadersTable($header_activities_endpoind);
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
        $title_lobbyist = '<div class="header-view">'
        .    '<div class="header-row no-padding row col-xs-12">'
        .      '<p>' .   t('Lobbying activities you are associated with') .'</p>'
      	.    '</div>'
        .  '</div>';
        $content[] = array( '#type' => 'markup', '#markup'  => $title_lobbyist  );
        
        $tableHeader = $header_response['header'];

/*
        $sort = tablesort_get_sort($tableHeader);
        $order = tablesort_get_order($tableHeader);
*/
        //GeneralFunctions::tablemanager_sort($activity_rows, $order['sql'], $sort);

        
        $content[] = array('#theme' => 'table',//'views_view_table',
          '#header' => $tableHeader,
          '#rows' => $activity_rows);
      }
      $content[] = array(
        '#type' => 'markup',
        '#markup' => '<a type="button" href="/consultant-add-activity" class="btn btn-primary purple-button">ADD A LOBBYING ACTIVITY</a>',
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
    public static function test_getHeadersTable($endpoind){
      $url_base = \Drupal::config('biz_lobbyist_registration.settings')->get('base_url');
     
      if(!empty($url_base) && !empty($endpoind)){
        $url = $url_base . $endpoind  . '?_format=json' ;
        $data = [];
        $headers = [];
        $method = "GET";
        
        $response = BizWebformController::get_endpoint($url, $data, "GET", [] );
        if($response['code'] !== "400"){
          $taxonony= json_decode($response['message']);
          
          if(is_array($taxonony)){
            $count = 0;
            foreach($taxonony as $taxonony_value){
              $taxonony_value->title = $taxonony_value->title == "empty" ? "" : $taxonony_value->title;
              $header [$count]['data']= trim(strip_tags($taxonony_value->title));
              $header[$count]['field'] = trim(strip_tags($taxonony_value->field));
              //$header[$count]['class'][] = 'custom-img-sort'; 
              /*
              $header[$count]['url'] = '?order='.trim(strip_tags($taxonony_value->field)).'&sort=asc';
              $header[$count]['class'][] = 'custom-sort';
              
*/
              
              $fields[] = trim(strip_tags($taxonony_value->field));
              $count++;
            }
            return array('header' => $header, 'fields' =>$fields);
          }
        }
      }
      return FALSE; 
    }
  }
  