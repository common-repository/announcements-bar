jQuery(document).ready(function () {

    // disabling fields according to situations
    wico_annbar_pagemain_disable_actionbutton_controls();

    // attaching disable/enable of fields for the button
    jQuery("#wico_annbar_pagebutton_chk_enable").change(function (event) {
        wico_annbar_pagemain_disable_actionbutton_controls()
    });

    // selection change show on pages
    jQuery("#wico_annbar_pagepages_ddl_show").change(function () {
        wico_annbar_pagepages_ddl_show_change(true);
    });

    // sets the right status right after page load
    wico_annbar_pagepages_ddl_show_change(false);
});

// according to the checkbox, enables or disables the buttons controls (colors and content)
function wico_annbar_pagemain_disable_actionbutton_controls() {

    if (jQuery('#wico_annbar_pagebutton_chk_enable').is(':checked') == false) {
        jQuery('#fieldset_wico_annbar_pagebutton_txt_backgroundcolor').attr('disabled', 'disabled');
        jQuery('#fieldset_wico_annbar_pagebutton_txt_foregroundcolor').attr('disabled', 'disabled');
        jQuery('#fieldset_wico_annbar_pagebutton_txt_bordercolor').attr('disabled', 'disabled');
        jQuery('#fieldset_wico_annbar_pagebutton_txt_text').attr('disabled', 'disabled');
        jQuery('#fieldset_wico_annbar_pagebutton_txt_link').attr('disabled', 'disabled');
    }
    else {
        jQuery('#fieldset_wico_annbar_pagebutton_txt_backgroundcolor').removeAttr('disabled');
        jQuery('#fieldset_wico_annbar_pagebutton_txt_foregroundcolor').removeAttr('disabled');
        if (wico_annbar_premium_enabled_main == 1) { jQuery('#fieldset_wico_annbar_pagebutton_txt_bordercolor').removeAttr('disabled') };
        jQuery('#fieldset_wico_annbar_pagebutton_txt_text').removeAttr('disabled');
        jQuery('#fieldset_wico_annbar_pagebutton_txt_link').removeAttr('disabled');
    }
}

function wico_annbar_pagepages_ddl_show_change(showPopupPremium) {

    var selectedOption = jQuery("#wico_annbar_pagepages_ddl_show").val();

    if (selectedOption == 'home' || selectedOption == 'all') {
        jQuery("#fieldset_wico_annbar_pagepages_txt_urls").attr('disabled', 'disabled');
    }
    if (selectedOption == 'specific' && wico_annbar_premium_enabled_main == 1) {
        jQuery('#fieldset_wico_annbar_pagepages_txt_urls').removeAttr('disabled');
    }
    if (selectedOption == 'specific' && wico_annbar_premium_enabled_main != 1 && showPopupPremium) {
        wico_annbar_openModal({ 'id': 'wico_annbar_modal_premium' });
        jQuery("#wico_annbar_pagepages_ddl_show").val('home');
    }
}

function wico_annbar_modal_premium_save_button_click() {
    window.open('https://wisercoding.com/downloads/announcements-top-bar-premium-plugin-for-wordpress/', "_blank");
}

// handling tab keys navigation clicks
function wico_annbar_navtab_main_navigate(tabkey) {

    
jQuery('#cols_main').hide();
jQuery('#navtab_main_tab_main').removeClass('is-active');

jQuery('#cols_behaviour').hide();
jQuery('#navtab_main_tab_behaviour').removeClass('is-active');

jQuery('#cols_button').hide();
jQuery('#navtab_main_tab_button').removeClass('is-active');

jQuery('#cols_pages').hide();
jQuery('#navtab_main_tab_pages').removeClass('is-active');

jQuery('#cols_styles').hide();
jQuery('#navtab_main_tab_adjust').removeClass('is-active');

jQuery('#cols_css').hide();
jQuery('#navtab_main_tab_css').removeClass('is-active');


    
if (tabkey == 'main') { jQuery('#cols_main').show(); jQuery('#navtab_main_tab_main').addClass('is-active');}
if (tabkey == 'behaviour') { jQuery('#cols_behaviour').show(); jQuery('#navtab_main_tab_behaviour').addClass('is-active');}
if (tabkey == 'button') { jQuery('#cols_button').show(); jQuery('#navtab_main_tab_button').addClass('is-active');}
if (tabkey == 'pages') { jQuery('#cols_pages').show(); jQuery('#navtab_main_tab_pages').addClass('is-active');}
if (tabkey == 'adjust') { jQuery('#cols_styles').show(); jQuery('#navtab_main_tab_adjust').addClass('is-active');}
if (tabkey == 'css') { jQuery('#cols_css').show(); jQuery('#navtab_main_tab_css').addClass('is-active');}

}

function wico_annbar_pagemain_btn_save_settings_click() {
    var params = {};
    params.method = 'saveBarSettings';
    params.callback = 'wico_annbar_pagemain_btn_save_settings_click_callback';
    var paramsFormData = {};
	paramsFormData.displayBar = jQuery('#wico_annbar_pagemain_ddl_display_bar').val();
	paramsFormData.barContent = jQuery('#wico_annbar_pagemain_txt_content').val();
	paramsFormData.colorBackground = jQuery('#wico_annbar_pagecolors_txt_backgroundcolor').val();
	paramsFormData.colorText = jQuery('#wico_annbar_pagecolors_txt_forecolor').val();
	paramsFormData.barPosition = jQuery('#wico_annbar_pagebehaviour_ddl_position').val();
	paramsFormData.closeButton = jQuery('#wico_annbar_pagebehaviour_chk_closebutton').is(':checked') ? '1' : '0';
	paramsFormData.displayButton = jQuery('#wico_annbar_pagebutton_chk_enable').is(':checked') ? '1' : '0';
	paramsFormData.buttonBackgroundColor = jQuery('#wico_annbar_pagebutton_txt_backgroundcolor').val();
	paramsFormData.buttonForegroundColor = jQuery('#wico_annbar_pagebutton_txt_foregroundcolor').val();
	paramsFormData.buttonBorderColor = jQuery('#wico_annbar_pagebutton_txt_bordercolor').val();
	paramsFormData.buttonText = jQuery('#wico_annbar_pagebutton_txt_text').val();
	paramsFormData.buttonLink = jQuery('#wico_annbar_pagebutton_txt_link').val();
	paramsFormData.showOnPages = jQuery('#wico_annbar_pagepages_ddl_show').val();
	paramsFormData.showOnPagesList = jQuery('#wico_annbar_pagepages_txt_urls').val();
	paramsFormData.stylePaddingTop = jQuery('#wico_annbar_pagestyles_txt_paddingtop').val();
	paramsFormData.stylePaddingBottom = jQuery('#wico_annbar_pagestyles_txt_paddingbottom').val();
	paramsFormData.styleFontSize = jQuery('#wico_annbar_pagestyles_txt_fontsize').val();
	paramsFormData.styleFontFace = jQuery('#wico_annbar_pagestyles_txt_fontface').val();
	paramsFormData.customCss = jQuery('#wico_annbar_pagecss_txt_css').val();
	params.data = paramsFormData;
    params.controlId = 'wico_annbar_pagemain_btn_save_settings';
    wico_annbar_wicore_callajax(params);
}
function wico_annbar_pagemain_btn_save_settings_click_callback() {
    wico_annbar_toast({ 'text':'Settings updates succesfully!' });
}