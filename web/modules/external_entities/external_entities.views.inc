<?php

/**
 * @file
 * Provide views data for External Entities.
 */

use Drupal\views\Views;

/**
 * Implements hook_views_data_alter().
 * Add entity_browser_select field to all views whose base table is a Search API
 * index which depends on External Entities module.
 */
function external_entities_views_data_alter(&$data) {
  $entity_type_manager = \Drupal::entityTypeManager();
  $base_tables = [];
  $indexes = $entity_type_manager->getStorage('search_api_index')->loadByProperties([
    'status' => TRUE,
  ]);
  /* @var \Drupal\search_api\Entity\Index */
  foreach ($indexes as $key => $index) {
    $dependencies = $index->get('dependencies');
    if (isset($dependencies['module']) && in_array('external_entities', $dependencies['module'])) {
      $base_tables[] = 'search_api_index_' . $key;
    }
  }

  $entity_type_id = $entity_type_manager->getDefinition('external_entity_type')->getKey('id');
  $views = Views::getEnabledViews();
  foreach ($views as $view) {
    $base_table = $view->get('base_table');
    if (in_array($base_table, $base_tables) && !isset($data[$base_table]['entity_browser_select'])) {
      $data[$base_table]['entity_browser_select'] = [
        'title' => t('External entity browser bulk select form'),
        'help' => t('Add a form element that lets you use a view as a base to select entities in entity browser.'),
        'field' => [
          'id' => 'entity_browser_select',
          'real field' => $entity_type_id,
        ],
      ];
    }
  }
}