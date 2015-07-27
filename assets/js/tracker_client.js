/**
 * TDClient
 * 
 * Tracking Delivery Front-end Client
 */
var TDClient = {
    // Message status
    MS_ERROR:   0,
    MS_SUCCESS: 1,

    message_box:  null,
    request_url:  null,
    request_form: null,

    setRequestUrl: function(url) {
        this.request_url = url;
    },
    setMessageBox: function(block) {
        this.message_box = block;
    },
    setRequestForm: function(form) {
        this.request_form = form;
    },
    clearMessage: function() {
        this.message_box.html('');
    },
    showMessage: function(message) {
        this.message_box.html(message);
    },
    // Highlight message status
    setStatus: function(status) {
        if (status == this.MS_SUCCESS) {
            this.message_box.removeClass('error').addClass('success');
        } else if (status == this.MS_ERROR) {
            this.message_box.removeClass('success').addClass('error');
        }
    },
    // Get info for track code
    sendDeliveryRequest: function() {
        var request_data,
            self = this;

        if (!this.request_form || !this.request_url) return false;

        request_data = jQuery(this.request_form).serialize();
        request_data += '&action=td_check_code_action';

        jQuery.post(this.request_url, request_data, function(response) {
            self.setStatus(response.status);
            self.showMessage(response.message);
        });
    }
}