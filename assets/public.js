import './scss/public.scss';


jQuery(document).ready(function ($) {

    AddProductFreeButton();

    $(document.body).on('updated_cart_totals', function () {
        AddProductFreeButton();
    });
});

function AddProductFreeButton() {
    jQuery('#add_product_free').on('click', function (event) {
        event.preventDefault();

        jQuery('#add_product_free').attr("disabled", true);

        let free_product = jQuery('#woo-free-discount-product').val();

        sendAjaxRequestWooDiscount(free_product);
    });
}

function sendAjaxRequestWooDiscount(free_product) {
    jQuery.ajax({
        type: 'POST',
        url: '/wp-admin/admin-ajax.php',
        data: {
            action: 'add_free_product_woo_discount',
            product_id: free_product,
        },
        success: function (response) {
            jQuery("[name='update_cart']").removeAttr("disabled");
            jQuery("[name='update_cart']").trigger("click");

        },
        error: function (response) {
            console.log(response);
        },
    });
}

