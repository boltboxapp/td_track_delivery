/**
 * Track Delivery
 */

jQuery(document).ready(function($) {
    // Setup track delivery front-end client
    TDClient.setRequestUrl(TDObject.ajax_url);
    TDClient.setMessageBox( $('#td-track-response') );
    TDClient.setRequestForm( $('#td-delivery-check') );

    // Send request
    $('#td-delivery-check').submit(function(e) {
        TDClient.clearMessage();
        TDClient.sendDeliveryRequest();

        e.preventDefault;
        return false;
    });

    // Track code change
    $('#td-track-code').on('keyup', function(e) {
        TDClient.clearMessage();
    });

    // Delivery service change
    $('#td-delivery-check [name="td_delivery_service"]').change(function(e) {
        TDClient.clearMessage();
    });
});
