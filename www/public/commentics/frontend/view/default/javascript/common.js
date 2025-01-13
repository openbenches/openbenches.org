/* Wait for jQuery to load, in case loaded after Commentics */
var cmtx_wait_for_jquery = setInterval(function() {
    /* jQuery is loaded */
    if (window.jQuery) {
        clearInterval(cmtx_wait_for_jquery);

        /* The document (excluding images) has finished loading */
        jQuery(document).ready(function() {
            /* Form settings may not exist (if the form is disabled) */
            if (jQuery('#cmtx_js_settings_form').length) {
                cmtx_js_settings_form = JSON.parse(jQuery('#cmtx_js_settings_form').text());
            }

            /* Comment settings may not exist (if there are no comments) */
            if (jQuery('#cmtx_js_settings_comments').length) {
                cmtx_js_settings_comments = JSON.parse(jQuery('#cmtx_js_settings_comments').text());
            }

            /* Notify settings may not exist (if the notify feature is disabled) */
            if (jQuery('#cmtx_js_settings_notify').length) {
                cmtx_js_settings_notify = JSON.parse(jQuery('#cmtx_js_settings_notify').text());
            }

            /* Online settings may not exist (if the online feature is disabled) */
            if (jQuery('#cmtx_js_settings_online').length) {
                cmtx_js_settings_online = JSON.parse(jQuery('#cmtx_js_settings_online').text());
            }

            /* User settings may not exist (if not on user page) */
            if (jQuery('#cmtx_js_settings_user').length) {
                cmtx_js_settings_user = JSON.parse(jQuery('#cmtx_js_settings_user').text());
            }

            /* Is Commentics loaded using the iFrame integration method */
            isInIframe = (window.location != window.parent.location) ? true : false;

            /* Show a BB Code modal */
            jQuery('#cmtx_container span[data-cmtx-target-modal]').click(function(e) {
                e.preventDefault();

                var target = jQuery(this).attr('data-cmtx-target-modal');

                jQuery('body').append(jQuery(target));

                jQuery('body').append('<div class="cmtx_overlay"></div>');

                /*
                 * The following stops the modal from showing in the vertical centre of the iFrame
                 * Instead we position the modal relative to an element on the page
                 */
                if (isInIframe) {
                    var destination = jQuery('.cmtx_bb_code_container').offset();

                    jQuery(jQuery(target)).css({top: destination.top + 150});

                    /*
                     * The overlay is set to transparent as otherwise it would only cover the iFrame
                     * It's still useful to show it so that clicking on the overlay closes the modal
                     */
                    jQuery('.cmtx_overlay').css('background-color', 'transparent');
                }

                jQuery('.cmtx_overlay').fadeIn(200);

                jQuery(target).fadeIn(200);
            });

            /* Show an agreement modal */
            jQuery('#cmtx_container').on('click', 'a[data-cmtx-target-modal]', function(e) {
                e.preventDefault();

                var target = jQuery(this).attr('data-cmtx-target-modal');

                jQuery('body').append(jQuery(target));

                jQuery('body').append('<div class="cmtx_overlay"></div>');

                if (isInIframe) {
                    if (jQuery(this).closest('.quick_reply').length) {
                        var destination = jQuery('.quick_reply').offset();
                    } else if (jQuery(this).closest('.edit_comment').length) {
                        var destination = jQuery('.edit_comment').offset();
                    } else {
                        var destination = jQuery('.cmtx_checkbox_container').offset();
                    }

                    jQuery(jQuery(target)).css({top: destination.top - 150});

                    jQuery('.cmtx_overlay').css('background-color', 'transparent');
                }

                jQuery('.cmtx_overlay').fadeIn(200);

                jQuery(target).fadeIn(200);
            });

            /* Show the flag modal */
            jQuery('#cmtx_container').on('click', '.cmtx_flag_link', function(e) {
                e.preventDefault();

                var comment_id = jQuery(this).closest('.cmtx_comment_box').attr('data-cmtx-comment-id');

                jQuery('#cmtx_flag_modal_yes').attr('data-cmtx-comment-id', comment_id);

                if (jQuery('body > #cmtx_flag_modal').length === 0) {
                    jQuery('body').append(jQuery('#cmtx_flag_modal'));
                }

                jQuery('body').append('<div class="cmtx_overlay"></div>');

                if (isInIframe) {
                    var destination = jQuery(this).offset();

                    jQuery('#cmtx_flag_modal').css({top: destination.top - 150});

                    jQuery('.cmtx_overlay').css('background-color', 'transparent');
                }

                jQuery('.cmtx_overlay').fadeIn(200);

                jQuery('#cmtx_flag_modal').fadeIn(200);
            });

            /* Show the delete modal */
            jQuery('#cmtx_container').on('click', '.cmtx_delete_link', function(e) {
                e.preventDefault();

                var comment_id = jQuery(this).closest('.cmtx_comment_box').attr('data-cmtx-comment-id');

                jQuery('#cmtx_delete_modal_yes').attr('data-cmtx-comment-id', comment_id);

                if (jQuery('body > #cmtx_delete_modal').length === 0) {
                    jQuery('body').append(jQuery('#cmtx_delete_modal'));
                }

                jQuery('body').append('<div class="cmtx_overlay"></div>');

                if (isInIframe) {
                    var destination = jQuery(this).offset();

                    jQuery('#cmtx_delete_modal').css({top: destination.top - 150});

                    jQuery('.cmtx_overlay').css('background-color', 'transparent');
                }

                jQuery('.cmtx_overlay').fadeIn(200);

                jQuery('#cmtx_delete_modal').fadeIn(200);
            });

            /* Modal cancel button */
            jQuery('body').on('click', '.cmtx_modal_box .cmtx_button_secondary', function(e) {
                e.preventDefault();

                jQuery('.cmtx_modal_close').trigger('click');
            });

            /* Close modal */
            jQuery('body').on('click', '.cmtx_modal_close, .cmtx_overlay', function(e) {
                e.preventDefault();

                jQuery('.cmtx_modal_box, .cmtx_overlay').fadeOut(200, function() {
                    jQuery('.cmtx_overlay').remove();
                });
            });

            /* Style the blank select options as placeholders */
            var selected = jQuery('.cmtx_form_container select').find('option:selected');

            if (selected.val() == '') {
                jQuery('.cmtx_form_container select').css('color', '#666');
                jQuery('.cmtx_form_container select').children().css('color', 'black');
            }

            jQuery('body').on('change', '.cmtx_form_container select', function() {
                if (jQuery(this).find('option:selected').val() == '') {
                    jQuery(this).css('color', '#666');
                    jQuery(this).children().css('color', 'black');
                } else {
                    jQuery(this).css('color', 'black');
                    jQuery(this).children().css('color', 'black');
                }
            });

            /* When the comment field gets focus, show some other fields */
            jQuery('#cmtx_comment').focus(function() {
                jQuery(this).addClass('cmtx_comment_field_active');

                jQuery('.cmtx_comment_container').addClass('cmtx_comment_container_active');

                if (jQuery('input[name="cmtx_reply_to"]').val() == '') {
                    jQuery('.cmtx_wait_for_comment').fadeIn('slow');
                }
            });

            if (typeof(cmtx_js_settings_form) != 'undefined') {
                if (!cmtx_js_settings_form.cmtx_wait_for_comment) {
                    jQuery('#cmtx_comment').addClass('cmtx_comment_field_active');

                    jQuery('.cmtx_comment_container').addClass('cmtx_comment_container_active');

                    if (jQuery('input[name="cmtx_reply_to"]').val() == '') {
                        jQuery('.cmtx_wait_for_comment').fadeIn('slow');
                    }
                }
            }

            /* When the name or email field gets focus, show some other fields */
            jQuery('#cmtx_name, #cmtx_email').focus(function() {
                if (jQuery('input[name="cmtx_subscribe"]').val() == '') {
                    jQuery('.cmtx_wait_for_user').fadeIn('slow');
                }
            });

            /* Adds a BB Code tag for the simple non-modal ones */
            jQuery('.cmtx_bb_code:not([data-cmtx-target-modal])').click(function() {
                var bb_code = jQuery(this).attr('data-cmtx-tag');

                if (bb_code) {
                    bb_code = bb_code.split('|');

                    if (typeof(bb_code[1]) === 'undefined') {
                        cmtx_add_tag('', bb_code[0]);
                    } else {
                        cmtx_add_tag(bb_code[0], bb_code[1]);
                    }
                }
            });

            /* Adds a smiley tag */
            jQuery('.cmtx_smilies_container .cmtx_smiley').click(function() {
                var smiley = jQuery(this).attr('data-cmtx-tag');

                cmtx_add_tag('', smiley);
            });

            /* Insert content from bullet modal */
            jQuery('#cmtx_bullet_modal_insert').click(function(e) {
                var bb_code = jQuery('.cmtx_bb_code_bullet').attr('data-cmtx-tag');

                bb_code = bb_code.split('|');

                var tag = '';

                jQuery('#cmtx_bullet_modal input[type="text"]').each(function() {
                    var item = cmtxTrim(jQuery(this).val());

                    if (item != null && item != '') {
                        tag += bb_code[1] + item + bb_code[2] + '\r\n';
                    }
                });

                if (tag != null && tag != '') {
                    tag = bb_code[0] + '\r\n' + tag + bb_code[3];

                    cmtx_add_tag('', tag);
                }

                jQuery('#cmtx_bullet_modal input[type="text"]').val('');

                jQuery('.cmtx_modal_close').trigger('click');
            });

            /* Insert content from numeric modal */
            jQuery('#cmtx_numeric_modal_insert').click(function(e) {
                var bb_code = jQuery('.cmtx_bb_code_numeric').attr('data-cmtx-tag');

                bb_code = bb_code.split('|');

                var tag = '';

                jQuery('#cmtx_numeric_modal input[type="text"]').each(function() {
                    var item = cmtxTrim(jQuery(this).val());

                    if (item != null && item != '') {
                        tag += bb_code[1] + item + bb_code[2] + '\r\n';
                    }
                });

                if (tag != null && tag != '') {
                    tag = bb_code[0] + '\r\n' + tag + bb_code[3];

                    cmtx_add_tag('', tag);
                }

                jQuery('#cmtx_numeric_modal input[type="text"]').val('');

                jQuery('.cmtx_modal_close').trigger('click');
            });

            /* Insert content from link modal */
            jQuery('#cmtx_link_modal_insert').click(function(e) {
                var bb_code = jQuery('.cmtx_bb_code_link').attr('data-cmtx-tag');

                bb_code = bb_code.split('|');

                var link = cmtxTrim(jQuery('#cmtx_link_modal input[type="url"]').val());

                if (link != null && link != '' && link != 'http://') {
                    var text = cmtxTrim(jQuery('#cmtx_link_modal input[type="text"]').val());

                    if (text != null && text != '') {
                        var tag = bb_code[1] + link + bb_code[2] + text + bb_code[3];
                    } else {
                        var tag = bb_code[0] + link + bb_code[3];
                    }

                    cmtx_add_tag('', tag);
                }

                jQuery('#cmtx_link_modal input[type="url"]').val('http://');

                jQuery('#cmtx_link_modal input[type="text"]').val('');

                jQuery('.cmtx_modal_close').trigger('click');
            });

            /* Insert content from email modal */
            jQuery('#cmtx_email_modal_insert').click(function(e) {
                var bb_code = jQuery('.cmtx_bb_code_email').attr('data-cmtx-tag');

                bb_code = bb_code.split('|');

                var email = cmtxTrim(jQuery('#cmtx_email_modal input[type="email"]').val());

                if (email != null && email != '') {
                    var text = cmtxTrim(jQuery('#cmtx_email_modal input[type="text"]').val());

                    if (text != null && text != '') {
                        var tag = bb_code[1] + email + bb_code[2] + text + bb_code[3];
                    } else {
                        var tag = bb_code[0] + email + bb_code[3];
                    }

                    cmtx_add_tag('', tag);
                }

                jQuery('#cmtx_email_modal input[type="email"]').val('');

                jQuery('#cmtx_email_modal input[type="text"]').val('');

                jQuery('.cmtx_modal_close').trigger('click');
            });

            /* Insert content from image modal */
            jQuery('#cmtx_image_modal_insert').click(function(e) {
                var bb_code = jQuery('.cmtx_bb_code_image').attr('data-cmtx-tag');

                bb_code = bb_code.split('|');

                var image = cmtxTrim(jQuery('#cmtx_image_modal input[type="url"]').val());

                if (image != null && image != '' && image != 'http://') {
                    var tag = bb_code[0] + image + bb_code[1];

                    cmtx_add_tag('', tag);
                }

                jQuery('#cmtx_image_modal input[type="url"]').val('http://');

                jQuery('.cmtx_modal_close').trigger('click');
            });

            /* Insert content from YouTube modal */
            jQuery('#cmtx_youtube_modal_insert').click(function(e) {
                var bb_code = jQuery('.cmtx_bb_code_youtube').attr('data-cmtx-tag');

                bb_code = bb_code.split('|');

                var video = cmtxTrim(jQuery('#cmtx_youtube_modal input[type="url"]').val());

                if (video != null && video != '' && video != 'http://') {
                    var tag = bb_code[0] + video + bb_code[1];

                    cmtx_add_tag('', tag);
                }

                jQuery('#cmtx_youtube_modal input[type="url"]').val('http://');

                jQuery('.cmtx_modal_close').trigger('click');
            });

            /* Update the comment counter whenever anything is entered */
            jQuery('#cmtx_comment').keyup(function(e) {
                cmtxUpdateCommentCounter();
            });

            /* Simulate entering a comment on page load to update the counter in case it has default text */
            cmtxUpdateCommentCounter();

            /* Allows the user to deselect the star rating */
            jQuery('input[type="radio"][name="cmtx_rating"]').on('click', function() {
                if (jQuery(this).is('.cmtx_rating_active')) {
                    jQuery(this).prop('checked', false).removeClass('cmtx_rating_active');
                } else {
                    jQuery('input[type="radio"][name="cmtx_rating"].cmtx_rating_active').removeClass('cmtx_rating_active');

                    jQuery(this).addClass('cmtx_rating_active');
                }
            });

            /* Image uploads */
            if (typeof(cmtx_js_settings_form) != 'undefined') {
                if (cmtx_js_settings_form.enabled_upload) {
                    total_size = 0;

                    jQuery('#cmtx_upload').change(function(e) {
                        e.preventDefault();

                        e.stopPropagation();

                        var image = jQuery('#cmtx_upload')[0].files[0];

                        cmtx_upload(image);
                    });

                    function cmtx_upload(image) {
                        jQuery('.cmtx_upload_container').removeClass('cmtx_dragging');

                        if (image) {
                            var size = parseFloat((image.size / 1024 / 1024).toFixed(2));
                            var filename = image.name;
                            var extension = filename.split('.').pop().toLowerCase();

                            if (cmtx_validate_upload(size, extension)) {
                                var reader = new FileReader();

                                reader.onload = function(e) {
                                    var src = e.target.result;

                                    template = '';

                                    template += '<div class="cmtx_image_upload">';
                                    template += '    <div class="cmtx_image_section">';
                                    template += '        <img src="' + src + '" draggable="false" data-cmtx-size="' + size + '">';
                                    template += '        <span class="cmtx_image_overlay">' + size + ' MB</span>';
                                    template += '    </div>';
                                    template += '    <div class="cmtx_button_section">';
                                    template += '        <button type="button" class="cmtx_button cmtx_button_remove" title="' + cmtx_js_settings_form.lang_button_remove + '">' + cmtx_js_settings_form.lang_button_remove + '</button>';
                                    template += '    </div>';
                                    template += '</div>';

                                    jQuery('.cmtx_image_container').append(template);
                                    jQuery('.cmtx_image_row').show();
                                };

                                reader.readAsDataURL(image);
                            }
                        }
                    }

                    jQuery('.cmtx_upload_container').bind('dragenter', function(e) {
                        e.preventDefault();

                        e.stopPropagation();
                    });

                    jQuery('.cmtx_upload_container').bind('dragover', function(e) {
                        e.preventDefault();

                        e.stopPropagation();

                        jQuery('.cmtx_upload_container').addClass('cmtx_dragging');
                    });

                    jQuery('.cmtx_upload_container').bind('dragleave', function(e) {
                        e.preventDefault();

                        e.stopPropagation();

                        jQuery('.cmtx_upload_container').removeClass('cmtx_dragging');
                    });

                    jQuery('body').on('click', '.cmtx_button_remove', function(e) {
                        e.preventDefault();

                        jQuery(this).closest('.cmtx_image_upload').remove();

                        // Hide container if no images
                        var num_images = jQuery('.cmtx_image_upload').length;

                        if (num_images == 0) {
                            jQuery('.cmtx_image_row').hide();
                        }
                    });

                    jQuery('body').on('drop', '#cmtx_upload', function(e) {
                        e.preventDefault();

                        e.stopPropagation();

                        if (e.originalEvent.dataTransfer && e.originalEvent.dataTransfer.files.length) {
                            cmtx_upload(e.originalEvent.dataTransfer.files[0]);
                        }
                    });

                    function cmtx_validate_upload(size, extension) {
                        var num_images = jQuery('.cmtx_image_upload').length;

                        var total_size = 0;

                        jQuery('.cmtx_image_upload').each(function() {
                            total_size = Number(total_size) + Number(jQuery(this).find('img').attr('data-cmtx-size'));
                        });

                        total_size = Number(total_size) + Number(size);

                        if (num_images >= cmtx_js_settings_form.maximum_upload_amount) {
                            jQuery('#cmtx_upload_modal .cmtx_modal_body').html('<span class="cmtx_icon cmtx_alert_icon" aria-hidden="true"></span> ' + cmtx_js_settings_form.lang_error_file_num.replace('%d', cmtx_js_settings_form.maximum_upload_amount));

                            jQuery('body').append(jQuery('#cmtx_upload_modal'));

                            jQuery('body').append('<div class="cmtx_overlay"></div>');

                            if (isInIframe) {
                                var destination = jQuery('.cmtx_upload_container').offset();

                                jQuery('#cmtx_upload_modal').css({top: destination.top + 50});

                                jQuery('.cmtx_overlay').css('background-color', 'transparent');
                            }

                            jQuery('.cmtx_overlay').fadeIn(200);

                            jQuery('#cmtx_upload_modal').fadeIn(200);

                            return false;
                        }

                        if (size > cmtx_js_settings_form.maximum_upload_size) {
                            jQuery('#cmtx_upload_modal .cmtx_modal_body').html('<span class="cmtx_icon cmtx_alert_icon" aria-hidden="true"></span> ' + cmtx_js_settings_form.lang_error_file_size.replace('%.1f', cmtx_js_settings_form.maximum_upload_size));

                            jQuery('body').append(jQuery('#cmtx_upload_modal'));

                            jQuery('body').append('<div class="cmtx_overlay"></div>');

                            if (isInIframe) {
                                var destination = jQuery('.cmtx_upload_container').offset();

                                jQuery('#cmtx_upload_modal').css({top: destination.top + 50});

                                jQuery('.cmtx_overlay').css('background-color', 'transparent');
                            }

                            jQuery('.cmtx_overlay').fadeIn(200);

                            jQuery('#cmtx_upload_modal').fadeIn(200);

                            return false;
                        }

                        if (total_size > cmtx_js_settings_form.maximum_upload_total) {
                            jQuery('#cmtx_upload_modal .cmtx_modal_body').html('<span class="cmtx_icon cmtx_alert_icon" aria-hidden="true"></span> ' + cmtx_js_settings_form.lang_error_file_total.replace('%.1f', cmtx_js_settings_form.maximum_upload_total));

                            jQuery('body').append(jQuery('#cmtx_upload_modal'));

                            jQuery('body').append('<div class="cmtx_overlay"></div>');

                            if (isInIframe) {
                                var destination = jQuery('.cmtx_upload_container').offset();

                                jQuery('#cmtx_upload_modal').css({top: destination.top + 50});

                                jQuery('.cmtx_overlay').css('background-color', 'transparent');
                            }

                            jQuery('.cmtx_overlay').fadeIn(200);

                            jQuery('#cmtx_upload_modal').fadeIn(200);

                            return false;
                        }

                        if (jQuery.inArray(extension, ['gif', 'jpg', 'jpeg', 'png']) == -1) {
                            jQuery('#cmtx_upload_modal .cmtx_modal_body').html('<span class="cmtx_icon cmtx_alert_icon" aria-hidden="true"></span> ' + cmtx_js_settings_form.lang_error_file_type);

                            jQuery('body').append(jQuery('#cmtx_upload_modal'));

                            jQuery('body').append('<div class="cmtx_overlay"></div>');

                            if (isInIframe) {
                                var destination = jQuery('.cmtx_upload_container').offset();

                                jQuery('#cmtx_upload_modal').css({top: destination.top + 50});

                                jQuery('.cmtx_overlay').css('background-color', 'transparent');
                            }

                            jQuery('.cmtx_overlay').fadeIn(200);

                            jQuery('#cmtx_upload_modal').fadeIn(200);

                            return false;
                        }

                        return true;
                    }
                }
            }

            /* Populate countries field */
            if (typeof(cmtx_js_settings_form) != 'undefined') {
                if (cmtx_js_settings_form.enabled_country) {
                    var request = jQuery.ajax({
                        type: 'POST',
                        cache: false,
                        url: cmtx_js_settings_form.commentics_url + 'frontend/index.php?route=main/form/getCountries',
                        dataType: 'json',
                        beforeSend: function() {
                            jQuery('#cmtx_country').html('<option value="">' + cmtx_js_settings_form.lang_text_loading + '</option>');
                        }
                    });

                    request.done(function(response) {
                        setTimeout(function() {
                            countries = response;

                            html = '<option value="" hidden>' + cmtx_js_settings_form.lang_placeholder_country + '</option>';

                            if (countries.length) {
                                for (i = 0; i < countries.length; i++) {
                                    html += '<option value="' + countries[i]['id'] + '"';

                                    if (countries[i]['name'] == '---') {
                                        html += ' disabled';
                                    } else if (countries[i]['id'] == cmtx_js_settings_form.country_id) {
                                        html += ' selected';
                                    }

                                    html += '>' + countries[i]['name'] + '</option>';
                                }
                            }

                            jQuery('#cmtx_country').html(html);

                            if (cmtx_js_settings_form.enabled_state) {
                                jQuery('#cmtx_country').trigger('change');
                            }
                        }, 500);
                    });

                    request.fail(function(jqXHR, textStatus, errorThrown) {
                        if (console && console.log) {
                            console.log(jqXHR.responseText);
                        }
                    });
                }
            }

            /* Populate states field (when country field is enabled) */
            if (typeof(cmtx_js_settings_form) != 'undefined') {
                if (cmtx_js_settings_form.enabled_country && cmtx_js_settings_form.enabled_state) {
                    jQuery('#cmtx_country').bind('change', function() {
                        var country_id = encodeURIComponent(jQuery('#cmtx_country').val());

                        if (country_id) {
                            var request = jQuery.ajax({
                                type: 'POST',
                                cache: false,
                                url: cmtx_js_settings_form.commentics_url + 'frontend/index.php?route=main/form/getStates',
                                data: 'country_id=' + country_id,
                                dataType: 'json',
                                beforeSend: function() {
                                    jQuery('#cmtx_state').html('<option value="">' + cmtx_js_settings_form.lang_text_loading + '</option>');
                                }
                            });

                            request.done(function(response) {
                                setTimeout(function() {
                                    states = response;

                                    html = '<option value="" hidden>' + cmtx_js_settings_form.lang_placeholder_state + '</option>';

                                    if (states.length) {
                                        for (i = 0; i < states.length; i++) {
                                            html += '<option value="' + states[i]['id'] + '"';

                                            if (states[i]['id'] == cmtx_js_settings_form.state_id) {
                                                html += ' selected';
                                            }

                                            html += '>' + states[i]['name'] + '</option>';
                                        }
                                    } else {
                                        html += '<option value="" disabled>' + cmtx_js_settings_form.lang_text_country_first + '</option>';
                                    }

                                    jQuery('#cmtx_state').html(html);

                                    jQuery('#cmtx_state').trigger('change');
                                }, 500);
                            });

                            request.fail(function(jqXHR, textStatus, errorThrown) {
                                if (console && console.log) {
                                    console.log(jqXHR.responseText);
                                }
                            });
                        } else {
                            html = '<option value="" hidden>' + cmtx_js_settings_form.lang_placeholder_state + '</option>';

                            html += '<option value="" disabled>' + cmtx_js_settings_form.lang_text_country_first + '</option>';

                            jQuery('#cmtx_state').html(html);
                        }
                    });

                    jQuery('#cmtx_country').trigger('change');
                }
            }

            /* Populate states field (when country field is disabled) */
            if (typeof(cmtx_js_settings_form) != 'undefined') {
                if (!cmtx_js_settings_form.enabled_country && cmtx_js_settings_form.enabled_state) {
                    var request = jQuery.ajax({
                        type: 'POST',
                        cache: false,
                        url: cmtx_js_settings_form.commentics_url + 'frontend/index.php?route=main/form/getStates',
                        data: 'country_id=0',
                        dataType: 'json',
                        beforeSend: function() {
                            jQuery('#cmtx_state').html('<option value="">' + cmtx_js_settings_form.lang_text_loading + '</option>');
                        }
                    });

                    request.done(function(response) {
                        setTimeout(function() {
                            states = response;

                            html = '<option value="" hidden>' + cmtx_js_settings_form.lang_placeholder_state + '</option>';

                            if (states.length) {
                                for (i = 0; i < states.length; i++) {
                                    html += '<option value="' + states[i]['id'] + '"';

                                    if (states[i]['id'] == cmtx_js_settings_form.state_id) {
                                        html += ' selected';
                                    }

                                    html += '>' + states[i]['name'] + '</option>';
                                }
                            }

                            jQuery('#cmtx_state').html(html);

                            jQuery('#cmtx_state').trigger('change');
                        }, 500);
                    });

                    request.fail(function(jqXHR, textStatus, errorThrown) {
                        if (console && console.log) {
                            console.log(jqXHR.responseText);
                        }
                    });
                }
            }

            /* Image captcha */
            if (typeof(cmtx_js_settings_form) != 'undefined') {
                if (cmtx_js_settings_form.captcha) {
                    jQuery('#cmtx_captcha_refresh').click(function() {
                        var src = cmtx_js_settings_form.captcha_url + '&' + Math.random();

                        jQuery('#cmtx_captcha_image').attr('src', src);
                    });
                }
            }

            /* Submit or preview a comment */
            jQuery('#cmtx_submit_button, #cmtx_preview_button').click(function(e) {
                e.preventDefault();

                jQuery('.cmtx_upload_field').remove();

                jQuery('.cmtx_image_upload').each(function() {
                    var image = jQuery(this).find('img').attr('src');

                    jQuery('#cmtx_form').append('<input type="hidden" name="cmtx_upload[]" class="cmtx_upload_field" value="' + image + '">');
                });

                // Find any disabled inputs and remove the "disabled" attribute
                var disabled = jQuery('#cmtx_form').find(':input:disabled').removeAttr('disabled');

                // Serialize the form
                var serialized = jQuery('#cmtx_form').serialize();

                // Re-disable the set of inputs that were originally disabled
                disabled.attr('disabled', 'disabled');

                var request = jQuery.ajax({
                    type: 'POST',
                    cache: false,
                    url: cmtx_js_settings_form.commentics_url + 'frontend/index.php?route=main/form/submit',
                    data: serialized + '&cmtx_page_id=' + encodeURIComponent(cmtx_js_settings_form.page_id) + '&cmtx_type=' + encodeURIComponent(jQuery(this).attr('data-cmtx-type')) + jQuery('#cmtx_hidden_data').val(),
                    dataType: 'json',
                    beforeSend: function() {
                        jQuery('#cmtx_submit_button, #cmtx_preview_button').val(cmtx_js_settings_form.lang_button_processing);

                        jQuery('#cmtx_submit_button, #cmtx_preview_button').prop('disabled', true);

                        jQuery('#cmtx_submit_button, #cmtx_preview_button').addClass('cmtx_button_disabled');
                    }
                });

                request.always(function() {
                    jQuery('#cmtx_submit_button').val(cmtx_js_settings_form.lang_button_submit);

                    jQuery('#cmtx_preview_button').val(cmtx_js_settings_form.lang_button_preview);

                    jQuery('#cmtx_submit_button, #cmtx_preview_button').prop('disabled', false);

                    jQuery('#cmtx_submit_button, #cmtx_preview_button').removeClass('cmtx_button_disabled');

                    jQuery('#cmtx_comment').addClass('cmtx_comment_field_active');

                    jQuery('.cmtx_comment_container').addClass('cmtx_comment_container_active');

                    jQuery('.cmtx_wait_for_user, .cmtx_wait_for_comment').not('.cmtx_headline_row, .cmtx_rating_row').fadeIn('slow');

                    if (jQuery('input[name="cmtx_reply_to"]').val() == '') {
                        jQuery('.cmtx_headline_row').fadeIn('slow');

                        jQuery('.cmtx_rating_row').fadeIn('slow');
                    }
                });

                request.done(function(response) {
                    jQuery('.cmtx_message:not(.cmtx_message_reply), .cmtx_error').remove();

                    jQuery('.cmtx_field, .cmtx_rating').removeClass('cmtx_field_error');

                    if (response['result']['preview']) {
                        jQuery('#cmtx_preview').html(response['result']['preview']);

                        cmtxHighlightCode();
                    } else {
                        jQuery('#cmtx_preview').html('');
                    }

                    if (response['result']['success']) {
                        jQuery('.cmtx_message').remove();

                        jQuery('#cmtx_comment, #cmtx_headline, #cmtx_answer, [name^="cmtx_field_"], #cmtx_captcha').val('');

                        cmtxUpdateCommentCounter();

                        jQuery('.cmtx_image_upload').remove();

                        jQuery('.cmtx_image_row').hide();

                        jQuery('input[name="cmtx_rating"]').prop('checked', false).removeClass('cmtx_rating_active');

                        if (response['hide_rating']) {
                            jQuery('.cmtx_rating_row').remove();
                        }

                        jQuery('#cmtx_captcha_refresh').trigger('click');

                        if (typeof(grecaptcha) != 'undefined') {
                            grecaptcha.reset();
                        }

                        jQuery('input[name="cmtx_reply_to"]').val('');

                        jQuery('.cmtx_message_reply').remove();

                        jQuery('#cmtx_form').before('<div class="cmtx_message cmtx_message_success">' + response['result']['success'] + '</div>');

                        jQuery('.cmtx_message_success').fadeIn(1500);

                        if (response['user_link']) {
                            jQuery('#cmtx_form').before('<div class="cmtx_message cmtx_message_info">' + response['user_link'] + '</div>');

                            jQuery('.cmtx_message_info').fadeIn(1500);
                        }

                        var options = {
                            'commentics_url': cmtx_js_settings_form.commentics_url,
                            'page_id'       : cmtx_js_settings_form.page_id,
                            'page_number'   : '',
                            'sort_by'       : '',
                            'search'        : '',
                            'effect'        : false
                        }

                        cmtxRefreshComments(options);
                    }

                    if (response['result']['error']) {
                        if (response['error']) {
                            if (response['error']['comment']) {
                                jQuery('#cmtx_comment').addClass('cmtx_field_error');

                                jQuery('#cmtx_comment').after('<span class="cmtx_error">' + response['error']['comment'] + '</span>');
                            }

                            if (response['error']['headline']) {
                                jQuery('#cmtx_headline').addClass('cmtx_field_error');

                                jQuery('#cmtx_headline').after('<span class="cmtx_error">' + response['error']['headline'] + '</span>');
                            }

                            if (response['error']['name']) {
                                jQuery('#cmtx_name').addClass('cmtx_field_error');

                                jQuery('#cmtx_name').after('<span class="cmtx_error">' + response['error']['name'] + '</span>');
                            }

                            if (response['error']['email']) {
                                jQuery('#cmtx_email').addClass('cmtx_field_error');

                                jQuery('#cmtx_email').after('<span class="cmtx_error">' + response['error']['email'] + '</span>');
                            }

                            if (response['error']['rating']) {
                                jQuery('#cmtx_rating').addClass('cmtx_field_error');

                                jQuery('#cmtx_rating').after('<span class="cmtx_error">' + response['error']['rating'] + '</span>');
                            }

                            if (response['error']['website']) {
                                jQuery('#cmtx_website').addClass('cmtx_field_error');

                                jQuery('#cmtx_website').after('<span class="cmtx_error">' + response['error']['website'] + '</span>');
                            }

                            if (response['error']['town']) {
                                jQuery('#cmtx_town').addClass('cmtx_field_error');

                                jQuery('#cmtx_town').after('<span class="cmtx_error">' + response['error']['town'] + '</span>');
                            }

                            if (response['error']['country']) {
                                jQuery('#cmtx_country').addClass('cmtx_field_error');

                                jQuery('#cmtx_country').after('<span class="cmtx_error">' + response['error']['country'] + '</span>');
                            }

                            if (response['error']['state']) {
                                jQuery('#cmtx_state').addClass('cmtx_field_error');

                                jQuery('#cmtx_state').after('<span class="cmtx_error">' + response['error']['state'] + '</span>');
                            }

                            if (response['error']['answer']) {
                                jQuery('#cmtx_answer').addClass('cmtx_field_error');

                                jQuery('#cmtx_answer').after('<span class="cmtx_error">' + response['error']['answer'] + '</span>');
                            }

                            for (var field in response['error']) {
                                if (field.startsWith('cmtx_field_')) {
                                    jQuery('[name="' + field + '"]').addClass('cmtx_field_error');

                                    jQuery('[name="' + field + '"]').after('<span class="cmtx_error">' + response['error'][field] + '</span>');
                                }
                            }

                            if (response['error']['recaptcha']) {
                                jQuery('#g-recaptcha').after('<span class="cmtx_error">' + response['error']['recaptcha'] + '</span>');

                                grecaptcha.reset();
                            }

                            if (response['error']['captcha']) {
                                jQuery('#cmtx_captcha').addClass('cmtx_field_error');

                                jQuery('#cmtx_captcha').after('<span class="cmtx_error">' + response['error']['captcha'] + '</span>');

                                jQuery('#cmtx_captcha_refresh').trigger('click');

                                jQuery('#cmtx_captcha').val('');
                            }
                        }

                        jQuery('#cmtx_form').before('<div class="cmtx_message cmtx_message_error">' + response['result']['error'] + '</div>');

                        jQuery('.cmtx_message_error, .cmtx_error').fadeIn(2000);
                    }

                    if (response['question']) {
                        jQuery('#cmtx_question').text(response['question']);
                    }

                    cmtxAutoScroll(jQuery('#cmtx_form_container'));
                });

                request.fail(function(jqXHR, textStatus, errorThrown) {
                    if (console && console.log) {
                        console.log(jqXHR.responseText);
                    }
                });
            });

            /* Show a bio popup when hovering over the avatar image */
            jQuery('body').on('mouseenter', '.cmtx_avatar_area', function() {
                jQuery(this).find('.cmtx_bio').stop(true, true).fadeIn(750);
            });

            jQuery('body').on('mouseleave', '.cmtx_avatar_area', function() {
                jQuery(this).find('.cmtx_bio').stop(true, true).fadeOut(500);
            });

            if (navigator.userAgent.match(/(iPod|iPhone|iPad)/)) {
                jQuery('body').on('mouseenter', '.cmtx_bio', function() {
                    jQuery(this).stop(true, true).fadeOut(500);
                });
            }

            /* Show replies when view replies link is clicked */
            jQuery('body').on('click', '.cmtx_view_replies_link', function(e) {
                e.preventDefault();

                jQuery(this).parent().hide();

                jQuery(this).closest('.cmtx_comment_box').next().fadeIn('slow');
            });

            /* Sort by */
            jQuery('#cmtx_container').on('change', '.cmtx_sort_by_field', function(e) {
                e.preventDefault();

                var options = {
                    'commentics_url': cmtx_js_settings_comments.commentics_url,
                    'page_id'       : cmtx_js_settings_comments.page_id,
                    'page_number'   : '',
                    'sort_by'       : jQuery(this).val(),
                    'search'        : cmtxGetSearchValue(),
                    'effect'        : true
                }

                cmtxRefreshComments(options);
            });

            /* Search */
            jQuery('#cmtx_container').on('focus', '.cmtx_search', function(e) {
                jQuery(this).addClass('cmtx_search_focus');
            });

            jQuery('#cmtx_container').on('click', '.cmtx_search_container .fa-search', function(e) {
                e.preventDefault();

                var options = {
                    'commentics_url': cmtx_js_settings_comments.commentics_url,
                    'page_id'       : cmtx_js_settings_comments.page_id,
                    'page_number'   : '',
                    'sort_by'       : cmtxGetSortByValue(),
                    'search'        : jQuery(this).prev().val(),
                    'effect'        : true
                }

                cmtxRefreshComments(options);
            });

            jQuery('#cmtx_container').on('keypress', '.cmtx_search', function(e) {
                if (e.which == 13) {
                    jQuery(this).next().trigger('click');
                }
            });

            /* Average rating */
            jQuery('#cmtx_container').on('click', '.cmtx_average_rating_can_rate label', function(e) {
                e.preventDefault();

                var element = jQuery(this);

                var rating = jQuery(this).prev().val();

                var request = jQuery.ajax({
                    type: 'POST',
                    cache: false,
                    url: cmtx_js_settings_comments.commentics_url + 'frontend/index.php?route=part/average_rating/rate',
                    data: 'cmtx_page_id=' + encodeURIComponent(cmtx_js_settings_comments.page_id) + '&cmtx_rating=' + encodeURIComponent(rating),
                    dataType: 'json'
                });

                request.done(function(response) {
                    if (response['success']) {
                        jQuery('.cmtx_average_rating input').prop('checked', false);

                        jQuery('.cmtx_average_rating input[value=' + response['average_rating'] + ']').prop('checked', true);

                        jQuery('.cmtx_average_rating_stat_rating, .cmtx_average_rating_stat_number').fadeOut(250, function() {
                            jQuery('.cmtx_average_rating_stat_rating').text(response['average_rating']).fadeIn(2000);

                            jQuery('.cmtx_average_rating_stat_number').text(response['num_of_ratings']).fadeIn(2000);
                        });

                        jQuery('.cmtx_action_message_success').clearQueue();
                        jQuery('.cmtx_action_message_success').html(response['success']);
                        jQuery('.cmtx_action_message_success').fadeIn(500).delay(2000).fadeOut(500);

                        var destination = element.offset();

                        jQuery('.cmtx_action_message_success').offset({ top: destination.top - 25 , left: destination.left + 5 });
                    }

                    if (response['error']) {
                        jQuery('.cmtx_action_message_error').clearQueue();
                        jQuery('.cmtx_action_message_error').html(response['error']);
                        jQuery('.cmtx_action_message_error').fadeIn(500).delay(2000).fadeOut(500);

                        var destination = element.offset();

                        jQuery('.cmtx_action_message_error').offset({ top: destination.top - 25 , left: destination.left + 5 });
                    }
                });

                request.fail(function(jqXHR, textStatus, errorThrown) {
                    if (console && console.log) {
                        console.log(jqXHR.responseText);
                    }
                });
            });

            /* Prevent users from clicking the stars if guest rating is disabled */
            jQuery('#cmtx_container').on('click', '.cmtx_average_rating_cannot_rate label', function(e) {
                e.preventDefault();
            });

            /* Pagination */
            jQuery('#cmtx_container').on('click', '.cmtx_pagination_url', function(e) {
                e.preventDefault();

                // This is to stop multiple calls to this event.
                // Occurs when pagination links shown twice (e.g. above and below comments).
                e.stopImmediatePropagation();

                var options = {
                    'commentics_url': cmtx_js_settings_comments.commentics_url,
                    'page_id'       : cmtx_js_settings_comments.page_id,
                    'page_number'   : jQuery(this).find('span').attr('data-cmtx-page'),
                    'sort_by'       : cmtxGetSortByValue(),
                    'search'        : cmtxGetSearchValue(),
                    'effect'        : true
                }

                cmtxRefreshComments(options);
            });

            /* Notify */
            jQuery('#cmtx_container').on('click', '.cmtx_notify_block a', function(e) {
                e.preventDefault();

                jQuery('.cmtx_message, .cmtx_error, .cmtx_subscribe_row').remove();

                jQuery('.cmtx_field, .cmtx_rating').removeClass('cmtx_field_error');

                jQuery('.cmtx_wait_for_user').show();

                jQuery('.cmtx_icons_row, .cmtx_comment_row, .cmtx_counter_row, .cmtx_headline_row, .cmtx_upload_row, .cmtx_image_row, .cmtx_rating_row, .cmtx_website_row, .cmtx_geo_row, .cmtx_checkbox_container, .cmtx_button_row, .cmtx_extra_row').hide();

                jQuery('.cmtx_question_row, .cmtx_captcha_row').show();

                if (jQuery('input[name="cmtx_subscribe"]').val() == '') {
                    cmtx_heading_text = jQuery('.cmtx_form_heading').text();
                }

                jQuery('.cmtx_form_heading').text(cmtx_js_settings_notify.lang_heading_notify);

                var notify_button = '';

                notify_button += '<div class="cmtx_row cmtx_button_row cmtx_subscribe_row cmtx_clear">';

                    notify_button += '<div class="cmtx_col_2">';

                        notify_button += '<div class="cmtx_container cmtx_submit_button_container">';

                            notify_button += '<input type="button" id="cmtx_notify_button" class="cmtx_button cmtx_button_primary" value="' + cmtx_js_settings_notify.lang_button_notify + '" title="' + cmtx_js_settings_notify.lang_button_notify + '">';

                        notify_button += '</div>';

                    notify_button += '</div>';

                    notify_button += '<div class="cmtx_col_10"></div>';

                notify_button += '</div>';

                jQuery('.cmtx_button_row').after(notify_button);

                jQuery('input[name="cmtx_subscribe"]').val('1');

                jQuery('#cmtx_form').before('<div class="cmtx_message cmtx_message_info cmtx_message_notify">' + cmtx_js_settings_notify.lang_text_notify_info + ' ' + '<a href="#" title="' + cmtx_js_settings_notify.lang_title_cancel_notify + '">' + cmtx_js_settings_notify.lang_link_cancel + '</a></div>');

                cmtxAutoScroll(jQuery('#cmtx_form_container'));

                jQuery('.cmtx_message_notify').fadeIn(1000);
            });

            jQuery('body').on('click', '.cmtx_message_notify a', function(e) {
                e.preventDefault();

                cmtx_cancel_notify();
            });

            jQuery('body').on('click', '#cmtx_notify_button', function(e) {
                e.preventDefault();

                // Find any disabled inputs and remove the "disabled" attribute
                var disabled = jQuery('#cmtx_form').find(':input:disabled').removeAttr('disabled');

                // Serialize the form
                var serialized = jQuery('#cmtx_form').serialize();

                // Re-disable the set of inputs that were originally disabled
                disabled.attr('disabled', 'disabled');

                var request = jQuery.ajax({
                    type: 'POST',
                    cache: false,
                    url: cmtx_js_settings_comments.commentics_url + 'frontend/index.php?route=part/notify/notify',
                    data: serialized + '&cmtx_page_id=' + encodeURIComponent(cmtx_js_settings_comments.page_id) + jQuery('#cmtx_hidden_data').val(),
                    dataType: 'json',
                    beforeSend: function() {
                        jQuery('#cmtx_notify_button').val(cmtx_js_settings_notify.lang_button_processing);

                        jQuery('#cmtx_notify_button').prop('disabled', true);

                        jQuery('#cmtx_notify_button').addClass('cmtx_button_disabled');
                    }
                });

                request.always(function() {
                    jQuery('#cmtx_notify_button').val(cmtx_js_settings_notify.lang_button_notify);

                    jQuery('#cmtx_notify_button').prop('disabled', false);

                    jQuery('#cmtx_notify_button').removeClass('cmtx_button_disabled');
                });

                request.done(function(response) {
                    jQuery('.cmtx_message:not(.cmtx_message_notify), .cmtx_error').remove();

                    jQuery('.cmtx_field, .cmtx_rating').removeClass('cmtx_field_error');

                    if (response['result']['success']) {
                        jQuery('#cmtx_answer, #cmtx_captcha').val('');

                        jQuery('#cmtx_captcha_refresh').trigger('click');

                        if (typeof(grecaptcha) != 'undefined') {
                            grecaptcha.reset();
                        }

                        cmtx_cancel_notify();

                        jQuery('#cmtx_form').before('<div class="cmtx_message cmtx_message_success">' + response['result']['success'] + '</div>');

                        jQuery('.cmtx_message_success').fadeIn(1500);
                    }

                    if (response['result']['error']) {
                        if (response['error']) {
                            if (response['error']['name']) {
                                jQuery('#cmtx_name').addClass('cmtx_field_error');

                                jQuery('#cmtx_name').after('<span class="cmtx_error">' + response['error']['name'] + '</span>');
                            }

                            if (response['error']['email']) {
                                jQuery('#cmtx_email').addClass('cmtx_field_error');

                                jQuery('#cmtx_email').after('<span class="cmtx_error">' + response['error']['email'] + '</span>');
                            }

                            if (response['error']['answer']) {
                                jQuery('#cmtx_answer').addClass('cmtx_field_error');

                                jQuery('#cmtx_answer').after('<span class="cmtx_error">' + response['error']['answer'] + '</span>');
                            }

                            if (response['error']['recaptcha']) {
                                jQuery('#g-recaptcha').after('<span class="cmtx_error">' + response['error']['recaptcha'] + '</span>');

                                grecaptcha.reset();
                            }

                            if (response['error']['captcha']) {
                                jQuery('#cmtx_captcha').addClass('cmtx_field_error');

                                jQuery('#cmtx_captcha').after('<span class="cmtx_error">' + response['error']['captcha'] + '</span>');

                                jQuery('#cmtx_captcha_refresh').trigger('click');

                                jQuery('#cmtx_captcha').val('');
                            }
                        }

                        jQuery('#cmtx_form').before('<div class="cmtx_message cmtx_message_error">' + response['result']['error'] + '</div>');

                        jQuery('.cmtx_message_error, .cmtx_error').fadeIn(2000);
                    }

                    if (response['question']) {
                        jQuery('#cmtx_question').text(response['question']);
                    }

                    cmtxAutoScroll(jQuery('#cmtx_form_container'));
                });
            });

            function cmtx_cancel_notify() {
                jQuery('.cmtx_message, .cmtx_error, .cmtx_subscribe_row').remove();

                jQuery('.cmtx_field, .cmtx_rating').removeClass('cmtx_field_error');

                jQuery('.cmtx_icons_row, .cmtx_comment_row, .cmtx_counter_row, .cmtx_headline_row, .cmtx_upload_row, .cmtx_rating_row, .cmtx_website_row, .cmtx_geo_row, .cmtx_question_row, .cmtx_captcha_row, .cmtx_checkbox_container, .cmtx_button_row, .cmtx_extra_row').show();

                jQuery('#cmtx_comment').addClass('cmtx_comment_field_active');

                jQuery('.cmtx_comment_container').addClass('cmtx_comment_container_active');

                jQuery('.cmtx_form_heading').text(cmtx_heading_text);

                jQuery('input[name="cmtx_subscribe"]').val('');
            }

            /* Like or dislike a comment */
            jQuery('#cmtx_container').on('click', '.cmtx_vote_link', function(e) {
                e.preventDefault();

                var vote_link = jQuery(this);

                if (jQuery(this).hasClass('cmtx_like_link')) {
                    var type = 'like';
                } else {
                    var type = 'dislike';
                }

                var request = jQuery.ajax({
                    type: 'POST',
                    cache: false,
                    url: cmtx_js_settings_comments.commentics_url + 'frontend/index.php?route=main/comments/vote',
                    data: 'cmtx_comment_id=' + encodeURIComponent(jQuery(this).closest('.cmtx_comment_box').attr('data-cmtx-comment-id')) + '&cmtx_type=' + encodeURIComponent(type),
                    dataType: 'json'
                });

                request.done(function(response) {
                    if (response['success']) {
                        vote_link.find('.cmtx_vote_count').text(parseInt(vote_link.find('.cmtx_vote_count').text(), 10) + 1);

                        if (type == 'like') {
                            vote_link.find('.cmtx_vote_count').addClass('like_animation');

                            setTimeout(function() {
                                jQuery('.cmtx_vote_count').removeClass('like_animation');
                            }, 2000);
                        } else {
                            vote_link.find('.cmtx_vote_count').addClass('dislike_animation');

                            setTimeout(function() {
                                jQuery('.cmtx_vote_count').removeClass('dislike_animation');
                            }, 2000);
                        }
                    }

                    if (response['error']) {
                        jQuery('.cmtx_action_message_error').clearQueue();
                        jQuery('.cmtx_action_message_error').html(response['error']);
                        jQuery('.cmtx_action_message_error').fadeIn(500).delay(2000).fadeOut(500);

                        var destination = vote_link.offset();

                        jQuery('.cmtx_action_message_error').offset({ top: destination.top - 25 , left: destination.left - 45 });
                    }
                });

                request.fail(function(jqXHR, textStatus, errorThrown) {
                    if (console && console.log) {
                        console.log(jqXHR.responseText);
                    }
                });
            });

            /* Share a comment */
            jQuery('#cmtx_container').on('click', '.cmtx_share_link', function(e) {
                e.preventDefault();

                jQuery('.cmtx_share_box').hide();

                var share_link = jQuery(this);

                var permalink = encodeURIComponent(jQuery(this).attr('data-cmtx-sharelink'));

                var reference = encodeURIComponent(jQuery('.cmtx_share_box').attr('data-cmtx-reference'));

                jQuery('.cmtx_share_digg').parent().attr('href', 'http://digg.com/submit?url=' + permalink + '&title=' + reference);
                jQuery('.cmtx_share_facebook').parent().attr('href', 'https://www.facebook.com/sharer.php?u=' + permalink);
                jQuery('.cmtx_share_linkedin').parent().attr('href', 'https://www.linkedin.com/shareArticle?mini=true&url=' + permalink + '&title=' + reference);
                jQuery('.cmtx_share_reddit').parent().attr('href', 'https://reddit.com/submit?url=' + permalink + '&title=' + reference);
                jQuery('.cmtx_share_twitter').parent().attr('href', 'https://twitter.com/intent/tweet?url=' + permalink + '&text=' + reference);
                jQuery('.cmtx_share_weibo').parent().attr('href', 'http://service.weibo.com/share/share.php?url=' + permalink + '&title=' + reference);

                jQuery('.cmtx_share_box').clearQueue();
                jQuery('.cmtx_share_box').fadeIn(400);

                var destination = share_link.offset();

                jQuery('.cmtx_share_box').offset({ top: destination.top - 30 , left: destination.left - 55 });
            });

            /* Flag modal */
            jQuery('body').on('click', '#cmtx_flag_modal_yes', function(e) {
                e.preventDefault();

                var comment_id = jQuery(this).attr('data-cmtx-comment-id');

                var flag_link = jQuery('.cmtx_comment_box[data-cmtx-comment-id=' + comment_id + '] .cmtx_flag_link');

                var request = jQuery.ajax({
                    type: 'POST',
                    cache: false,
                    url: cmtx_js_settings_comments.commentics_url + 'frontend/index.php?route=main/comments/flag',
                    data: 'cmtx_comment_id=' + encodeURIComponent(comment_id) + '&cmtx_page_id=' + encodeURIComponent(cmtx_js_settings_comments.page_id),
                    dataType: 'json'
                });

                request.done(function(response) {
                    if (response['success']) {
                        jQuery('.cmtx_action_message_success').clearQueue();
                        jQuery('.cmtx_action_message_success').html(response['success']);
                        jQuery('.cmtx_action_message_success').fadeIn(500).delay(2000).fadeOut(500);

                        var destination = flag_link.offset();

                        jQuery('.cmtx_action_message_success').offset({ top: destination.top - 25 , left: destination.left - 100 });
                    }

                    if (response['error']) {
                        jQuery('.cmtx_action_message_error').clearQueue();
                        jQuery('.cmtx_action_message_error').html(response['error']);
                        jQuery('.cmtx_action_message_error').fadeIn(500).delay(2000).fadeOut(500);

                        var destination = flag_link.offset();

                        jQuery('.cmtx_action_message_error').offset({top: destination.top - 25, left: destination.left - 100});
                    }
                });

                request.fail(function(jqXHR, textStatus, errorThrown) {
                    if (console && console.log) {
                        console.log(jqXHR.responseText);
                    }
                });

                jQuery('.cmtx_modal_close').trigger('click');
            });

            /* Original comment */
            jQuery('#cmtx_container').on('click', '.cmtx_edit_link', function(e) {
                e.preventDefault();

                var edit_link = jQuery(this);

                var comment_id = jQuery(this).closest('.cmtx_comment_box').attr('data-cmtx-comment-id');

                var request = jQuery.ajax({
                    type: 'POST',
                    cache: false,
                    url: cmtx_js_settings_comments.commentics_url + 'frontend/index.php?route=main/comments/original',
                    data: 'cmtx_comment_id=' + encodeURIComponent(comment_id) + '&cmtx_page_id=' + encodeURIComponent(cmtx_js_settings_comments.page_id),
                    dataType: 'json'
                });

                request.done(function(response) {
                    jQuery('.quick_reply, .edit_comment').remove();

                    jQuery('.cmtx_message:not(.cmtx_message_reply), .cmtx_error').remove();

                    edit_link.closest('.cmtx_comment_box').find('.cmtx_view_replies_link').trigger('click');

                    if (response['success']) {
                        html =  '<div class="edit_comment">';
                        html += '  <div class="cmtx_edit_comment_comment_holder"><textarea name="cmtx_edit_comment" class="cmtx_field cmtx_textarea_field cmtx_comment_field cmtx_comment_field_active" placeholder="' + jQuery('#cmtx_comment').attr('placeholder') + '" title="' + jQuery('#cmtx_comment').attr('title') + '" maxlength="' + jQuery('#cmtx_comment').attr('maxlength') + '">' + response['original_comment'] + '</textarea></div>';

                        var lang_text_agree = cmtx_js_settings_comments.lang_text_agree;
                        lang_text_agree = lang_text_agree.replace('[1]', '<a href="#" data-cmtx-target-modal="#cmtx_privacy_modal">' + cmtx_js_settings_comments.lang_text_privacy + '</a>');
                        lang_text_agree = lang_text_agree.replace('[2]', '<a href="#" data-cmtx-target-modal="#cmtx_terms_modal">' + cmtx_js_settings_comments.lang_text_terms + '</a>');

                        html += '  <div class="cmtx_edit_comment_lower">';
                        html += '    <div class="cmtx_edit_comment_link"></div>';
                        html += '    <div class="cmtx_edit_comment_agree">' + lang_text_agree + '</div>';
                        html += '    <div class="cmtx_edit_comment_button"><input type="button" class="' + jQuery('#cmtx_submit_button').attr('class') + ' cmtx_button_edit_comment" value="' + cmtx_js_settings_comments.lang_button_edit + '" title="' + cmtx_js_settings_comments.lang_button_edit + '"></div>';
                        html += '  </div>';
                        html += '</div>';

                        edit_link.closest('.cmtx_main_area').append(html);
                    }

                    if (response['error']) {
                        jQuery('.edit_comment').prepend('<div class="cmtx_message cmtx_message_error">' + response['error'] + '</div>');

                        jQuery('.cmtx_message_error, .cmtx_error').fadeIn(2000);
                    }
                });

                request.fail(function(jqXHR, textStatus, errorThrown) {
                    if (console && console.log) {
                        console.log(jqXHR.responseText);
                    }
                });
            });

            /* Edit Comment */
            jQuery('#cmtx_container').on('click', '.cmtx_button_edit_comment', function(e) {
                e.preventDefault();

                var edit_comment = jQuery(this).closest('.cmtx_comment_box').find('.edit_comment');

                var comment_id = jQuery(this).closest('.cmtx_comment_box').attr('data-cmtx-comment-id');

                var request = jQuery.ajax({
                    type: 'POST',
                    cache: false,
                    url: cmtx_js_settings_form.commentics_url + 'frontend/index.php?route=main/form/edit',
                    data: '&cmtx_comment=' + encodeURIComponent(edit_comment.find('textarea[name="cmtx_edit_comment"]').val().replace(/(\r\n|\n|\r)/gm, "\r\n")) + '&cmtx_comment_id=' + encodeURIComponent(comment_id) + '&cmtx_page_id=' + encodeURIComponent(cmtx_js_settings_form.page_id),
                    dataType: 'json',
                    beforeSend: function() {
                        edit_comment.find('.cmtx_button_edit_comment').val(cmtx_js_settings_form.lang_button_processing);

                        edit_comment.find('.cmtx_button_edit_comment').prop('disabled', true);

                        edit_comment.find('.cmtx_button_edit_comment').addClass('cmtx_button_disabled');
                    }
                });

                request.always(function() {
                    edit_comment.find('.cmtx_button_edit_comment').val(cmtx_js_settings_comments.lang_button_edit);

                    edit_comment.find('.cmtx_button_edit_comment').prop('disabled', false);

                    edit_comment.find('.cmtx_button_edit_comment').removeClass('cmtx_button_disabled');
                });

                request.done(function(response) {
                    jQuery('.cmtx_message:not(.cmtx_message_reply), .cmtx_error').remove();

                    jQuery('.cmtx_field').removeClass('cmtx_field_error');

                    if (response['result']['success']) {
                        jQuery('.cmtx_message').remove();

                        if (response['result']['approve']) {
                            edit_comment.html('<div class="cmtx_message cmtx_message_success cmtx_m-0">' + response['result']['success'] + '</div>');
                        } else {
                            edit_comment.html('<div class="cmtx_message cmtx_message_success cmtx_m-0">' + response['result']['success'] + ' <a href="#" class="cmtx_edit_comment_refresh">' + cmtx_js_settings_comments.lang_link_refresh + '</a>' + '</div>');
                        }

                        jQuery('.cmtx_message_success').fadeIn(1500);
                    }

                    if (response['result']['error']) {
                        if (response['error']) {
                            if (response['error']['comment']) {
                                edit_comment.find('textarea[name="cmtx_edit_comment"]').addClass('cmtx_field_error');

                                edit_comment.find('textarea[name="cmtx_edit_comment"]').after('<span class="cmtx_error">' + response['error']['comment'] + '</span>');
                            }
                        }

                        edit_comment.prepend('<div class="cmtx_message cmtx_message_error">' + response['result']['error'] + '</div>');

                        jQuery('.cmtx_message_error, .cmtx_error').fadeIn(2000);
                    }
                });

                request.fail(function(jqXHR, textStatus, errorThrown) {
                    if (console && console.log) {
                        console.log(jqXHR.responseText);
                    }
                });
            });

            /* Delete modal */
            jQuery('body').on('click', '#cmtx_delete_modal_yes', function(e) {
                e.preventDefault();

                var comment_id = jQuery(this).attr('data-cmtx-comment-id');

                var delete_link = jQuery('.cmtx_comment_box[data-cmtx-comment-id=' + comment_id + '] .cmtx_delete_link');

                var request = jQuery.ajax({
                    type: 'POST',
                    cache: false,
                    url: cmtx_js_settings_comments.commentics_url + 'frontend/index.php?route=main/comments/delete',
                    data: 'cmtx_comment_id=' + encodeURIComponent(comment_id) + '&cmtx_page_id=' + encodeURIComponent(cmtx_js_settings_comments.page_id),
                    dataType: 'json'
                });

                request.done(function(response) {
                    if (response['success']) {
                        var options = {
                            'commentics_url': cmtx_js_settings_comments.commentics_url,
                            'page_id'       : cmtx_js_settings_comments.page_id,
                            'page_number'   : '',
                            'sort_by'       : '',
                            'search'        : '',
                            'effect'        : true
                        }

                        cmtxRefreshComments(options);
                    }

                    if (response['error']) {
                        jQuery('.cmtx_action_message_error').clearQueue();
                        jQuery('.cmtx_action_message_error').html(response['error']);
                        jQuery('.cmtx_action_message_error').fadeIn(500).delay(2000).fadeOut(500);

                        var destination = delete_link.offset();

                        jQuery('.cmtx_action_message_error').offset({top: destination.top - 25, left: destination.left - 100});
                    }
                });

                request.fail(function(jqXHR, textStatus, errorThrown) {
                    if (console && console.log) {
                        console.log(jqXHR.responseText);
                    }
                });

                jQuery('.cmtx_modal_close').trigger('click');
            });

            /* Permalink for a comment */
            jQuery('#cmtx_container').on('click', '.cmtx_permalink_link', function(e) {
                e.preventDefault();

                jQuery('.cmtx_permalink_box').hide();

                var permalink_link = jQuery(this);

                var permalink = jQuery(this).attr('data-cmtx-permalink');

                jQuery('#cmtx_permalink').val(permalink);

                jQuery('.cmtx_permalink_box').clearQueue();
                jQuery('.cmtx_permalink_box').fadeIn(400);

                var box_width = jQuery('.cmtx_permalink_box').width();

                var destination = permalink_link.offset();

                jQuery('.cmtx_permalink_box').offset({ top: destination.top - 70 , left: destination.left - box_width });

                jQuery('#cmtx_permalink').select();
            });

            jQuery('#cmtx_container').on('click', '.cmtx_permalink_box a', function(e) {
                e.preventDefault();

                jQuery('.cmtx_permalink_box').fadeOut(400);
            });

            /* Reply to a comment */
            jQuery('#cmtx_container').on('click', '.cmtx_reply_link', function(e) {
                e.preventDefault();

                if (jQuery(this).closest('.quick_reply').length) {
                    var is_quick_reply = false;
                } else {
                    var is_quick_reply = true;
                }

                var comment_id = jQuery(this).closest('.cmtx_comment_box').attr('data-cmtx-comment-id');

                jQuery('input[name="cmtx_reply_to"]').val(comment_id);

                var name = jQuery(this).closest('.cmtx_comment_box').find('.cmtx_name_text').text();

                var quick_reply_name = jQuery(this).closest('.cmtx_comment_box').find('input[name="cmtx_quick_reply_name"]').val();
                var quick_reply_email = jQuery(this).closest('.cmtx_comment_box').find('input[name="cmtx_quick_reply_email"]').val();
                var quick_reply_comment = jQuery(this).closest('.cmtx_comment_box').find('textarea[name="cmtx_quick_reply_comment"]').val();

                jQuery('.quick_reply, .edit_comment').remove();

                if (cmtx_js_settings_comments.quick_reply && is_quick_reply) {
                    jQuery('.cmtx_message:not(.cmtx_message_reply), .cmtx_error').remove();

                    jQuery(this).closest('.cmtx_comment_box').find('.cmtx_view_replies_link').trigger('click');

                    html =  '<div class="quick_reply">';
                    html += '  <div class="cmtx_quick_reply_comment_holder"><textarea name="cmtx_quick_reply_comment" class="cmtx_field cmtx_textarea_field cmtx_comment_field cmtx_comment_field_active" placeholder="' + jQuery('#cmtx_comment').attr('placeholder') + '" title="' + jQuery('#cmtx_comment').attr('title') + '" maxlength="' + jQuery('#cmtx_comment').attr('maxlength') + '"></textarea></div>';

                    if (jQuery('#cmtx_name').length || jQuery('#cmtx_email').length) {
                        html += '  <div class="cmtx_quick_reply_user">';

                        if (jQuery('#cmtx_name').length) {
                            html += '<div class="cmtx_quick_reply_name_holder"><input type="text" name="cmtx_quick_reply_name" class="' + jQuery('#cmtx_name').attr('class') + '" value="' + jQuery('#cmtx_name').val() + '" placeholder="' + jQuery('#cmtx_name').attr('placeholder') + '" title="' + jQuery('#cmtx_name').attr('title') + '" maxlength="' + jQuery('#cmtx_name').attr('maxlength') + '" ' + (jQuery('#cmtx_name').is('[readonly]') ? 'readonly' : '') + '></div>';
                        }

                        if (jQuery('#cmtx_email').length) {
                            html += '<div class="cmtx_quick_reply_email_holder"><input type="email" name="cmtx_quick_reply_email" class="' + jQuery('#cmtx_email').attr('class') + '" value="' + jQuery('#cmtx_email').val() + '" placeholder="' + jQuery('#cmtx_email').attr('placeholder') + '" title="' + jQuery('#cmtx_email').attr('title') + '" maxlength="' + jQuery('#cmtx_email').attr('maxlength') + '" ' + (jQuery('#cmtx_email').is('[readonly]') ? 'readonly' : '') + '></div>';
                        }

                        html += '  </div>';
                    }

                    var lang_text_agree = cmtx_js_settings_comments.lang_text_agree;
                    lang_text_agree = lang_text_agree.replace('[1]', '<a href="#" data-cmtx-target-modal="#cmtx_privacy_modal">' + cmtx_js_settings_comments.lang_text_privacy + '</a>');
                    lang_text_agree = lang_text_agree.replace('[2]', '<a href="#" data-cmtx-target-modal="#cmtx_terms_modal">' + cmtx_js_settings_comments.lang_text_terms + '</a>');

                    html += '  <div class="cmtx_quick_reply_lower">';
                    html += '    <div class="cmtx_quick_reply_link"><a href="#" class="cmtx_reply_link">' + cmtx_js_settings_comments.lang_link_reply + '</a></div>';
                    html += '    <div class="cmtx_quick_reply_agree">' + lang_text_agree + '</div>';
                    html += '    <div class="cmtx_quick_reply_button"><input type="button" class="' + jQuery('#cmtx_submit_button').attr('class') + ' cmtx_button_quick_reply" value="' + cmtx_js_settings_comments.lang_button_reply + '" title="' + cmtx_js_settings_comments.lang_button_reply + '"></div>';
                    html += '  </div>';
                    html += '</div>';

                    jQuery(this).closest('.cmtx_main_area').append(html);
                } else {
                    /* Copy quick reply values to main form */
                    if (quick_reply_name) {
                        jQuery('#cmtx_name').val(quick_reply_name);
                    }

                    if (quick_reply_email) {
                        jQuery('#cmtx_email').val(quick_reply_email);
                    }

                    if (quick_reply_comment) {
                        jQuery('#cmtx_comment').val(quick_reply_comment);
                    }
                    /* End of copying values */

                    if (jQuery('input[name="cmtx_subscribe"]').val() == '1') {
                        cmtx_cancel_notify();
                    }

                    jQuery('.cmtx_message_info').remove();

                    jQuery('.cmtx_icons_row, .cmtx_comment_row, .cmtx_counter_row, .cmtx_upload_row, .cmtx_website_row, .cmtx_geo_row, .cmtx_question_row, .cmtx_captcha_row, .cmtx_checkbox_container, .cmtx_button_row, .cmtx_extra_row').show();

                    jQuery('.cmtx_headline_row, .cmtx_rating_row').hide();

                    jQuery('#cmtx_comment').addClass('cmtx_comment_field_active');

                    jQuery('.cmtx_comment_container').addClass('cmtx_comment_container_active');

                    jQuery('#cmtx_form').before('<div class="cmtx_message cmtx_message_info cmtx_message_reply">' + cmtx_js_settings_comments.lang_text_replying_to + ' ' + name + ' <a href="#" title="' + cmtx_js_settings_comments.lang_title_cancel_reply + '">' + cmtx_js_settings_comments.lang_link_cancel + '</a></div>');

                    cmtxAutoScroll(jQuery('#cmtx_form_container'));

                    jQuery('.cmtx_message_reply').fadeIn(2000);
                }
            });

            jQuery('body').on('click', '.cmtx_message_reply a', function(e) {
                e.preventDefault();

                jQuery('.cmtx_headline_row, .cmtx_rating_row').show();

                jQuery('input[name="cmtx_reply_to"]').val('');

                jQuery('.cmtx_message_reply').text(cmtx_js_settings_comments.lang_text_not_replying);
            });

            /* Lightbox for comment uploads */
            jQuery('#cmtx_container').on('click', '.cmtx_comments_container .cmtx_upload_area a', function(e) {
                var src = jQuery(this).find('img').attr('src');

                if (isInIframe) {
                    jQuery(this).attr('href', src);
                } else {
                    e.preventDefault();

                    jQuery('#cmtx_lightbox_modal .cmtx_modal_body').html('<img src="' + src + '" class="cmtx_lightbox_image">');

                    jQuery('body').append(jQuery('#cmtx_lightbox_modal'));

                    jQuery('body').append('<div class="cmtx_overlay"></div>');

                    jQuery('.cmtx_overlay').fadeIn(200);

                    jQuery('#cmtx_lightbox_modal').fadeIn(200);
                }
            });

            /* Load more comments button */
            jQuery('#cmtx_container').on('click', '#cmtx_more_button', function(e) {
                e.preventDefault();

                jQuery('#cmtx_more_button').val(cmtx_js_settings_comments.lang_button_loading);

                jQuery('#cmtx_more_button').prop('disabled', true);

                jQuery('#cmtx_more_button').addClass('cmtx_button_disabled');

                var next_page = parseInt(jQuery('#cmtx_next_page').val());

                jQuery('#cmtx_next_page').val(next_page + 1);

                var options = {
                    'commentics_url': cmtx_js_settings_comments.commentics_url,
                    'page_id'       : cmtx_js_settings_comments.page_id,
                    'page_number'   : next_page,
                    'sort_by'       : cmtxGetSortByValue(),
                    'search'        : cmtxGetSearchValue(),
                    'pagination'    : 'button',
                    'effect'        : false
                }

                cmtxRefreshComments(options);
            });

            /* Return to comments link */
            jQuery('#cmtx_container').on('click', '.cmtx_no_results a, .cmtx_return a', function(e) {
                e.preventDefault();

                jQuery('#cmtx_search').val('');

                cmtx_js_settings_comments.is_permalink = false;

                var options = {
                    'commentics_url': cmtx_js_settings_comments.commentics_url,
                    'page_id'       : cmtx_js_settings_comments.page_id,
                    'page_number'   : '',
                    'sort_by'       : '',
                    'search'        : '',
                    'effect'        : true
                }

                cmtxRefreshComments(options);
            });

            /* Refresh comments link */
            jQuery('#cmtx_container').on('click', '.cmtx_edit_comment_refresh, .cmtx_quick_reply_refresh', function(e) {
                e.preventDefault();

                var options = {
                    'commentics_url': cmtx_js_settings_comments.commentics_url,
                    'page_id'       : cmtx_js_settings_comments.page_id,
                    'page_number'   : '',
                    'sort_by'       : cmtxGetSortByValue(),
                    'search'        : cmtxGetSearchValue(),
                    'effect'        : true
                }

                cmtxRefreshComments(options);
            });

            /* Infinite scroll */
            if (typeof(cmtx_js_settings_comments) != 'undefined') {
                if (cmtx_js_settings_comments.show_pagination && cmtx_js_settings_comments.pagination_type == 'infinite') {
                    if (isInIframe) {
                        if (window.addEventListener) {
                            window.addEventListener('message', function (e) {
                                if (e.data && e.data == 'infinite_scroll' && !cmtx_js_settings_comments.is_permalink) {
                                    cmtxInfiniteScrollIframe();
                                }
                            }, false);
                        }
                    } else {
                        jQuery(window).off('scroll', cmtxInfiniteScroll).on('scroll', cmtxInfiniteScroll);
                    }
                }
            }

            cmtxViewReplies();
            cmtxTimeago();
            cmtxHighlightCode();
            cmtxViewersOnline();
            cmtxCloseShareBox();
            cmtxClosePermalinkBox();

            /* Admin Detect modal */

            if (typeof(cmtx_js_settings_form) != 'undefined') {
                if (jQuery('#cmtx_admindetect_modal').length) {
                    jQuery('body').append(jQuery('#cmtx_admindetect_modal'));

                    jQuery('body').append('<div class="cmtx_overlay"></div>');

                    if (isInIframe) {
                        var destination = jQuery('#cmtx_container').offset();

                        jQuery('#cmtx_admindetect_modal').css({top: destination.top + 130});

                        jQuery('.cmtx_overlay').css('background-color', 'transparent');
                    }

                    jQuery('.cmtx_overlay').fadeIn(200);

                    jQuery('#cmtx_admindetect_modal').fadeIn(200);
                }

                jQuery('body').on('click', '#cmtx_admindetect_modal_stop', function(e) {
                    e.preventDefault();

                    jQuery.ajax({
                        url: cmtx_js_settings_form.commentics_url + 'frontend/index.php?route=main/page/adminDetect',
                    })

                    jQuery('.cmtx_modal_close').trigger('click');
                });
            }

            /* Quick reply */
            jQuery('#cmtx_container').on('click', '.cmtx_button_quick_reply', function(e) {
                e.preventDefault();

                var quick_reply = jQuery(this).closest('.cmtx_comment_box').find('.quick_reply');

                // Find any disabled inputs and remove the "disabled" attribute
                var disabled = jQuery('#cmtx_form').find(':input:disabled').removeAttr('disabled');

                // Serialize the form
                var serialized = jQuery('#cmtx_form').serialize();

                // Re-disable the set of inputs that were originally disabled
                disabled.attr('disabled', 'disabled');

                var request = jQuery.ajax({
                    type: 'POST',
                    cache: false,
                    url: cmtx_js_settings_form.commentics_url + 'frontend/index.php?route=main/form/reply',
                    data: serialized + '&cmtx_comment=' + encodeURIComponent(quick_reply.find('textarea[name="cmtx_quick_reply_comment"]').val().replace(/(\r\n|\n|\r)/gm, "\r\n")) + '&cmtx_email=' + encodeURIComponent(quick_reply.find('input[name="cmtx_quick_reply_email"]').val()) + '&cmtx_name=' + encodeURIComponent(quick_reply.find('input[name="cmtx_quick_reply_name"]').val()) + '&cmtx_page_id=' + encodeURIComponent(cmtx_js_settings_form.page_id) + jQuery('#cmtx_hidden_data').val(),
                    dataType: 'json',
                    beforeSend: function() {
                        quick_reply.find('.cmtx_button_quick_reply').val(cmtx_js_settings_form.lang_button_processing);

                        quick_reply.find('.cmtx_button_quick_reply').prop('disabled', true);

                        quick_reply.find('.cmtx_button_quick_reply').addClass('cmtx_button_disabled');
                    }
                });

                request.always(function() {
                    quick_reply.find('.cmtx_button_quick_reply').val(cmtx_js_settings_comments.lang_button_reply);

                    quick_reply.find('.cmtx_button_quick_reply').prop('disabled', false);

                    quick_reply.find('.cmtx_button_quick_reply').removeClass('cmtx_button_disabled');
                });

                request.done(function(response) {
                    jQuery('.cmtx_message:not(.cmtx_message_reply), .cmtx_error').remove();

                    jQuery('.cmtx_field').removeClass('cmtx_field_error');

                    if (response['result']['success']) {
                        jQuery('.cmtx_message').remove();

                        jQuery('input[name="cmtx_reply_to"]').val('');

                        if (response['result']['approve']) {
                            quick_reply.html('<div class="cmtx_message cmtx_message_success cmtx_m-0">' + response['result']['success'] + '</div>');
                        } else {
                            quick_reply.html('<div class="cmtx_message cmtx_message_success cmtx_m-0">' + response['result']['success'] + ' <a href="#" class="cmtx_quick_reply_refresh">' + cmtx_js_settings_comments.lang_link_refresh + '</a>' + '</div>');
                        }

                        jQuery('.cmtx_message_success').fadeIn(1500);
                    }

                    if (response['result']['error']) {
                        if (response['error']) {
                            if (response['error']['comment']) {
                                quick_reply.find('textarea[name="cmtx_quick_reply_comment"]').addClass('cmtx_field_error');

                                quick_reply.find('textarea[name="cmtx_quick_reply_comment"]').after('<span class="cmtx_error">' + response['error']['comment'] + '</span>');
                            }

                            if (response['error']['name']) {
                                quick_reply.find('input[name="cmtx_quick_reply_name"]').addClass('cmtx_field_error');

                                quick_reply.find('input[name="cmtx_quick_reply_name"]').after('<span class="cmtx_error">' + response['error']['name'] + '</span>');
                            }

                            if (response['error']['email']) {
                                quick_reply.find('input[name="cmtx_quick_reply_email"]').addClass('cmtx_field_error');

                                quick_reply.find('input[name="cmtx_quick_reply_email"]').after('<span class="cmtx_error">' + response['error']['email'] + '</span>');
                            }
                        }

                        quick_reply.prepend('<div class="cmtx_message cmtx_message_error">' + response['result']['error'] + '</div>');

                        jQuery('.cmtx_message_error, .cmtx_error').fadeIn(2000);
                    }
                });

                request.fail(function(jqXHR, textStatus, errorThrown) {
                    if (console && console.log) {
                        console.log(jqXHR.responseText);
                    }
                });
            });

            /* User Page */

            if (typeof(cmtx_js_settings_user) != 'undefined') {
                /* Show an avatar selection modal */
                jQuery('#cmtx_avatar_selection_link').click(function(e) {
                    e.preventDefault();

                    jQuery('body').append('<div class="cmtx_overlay"></div>');

                    jQuery('.cmtx_overlay').fadeIn(200);

                    jQuery('#cmtx_avatar_selection_modal').fadeIn(200);
                });

                jQuery('.cmtx_avatar_selection_img').click(function(e) {
                    e.preventDefault();

                    var src = jQuery(this).attr('src');

                    jQuery('.cmtx_avatar_image').attr('src', src);

                    jQuery('.cmtx_avatar_image_links').show();

                    jQuery('.cmtx_modal_close').trigger('click');
                });

                var readURL = function(input) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();

                        reader.onload = function(e) {
                            jQuery('.cmtx_avatar_image').attr('src', e.target.result);
                        }

                        reader.readAsDataURL(input.files[0]);

                        jQuery('.cmtx_avatar_image_links').show();
                    }
                }

                jQuery('#cmtx_avatar_image_input').on('change', function() {
                    readURL(this);
                });

                jQuery('#cmtx_avatar_upload_link').on('click', function(e) {
                    e.preventDefault();

                   jQuery('#cmtx_avatar_image_input').click();
                });

                jQuery('#cmtx_avatar_save_link').on('click', function(e) {
                    e.preventDefault();

                    var avatar_type = jQuery('.cmtx_avatar_image').attr('data-type');

                    var formData = new FormData();

                    formData.append('u-t', cmtx_js_settings_user.token);

                    if (avatar_type == 'selection') {
                        formData.append('avatar', jQuery('.cmtx_avatar_image').attr('src'));
                        var method = 'saveSelectedAvatar';
                    } else {
                        formData.append('avatar', jQuery('#cmtx_avatar_image_input')[0].files[0]);
                        var method = 'saveUploadedAvatar';
                    }

                    var request = jQuery.ajax({
                        type: 'POST',
                        cache: false,
                        url: cmtx_js_settings_user.commentics_url + 'frontend/index.php?route=main/user/' + method,
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        beforeSend: function() {
                            jQuery('.cmtx_message').remove();

                            jQuery('form').before('<div class="cmtx_message cmtx_message_info">' + cmtx_js_settings_user.lang_text_saving + '</div>');

                            jQuery('.cmtx_message').show();
                        }
                    });

                    request.done(function(response) {
                        cmtxAutoScroll(jQuery('.cmtx_user_container'));

                        setTimeout(function() {
                            jQuery('.cmtx_message').remove();

                            if (response['success']) {
                                jQuery('form').before('<div class="cmtx_message cmtx_message_success">' + response['success'] + '</div>');

                                jQuery('.cmtx_message').show();

                                jQuery('.cmtx_avatar_image_links').hide();
                            }

                            if (response['error']) {
                                jQuery('form').before('<div class="cmtx_message cmtx_message_error">' + response['error'] + '</div>');

                                jQuery('.cmtx_message').show();
                            }
                        }, 1000);
                    });
                });

                if (cmtx_js_settings_user.to_all) {
                    jQuery('#cmtx_user_container .cmtx_notifications_area_custom').hide();
                } else {
                    jQuery('#cmtx_user_container .cmtx_notifications_area_custom').show();
                }

                jQuery('#cmtx_user_container input[name="to_all"]').on('change', function() {
                    if (jQuery(this).val() == '1') {
                        jQuery('#cmtx_user_container .cmtx_notifications_area_custom').fadeOut(500);
                    } else {
                        jQuery('#cmtx_user_container .cmtx_notifications_area_custom').fadeIn(1000);
                    }
                });

                cmtxTimeago();

                jQuery('#cmtx_user_container .cmtx_settings_container input').change(function(e) {
                    var request = jQuery.ajax({
                        type: 'POST',
                        cache: false,
                        url: cmtx_js_settings_user.commentics_url + 'frontend/index.php?route=main/user/save',
                        data: jQuery('form').serialize() + '&u-t=' + encodeURIComponent(cmtx_js_settings_user.token),
                        dataType: 'json',
                        beforeSend: function() {
                            jQuery('.cmtx_message').remove();

                            jQuery('form').before('<div class="cmtx_message cmtx_message_info">' + cmtx_js_settings_user.lang_text_saving + '</div>');

                            jQuery('.cmtx_message').show();
                        }
                    });

                    request.done(function(response) {
                        cmtxAutoScroll(jQuery('.cmtx_user_container'));

                        setTimeout(function() {
                            jQuery('.cmtx_message').remove();

                            if (response['success']) {
                                jQuery('form').before('<div class="cmtx_message cmtx_message_success">' + response['success'] + '</div>');

                                jQuery('.cmtx_message').show();
                            }

                            if (response['error']) {
                                jQuery('form').before('<div class="cmtx_message cmtx_message_error">' + response['error'] + '</div>');

                                jQuery('.cmtx_message').show();
                            }
                        }, 1000);
                    });
                });

                jQuery('#cmtx_user_container .cmtx_trash_icon').click(function(e) {
                    var trash_icon = jQuery(this);

                    var request = jQuery.ajax({
                        type: 'POST',
                        cache: false,
                        url: cmtx_js_settings_user.commentics_url + 'frontend/index.php?route=main/user/deleteSubscription',
                        data: '&u-t=' + encodeURIComponent(cmtx_js_settings_user.token) + '&s-t=' + encodeURIComponent(jQuery(trash_icon).attr('data-sub-token')),
                        dataType: 'json'
                    });

                    request.done(function(response) {
                        jQuery('.cmtx_message').remove();

                        if (response['success']) {
                            jQuery(trash_icon).parent().parent().remove();

                            jQuery('.count').text(response['count']);

                            if (response['count'] == '0') {
                                jQuery('tbody').append('<tr><td class="cmtx_no_results" colspan="4">' + cmtx_js_settings_user.lang_text_no_results + '</td></tr>');
                            } else {
                                var i = 1;

                                jQuery('tbody tr td:first-child').each(function() {
                                    jQuery(this).text(i);

                                    i++;
                                });
                            }
                        }

                        if (response['error']) {
                            cmtxAutoScroll(jQuery('.cmtx_user_container'));

                            jQuery('form').before('<div class="cmtx_message cmtx_message_error">' + response['error'] + '</div>');

                            jQuery('.cmtx_message').show();
                        }
                    });
                });

                jQuery('#cmtx_user_container .cmtx_delete_all').click(function(e) {
                    e.preventDefault();

                    var request = jQuery.ajax({
                        type: 'POST',
                        cache: false,
                        url: cmtx_js_settings_user.commentics_url + 'frontend/index.php?route=main/user/deleteAllSubscriptions',
                        data: '&u-t=' + encodeURIComponent(cmtx_js_settings_user.token),
                        dataType: 'json'
                    });

                    request.done(function(response) {
                        jQuery('.cmtx_message').remove();

                        if (response['success']) {
                            jQuery('.count').text('0');

                            jQuery('tbody').html('<tr><td class="cmtx_no_results" colspan="4">' + cmtx_js_settings_user.lang_text_no_results + '</td></tr>');
                        }

                        if (response['error']) {
                            cmtxAutoScroll(jQuery('.cmtx_user_container'));

                            jQuery('form').before('<div class="cmtx_message cmtx_message_error">' + response['error'] + '</div>');

                            jQuery('.cmtx_message').show();
                        }
                    });
                });
            }
        });
    }
}, 100);

