<?php
  namespace Drupal\biz_block_plugin\Plugin\Block;
  
  use Drupal\block\BlockBase;
  use Drupal\biz_webforms\BizWebformController;

  class InHouseActivities extends BlockBase {
  
   /**
   * {@inheritdoc}
   */
  public function build() {
  
  //$config = $this->getConfiguration();
    //$fax_number = isset($config['fax_number']) ? $config['fax_number'] : '';
    return array(
      '#type' => 'markup',
      '#markup' => "HI",
    );  
  }
  
  }
  