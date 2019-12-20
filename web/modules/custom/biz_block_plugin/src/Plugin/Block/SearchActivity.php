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
 *   id = "search_activity_custom_block",
 *   admin_label = @Translation("Search activity block"),
 *   category = @Translation("Bizont custom block")
 * )
 */
  class SearchActivity extends BlockBase implements BlockPluginInterface{
    
    public function getCacheMaxAge() {
      // If you want to disable caching for this block.
      return 0;
    }
    /**
     * {@inheritdoc}
    */
    public function build() {
      //Get params if exist and needs to be filtered
      $param = \Drupal::request()->query->all();
      $name_organization = trim(isset($param['organization-name']) ? $param['organization-name'] : '');
      $department = trim(isset($param['department']) ? $param['department'] : '') ;
      $query_param = "";
      $content = [];
      $url_base = \Drupal::config('biz_lobbyist_registration.settings')->get('base_url');
      $header_activities_endpoind = \Drupal::config('biz_block_plugin.settings')->get('header_all_activities');
      $activities_endpoind = \Drupal::config('biz_block_plugin.settings')->get('all_activities');
 
      $header_response = GeneralFunctions::getHeadersTable($header_activities_endpoind);
      
      $query_param = !empty($name_organization) ? '&combine='.$name_organization: '' ;
      $query_param .= !empty($department) ?  '':'';
      $url = $url_base . $activities_endpoind . "?_format=json" . $query_param; 
      $activities_response = BizWebformController::get_endpoint($url, [], 'GET', []);  
      
      $activity_rows = [];
      $activity_row = [];

      if($header_response && $activities_response){
        $rows_response = json_decode($activities_response['message']);
        $activity_rows = GeneralFunctions::generateTableRows($header_response, $rows_response);         
      }

      if(!empty($activity_rows)){
        $title_lobbyist = '<div class="row search-activity-header">'
        .    '<div class="header-row  col-xs-12">'
        .      '<p>' . t('Showing all records for all departments') . '</p>'
      	.    '</div>'
        .  '</div>';
        $content[] = array( '#type' => 'markup', '#markup'  => $title_lobbyist  );
        $content[] = array('#theme' => 'table',
          '#header' => $header_response['header'],
          '#rows' => $activity_rows);
      }
      return $content;
    }
  }
  