/* Get the value from the sort by field */
function cmtxGetSortByValue() {
    var sort_by = jQuery('select[name="cmtx_sort_by"]').val();

    if (typeof(sort_by) == 'undefined') {
        sort_by = '';
    }

    return sort_by;
}

/* Get the value from the search field */
function cmtxGetSearchValue() {
    var search = jQuery('input[name="cmtx_search"]').val();

    if (typeof(search) == 'undefined') {
        search = '';
    }

    return search;
}

/* Get the current page number */
function cmtxGetCurrentPage() {
    if (jQuery('.cmtx_pagination_box_active').length) {
        return parseInt(jQuery('.cmtx_pagination_box_active').attr('data-cmtx-page'));
    } else if (jQuery('#cmtx_next_page').length) {
        return parseInt(jQuery('#cmtx_next_page').val() - 1);
    } else {
        return 1;
    }
}

/* Infinite scroll */
var scroll_timeout = null;

function cmtxInfiniteScroll() {
    if (jQuery('#cmtx_loading_helper').attr('data-cmtx-load') == '1') {
        clearTimeout(scroll_timeout);

        scroll_timeout = setTimeout(function() {
            var element_distance = Math.ceil(jQuery('#cmtx_loading_helper').offset().top);

            // if the sum of the window height and scroll distance from the top is greater than the target element's distance from the top
            if ((jQuery(window).height() + jQuery(this).scrollTop()) > element_distance) {
                var next_page = parseInt(jQuery('#cmtx_next_page').val());

                jQuery('#cmtx_next_page').val(next_page + 1);

                var options = {
                    'commentics_url': cmtx_js_settings_comments.commentics_url,
                    'page_id'       : cmtx_js_settings_comments.page_id,
                    'page_number'   : next_page,
                    'sort_by'       : cmtxGetSortByValue(),
                    'search'        : cmtxGetSearchValue(),
                    'pagination'    : 'infinite',
                    'effect'        : false
                }

                cmtxRefreshComments(options);
            }
        }, 200);
    }
}

