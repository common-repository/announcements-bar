<?php

if ( defined( 'ABSPATH' ) == false ) { exit; }

// called by main plugin file
function wico_annbar_functions_startup() {

    // adds the page in the admin dashboard menu
    wico_annbar_wicore_business_pages_addadminpage();

    // adds the color picker script utility from wordpress utilities, only in admin page
    add_action( 'admin_enqueue_scripts', 'wico_annbar_addcolorpicker' );

}

// adding plugin css in the frontend
function wico_annbar_addfrontend_css() {
    wp_enqueue_style( 'wico-annbar-frontend', wico_annbar_consts_pluginUrl . '/client/plugin.css' );
}
add_action( 'wp_enqueue_scripts', 'wico_annbar_addfrontend_css' );

// color picker init
function wico_annbar_addcolorpicker( $hook ) {
 
    if( is_admin() ) {        
        wp_enqueue_style( 'wp-color-picker' ); 
        wp_enqueue_script( 'wp-color-picker');
    }
}

?>