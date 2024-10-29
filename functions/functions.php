<?php

// all ajax calls will land here
function wico_annbar_functions_ajax_receiver_handler( $methodName, $params )
{
	$result = "";

	if ( $methodName == "saveBarSettings" )
	{
		$result = wico_annbar_functions_save_bar_settings( $params );
	}

	return $result;
}

// saves the configuration into wp options
function wico_annbar_functions_save_bar_settings( $params )
{
	$optionDisplayBar = $params['displayBar'];
	$optionBarContent = $params['barContent'];
	$optioncolorBackground = $params['colorBackground'];
	$optioncolorText = $params['colorText'];
	$optionbarPosition = $params['barPosition'];
	$optionbarDisplayButton = $params['displayButton'];
	$topbarButtonBgColor = $params['buttonBackgroundColor'];
	$topbarButtonFgColor = $params['buttonForegroundColor'];
	$topbarButtonText = $params['buttonText'];
	$topbarButtonLink = $params['buttonLink'];
	$topbarShowOnPages = $params['showOnPages'];


	update_option( wico_annbar_consts_opt_topbar_enabled, $optionDisplayBar );
	update_option( wico_annbar_consts_opt_topbar_content, $optionBarContent );
	update_option( wico_annbar_consts_opt_topbar_color_bg, $optioncolorBackground );
	update_option( wico_annbar_consts_opt_topbar_color_text, $optioncolorText );
	update_option( wico_annbar_consts_opt_topbar_position, $optionbarPosition );
	update_option( wico_annbar_consts_opt_topbar_display_button, $optionbarDisplayButton );
	update_option( wico_annbar_consts_opt_topbar_button_bg, $topbarButtonBgColor );
	update_option( wico_annbar_consts_opt_topbar_button_fg, $topbarButtonFgColor );
	update_option( wico_annbar_consts_opt_topbar_button_text, $topbarButtonText );
	update_option( wico_annbar_consts_opt_topbar_button_link, $topbarButtonLink );
	update_option( wico_annbar_consts_opt_topbar_show_on_pages, $topbarShowOnPages );


	// saves premium options
	global $wico_annbar_premium_enabled_main;
	if ( $wico_annbar_premium_enabled_main == '1' )
	{
		wico_annbar_functions_save_bar_settings_premium($params);
	}
	

	return "ok";
}