function cmtxInfiniteScrollIframe(e) {
    if (jQuery('#cmtx_loading_helper').attr('data-cmtx-load') == '1') {
        var next_page = parseInt(jQuery('#cmtx_next_page').val());

        jQuery('#cmtx_next_page').val(next_page + 1);

        var options = {
            'commentics_url': cmtx_js_settings_comments.commentics_url,
            'page_id'       : cmtx_js_settings_comments.page_id,
            'page_number'   : next_page,
            'sort_by'       : cmtxGetSortByValue(),
            'search'        : cmtxGetSearchValue(),
            'pagination'    : 'infinite',
            'effect'        : false
        }

        cmtxRefreshComments(options);
    }
}

if (typeof window.iFrameResizer != 'undefined' && window.iFrameResizer != null) {
    window.iFrameResizer = {
        messageCallback: function (message) {
            console.log(message);
        }
    };
}

/* Auto update the time with e.g. '2 minutes ago' */
function cmtxTimeago() {
    if (typeof(cmtx_js_settings_comments) != 'undefined') {
        if (cmtx_js_settings_comments.date_auto) {
            jQuery.timeago.settings.strings = {
                suffixAgo: cmtx_js_settings_comments.timeago_suffixAgo,
                inPast   : cmtx_js_settings_comments.timeago_inPast,
                seconds  : cmtx_js_settings_comments.timeago_seconds,
                minute   : cmtx_js_settings_comments.timeago_minute,
                minutes  : cmtx_js_settings_comments.timeago_minutes,
                hour     : cmtx_js_settings_comments.timeago_hour,
                hours    : cmtx_js_settings_comments.timeago_hours,
                day      : cmtx_js_settings_comments.timeago_day,
                days     : cmtx_js_settings_comments.timeago_days,
                month    : cmtx_js_settings_comments.timeago_month,
                months   : cmtx_js_settings_comments.timeago_months,
                year     : cmtx_js_settings_comments.timeago_year,
                years    : cmtx_js_settings_comments.timeago_years
            };

            jQuery('.cmtx_date_area .timeago').timeago();
        }
    }

    if (typeof(cmtx_js_settings_user) != 'undefined') {
        jQuery.timeago.settings.strings = {
            suffixAgo: cmtx_js_settings_user.timeago_suffixAgo,
            inPast   : cmtx_js_settings_user.timeago_inPast,
            seconds  : cmtx_js_settings_user.timeago_seconds,
            minute   : cmtx_js_settings_user.timeago_minute,
            minutes  : cmtx_js_settings_user.timeago_minutes,
            hour     : cmtx_js_settings_user.timeago_hour,
            hours    : cmtx_js_settings_user.timeago_hours,
            day      : cmtx_js_settings_user.timeago_day,
            days     : cmtx_js_settings_user.timeago_days,
            month    : cmtx_js_settings_user.timeago_month,
            months   : cmtx_js_settings_user.timeago_months,
            year     : cmtx_js_settings_user.timeago_year,
            years    : cmtx_js_settings_user.timeago_years
        };

        jQuery('#cmtx_user_container .timeago').timeago();
    }
}

