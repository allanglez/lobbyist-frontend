<?php

/**
 * Load services definition file.
 */
$settings['container_yamls'][] = __DIR__ . '/services.yml';

/**
 * Include the Pantheon-specific settings file.
 *
 * n.b. The settings.pantheon.php file makes some changes
 *      that affect all environments that this site
 *      exists in.  Always include this file, even in
 *      a local development environment, to ensure that
 *      the site settings remain consistent.
 */
include __DIR__ . "/settings.pantheon.php";

/**
 * Place the config directory outside of the Drupal root.
 */
$config_directories = array(
  CONFIG_SYNC_DIRECTORY => dirname(DRUPAL_ROOT) . '/config',
);

/**
 * If there is a local settings file, then include it
 */
$local_settings = __DIR__ . "/settings.local.php";
if (file_exists($local_settings)) {
  include $local_settings;
}

/**
 * Always install the 'standard' profile to stop the installer from
 * modifying settings.php.
 */
$settings['install_profile'] = 'wxt';
$databases['backend']['default'] = [
    'database' => 'pantheon',
    'username' => 'pantheon',
    'password' => '42ed5e132d444c1ba409bceafbf78a88',
    'host' => 'dbserver.pr-14.1408e6b1-2c9d-41f7-aa9c-d0e0920cba1c.drush.in',
    'port' => '15655',
    'driver' => 'mysql',
    'prefix' => '',
    'collation' => 'utf8mb4_general_ci',
  ];
