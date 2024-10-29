<?php


	// Exists on direct loading
	if ( defined( 'ABSPATH' ) == false ) { exit; }

	// registering ajax calls
	add_action( 'wp_ajax_wico_annbar_wicore_business_ajax_receiver', 'wico_annbar_wicore_business_ajax_receiver' );

	// common ajax received
	function wico_annbar_wicore_business_ajax_receiver( $params )
	{
		// checking nonce
		check_ajax_referer( wico_annbar, 'security' );

		$data = $_POST['methodData'];
		$methodName = $data['method'];

		$dataFormDataArray = $data['data'];

		$result = wico_annbar_functions_ajax_receiver_handler( $methodName, $dataFormDataArray );
				
		// sending response
		wp_send_json( $result );
	}



// adds the client needed resources for the dashboard page
function wico_annbar_wicore_business_clientresourceshandler_injectdashboardresources()
{
	
	require_once(ABSPATH .'wp-includes/pluggable.php');

	// css vendors via CDN
	wp_enqueue_style( 'wico_annbar-css-bulma', 'https://cdn.jsdelivr.net/npm/bulma@0.8.2/css/bulma.min.css', array(), '1.0.209' );
	
	// css vendors
	wico_annbar_wicore_business_clientresourceshandler_enqueueresourcestylefile( 'wico_annbar-css-reset', '/wicore/clientresources/reset.css' );
	wico_annbar_wicore_business_clientresourceshandler_enqueueresourcestylefile( 'wico_annbar-css-toastify', '/wicore/clientresources/toastify.min.css' );
	wico_annbar_wicore_business_clientresourceshandler_enqueueresourcestylefile( 'wico_annbar-css-fontawesome', '/wicore/clientresources/fontawesome.min.css' );
	wico_annbar_wicore_business_clientresourceshandler_enqueueresourcestylefile( 'wico_annbar-css-fontawesome-solid', '/wicore/clientresources/fontawesome.solid.min.css' );
	wico_annbar_wicore_business_clientresourceshandler_enqueueresourcestylefile( 'wico_annbar-css-fontawesome-regular', '/wicore/clientresources/fontawesome.regular.min.css' );

	// css plugin
	wico_annbar_wicore_business_clientresourceshandler_enqueueresourcestylefile( 'wico_annbar-css-wico-plugin', '/wicore/clientresources/wico-plugin.css' );
	wico_annbar_wicore_business_clientresourceshandler_enqueueresourcestylefile( 'wico_annbar-css-wico-plugin-specific', '/client/plugin.css' );

	// scripts vendors
	wp_enqueue_script( 'jquery' );
	wico_annbar_wicore_business_clientresourceshandler_enqueueresourcescriptfile( 'wico_annbar-toastify-js', '/wicore/clientresources/toastify.min.js' );
	
	// scripts plugin
	wico_annbar_wicore_business_clientresourceshandler_enqueueresourcescriptfile( 'wico_annbar-wico-plugin-js', '/wicore/script/common.js' );
	wico_annbar_wicore_business_clientresourceshandler_enqueueresourcescriptfile( 'wico_annbar-script-js', '/client/plugin.js' );

	// js files needed for controllers
	

}

// enqueue a single client style resource file. Attaches this current plugin version for caching matters.
// example:
// $elementKey="hst-css-reset"
// $path="css/reset.css"
function wico_annbar_wicore_business_clientresourceshandler_enqueueresourcestylefile( $elementKey, $path )
{
	$version = '1.0.209';
	wp_enqueue_style( $elementKey, wico_annbar_consts_pluginUrl . $path, array(), '1.0.209' );
}

// enqueue a single client script resource file. Attaches this current plugin version for caching matters.
// example:
// $elementKey="hst-shared-js"
// $path="scripts/shared.js"
function wico_annbar_wicore_business_clientresourceshandler_enqueueresourcescriptfile( $elementKey, $path )
{
	$version = '1.0.209';
	wp_register_script( $elementKey, wico_annbar_consts_pluginUrl . $path, array(), $version, false);
	wp_localize_script( $elementKey, 'wico_annbar_vars', wico_annbar_wicore_business_clientresourceshandler_buildarrayconstantsforscripts());
	wp_enqueue_script( $elementKey, wico_annbar_consts_pluginUrl	. $path);
}