/* Highlight any user-entered code */
function cmtxHighlightCode() {
    if (typeof(hljs) != 'undefined' && typeof(hljs.highlightElement) != 'undefined') {
        jQuery('.cmtx_code_box, .cmtx_php_box').each(function(i, el) {
            hljs.highlightElement(el);
        });
    }
}

/* Viewers Online */
function cmtxViewersOnline() {
    if (typeof(cmtx_js_settings_online) != 'undefined') {
        if (cmtx_js_settings_online.online_refresh_enabled) {
            setInterval(function() {
                var request = jQuery.ajax({
                    type: 'POST',
                    cache: false,
                    url: cmtx_js_settings_online.commentics_url + 'frontend/index.php?route=part/online/refresh',
                    data: 'cmtx_page_id=' + encodeURIComponent(cmtx_js_settings_online.page_id),
                    dataType: 'json'
                });

                request.done(function(response) {
                    if (response['online'] != 'undefined') { // may be zero
                        if (jQuery('.cmtx_online_num').first().text() != response['online']) { // only update if different
                            jQuery('.cmtx_online_num').fadeOut(function() {
                                jQuery('.cmtx_online_num').text(response['online']).fadeIn();
                            });
                        }
                    }
                });

                request.fail(function(jqXHR, textStatus, errorThrown) {
                    if (console && console.log) {
                        console.log(jqXHR.responseText);
                    }
                });
            }, cmtx_js_settings_online.online_refresh_interval);
        }
    }
}

