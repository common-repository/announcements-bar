<?php

/**
 * @package WicoAnnouncementsBar
 */
/*
Plugin Name: Announcements Bar
Description: Displays an announcements top bar in your website
Version: 1.0.211
Author: Wiser Coding
License: GPLv2 or later
Text Domain: wico_annbar
 */

if ( defined( 'ABSPATH' ) == false ) { exit; }

// basic constants
define( 'wico_annbar_consts_pluginPath', plugin_dir_path( dirname(__FILE__) . '/wico-announcements-bar.php') );
define( 'wico_annbar_consts_pluginUrl', plugin_dir_url( dirname(__FILE__) . '/wico-announcements-bar.php') );

// including the file that will manage all inclusions
include_once( wico_annbar_consts_pluginPath . 'wicore\business.php' );
wico_annbar_wicore_business_includes_includefiles();

// checks for db object install
// no scripts needed

// plugin init function
function wico_annbar_main_init()
{
	// core init
	wico_annbar_wicore_business_init();

	// plugin specific
	wico_annbar_functions_startup();
}
add_action( 'init', 'wico_annbar_main_init' );

// check for premium plugins to be installed and active
$wico_annbar_premium_enabled_main = '0';
if (wico_annbar_wicore_business_common_is_plugin_active('wico-announcements-bar-premium/wico-announcements-bar-premium.php')) {
     $wico_annbar_premium_enabled_main = '1';
}


?>