// returns the array that will be passed to javascripts files (constants)
function wico_annbar_wicore_business_clientresourceshandler_buildarrayconstantsforscripts()
{
	$result = array();

	$ajaxNonce = wp_create_nonce( "wico_annbar" );

	$result = array(
		'ajaxNonce' => $ajaxNonce,
		'ajaxHandlerUrl' => admin_url( 'admin-ajax.php' ),
		'pluginBaseUrl' => wico_annbar_consts_pluginUrl,
    'blockUIContent' => '<div class=\'wico-blockui\'><i class=\'fa fa-spinner fa-spin\'></i></div>'
	);

	return $result;
}



// returns the dashboard url for the main page of the plugin 
function wico_annbar_wicore_business_common_url_admin_plugin( $params )
{

	$adminUrl = admin_url('admin.php');
	$result = $adminUrl . "?page=wico_annbar";

	if ( isset($params["zone"] ) )
	{
		$result .= "&zone=" . $params["zone"];
	}

	return $result;

}

// returns ajax response object
function wico_annbar_wicore_business_common_build_ajax_response( $isSuccess, $data, $errorKey, $errorMessage, $errorData )
{
	
	$result = array();

	if ( $isSuccess )
	{
		$result["status"] = "ok";
	}
	else
	{
		$result["status"] = "ko";
	}

	$result["data"] = $data;
	$result["error_key"] = $errorKey;
	$result["error_message"] = $errorMessage;
	$result["error_data"] = $errorData;

	return $result;
}

// returns query string value
function wico_annbar_wicore_business_common_return_querystring( $params )
{
	
	if ( !isset( $params["key"] ) ) { return ""; }

	$key = $params["key"];

	$querystring = "";
	if ( isset( $_GET[$key] ) ) { $querystring = $_GET[$key]; }

	return $querystring;

}

// populates a dropdownlist by the array
function wico_annbar_wicore_business_common_populate_dropdown_by_array( $arrayData, $keyName, $valueName, $addThreeDots )
{

  if ( $addThreeDots )
  {
    echo( "<option value='...'>(...)</option>" ); 
  }

	if ( $arrayData == null) return;

	foreach( $arrayData as $itemData ) { 
		echo( "<option value='" . $itemData->$keyName . "'>" . $itemData->$valueName . "</option>" ); 
	}

}

// set menu as active by looking at the "zone" querystring parameter value
function wico_annbar_wicore_business_common_populate_set_menu_active( $pageKey, $isDefaultPage )
{

	if ( $pageKey == null) return "";

	// reads querystring
	$params = [];
	$params["key"] = "zone";
	$queryStringZone = wico_annbar_wicore_business_common_return_querystring( $params );
	
	// if the same, this menu is active (or if its the default page and there is no querystring)
	if ( $pageKey == $queryStringZone || ( $queryStringZone == "" && $isDefaultPage) )
	{
		return " class='active' ";
	}
	
}

// return true if a plugin is installed, false if not
// $pluginPath example: 'contact-form-7/wp-contact-form-7.php'
function wico_annbar_wicore_business_common_is_plugin_active( $pluginPath )
{

	if ( $pluginPath == null || $pluginPath == '' ) return false;

	// reads querystring
	return in_array( $pluginPath, (array) get_option( 'active_plugins', array() ) );
	
}

// shows a premium only crown icon near a label
function wico_annbar_wicore_business_common_show_premium_icon_near_label( $params )
{
	$onclickOpenModal = '';
	if ( $params['onclick_open_modal_id'] == 1)
	{
		$idModalToOpen = $params['modal_id'];
		$onclickOpenModal = "onclick=\"wico_annbar_openModal({'id':'" . $idModalToOpen . "'});\""; 
	}

	$iconHtml = "<i class='fas fa-crown wico-crown-icon-label' " . $onclickOpenModal . "></i>";
	return $iconHtml;
}



// ensure global is declared
global $wpdb;

// system
define( 'wico_annbar_consts_pluginversion', '1.0.209' );