/* Show the 'View x replies' link */
function cmtxViewReplies() {
    if (typeof(cmtx_js_settings_comments) != 'undefined') {
        jQuery('.cmtx_reply_counter').each(function() {
            var reply_counter = jQuery(this).text();

            if (reply_counter) {
                if (reply_counter == 1) {
                    var view_replies = '<span class="cmtx_reply_view">' + cmtx_js_settings_comments.lang_text_view + '</span> <span class="cmtx_reply_num">1</span> <span class="cmtx_reply_replies">' + cmtx_js_settings_comments.lang_text_reply + '</span>';
                } else {
                    var view_replies = '<span class="cmtx_reply_view">' + cmtx_js_settings_comments.lang_text_view + '</span> <span class="cmtx_reply_num">' + reply_counter + '</span> <span class="cmtx_reply_replies">' + cmtx_js_settings_comments.lang_text_replies + '</span>';
                }

                jQuery(this).closest('.cmtx_comment_section').find('.cmtx_view_replies_link').html('<i class="fa fa-commenting-o" aria-hidden="true"></i> ' + view_replies);
            }
        });
    }
}

/* Close the share box when clicking off it */
function cmtxCloseShareBox() {
    if (jQuery('.cmtx_share_box').length) {
        jQuery(document).mouseup(function(e) {
            var container = jQuery('.cmtx_share_box');

            if (!container.is(e.target) && container.has(e.target).length === 0) {
                container.fadeOut(400);
            }
        });
    }
}