// this is the main function, that gets data from the service and return the html to be shown as top bar, if enabled in the settings
function wico_annbar_functions_create_top_bar()
{
	$barEnabled = get_option(wico_annbar_consts_opt_topbar_enabled, 0);
	$barContent = get_option(wico_annbar_consts_opt_topbar_content, '');
	$barColorBg = get_option(wico_annbar_consts_opt_topbar_color_bg, '#fff');
	$barColorText = get_option(wico_annbar_consts_opt_topbar_color_text, '#000');

	$closeButton = get_option(wico_annbar_consts_opt_topbar_close_button, '0');
	$barPosition = get_option(wico_annbar_consts_opt_topbar_position, 'top_normal');

	$barDisplayButton = get_option(wico_annbar_consts_opt_topbar_display_button, '0');
	$barButtonBgColor = get_option(wico_annbar_consts_opt_topbar_button_bg, 'blue');
	$barButtonFgColor = get_option(wico_annbar_consts_opt_topbar_button_fg, 'white');
	$barButtonBorderColor = get_option(wico_annbar_consts_opt_topbar_button_bordercol, '');
	$barButtonText = get_option(wico_annbar_consts_opt_topbar_button_text, '');
	$barButtonLink = get_option(wico_annbar_consts_opt_topbar_button_link, '');

	$barShowOnPages = get_option(wico_annbar_consts_opt_topbar_show_on_pages, 'home');

	// getting premium values from options
	$barStyleTopPadding = "";
	$barStyleBottomPadding = "";
	$barStyleFontSize = "";
	$barStyleFontFamilyName = "";
	$barShowOnPagesList = "";
	$barCss = "";
	global $wico_annbar_premium_enabled_main;
	if ( $wico_annbar_premium_enabled_main == '1' )
	{
		$premiumSettingsValues = wico_annbar_functions_get_settings_premium();
		$barStyleTopPadding = $premiumSettingsValues["styleTopPadding"];
		$barStyleBottomPadding = $premiumSettingsValues["styleBottomPadding"];
		$barStyleFontSize = $premiumSettingsValues["styleFontSize"];
		$barStyleFontFamilyName = $premiumSettingsValues["styleFontFamilyName"];
		$barShowOnPagesList = $premiumSettingsValues["showListPages"];
		$barCss = $premiumSettingsValues["css"];
	}

	// if not in the right page, the bar won't just be shown
	if ($barShowOnPages == 'home' && !is_home())
	{
		$barEnabled = 0;	// the bar should only be visible in homepage and this is not the homepage
	}
	if ($barShowOnPages == 'specific')
	{
		global $wp;
		$currentUrl = home_url( add_query_arg( array(), $wp->request ) );
		$allowedPages = []; 
		$pageAllowedFound = false;
		if ($barShowOnPagesList != null && $barShowOnPagesList != "") {
			$allowedPages = preg_split('/\r\n|\r|\n/', $barShowOnPagesList);
			foreach ($allowedPages as $page) {
				if ($currentUrl == rtrim($page, "/"))
				{
					$pageAllowedFound = true;
				}
			}
			if ($pageAllowedFound == false)
			{
				$barEnabled = 0; // because this page was not found in the allowed pages
			}
		}
	}

	if ($barEnabled == 1)
	{

		$stickyStyles = '';
		if ($barPosition == 'top_sticky')
		{
			$stickyStyles = 'position:fixed; z-index:9999; width:100%; left:0px; top:0;';
		}
		if ($barPosition == 'bottom_sticky')
		{
			$stickyStyles = 'position:fixed; z-index:9999; width:100%; left:0px; bottom:0;';
		}

		$boxStyles = "background-color:" . $barColorBg . ";color:" . $barColorText . ";text-align:center;" . $stickyStyles;

		if ($wico_annbar_premium_enabled_main == '1' && $barStyleTopPadding != "")
		{
			$boxStyles .= "padding-top:" . $barStyleTopPadding . " !important;";
		}
		if ($wico_annbar_premium_enabled_main == '1' && $barStyleBottomPadding != "")
		{
			$boxStyles .= "padding-bottom:" . $barStyleTopPadding . " !important;";
		}
		if ($wico_annbar_premium_enabled_main == '1' && $barStyleFontSize != "")
		{
			$boxStyles .= "font-size:" . $barStyleFontSize . " !important;";
		}
		if ($wico_annbar_premium_enabled_main == '1' && $barStyleFontFamilyName != "")
		{
			$boxStyles .= "font-family:" . $barStyleFontFamilyName . " !important;";
		}

		$textStyles = "";

		$button = "";
		if ($barDisplayButton == '1')
		{
			$borderStyle = "";
			if ($barButtonBorderColor != "")
			{
				$borderStyle = "border:1px solid " . $barButtonBorderColor . ";";
			}
			$button = "<a href=\"" . $barButtonLink . "\" style=\"background-color:" . $barButtonBgColor . ";color:" . $barButtonFgColor . ";" . $borderStyle . "\" class=\"wico-annbar-topbar-button\">" . $barButtonText . "</a>";
		}

		$closeIcon = "";
		if ($closeButton == '1' && $wico_annbar_premium_enabled_main == '1')
		{
			$closeIcon = "<div style=\"float:right;\" onclick=\"wico_annbar_close();\">" . wico_annbar_functions_return_svg_icon_close() . "</span>";
		}

		$barHTML = "";
		$barHTML .= "<div style=\"" . $boxStyles. "\" id=\"wico_annbar_div\" class=\"wico-annbar-topbar-box\">";
		$barHTML .= "	<div style=\"" . $textStyles . "\"  class=\"wico-annbar-topbar-text\">";
		$barHTML .= "		<span>" . $barContent . "</span>" . $button . $closeIcon;
		$barHTML .= "	</div>";
		$barHTML .= "</div>";

		$scriptHTML = "";
		$scriptHTML .= "<script>"; 
		$scriptHTML .= "	document.addEventListener('DOMContentLoaded', function(){";
		$scriptHTML .= "		if (document.cookie.indexOf('wico_annbar_closed') > -1 ) { return; }";
		$scriptHTML .= "		bodyTag = document.getElementsByTagName('body')[0];";
		$scriptHTML .= "		theBarTag = document.createElement('div');";
		$scriptHTML .= "		theBarTag.innerHTML = '" . $barHTML . "';";
		if ($barPosition == 'top_normal' || $barPosition == 'top_sticky' || $barPosition == 'bottom_sticky')
		{
			$scriptHTML .= "		bodyTag.insertBefore(theBarTag, bodyTag.firstChild);";
		}
		if ($barPosition == 'bottom_normal')
		{
			$scriptHTML .= "		bodyTag.append(theBarTag);";
		}
		$scriptHTML .= "	});";
		if ($closeButton == '1')
		{
			$scriptHTML .= "function wico_annbar_close() {";
			$scriptHTML .= "	document.getElementById('wico_annbar_div').style.display = 'none';"; 
			$scriptHTML .= "	document.cookie = 'wico_annbar_closed=1';";
			$scriptHTML .= "}";
		}
		$scriptHTML .= "</script>";

		if ($barCss != "")
		{
			$scriptHTML .= "<style>";
			$scriptHTML .= $barCss;
			$scriptHTML .= "</style>";
		}

		echo($scriptHTML);
	}
}
add_action('wp_head', 'wico_annbar_functions_create_top_bar', 9);



?>