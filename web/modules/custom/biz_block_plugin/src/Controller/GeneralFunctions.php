<?php
  namespace Drupal\biz_block_plugin\Controller;
  use Drupal\biz_webforms\BizWebformController;
  use Drupal\Component\Render\FormattableMarkup; 
  use Drupal\Core\Render\Markup;
  /**
  *
  */
  class GeneralFunctions{
    /**
    * Sort function
    */
    static function tablemanager_sort(&$data, $field, $sort = "asc") {
    $code = "return strnatcasecmp(\$a['data']['$field'], \$b['data']['$field']);";
      if ($sort == "asc"){
        uasort($data, create_function('$a,$b', $code));
      }
      else {
        uasort($data, create_function('$b,$a', $code));
      }
    return;
    } // tablemanager_sort

  	 public static function getAllData($endpoind){
      $email = \Drupal::currentUser()->getEmail();
      $url_base = \Drupal::config('biz_lobbyist_registration.settings')->get('base_url');
      if(!empty($url_base) && !empty($endpoind) && !empty($email)){
        $url = $url_base . $endpoind  . "?_format=json&email=".$email ;
        $data = [];
        $headers = [];
        $method = "GET";
        $response = BizWebformController::get_endpoint($url, $data, "GET", [] );
        if($response['code'] !== "400"){
          return $response;
        }
        return FALSE;
      }
    }
    public static function getSubmission($endpoind, $id){
      $email = \Drupal::currentUser()->getEmail();
      $url_base = \Drupal::config('biz_lobbyist_registration.settings')->get('base_url');
      if(!empty($url_base) && !empty($endpoind) && !empty($id)){
        $url = $url_base . $endpoind  . "?_format=json&id=".$id ;
        $data = [];
        $headers = [];
        $method = "GET";
        $response = BizWebformController::get_endpoint($url, $data, "GET", [] );
        
        if(isset($response['code']) && $response['code'] !== "400"){
          return $response;
        }
        return FALSE;
      }
    }
    /*
     *
    */
    public static function getHeadersTable($endpoind){
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
              $fields[] = trim(strip_tags($taxonony_value->field));
              $count++;
            }
            return array('header' => $header, 'fields' =>$fields);
          }
        }
      }
      return FALSE; 
    }
    
    public static function generateTableRows($header_info, $rows){
      $activity_rows = [];
      if(is_array($rows) && !empty($rows)){
        foreach($rows as $row){
         $activity_row = new \StdClass();
          foreach($header_info['fields'] as $field){
  /*
            if($field == 'view'){
              $row->{$field} = "";
            }
  */        $row->{$field} = isset($row->{$field}) ? $row->{$field} : "" ;
            if(strpos($row->{$field}, "</a>") === FALSE){
              $activity_row->{$field} = $row->{$field};
            }else{
              $activity_row->{$field} = Markup::create($row->{$field}) ;
            }
            
          }
          $activity_rows[] = array ('data' => $activity_row);
        }
      }
      return $activity_rows;
              
    }
  
  }
  