// options
define( 'wico_annbar_consts_opt_topbar_enabled', 'wico_annbar_topbar_enabled' );
define( 'wico_annbar_consts_opt_topbar_content', 'wico_annbar_topbar_content' );
define( 'wico_annbar_consts_opt_topbar_color_bg', 'wico_annbar_topbar_color_bg' );
define( 'wico_annbar_consts_opt_topbar_color_text', 'wico_annbar_topbar_color_text' );
define( 'wico_annbar_consts_opt_topbar_position', 'wico_annbar_topbar_position' );
define( 'wico_annbar_consts_opt_topbar_display_button', 'wico_annbar_topbar_display_button' );
define( 'wico_annbar_consts_opt_topbar_button_bg', 'wico_annbar_topbar_button_bg' );
define( 'wico_annbar_consts_opt_topbar_button_fg', 'wico_annbar_topbar_button_fg' );
define( 'wico_annbar_consts_opt_topbar_button_bordercol', 'wico_annbar_topbar_button_bordercol' );
define( 'wico_annbar_consts_opt_topbar_button_text', 'wico_annbar_topbar_button_text' );
define( 'wico_annbar_consts_opt_topbar_button_link', 'wico_annbar_topbar_button_link' );
define( 'wico_annbar_consts_opt_topbar_close_button', 'wico_annbar_topbar_close_button' );
define( 'wico_annbar_consts_opt_topbar_show_on_pages', 'wico_annbar_topbar_show_on_pages' );
define( 'wico_annbar_consts_opt_topbar_show_on_pages_list', 'wico_annbar_topbar_show_on_pages_list' );
define( 'wico_annbar_consts_opt_topbar_style_padding_top', 'wico_annbar_topbar_style_padding_top' );
define( 'wico_annbar_consts_opt_topbar_style_padding_bottom', 'wico_annbar_topbar_style_padding_bottom' );
define( 'wico_annbar_consts_opt_topbar_style_padding_font_size', 'wico_annbar_topbar_style_padding_font_size' );
define( 'wico_annbar_consts_opt_topbar_style_padding_font_face', 'wico_annbar_topbar_style_padding_font_face' );
define( 'wico_annbar_consts_opt_topbar_css_custom', 'wico_annbar_topbar_css_custom' );


// post meta TODO
// XXXXXXarray_of_post_metaXXXXXX

// array of pages
global $wico_annbarGlobalPages;
$wico_annbarGlobalPages = [];
array_push( $wico_annbarGlobalPages, ['key' => 'main', 'title' => 'Announcement Bar Settings', 'is_default' => '1']);




// called from the main file, it will include all needed php files for the plugin
function wico_annbar_wicore_business_includes_includefiles()
{

	// including controllers needed files


	// plugin functions/startup.php must always be present
  include( wico_annbar_consts_pluginPath . '//functions//startup.php' );

  	// plugin functions/functions.php must always be present
  include( wico_annbar_consts_pluginPath . '//functions//functions.php' );
}



// called from main root .php file
function wico_annbar_wicore_business_init()
{
	
}



// call this in your /function/main.php plugin specific file
function wico_annbar_wicore_business_pages_addadminpage()
{

	// attaches it to the correct event so WP can manage this correctly
	add_action( 'admin_menu', 'wico_annbar_wicore_business_pages_addadminpage_callback' );

}
// callback from the function above
function wico_annbar_wicore_business_pages_addadminpage_callback()
{

	// the capability that the user must have in order to access the page
	$capabilityName = 'manage_options';

	// generates page
	$generatedPage = add_menu_page( 'Announ. Bar', 'Announ. Bar', $capabilityName, 'wico_annbar', 'wico_annbar_wicore_business_pages_addadminpage_childcallback', wico_annbar_consts_pluginUrl . '/images/wp-pluginlogo.png', 74 );

	// adds the page and calls the function to include client resources needed
	add_action( 'load-' . $generatedPage, 'wico_annbar_wicore_business_clientresourceshandler_injectdashboardresources' );

}
// callback from the function above
function wico_annbar_wicore_business_pages_addadminpage_childcallback() {
	
	$zoneQueryString = wico_annbar_wicore_business_common_return_querystring( array( "key"=>"zone") );
	if ( $zoneQueryString == "" )
	{
		$zoneQueryString = "main";
	}
	
	// the page must have the same name as the page key 
	include( wico_annbar_consts_pluginPath . "/pages/admin/" . $zoneQueryString . ".php");

}


?>