/* Close the permalink box when clicking off it */
function cmtxClosePermalinkBox() {
    if (jQuery('.cmtx_permalink_box').length) {
        jQuery(document).mouseup(function(e) {
            var container = jQuery('.cmtx_permalink_box');

            if (!container.is(e.target) && container.has(e.target).length === 0) {
                container.fadeOut(400);
            }
        });
    }
}

/* Auto scroll to element */
function cmtxAutoScroll(element) {
    try {
       element[0].scrollIntoView({ behavior: 'smooth', block: 'start' });
    } catch (error) {
       // fallback for browsers like Edge that don't yet support the options parameter
       element[0].scrollIntoView(true);
    }
}

/* Update the comment counter */
function cmtxUpdateCommentCounter() {
    if (jQuery('#cmtx_comment').length) {
        var length = jQuery('#cmtx_comment').val().length;

        var maximum = jQuery('#cmtx_comment').attr('maxlength');

        jQuery('#cmtx_counter').html(maximum - length);
    }
}

/* Adds a tag (BB Code or Smiley) to the comment field */
function cmtx_add_tag(start, end) {
    var obj = document.getElementById('cmtx_comment');

    jQuery('#cmtx_comment').focus();

    if (document.selection && document.selection.createRange) { // Internet Explorer
        selection = document.selection.createRange();

        if (selection.parentElement() == obj) {
            selection.text = start + selection.text + end;
        }
    } else if (typeof(obj) != 'undefined') { // Firefox
        var length = jQuery('#cmtx_comment').val().length;
        var selection_start = obj.selectionStart;
        var selection_end = obj.selectionEnd;

        jQuery('#cmtx_comment').val(obj.value.substring(0, selection_start) + start + obj.value.substring(selection_start, selection_end) + end + obj.value.substring(selection_end, length));
    } else {
        jQuery('#cmtx_comment').val(start + end);
    }

    cmtxUpdateCommentCounter();

    jQuery('#cmtx_comment').focus();
}

