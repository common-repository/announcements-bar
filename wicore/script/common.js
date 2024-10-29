// generic function to call ajax
function wico_annbar_wicore_callajax(params) {

    console.log(params);

    // setting button in animation, if the id was passed
    if (params.controlId != undefined) {
        jQuery("#" + params.controlId).addClass('is-loading');
    }

    jQuery.ajax(
        {
            url: wico_annbar_vars.ajaxHandlerUrl,
            type: 'post',
            dataType: 'json',
            data: {
                action: 'wico_annbar_wicore_business_ajax_receiver',
                security: wico_annbar_vars.ajaxNonce,
                methodData: params
            },
            success: function (response) {
                //console.log(response);
                //wico_suptik_common_ajax_callback(response);
                // removing animation, if the id was passed
                if (params.controlId != undefined) {
                    jQuery("#" + params.controlId).removeClass('is-loading');
                }

                if (params.callback != undefined) {
                    var fn = window[params.callback];
                    fn();
                }

            },
            error: function () {

                // removing animation, if the id was passed
                if (params.controlId != undefined) {
                    jQuery("#" + params.controlId).removeClass('is-loading');
                }

            }
        });
}

// make toast
function wico_annbar_toast(params) {

    Toastify({
        text: params.text,
        duration: 3000,
        close: true,
        gravity: "bottom", 
        position: 'right', 
        backgroundColor: "#48c774",
        stopOnFocus: true
    }).showToast();

}

// open modal
function wico_annbar_openModal(params) {

    jQuery('#' + params.id).addClass('is-active');

}

// close modal
function wico_annbar_closeModal(params) {

    jQuery('#' + params.id).removeClass('is-active');

}
