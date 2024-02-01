import './scss/style.scss';


jQuery(document).ready(function ($) {

    $('#woo-discount-managment-form').on('submit', function (event) {
        event.preventDefault();

        let data = {
            'discount_cat':       $('#search-cat').val(),
            'discount_count':     $('#search-count').val(),
            'discount_cat_free':  $('#search-cat-free').val(),
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
            if(response.success == true){
                var successNotice = '<div class="notice notice-success is-dismissible"><p>' + response.data.success + '</p></div>';

                jQuery('#woo-discount-managment-section').prepend(successNotice);
            }
            else{
                var errorNotice = '<div class="notice notice-error is-dismissible"><p>' + response.data.error + '</p></div>';

                jQuery('#woo-discount-managment-section').prepend(errorNotice);
            }

        },
        error: function (response) {
            console.log(response);
        },
    });
}