/* Function to refresh the comments using ajax */
function cmtxRefreshComments(options) {
    var request = jQuery.ajax({
        type: 'POST',
        cache: false,
        url: options.commentics_url + 'frontend/index.php?route=main/comments/getComments',
        data: 'cmtx_page_id=' + encodeURIComponent(options.page_id) + '&cmtx_sort_by=' + encodeURIComponent(options.sort_by) + '&cmtx_search=' + encodeURIComponent(options.search) + '&cmtx_page=' + encodeURIComponent(options.page_number),
        dataType: 'json',
        beforeSend: function() {
            if (options.effect) {
                jQuery('.cmtx_loading_icon').show();

                jQuery('body').addClass('cmtx_loading_body');
            }
        }
    });

    request.always(function() {
        if (options.effect) {
            jQuery('.cmtx_loading_icon').hide();

            jQuery('body').removeClass('cmtx_loading_body');
        }
    });

    request.done(function(response) {
        if (response['result']) {
            if (options.pagination == 'button' || options.pagination == 'infinite') {
                var comments = jQuery('.cmtx_comment_boxes', jQuery(response['result'])).html();

                jQuery('.cmtx_comment_boxes').append(comments);

                jQuery('#cmtx_more_button').val(cmtx_js_settings_comments.lang_button_more);

                jQuery('#cmtx_more_button').prop('disabled', false);

                jQuery('#cmtx_more_button').removeClass('cmtx_button_disabled');

                var total_comments = parseInt(jQuery('#cmtx_loading_helper').attr('data-cmtx-total-comments'));

                if (total_comments > jQuery('.cmtx_comment_section').length) {
                    // there are more comments that can be loaded
                } else {
                    jQuery('#cmtx_more_button').remove();

                    jQuery('#cmtx_loading_helper').attr('data-cmtx-load', '0');
                }
            } else {
                jQuery('.cmtx_comments_section').html(response['result']);
            }

            if (jQuery('#cmtx_search').val() != '') {
                jQuery('#cmtx_search').addClass('cmtx_search_focus');
            };

            /* Load the comment settings in case they weren't already loaded (if there were no comments) */
            if (jQuery('#cmtx_js_settings_comments').length) {
                cmtx_js_settings_comments = JSON.parse(jQuery('#cmtx_js_settings_comments').text());
            }

            /* Load the notify settings in case they weren't already loaded (if there were no comments) */
            if (jQuery('#cmtx_js_settings_notify').length) {
                cmtx_js_settings_notify = JSON.parse(jQuery('#cmtx_js_settings_notify').text());
            }

            /* Load the online settings in case they weren't already loaded (if there were no comments) */
            if (jQuery('#cmtx_js_settings_online').length) {
                cmtx_js_settings_online = JSON.parse(jQuery('#cmtx_js_settings_online').text());
            }

            cmtxViewReplies();
            cmtxTimeago();
            cmtxHighlightCode();
            cmtxViewersOnline();
            cmtxCloseShareBox();
            cmtxClosePermalinkBox();
        }
    });

    request.fail(function(jqXHR, textStatus, errorThrown) {
        if (console && console.log) {
            console.log(jqXHR.responseText);
        }
    });
}

/* Trims a string */
function cmtxTrim(string) {
    return string.trim(string);
}