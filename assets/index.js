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

    function sendAjaxRequestWooDiscount(data) {
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'custom_woo_discount',
                data: data,
            },
            success: function (response) {
                
                /*$('.result-column-items').empty();
    
                addResultsToColumn(response.title, '#title-results', '#text-manage-form-title');
                addResultsToColumn(response.content, '#content-results', '#text-manage-form-content');
                addResultsToColumn(response.meta_title, '#meta-title-results', '#text-manage-form-meta-title');
                addResultsToColumn(response.meta_description, '#meta-description-results', '#text-manage-form-meta-description');
    
                $('#search-nothing').toggle(!response.title.length && !response.content.length && !response.meta_title.length && !response.meta_description.length);
                
                $('#search-info').show();
                $('#search-info-text').text(searchText);*/
            },
            error: function (error) {
                console.log(error);
            },
        });
    }
    
    function addResultsToColumn(results, columnId, formId) {
        var column  = $(columnId);
        var form    = $(formId);
    
        if (results.length > 0) {
            
            form.find('input[type=submit]').prop('disabled', false);
    
            $.each(results, function (index, post) {
                var resultItem = $('<div class="result-item" post-id="' + post.ID + '"><div>' + post.result_string + '</div></div>');
                column.append(resultItem);
            });
        } else {
            
            form.find('input[type=submit]').prop('disabled', true);
    
            column.append('<div class="no-matches">No matches found</div>');
        }
    }
    


    $('.text-manage-form-column').on('submit', function (event) {
        
        event.preventDefault();
        var type = $(this).attr('form-type');

        $('form[form-type="' + type + '"] input[type="submit"]').attr('disabled', true);

        var replaceText = $('input[id="replace-text-' + type + '"]').val();

        console.log(replaceText);

        if(replaceText){
            sendReplaceRequest(type, replaceText);
        }else{
            $('form[form-type="' + type + '"] input[type="submit"]').attr('disabled', false);
        }

    });

    function sendReplaceRequest(type, replaceText) {
        var postData = {
            action: 'text_manage_replace',
            type: type,
            replace_text: replaceText,
            replaced_text: $('#search-info-text').html(),
        };

        console.log(postData);

        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: postData,
            success: function (response) {
                var searchText = $('#search-text').val();
                sendAjaxRequest(searchText);
            },
            error: function (error) {
                console.log(error);
            },
        });
    }
});

