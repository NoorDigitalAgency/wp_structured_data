<?php
/**
 * Plugin Name: Structured Data
 * Description: Inserts structured data to page head
 * Version: 1.4.1
 * Author: Noor Digital Agency
 * Author URI: https://noordigital.com
 */

if ( defined( ABSPATH ) ) exit;
  
if ( ! file_exists( $autoload = __DIR__ . '/vendor/autoload.php' ) ) {
  
  return;
}
  
require $autoload;
  
$package = json_decode( file_get_contents( __DIR__ . '/composer.json' ), false );
  
$plugin_updater = \Puc_v4_Factory::buildUpdateChecker( $package->homepage, __FILE__, $package->name );
  
$plugin_updater->getVcsApi()->enableReleaseAssets();

new WPPlugin\StructuredData\StructuredData();
