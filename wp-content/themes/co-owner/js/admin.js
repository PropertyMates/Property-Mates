const calculateSale = (listPrice, discount) => {
    listPrice = parseFloat(listPrice);
    discount  = parseFloat(discount);
    var response = (( listPrice * discount / 100 )).toFixed(0); // Sale price
    return isNaN(response) && !isFinite(response) ? 0 : response;
}


jQuery(function($){
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    $(function(){
        let body = $('body');
        $(document).on('keypress input',".input-only-number,.room-counter input",function (e) {
            if (e.which !== 8 && e.which !== 0 && (e.which < 48 || e.which > 57) ) {
                return false;
            }
        });

        $(document).on('keypress input',".room-counter input",function (e) {
            if (e.which !== 8 && e.which !== 0 && (e.which < 48 || e.which > 57) ) {
                return false;
            }
            var input = $(this);
            var value = input.val() ? input.val() : 0;
            if(99 < value){
                $(this).val(99);
                event.preventDefault();
                return false;
            }
        });

        $(document).on('focusout input','.room-counter input',function (e) {
            if($(this).val().length == 0){
                $(this).val(0);
            }
        });

        $(document).on('click','.room-counter .counter-minus',function (e) {
            e.preventDefault();
            var input = $(this).parent('.room-counter').find('input');
            var value = input.val() ? input.val() : 0;
            if((value - 1) >= 0){
                input.val(parseInt(value) - parseInt(1));
            }
        });

        $(document).on('click','.room-counter .counter-plus',function (e) {
            e.preventDefault();
            var input = $(this).parent('.room-counter').find('input');
            var value = input.val() ? input.val() : 0;
            if(value >= 99){
                input.val(99);
            }else{
                input.val(parseInt(value) + parseInt(1));
            }
        });

        $('.select2-tags').each(function (index,element) {
            $(element).select2({
                tags: true,
            });
        });

        if(body.hasClass('post-type-feedback')){
            $('[name="carbon_fields_compact_input[_feedback_rating_1]"]').prop('disabled',true);
            $('[name="carbon_fields_compact_input[_feedback_rating_2]"]').prop('disabled',true);
            $('[name="carbon_fields_compact_input[_feedback_rating_3]"]').prop('disabled',true);
        }

        else if(body.hasClass('user-edit-php') || body.hasClass('user-new-php')){

            let email_is_verified = body.find('#email-verified');
            if(email_is_verified.length > 0 && email_is_verified.val() === 'true'){
                $('.user-email-wrap').find('td').append(php_vars.svg.verified);
            }

            select_property_category_person_page();
            $('[name="_user_property_category[]"]').on('change',function(e){
                select_property_category_person_page();
            });

            $('.single-select2').each(function (index,element) {
                $(element).select2();
            });

            $("#role").closest('tr').hide();
        }

        else if(body.hasClass('post-type-property') && (body.hasClass('post-new-php') || body.hasClass('post-php'))){
            let container__fields = '.cf-container__fields';
            $(container__fields).eq(0).addClass('step-1-parent');
            $(container__fields).eq(1).addClass('step-2-parent');
            $(container__fields).eq(2).addClass('step-3-parent');
            $(container__fields).eq(3).addClass('step-4-parent');

            $(".cf-container__tabs").remove();
            $(container__fields).removeAttr('hidden');

            $('.cf-field__label').each(function (e,item) {
                if($(item).find('.cf-field__asterisk').length == 0){
                    $(this).append('<span class="cf-field__asterisk">*</span>');
                }
            });

            $('[name="carbon_fields_compact_input[_pl_property_category]"]').on('change',function (e){
                hide_show_on_change_property_category();
            });
            hide_show_on_change_property_category();

            $('[name="carbon_fields_compact_input[_pl_interested_in_selling]"]').on('change',function (e){
                hide_show_on_change_interested_in_selling();
            });
            hide_show_on_change_interested_in_selling();

            $('[name="carbon_fields_compact_input[_pl_currently_on_leased]"]').on('change',function (e){
                hide_show_on_change_pl_currently_on_leased();
            });
            hide_show_on_change_pl_currently_on_leased();

            make_carbon_input_plus_minus_input($('[name="carbon_fields_compact_input[_pl_bathroom]"]'));
            make_carbon_input_plus_minus_input($('[name="carbon_fields_compact_input[_pl_bedroom]"]'));
            make_carbon_input_plus_minus_input($('[name="carbon_fields_compact_input[_pl_parking]"]'));

            $("[name='carbon_fields_compact_input[_pl_property_type]']").closest('.cf-field__body').append('<label id="carbon_fields_compact_input[_pl_property_type]-error" class="cf-field__error" for="carbon_fields_compact_input[_pl_property_type]"></label>');


            let step_3_box = $(".step-3-inputs").parent('.cf-container__fields');
            if(step_3_box.length > 0){
                step_3_box.append($('.pl-property-features-box'));
                step_3_box.append($('.pl-property-manually-features-box'));
            }

            let step_4_box = $(".step-4-inputs").parent('.cf-container__fields');
            if(step_4_box.length > 0){
                step_4_box.append($('.pl-shares'));
                step_4_box.append($('.pl-calculated'));
            }


            $.validator.setDefaults({
                normalizer: function (value) {
                    return $.trim(value);
                },
                debug: false,
                errorClass: "cf-field__error",
            });

            let property_form = $('form[name="post"]').validate({
                ignore : '.ignore',
                rules : {
                    _pl_calculated : {required : true},
                    _pl_i_want_to_sell : {required : true},
                    'post_author_override' : {required : true},
                    'carbon_fields_compact_input[_pl_property_type]' : {required : true},
                    '_pl_property_features[]' : {required : true},
                    '_pl_address' : {required : true},
                    'carbon_fields_compact_input[_pl_street_no]' : {required : true},
                    'carbon_fields_compact_input[_pl_street_name]' : {required : true},
                    'carbon_fields_compact_input[_pl_suburb]' : {required : true},
                    'carbon_fields_compact_input[_pl_postcode]' : {required : true},
                    'carbon_fields_compact_input[_pl_state]' : {required : true},
                    'carbon_fields_compact_input[_pl_rent_per_month]' : {required : true},
                },
                submitHandler(form){
                    if($('.img-preview-box').length == 0){
                        if(!($("#property-image-error").length > 0)){
                            $('.media-select').parent().append('<label class="text-error" id="property-image-error">Please select or upload property images.</label>');
                        }
                    } else {
                        $("#property-image-error").remove();
                        form.submit();
                    }
                },
            });


            let property_image = wp.media({
                title: 'Select Or Upload Image',
                multiple: true
            });
            $("#open-wp-media-library").on('click',function (e) {
                property_image.open();
            });
            let selected_images = [];
            property_image.on('select', function(e){
                let images = property_image.state().get('selection').toJSON();
                $("#property-image-error").remove();
                $.each(images,function (index,item) {
                    if($.inArray( item.id,selected_images ) < 0){
                        selected_images.push(item.id);
                        let html = '<div class="co-owner-col co-owner-col-md-2 img-preview-box"><input type="hidden" name="_pl_property_new_image[]" value="'+item.id+'"><img src="'+item.url+'" alt=""><a href="#" class="text-error remove-selected-image">Remove</a></div>';
                        $('.media-images').prepend(html);
                    }
                });
            });

            $(document).on('click','.remove-selected-image',function (e) {
                e.preventDefault();
                let removeItem = $(this).parent().find('input').val();
                selected_images.splice( $.inArray(removeItem, selected_images), 1 );
                $(this).closest('.img-preview-box').fadeOut(500,function(e){ $(this).remove(); });
            });

            $(document).on('click','.remove-old-image',function (e) {
                e.preventDefault();
                let removeItem = $(this).data('index');
                if($('.remove-old-image').length > 1){
                    let html = '<input type="hidden" name="remove_old_image[]" value="'+removeItem+'">';
                    $('.media-images').append(html);
                    $(this).closest('.img-preview-box').fadeOut(500,function(e){ $(this).remove(); });
                } else {
                    toastr.error("Please You don't remove all images.");
                }
            });

            $('.single-pr-select2').each(function (index,element) {
                $(element).select2({
                    tags: true,
                    dropdownParent: $(this).parent(),
                    containerCssClass: " pr-for-price",
                    dropdownCssClass: " pr-for-price",
                    createTag: function (params) {
                        var term = $.trim(params.term);

                        if (term === '') {
                            return null;
                        }

                        let max = $(element).data('max') ? $(element).data('max') : 99.00;

                        let value = (parseFloat(max) < parseFloat(term)) ? max : parseFloat(term).toFixed(0);

                        return {
                            id: value,
                            text: value+'%',
                            newTag: true
                        }
                    }
                })
            }).on('select2:open',function (e) {
                if($(this).hasClass('property-input-disable')){
                    $('.select2-search--dropdown').remove();
                }
            });

            $(document).on('keypress input',".pr-for-price input.select2-search__field",function (e) {
                if (((event.which != 46 || (event.which == 46 && $(this).val() == '')) || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                    event.preventDefault();
                }
                let value = $(this).val();
                if(value > 99){
                    $(this).val(99);
                }
            });

            $(document).on('change','#_pl_i_want_to_sell',function () {
                var propertyShareInput = $(this).val();
                var propertyValueInput = $($(this).data('property-value-input'));
                var calculatedValueInput = $($(this).data('calculated-value-input'));
                change_property_price_inputs(propertyShareInput,propertyValueInput,calculatedValueInput);
            });

            $(document).on('input','.property-market-price input',function () {
                let self = '#_pl_i_want_to_sell';
                var propertyShareInput = $(self).val();
                var propertyValueInput = $($(self).data('property-value-input'));
                var calculatedValueInput = $($(self).data('calculated-value-input'));
                change_property_price_inputs(propertyShareInput,propertyValueInput,calculatedValueInput);
            });

            make_property_disabled_if_property_members();

            $(".step-2-parent").prepend($(".pl-address-api-select"));

            setTimeout(() => {
                $('.select2-property-address-api').select2({
                    width:'100%',
                    placeholder: 'Search Property Address.',
                    minimumInputLength: 3,
                    multiple: true,
                    maximumSelectionLength: 1,
                    ajax: {
                        url: php_vars.ajax_url,
                        method: 'post',
                        data: function (params) {
                            return {
                                search: params.term,
                                ajax_nonce: php_vars.ajax_nonce,
                                action : 'get_property_addresses',
                            };
                        },
                        processResults: function (data) {
                            data = JSON.parse(data);
                            return {
                                results : data.items
                            };
                        }
                    }
                }).on('select2:select',function(e){
                    let data = (e.hasOwnProperty('params') && e.params.hasOwnProperty('data')) ? e.params.data : null;
                    if(data){
                        let box = $('.step-2-parent');
                        box.find('[name="carbon_fields_compact_input[_pl_unit_no]"]').val(data.unitNumber);
                        box.find('[name="carbon_fields_compact_input[_pl_street_no]"]').val(data.streetNumber);
                        box.find('[name="carbon_fields_compact_input[_pl_street_name]"]').val(data.streetName);
                        box.find('[name="carbon_fields_compact_input[_pl_suburb]"]').val(data.suburb);
                        box.find('[name="carbon_fields_compact_input[_pl_postcode]"]').val(data.postCode);
                        box.find('[name="carbon_fields_compact_input[_pl_state]"]').val(data.state).change();

                    }
                });

                $(document).on('click','.add-manually-property-address',function(e){
                    e.preventDefault();
                    hide_show_address_fields();
                });
                let box = $('.step-2-parent');
                box.find('[name="carbon_fields_compact_input[_pl_unit_no]"]').closest('.cf-field').addClass('address-manually');
                box.find('[name="carbon_fields_compact_input[_pl_street_no]"]').closest('.cf-field').addClass('address-manually');
                box.find('[name="carbon_fields_compact_input[_pl_street_name]"]').closest('.cf-field').addClass('address-manually');
                box.find('[name="carbon_fields_compact_input[_pl_suburb]"]').closest('.cf-field').addClass('address-manually');
                box.find('[name="carbon_fields_compact_input[_pl_postcode]"]').closest('.cf-field').addClass('address-manually');
                box.find('[name="carbon_fields_compact_input[_pl_state]"]').closest('.cf-field').addClass('address-manually');

                let address_manually = $('[name="_pl_address_manually"]');
                let manually_box = $('.address-manually');
                let suggest_box = $('.address-by-suggest');

                if(address_manually.val() == 'true'){
                    manually_box.show(500);
                    suggest_box.hide(500);
                } else {
                    manually_box.hide(500);
                    suggest_box.show(500);
                }
            },500);
        }

        else if(body.hasClass())
        {

        }
    });

    $(document).on('click','.select-how-its-works',function(e){
        let media = wp.media({
            multiple: false,
            library : {
                type : 'video',
            }
        }).open().on('select',function (e) {
            let file = media.state().get('selection').first().toJSON();
            $(".how-its-works-input-link").find('input').val(file.url);
        });
    });

    function hide_show_address_fields()
    {
        let self = $('.add-manually-property-address');
        let address_manually = $('[name="_pl_address_manually"]');
        let manually_box = $('.address-manually');
        let suggest_box = $('.address-by-suggest');

        if(address_manually.val() == 'true'){
            manually_box.hide(500);
            suggest_box.show(500);
            address_manually.val('false');
            self.html('Add Manually');
            suggest_box.find('select').removeClass('ignore');
            manually_box.find('input,select').addClass('ignore');
        } else {
            manually_box.show(500);
            suggest_box.hide(500);
            self.html('Add By Suggestion');
            address_manually.val('true');
            manually_box.find('select').val(null).change();
            manually_box.find('input[type="text"]').val(null);
            manually_box.find('input,select').removeClass('ignore');
            manually_box.find('input[type="checkbox"]').prop('checked',false);
            suggest_box.find('select').addClass('ignore').val(null).change();
        }
    }

    function make_property_disabled_if_property_members()
    {
        let count = $("#property-members-inputs").find('.member-box').length
        if(count > 1){
            let inputs = [
                'name="carbon_fields_compact_input[_pl_property_market_price]"',
                'name="carbon_fields_compact_input[_pl_interested_in_selling]"',
                'name="carbon_fields_compact_input[_pl_this_property_is]"',
                'name="carbon_fields_compact_input[_pl_enable_pool]"',
                'name="_pl_i_want_to_sell"',
                'name="_pl_calculated"',
            ];

            $.each(inputs,function (index,item) {
                let self = $('['+item+']');
                self.addClass('property-input-disable');
                if(self.prop('type') == 'text'){
                    self.prop('readonly',true);
                }
            });

            $('select.property-input-disable').find('option:not(:selected)').remove();
            $(document).on('click change input','.property-input-disable',function(e){
                e.preventDefault();
            });
        }
    }

    function change_property_price_inputs(propertyShareInput,propertyValueInput,calculatedValueInput)
    {
        if(
            propertyValueInput.length > 0 &&
            calculatedValueInput.length > 0
        ) {
            if(
                propertyValueInput.is(':visible') === true &&
                calculatedValueInput.is(':visible') === true
            ) {
                let calculated = calculateSale(propertyValueInput.val(),propertyShareInput);
                calculatedValueInput.val(calculated);
            } else {
                calculatedValueInput.val(null);
            }
        }
    }

    function make_carbon_input_plus_minus_input(element)
    {
        element.addClass('form-control input-only-number');
        element.parent('.cf-field__body').addClass('d-flex align-items-center room-counter');
        element.parent('.cf-field__body').prepend('<a href="#" class="text-center counter-minus">-</a>');
        element.parent('.cf-field__body').append('<a href="#" class="text-center counter-plus">+</a>');
    }

    function hide_show_on_change_pl_currently_on_leased()
    {
        let currently_on_leased = $('[name="carbon_fields_compact_input[_pl_currently_on_leased]"]:checked').val();
        let rate_per_month = $('.pl-currently-on-leased');
        if(currently_on_leased == 'Yes'){
            rate_per_month.show().find('input').removeClass('ignore');
        } else {
            rate_per_month.hide().find('input').val(0).addClass('ignore');
        }
    }

    function hide_show_on_change_interested_in_selling()
    {
        let property_category = $('[name="carbon_fields_compact_input[_pl_interested_in_selling]"]:checked').val();
        let residential_inputs = $('.portion-of-it-box');
        if(property_category == 'portion_of_it'){
            residential_inputs.show();
            residential_inputs.find('select').removeClass('ignore');
            residential_inputs.find('input').removeClass('ignore');
        } else {
            residential_inputs.hide();
            residential_inputs.find('input').val(null);
            residential_inputs.find('select').val(null).change();
            residential_inputs.find('select').addClass('ignore');
            residential_inputs.find('input').addClass('ignore');
        }
    }

    function hide_show_on_change_property_category()
    {
        let property_category = $('[name="carbon_fields_compact_input[_pl_property_category]"]:checked').val();
        let residential_inputs = $('.residential-inputs');
        let commercial_inputs = $('.commercial-inputs');
        if(property_category == 'commercial'){
            $('input[value="house"]').prop('checked',false).parent('.cf-radio__list-item').hide().find('input').addClass('ignore');
            $('input[value="apartment"]').prop('checked',false).parent('.cf-radio__list-item').hide().find('input').addClass('ignore');
            $('input[value="townhouse"]').prop('checked',false).parent('.cf-radio__list-item').hide().find('input').addClass('ignore');
            $('input[value="land"]').prop('checked',false).parent('.cf-radio__list-item').hide().find('input').addClass('ignore');
            $('input[value="retirement"]').prop('checked',false).parent('.cf-radio__list-item').hide().find('input').addClass('ignore');

            $('input[value="office"]').parent('.cf-radio__list-item').show().find('input').removeClass('ignore');
            $('input[value="leisure"]').parent('.cf-radio__list-item').show().find('input').removeClass('ignore');
            $('input[value="retails"]').parent('.cf-radio__list-item').show().find('input').removeClass('ignore');
            $('input[value="healthcare"]').parent('.cf-radio__list-item').show().find('input').removeClass('ignore');
            $('input[value="multifamily"]').parent('.cf-radio__list-item').show().find('input').removeClass('ignore');

            residential_inputs.hide();
            residential_inputs.find('.input-only-number').val(0);
            residential_inputs.find('[type="checkbox"]').prop('checked',false);
            residential_inputs.find('select').addClass('ignore').val(null).change();
            residential_inputs.find('input').addClass('ignore');
            commercial_inputs.show();
            commercial_inputs.find('.ignore').removeClass('ignore');
        } else {
            $('input[value="house"]').parent('.cf-radio__list-item').show().find('input').removeClass('ignore');
            $('input[value="apartment"]').parent('.cf-radio__list-item').show().find('input').removeClass('ignore');
            $('input[value="townhouse"]').parent('.cf-radio__list-item').show().find('input').removeClass('ignore');
            $('input[value="land"]').parent('.cf-radio__list-item').show().find('input').removeClass('ignore');
            $('input[value="retirement"]').parent('.cf-radio__list-item').show().find('input').removeClass('ignore');

            $('input[value="office"]').prop('checked',false).parent('.cf-radio__list-item').hide().find('input').addClass('ignore');
            $('input[value="leisure"]').prop('checked',false).parent('.cf-radio__list-item').hide().find('input').addClass('ignore');
            $('input[value="retails"]').prop('checked',false).parent('.cf-radio__list-item').hide().find('input').addClass('ignore');
            $('input[value="healthcare"]').prop('checked',false).parent('.cf-radio__list-item').hide().find('input').addClass('ignore');
            $('input[value="multifamily"]').prop('checked',false).parent('.cf-radio__list-item').hide().find('input').addClass('ignore');
            residential_inputs.show();
            residential_inputs.find('.ignore').removeClass('ignore');
            commercial_inputs.hide();
            commercial_inputs.find('.input-only-number').val(0);
            commercial_inputs.find('[type="checkbox"]').prop('checked',false);
            commercial_inputs.find('select').addClass('ignore').val(null).change();
            commercial_inputs.find('input').addClass('ignore');
        }
    }

    function select_property_category_person_page()
    {
        var arr = $('[name="_user_property_category[]"]:checked').map(function () { return this.value; }).get();
        let commercial = '.commercial';
        let residential = '.residential';
        if($.inArray('commercial',arr) >= 0){
            $(commercial).show();
        } else {
            $(commercial).hide();
            $(commercial).find('input').prop('checked',false);
        }
        if($.inArray('residential',arr) >= 0){
            $(residential).show();
            $(residential).find('input').show();
        } else {
            $(residential).hide();
            $(residential).find('input').hide();
            $(residential).find('input.input-only-number').val(0);
            $(residential).find('input').prop('checked',false);
        }
        if(arr.length === 0){
            $('.residential-commercial').hide();
        }else{
            $('.residential-commercial').show();
        }
    }

    function remove_url_segment(parameter = null)
    {
        let url = window.location.toString();
        if(parameter) {
            var urlparts = url.split('?');
            if (urlparts.length >= 2) {
                let prefix = encodeURIComponent(parameter) + '=';
                let pars = urlparts[1].split(/[&;]/g);
                for (let i = pars.length; i-- > 0;) {
                    if (pars[i].lastIndexOf(prefix, 0) !== -1) {
                        pars.splice(i, 1);
                    }
                }
                url = urlparts[0] + (pars.join('&') !== "" ? '?' + pars.join('&') : "");
            }
        } else {
            if (url.indexOf("?") > 0) {
                url = url.substring(0, url.indexOf("?"));
            }
        }
        window.history.replaceState({}, document.title, url);
    }

    remove_url_segment('co_owner_alert');
    remove_url_segment('co_owner_alert_type');

    function post_button_disabled(isLocked)
    {
        const nodes = document.querySelectorAll( `
			#publishing-action input#publish,
			#publishing-action input#save,
			#addtag input#submit,
			#edittag input[type="submit"],
			#your-profile input#submit
		`);

        nodes.forEach( ( node ) => {
            node.disabled = isLocked;
        } );
    }

    if(php_vars.hasOwnProperty('alert_toastr')){
        let type = php_vars.hasOwnProperty('alert_toastr_type') ? php_vars.alert_toastr_type : 'success';
        toastr[type](php_vars.alert_toastr);
        remove_url_segment('co_owner_toastr');
        remove_url_segment('co_owner_toastr_type');
    }

    let form = $('#reject-user-shield-form');
    let is_error = $("#_document_shield_reject_reason_error");
    let reason = $('[name="_document_shield_reject_reason"]');

    let dialog = $( "#reject-user-shield" ).dialog({
        autoOpen: false,
        draggable: false,
        modal: true,
        buttons: [
            {
                text: "Cancel",
                click: function() {
                    $( this ).dialog( "close" );
                }
            },
            {
                text: "Submit",
                type : 'submit',
                click: function() {
                    if(reason.val().trim().length > 10 && reason.val().trim().length < 2000){
                        is_error.html(null);
                        form.submit();
                    } else {
                        is_error.html("Please enter valid reason.with 10 to 2000 string length.");
                    }
                }
            }
        ],
        close: function() {
            $("#user_id").val(null);
            form.get(0).reset();
            is_error.html(null);
        }
    });

    $('.reject-user-shield').click(function (e) {
        let self = $(this);
        $("#user_id").val(self.data('user'));
        dialog.dialog( "open" );
    });

    $('.carbon-box').find('iframe').removeAttr('style');
});


