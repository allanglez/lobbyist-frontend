<?php
  namespace Drupal\biz_block_plugin\Plugin\Block;
  
  use Drupal\Core\Block\BlockBase;
  use Drupal\Core\Block\BlockPluginInterface;
  use Drupal\Core\Form\FormBuilderInterface;
  use Drupal\Core\Form\FormStateInterface;
  use Drupal\Core\Access\AccessResult;
  use Drupal\Core\Cache\Cache;



/**
 * Provides a custom block.
 *
 * @Block(
 *   id = "header_search_block",
 *   admin_label = @Translation("Header Search activity block"),
 *   category = @Translation("Bizont custom block")
 * )
 */
  class HeaderSearchBlock extends BlockBase implements BlockPluginInterface{
    public function getCacheMaxAge() {
        return 0;
    }
    /**
     * {@inheritdoc}
    */
    public function build() {
      $content = [];
      $title = '<div class="view-filters-search-activities">'
      .'	<form class="views-exposed-form container"  action="/search-activity" method="get" id="views-exposed-form-search-activities" accept-charset="UTF-8" data-drupal-form-fields="organization-name,department,edit-submit-search-activity">'
      .'		<div class="all-filters row">'
      .'			<div class="form-type-textfield form-item-organization-name form-group col-xs-12 col-md-5">'
      .'				<label for="organization-name" class="control-label">'.t('Search for a lobbyist by an individual’s name  or by the name of the organization.').'</label>'
      .'				<input data-drupal-selector="organization-name" class="form-text form-control" type="text" id="organization-name" name="organization-name" value="" size="30" maxlength="128">'
      .'  			</div>'
      .'			<div class="form-type-textfield form-item-department form-group col-xs-12 col-md-5">'
      .'				<label for="department" class="control-label">'.t('Department or corporation').':</label>'
      .'				<input data-drupal-selector="department" class="form-text form-control" type="text" id="department" name="department" value="" size="30" maxlength="128" data-original-title="This should be your business email address. An email address can only be used once to register. This is the email address our system uses to correspond with you.">'
      .'  			</div>'
      .'			<div data-drupal-selector="edit-actions" class="form-actions col-xs-12 col-md-2" id="edit-actions">'
      .'				<button data-drupal-selector="edit-submit-search-activity" class="button js-form-submit form-submit btn-primary btn icon-before" type="submit" id="edit-submit-search-activity" value="SEARCH" name=""><span class="icon glyphicon glyphicon-search" aria-hidden="true"></span>'.t('Search').' </button>'
      .'			</div>'
      .'		</div>'
      .'	</form>'
      .'</div>';
      $content[] = array( '#type' => 'markup', '#markup'  => $title, '#allowed_tags' => ['button','form', 'input', 'div', 'label']  );

      return $content;
    }  
  }
  