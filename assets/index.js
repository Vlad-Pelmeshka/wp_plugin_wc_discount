import './scss/style.scss';


jQuery(document).ready(function ($) {

    $('#woo-discount-managment-form').on('submit', function (event) {
        event.preventDefault();

        $('#woo-discount-managment-submit-id').attr('disabled','true');

        let data = {
            'discount_cat': $('#search-cat').val(),
            'discount_count': $('#search-count').val(),
            'discount_cat_free': $('#search-cat-free').val(),
        };

        sendAjaxRequestWooDiscount(data);
    });

});

function sendAjaxRequestWooDiscount(data) {
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            action: 'custom_woo_discount',
            data: data,
        },
        success: function (response) {
            jQuery('#woo-discount-managment-section-notice').remove();
            jQuery('#woo-discount-managment-submit-id').removeAttr('disabled');
            if (response.success == true) {
                var successNotice = '<div id="woo-discount-managment-section-notice" class="notice notice-success is-dismissible"><p>' + response.data.success + '</p></div>';

                jQuery('#woo-discount-managment-section').prepend(successNotice);
            }
            else {
                var errorNotice = '<div id="woo-discount-managment-section-notice" class="notice notice-error is-dismissible"><p>' + response.data.error + '</p></div>';

                jQuery('#woo-discount-managment-section').prepend(errorNotice);
            }

        },
        error: function (response) {
            console.log(response);
            var successNotice = '<div id="woo-discount-managment-section-notice" class="notice notice-success is-dismissible"><p>Error. Open console</p></div>';

            jQuery('#woo-discount-managment-section').prepend(successNotice);
        },
    });
}