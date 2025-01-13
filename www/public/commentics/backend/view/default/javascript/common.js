$(document).ready(function() {
    js_settings = JSON.parse($('#js_settings').text());

    /* Initialise the FlexNav menu */
    $('.flexnav').flexNav({
        'animationSpeed': 300,
        'transitionOpacity': false,
        'calcItemWidths': true
    });

    /* When pressing Enter inside filter fields, submit the form */
    $('.filter').keydown(function(e) {
        if (e.keyCode == 13) {
            $('#filter').trigger('click');
        }
    });

    /* When top checkbox is selected, select all checkboxes */
    $('table thead input:checkbox').change(function() {
        if ($('table thead input:checkbox').is(':checked')) {
            $('table tbody input:checkbox').prop('checked', true);
        } else {
            $('table tbody input:checkbox').prop('checked', false);
        }
    });

    /* When all checkboxes are selected, select top checkbox */
    $('table tbody input:checkbox').change(function() {
        var all_checked = true;

        $('table tbody input:checkbox').each(function() {
            if (!$(this).is(':checked')) {
                all_checked = false;
            }
        });

        if (all_checked) {
            $('table thead input:checkbox').prop('checked', true);
        } else {
            $('table thead input:checkbox').prop('checked', false);
        }
    });

    /* Show a password strength indicator for better security */
    $('input[name="password_1"]').keyup(function() {
        var password = $('input[name="password_1"]').val();

        password = $.trim(password);

        var description = [];

        description[0] = '';
        description[1] = 'Very Weak';
        description[2] = 'Weak';
        description[3] = 'Fair';
        description[4] = 'Good';
        description[5] = 'Strong';
        description[6] = 'Strongest';

        var score = 0;

        // if password is bigger than 0 give 1 point
        if (password.length > 0) {
            score++;
        }

        // if password bigger than 6 give 1 point
        if (password.length > 6) {
            score++;
        }

        // if password has both lowercase and uppercase characters give 1 point
        if (password.match(/[a-z]/) && password.match(/[A-Z]/)) {
            score++;
        }

        // if password has at least one number give 1 point
        if (password.match(/\d+/)) {
            score++;
        }

        // if password has at least one special character give 1 point
        if (password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)) {
            score++;
        }

        // if password bigger than 12 give 1 point
        if (password.length > 12) {
            score++;
        }

        $('#password_description').html(description[score]);

        $('#password_strength').removeClass();

        $('#password_strength').addClass('strength_' + score);
    });

    /* Convert certain inputs to jQuery UI datepicker */
    $('.datepicker').datepicker({
        dateFormat: 'yy-mm-dd'
    });

    /* Convert certain inputs to jQuery UI tabs */
    $('#tabs').tabs();

    /* Add a divider after certain fields */
    $('.divide_after').after('<div class="fieldset"><label></label></div>');

    /* Hint tooltips */
    $('[data-hint]').mouseover(function(e) {
        showhint($(this).attr('data-hint'), this, e, '');
    });

    /* Dismiss info messages */
    $('div.info a:last-child').click(function(e) {
        if ($(this).text() == 'x') {
            e.preventDefault();

            var page = $('main').children(':first').attr('id');

            if (page) {
                page = page.substring(0, page.length-5); // remove '_page' from end

                page = page.replace(/_/, '/');

                $.ajax({
                    url: 'index.php?route=' + page + '/dismiss',
                })

                $('div.info').fadeOut(2000);
            }
        }
    });

    /* Dismiss warning messages */
    $('div.warning a:last-child').click(function(e) {
        if ($(this).text() == 'x') {
            e.preventDefault();

            var page = $('main').children(':first').attr('id');

            if (page) {
                page = page.substring(0, page.length-5); // remove '_page' from end

                page = page.replace(/_/, '/');

                $.ajax({
                    url: 'index.php?route=' + page + '/discard',
                })

                $('div.warning').fadeOut(2000);
            }
        }
    });

    /* Fix for dialog close button */

    $.widget('ui.dialog', $.ui.dialog, {
        open: function() {
            $('.ui-dialog-titlebar-close').html('<span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span>');

            return this._super();
        }
    });

    /* Delete dialog on 'edit' pages */
    $('input[name="delete"]').click(function(e) {
        e.preventDefault();

        var id = $(this).data('id');

        var url = $(this).data('url');

        $('#delete_dialog').dialog({
            modal: true,
            height: 'auto',
            width: 'auto',
            resizable: false,
            draggable: false,
            center: true,
            buttons: {
                'Yes': function() {
                    $('form').attr('action', 'index.php?route=' + url);

                    var input = $('<input>').attr('type', 'hidden').attr('name', 'single_delete').val(id);

                    $('form').append($(input));

                    $('form').submit();

                    $(this).dialog('close');
                },
                'No': function() {
                    $(this).dialog('close');
                }
            }
        });

        translate_buttons();

        $('#delete_dialog').dialog('open');
    });

    /* Single delete dialog on 'manage' pages */
    $('.single_delete').click(function(e) {
        e.preventDefault();

        var id = $(this).data('id');

        $('#single_delete_dialog').dialog({
            modal: true,
            height: 'auto',
            width: 'auto',
            resizable: false,
            draggable: false,
            center: true,
            buttons: {
                'Yes': function() {
                    var input = $('<input>').attr('type', 'hidden').attr('name', 'single_delete').val(id);

                    $('form').append($(input));

                    $('form').submit();

                    $(this).dialog('close');
                },
                'No': function() {
                    $(this).dialog('close');
                }
            }
        });

        translate_buttons();

        $('#single_delete_dialog').dialog('open');
    });

    /* Bulk delete dialog on 'manage' pages */
    $('input[name="bulk_delete"]').click(function(e) {
        e.preventDefault();

        $('#bulk_delete_dialog').dialog({
            modal: true,
            height: 'auto',
            width: 'auto',
            resizable: false,
            draggable: false,
            center: true,
            buttons: {
                'Yes': function() {
                    $('form').submit();

                    $(this).dialog('close');
                },
                'No': function() {
                    $(this).dialog('close');
                }
            }
        });

        translate_buttons();

        $('#bulk_delete_dialog').dialog('open');
    });

    /* Page restrictions */

    $('input[name="restrict_pages"]').change(function() {
        if ($(this).is(':checked')) {
            $('.restriction_fieldset').removeClass('restriction_fieldset_hidden');
        } else {
            $('.restriction_fieldset').addClass('restriction_fieldset_hidden');
        }
    });

    $('.restriction_fieldset input[type="checkbox"]').change(function() {
        $('input[name="viewable_pages[]"]').each(function() {
            if ($(this).next().attr('name') == 'modifiable_pages[]') {
                 if ($(this).is(':checked')) {
                     $(this).next().prop('disabled', false);
                 } else {
                     $(this).next().prop('checked', false);
                     $(this).next().prop('disabled', true);
                 }
            }
        });
    });

    $('input[name="restrict_pages"]').trigger('change');

    $('.restriction_fieldset input[type="checkbox"]').trigger('change');

    /* Dashboard */

    if ($('#version_issue_dialog').length) {
        $('#version_issue_dialog').dialog({
            modal: true,
            height: 'auto',
            width: 'auto',
            resizable: false,
            draggable: false,
            center: true,
            buttons: {
                'Stop': function() {
                    $.ajax({
                        url: 'index.php?route=main/dashboard/stopVersionDetect',
                    })

                    $(this).dialog('close');
                },
                'Close': function() {
                    $(this).dialog('close');
                }
            }
        });

        translate_buttons();

        $('#version_issue_dialog').dialog('open');
    }

    if ($('#system_settings_dialog').length) {
        $('#system_settings_dialog').dialog({
            modal: true,
            height: 'auto',
            width: 'auto',
            resizable: false,
            draggable: false,
            center: true,
            buttons: {
                'Stop': function() {
                    $.ajax({
                        url: 'index.php?route=main/dashboard/stopSystemDetect',
                    })

                    $(this).dialog('close');
                },
                'Close': function() {
                    $(this).dialog('close');
                }
            }
        });

        translate_buttons();

        $('#system_settings_dialog').dialog('open');
    }

    if ($('#main_dashboard_page #chart').length) {
        var ctx = $('#main_dashboard_page #chart');

        var data = {
            labels: [
                js_settings.lang_january,
                js_settings.lang_february,
                js_settings.lang_march,
                js_settings.lang_april,
                js_settings.lang_may,
                js_settings.lang_june,
                js_settings.lang_july,
                js_settings.lang_august,
                js_settings.lang_september,
                js_settings.lang_october,
                js_settings.lang_november,
                js_settings.lang_december
            ],
            datasets: [
                {
                    label: js_settings.lang_text_comments,
                    fill: true,
                    backgroundColor: "rgba(237,237,237,0.2)",
                    borderColor: "#A8A8A8",
                    borderWidth: 1,
                    data: [
                        $('#main_dashboard_page input[data-js="chart_comments_jan"]').val(),
                        $('#main_dashboard_page input[data-js="chart_comments_feb"]').val(),
                        $('#main_dashboard_page input[data-js="chart_comments_mar"]').val(),
                        $('#main_dashboard_page input[data-js="chart_comments_apr"]').val(),
                        $('#main_dashboard_page input[data-js="chart_comments_may"]').val(),
                        $('#main_dashboard_page input[data-js="chart_comments_jun"]').val(),
                        $('#main_dashboard_page input[data-js="chart_comments_jul"]').val(),
                        $('#main_dashboard_page input[data-js="chart_comments_aug"]').val(),
                        $('#main_dashboard_page input[data-js="chart_comments_sep"]').val(),
                        $('#main_dashboard_page input[data-js="chart_comments_oct"]').val(),
                        $('#main_dashboard_page input[data-js="chart_comments_nov"]').val(),
                        $('#main_dashboard_page input[data-js="chart_comments_dec"]').val()
                    ]
                },
                {
                    label: js_settings.lang_text_subscriptions,
                    fill: true,
                    backgroundColor: "rgba(237,237,237,0.2)",
                    borderColor: "#FF7F00",
                    borderWidth: 1,
                    data: [
                        $('#main_dashboard_page input[data-js="chart_subscriptions_jan"]').val(),
                        $('#main_dashboard_page input[data-js="chart_subscriptions_feb"]').val(),
                        $('#main_dashboard_page input[data-js="chart_subscriptions_mar"]').val(),
                        $('#main_dashboard_page input[data-js="chart_subscriptions_apr"]').val(),
                        $('#main_dashboard_page input[data-js="chart_subscriptions_may"]').val(),
                        $('#main_dashboard_page input[data-js="chart_subscriptions_jun"]').val(),
                        $('#main_dashboard_page input[data-js="chart_subscriptions_jul"]').val(),
                        $('#main_dashboard_page input[data-js="chart_subscriptions_aug"]').val(),
                        $('#main_dashboard_page input[data-js="chart_subscriptions_sep"]').val(),
                        $('#main_dashboard_page input[data-js="chart_subscriptions_oct"]').val(),
                        $('#main_dashboard_page input[data-js="chart_subscriptions_nov"]').val(),
                        $('#main_dashboard_page input[data-js="chart_subscriptions_dec"]').val()
                    ]
                }
            ]
        };

        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        ticks: {
                            beginAtZero: true,
                            precision: 0
                        }
                    }
                }
            }
        });
    }

    /* Manage Admins */

    $('#manage_admins_page #filter').click(function() {
        var url = 'index.php?route=manage/admins';

        var filter_username = $('input[name="filter_username"]').val();

        if (filter_username) {
            url += '&filter_username=' + encodeURIComponent(filter_username);
        }

        var filter_email = $('input[name="filter_email"]').val();

        if (filter_email) {
            url += '&filter_email=' + encodeURIComponent(filter_email);
        }

        var filter_enabled = $('select[name="filter_enabled"]').val();

        if (filter_enabled) {
            url += '&filter_enabled=' + encodeURIComponent(filter_enabled);
        }

        var filter_super = $('select[name="filter_super"]').val();

        if (filter_super) {
            url += '&filter_super=' + encodeURIComponent(filter_super);
        }

        var filter_last_login = $('input[name="filter_last_login"]').val();

        if (filter_last_login) {
            url += '&filter_last_login=' + encodeURIComponent(filter_last_login);
        }

        var filter_date = $('input[name="filter_date"]').val();

        if (filter_date) {
            url += '&filter_date=' + encodeURIComponent(filter_date);
        }

        location = url;
    });

    $('#manage_admins_page input[name=\'filter_username\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=manage/admins/autocomplete&filter_username=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.username,
                            value: item.username
                        }
                    }));
                }
            });
        }
    });

    $('#manage_admins_page input[name=\'filter_email\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=manage/admins/autocomplete&filter_email=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.email,
                            value: item.email
                        }
                    }));
                }
            });
        }
    });

    /* Manage Bans */

    $('#manage_bans_page #filter').click(function() {
        var url = 'index.php?route=manage/bans';

        var filter_ip_address = $('input[name="filter_ip_address"]').val();

        if (filter_ip_address) {
            url += '&filter_ip_address=' + encodeURIComponent(filter_ip_address);
        }

        var filter_reason = $('input[name="filter_reason"]').val();

        if (filter_reason) {
            url += '&filter_reason=' + encodeURIComponent(filter_reason);
        }

        var filter_date = $('input[name="filter_date"]').val();

        if (filter_date) {
            url += '&filter_date=' + encodeURIComponent(filter_date);
        }

        location = url;
    });

    $('#manage_bans_page input[name=\'filter_ip_address\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=manage/bans/autocomplete&filter_ip_address=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.ip_address,
                            value: item.ip_address
                        }
                    }));
                }
            });
        }
    });

    $('#manage_bans_page input[name=\'filter_reason\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=manage/bans/autocomplete&filter_reason=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.reason,
                            value: item.reason
                        }
                    }));
                }
            });
        }
    });

    /* Manage Comments */

    $('#manage_comments_page #filter').click(function() {
        var url = 'index.php?route=manage/comments';

        var filter_name = $('input[name="filter_name"]').val();

        if (filter_name) {
            url += '&filter_name=' + encodeURIComponent(filter_name);
        }

        var filter_comment = $('input[name="filter_comment"]').val();

        if (filter_comment) {
            url += '&filter_comment=' + encodeURIComponent(filter_comment);
        }

        var filter_rating = $('select[name="filter_rating"]').val();

        if (filter_rating) {
            url += '&filter_rating=' + encodeURIComponent(filter_rating);
        }

        var filter_page = $('input[name="filter_page"]').val();

        if (filter_page) {
            url += '&filter_page=' + encodeURIComponent(filter_page);
        }

        var filter_approved = $('select[name="filter_approved"]').val();

        if (filter_approved) {
            url += '&filter_approved=' + encodeURIComponent(filter_approved);
        }

        var filter_sent = $('select[name="filter_sent"]').val();

        if (filter_sent) {
            url += '&filter_sent=' + encodeURIComponent(filter_sent);
        }

        var filter_flagged = $('select[name="filter_flagged"]').val();

        if (filter_flagged) {
            url += '&filter_flagged=' + encodeURIComponent(filter_flagged);
        }

        var filter_ip_address = $('input[name="filter_ip_address"]').val();

        if (filter_ip_address) {
            url += '&filter_ip_address=' + encodeURIComponent(filter_ip_address);
        }

        var filter_date = $('input[name="filter_date"]').val();

        if (filter_date) {
            url += '&filter_date=' + encodeURIComponent(filter_date);
        }

        location = url;
    });

    $('#manage_comments_page input[name=\'filter_name\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=manage/comments/autocomplete&filter_name=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.name,
                            value: item.name
                        }
                    }));
                }
            });
        }
    });

    $('#manage_comments_page input[name=\'filter_comment\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=manage/comments/autocomplete&filter_comment=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.comment,
                            value: item.comment
                        }
                    }));
                }
            });
        }
    });

    $('#manage_comments_page input[name=\'filter_page\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=manage/comments/autocomplete&filter_page=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.page,
                            value: item.page
                        }
                    }));
                }
            });
        }
    });

    $('#manage_comments_page input[name=\'filter_ip_address\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=manage/comments/autocomplete&filter_ip_address=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.ip_address,
                            value: item.ip_address
                        }
                    }));
                }
            });
        }
    });

    $('#manage_comments_page .single_approve').click(function(e) {
        e.preventDefault();

        var id = $(this).data('id');

        var input = $('<input>').attr('type', 'hidden').attr('name', 'single_approve').val(id);

        $('form').append($(input));

        $('form').submit();
    });

    $('#manage_comments_page .single_send').click(function(e) {
        e.preventDefault();

        var id = $(this).data('id');

        var input = $('<input>').attr('type', 'hidden').attr('name', 'single_send').val(id);

        $('form').append($(input));

        $('form').submit();
    });

    $('#manage_comments_page input[name="bulk_approve"]').click(function(e) {
        e.preventDefault();

        var input = $('<input>').attr('type', 'hidden').attr('name', 'bulk_action').val('approve');

        $('form').append($(input));

        $('form').submit();
    });

    $('#manage_comments_page input[name="bulk_send"]').click(function(e) {
        e.preventDefault();

        var input = $('<input>').attr('type', 'hidden').attr('name', 'bulk_action').val('send');

        $('form').append($(input));

        $('form').submit();
    });

    $('#manage_comments_page .single_delete').click(function(e) {
        e.preventDefault();

        var id = $(this).data('id');

        $('#single_delete_dialog').dialog({
            modal: true,
            height: 'auto',
            width: 'auto',
            resizable: false,
            draggable: false,
            center: true,
            buttons: {
                'Yes': function() {
                    var input = $('<input>').attr('type', 'hidden').attr('name', 'single_delete').val(id);

                    $('form').append($(input));

                    $('form').submit();

                    $(this).dialog('close');
                },
                'No': function() {
                    $(this).dialog('close');
                }
            }
        });

        translate_buttons();

        $('#single_delete_dialog').dialog('open');
    });

    $('#manage_comments_page input[name="bulk_delete"]').click(function(e) {
        e.preventDefault();

        $('#bulk_delete_dialog').dialog({
            modal: true,
            height: 'auto',
            width: 'auto',
            resizable: false,
            draggable: false,
            center: true,
            buttons: {
                'Yes': function() {
                    var input = $('<input>').attr('type', 'hidden').attr('name', 'bulk_action').val('delete');

                    $('form').append($(input));

                    $('form').submit();

                    $(this).dialog('close');
                },
                'No': function() {
                    $(this).dialog('close');
                }
            }
        });

        translate_buttons();

        $('#bulk_delete_dialog').dialog('open');
    });

    /* Manage Countries */

    $('#manage_countries_page #filter').click(function() {
        var url = 'index.php?route=manage/countries';

        var filter_name = $('input[name="filter_name"]').val();

        if (filter_name) {
            url += '&filter_name=' + encodeURIComponent(filter_name);
        }

        var filter_code = $('input[name="filter_code"]').val();

        if (filter_code) {
            url += '&filter_code=' + encodeURIComponent(filter_code);
        }

        var filter_top = $('select[name="filter_top"]').val();

        if (filter_top) {
            url += '&filter_top=' + encodeURIComponent(filter_top);
        }

        var filter_enabled = $('select[name="filter_enabled"]').val();

        if (filter_enabled) {
            url += '&filter_enabled=' + encodeURIComponent(filter_enabled);
        }

        var filter_date = $('input[name="filter_date"]').val();

        if (filter_date) {
            url += '&filter_date=' + encodeURIComponent(filter_date);
        }

        location = url;
    });

    $('#manage_countries_page input[name=\'filter_name\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=manage/countries/autocomplete&filter_name=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.name,
                            value: item.name
                        }
                    }));
                }
            });
        }
    });

    $('#manage_countries_page input[name=\'filter_code\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=manage/countries/autocomplete&filter_code=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.code,
                            value: item.code
                        }
                    }));
                }
            });
        }
    });

    /* Manage Pages */

    $('#manage_pages_page #filter').click(function() {
        var url = 'index.php?route=manage/pages';

        var filter_identifier = $('input[name="filter_identifier"]').val();

        if (filter_identifier) {
            url += '&filter_identifier=' + encodeURIComponent(filter_identifier);
        }

        var filter_reference = $('input[name="filter_reference"]').val();

        if (filter_reference) {
            url += '&filter_reference=' + encodeURIComponent(filter_reference);
        }

        var filter_url = $('input[name="filter_url"]').val();

        if (filter_url) {
            url += '&filter_url=' + encodeURIComponent(filter_url);
        }

        var filter_moderate = $('select[name="filter_moderate"]').val();

        if (filter_moderate) {
            url += '&filter_moderate=' + encodeURIComponent(filter_moderate);
        }

        var filter_is_form_enabled = $('select[name="filter_is_form_enabled"]').val();

        if (filter_is_form_enabled) {
            url += '&filter_is_form_enabled=' + encodeURIComponent(filter_is_form_enabled);
        }

        var filter_date = $('input[name="filter_date"]').val();

        if (filter_date) {
            url += '&filter_date=' + encodeURIComponent(filter_date);
        }

        location = url;
    });

    $('#manage_pages_page input[name=\'filter_identifier\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=manage/pages/autocomplete&filter_identifier=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.identifier,
                            value: item.identifier
                        }
                    }));
                }
            });
        }
    });

    $('#manage_pages_page input[name=\'filter_reference\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=manage/pages/autocomplete&filter_reference=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.reference,
                            value: item.reference
                        }
                    }));
                }
            });
        }
    });

    $('#manage_pages_page input[name=\'filter_url\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=manage/pages/autocomplete&filter_url=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.url,
                            value: item.url
                        }
                    }));
                }
            });
        }
    });

    /* Manage Questions */

    $('#manage_questions_page #filter').click(function() {
        var url = 'index.php?route=manage/questions';

        var filter_question = $('input[name="filter_question"]').val();

        if (filter_question) {
            url += '&filter_question=' + encodeURIComponent(filter_question);
        }

        var filter_answer = $('input[name="filter_answer"]').val();

        if (filter_answer) {
            url += '&filter_answer=' + encodeURIComponent(filter_answer);
        }

        var filter_language = $('input[name="filter_language"]').val();

        if (filter_language) {
            url += '&filter_language=' + encodeURIComponent(filter_language);
        }

        var filter_date = $('input[name="filter_date"]').val();

        if (filter_date) {
            url += '&filter_date=' + encodeURIComponent(filter_date);
        }

        location = url;
    });

    $('#manage_questions_page input[name=\'filter_question\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=manage/questions/autocomplete&filter_question=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.question,
                            value: item.question
                        }
                    }));
                }
            });
        }
    });

    $('#manage_questions_page input[name=\'filter_answer\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=manage/questions/autocomplete&filter_answer=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.answer,
                            value: item.answer
                        }
                    }));
                }
            });
        }
    });

    $('#manage_questions_page input[name=\'filter_language\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=manage/questions/autocomplete&filter_language=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.language,
                            value: item.language
                        }
                    }));
                }
            });
        }
    });

    /* Manage Sites */

    $('#manage_sites_page #filter').click(function() {
        var url = 'index.php?route=manage/sites';

        var filter_name = $('input[name="filter_name"]').val();

        if (filter_name) {
            url += '&filter_name=' + encodeURIComponent(filter_name);
        }

        var filter_domain = $('input[name="filter_domain"]').val();

        if (filter_domain) {
            url += '&filter_domain=' + encodeURIComponent(filter_domain);
        }

        var filter_date = $('input[name="filter_date"]').val();

        if (filter_date) {
            url += '&filter_date=' + encodeURIComponent(filter_date);
        }

        location = url;
    });

    $('#manage_sites_page input[name=\'filter_name\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=manage/sites/autocomplete&filter_name=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.name,
                            value: item.name
                        }
                    }));
                }
            });
        }
    });

    $('#manage_sites_page input[name=\'filter_domain\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=manage/sites/autocomplete&filter_domain=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.domain,
                            value: item.domain
                        }
                    }));
                }
            });
        }
    });

    /* Manage States */

    $('#manage_states_page #filter').click(function() {
        var url = 'index.php?route=manage/states';

        var filter_name = $('input[name="filter_name"]').val();

        if (filter_name) {
            url += '&filter_name=' + encodeURIComponent(filter_name);
        }

        var filter_country_code = $('select[name="filter_country_code"]').val();

        if (filter_country_code) {
            url += '&filter_country_code=' + encodeURIComponent(filter_country_code);
        }

        var filter_enabled = $('select[name="filter_enabled"]').val();

        if (filter_enabled) {
            url += '&filter_enabled=' + encodeURIComponent(filter_enabled);
        }

        var filter_date = $('input[name="filter_date"]').val();

        if (filter_date) {
            url += '&filter_date=' + encodeURIComponent(filter_date);
        }

        location = url;
    });

    $('#manage_states_page input[name=\'filter_name\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=manage/states/autocomplete&filter_name=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.name,
                            value: item.name
                        }
                    }));
                }
            });
        }
    });

    $('#manage_states_page input[name=\'filter_country_code\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=manage/states/autocomplete&filter_country_code=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.country_code,
                            value: item.country_code
                        }
                    }));
                }
            });
        }
    });

    /* Manage Subscriptions */

    $('#manage_subscriptions_page #filter').click(function() {
        var url = 'index.php?route=manage/subscriptions';

        var filter_name = $('input[name="filter_name"]').val();

        if (filter_name) {
            url += '&filter_name=' + encodeURIComponent(filter_name);
        }

        var filter_email = $('input[name="filter_email"]').val();

        if (filter_email) {
            url += '&filter_email=' + encodeURIComponent(filter_email);
        }

        var filter_page = $('input[name="filter_page"]').val();

        if (filter_page) {
            url += '&filter_page=' + encodeURIComponent(filter_page);
        }

        var filter_confirmed = $('select[name="filter_confirmed"]').val();

        if (filter_confirmed) {
            url += '&filter_confirmed=' + encodeURIComponent(filter_confirmed);
        }

        var filter_ip_address = $('input[name="filter_ip_address"]').val();

        if (filter_ip_address) {
            url += '&filter_ip_address=' + encodeURIComponent(filter_ip_address);
        }

        var filter_date = $('input[name="filter_date"]').val();

        if (filter_date) {
            url += '&filter_date=' + encodeURIComponent(filter_date);
        }

        location = url;
    });

    $('#manage_subscriptions_page input[name=\'filter_name\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=manage/subscriptions/autocomplete&filter_name=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.name,
                            value: item.name
                        }
                    }));
                }
            });
        }
    });

    $('#manage_subscriptions_page input[name=\'filter_email\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=manage/subscriptions/autocomplete&filter_email=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.email,
                            value: item.email
                        }
                    }));
                }
            });
        }
    });

    $('#manage_subscriptions_page input[name=\'filter_page\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=manage/subscriptions/autocomplete&filter_page=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.page,
                            value: item.page
                        }
                    }));
                }
            });
        }
    });

    $('#manage_subscriptions_page input[name=\'filter_ip_address\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=manage/subscriptions/autocomplete&filter_ip_address=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.ip_address,
                            value: item.ip_address
                        }
                    }));
                }
            });
        }
    });

    /* Manage Users */

    $('#manage_users_page #filter').click(function() {
        var url = 'index.php?route=manage/users';

        var filter_name = $('input[name="filter_name"]').val();

        if (filter_name) {
            url += '&filter_name=' + encodeURIComponent(filter_name);
        }

        var filter_email = $('input[name="filter_email"]').val();

        if (filter_email) {
            url += '&filter_email=' + encodeURIComponent(filter_email);
        }

        var filter_avatar_approved = $('select[name="filter_avatar_approved"]').val();

        if (filter_avatar_approved) {
            url += '&filter_avatar_approved=' + encodeURIComponent(filter_avatar_approved);
        }

        var filter_moderate = $('select[name="filter_moderate"]').val();

        if (filter_moderate) {
            url += '&filter_moderate=' + encodeURIComponent(filter_moderate);
        }

        var filter_date = $('input[name="filter_date"]').val();

        if (filter_date) {
            url += '&filter_date=' + encodeURIComponent(filter_date);
        }

        location = url;
    });

    $('#manage_users_page input[name=\'filter_name\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=manage/users/autocomplete&filter_name=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.name,
                            value: item.name
                        }
                    }));
                }
            });
        }
    });

    $('#manage_users_page input[name=\'filter_email\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=manage/users/autocomplete&filter_email=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.email,
                            value: item.email
                        }
                    }));
                }
            });
        }
    });

    $('#manage_users_page input[name="bulk_approve_avatar"]').click(function(e) {
        e.preventDefault();

        var input = $('<input>').attr('type', 'hidden').attr('name', 'bulk_action').val('approve_avatar');

        $('form').append($(input));

        $('form').submit();
    });

    $('#manage_users_page input[name="bulk_disapprove_avatar"]').click(function(e) {
        e.preventDefault();

        var input = $('<input>').attr('type', 'hidden').attr('name', 'bulk_action').val('disapprove_avatar');

        $('form').append($(input));

        $('form').submit();
    });

    /* Edit Admin */

    $('input[name="is_super"]').change(function() {
        if (this.checked) {
            $('.super_admin_settings').hide();

            $('input[name="restrict_pages"]').prop('checked', false);

            $('input[name="restrict_pages"]').trigger('change');

            $('#super_dialog').dialog({
                modal: true,
                height: 'auto',
                width: 'auto',
                resizable: false,
                draggable: false,
                center: true,
                buttons: {
                    'Close': function() {
                        $(this).dialog('close');
                    }
                }
            });

            translate_buttons();

            $('#super_dialog').dialog('open');
        } else {
            $('.super_admin_settings').show();
        }
    });

    /* Edit Comment */

    if ($('#edit_comment_page .wysiwyg').length) {
        $('#edit_comment_page .wysiwyg').summernote({
            height: 175,
            disableDragAndDrop: true,
            shortcuts: false,
            toolbar: [
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol']],
                ['insert', ['link', 'picture', 'hr']],
                ['view', ['codeview']]
            ],
        });
    }

    $('#edit_comment_page select[name="reply_to"]').bind('change', function() {
        var reply_to = $(this).val();

        if (reply_to == '0') {
            $('select[name="is_sticky"]').attr('disabled', false);
        } else {
            $('select[name="is_sticky"]').val('0');

            $('select[name="is_sticky"]').attr('disabled', true);
        }
    });

    $('#edit_comment_page select[name="is_approved"]').bind('change', function() {
        var is_approved = $(this).val();

        if (is_approved == '1') {
            $('input[name="send"]').prop('checked', true);

            $('input[name="verify"]').prop('checked', true);
        } else {
            $('input[name="send"]').prop('checked', false);

            $('input[name="verify"]').prop('checked', false);
        }
    });

    $('#edit_comment_page input[name="spam"]').click(function() {
        location = $('input[data-js="link_spam"]').val();
    });

    $('#edit_comment_page select[name="page_id"]').bind('change', function() {
        var data = 'id=' + encodeURIComponent($('input[data-js="id"]').val()) + '&page_id=' + encodeURIComponent($('select[name="page_id"]').val());

        var request = $.ajax({
            type: 'POST',
            cache: false,
            url: 'index.php?route=edit/comment/getReplies',
            data: data,
            dataType: 'json',
            beforeSend: function() {
                $('select[name="page_id"]').after('<img src="' + js_settings.loading + '" class="loading" class="loading">');
            }
        });

        request.always(function() {
            setTimeout(function() {
                $('.loading').remove();
            }, 500);
        });

        request.done(function(response) {
            replies = response;

            html = '<option value="0">' + js_settings.lang_text_nobody + '</option>';

            for (i = 0; i < replies.length; i++) {
                html += '<option value="' + replies[i]['id'] + '"';

                if (replies[i]['id'] == $('input[data-js="reply_to"]').val()) {
                    html += ' selected';
                }

                html += '>' + replies[i]['name'] + ' - ' + replies[i]['date_added'] + '</option>';
            }

            $('select[name="reply_to"]').html(html);

            $('select[name="reply_to"]').trigger('change');
        });

        request.fail(function(jqXHR, textStatus, errorThrown) {
            if (console && console.log) {
                console.log(jqXHR.responseText);
            }
        });
    });

    $('#edit_comment_page select[name="page_id"]').trigger('change');

    $('#edit_comment_page select[name="country_id"]').bind('change', function() {
        var country_id = $('select[name="country_id"]').val();

        /* For cases where there's a state but no country */
        if (!country_id) {
            country_id = $('input[data-js="default_country"]').val();
        }

        var data = 'country_id=' + encodeURIComponent(country_id);

        var request = $.ajax({
            type: 'POST',
            cache: false,
            url: 'index.php?route=edit/comment/getStates',
            data: data,
            dataType: 'json',
            beforeSend: function() {
                $('select[name="country_id"]').after('<img src="' + js_settings.loading + '" class="loading">');
            }
        });

        request.always(function() {
            setTimeout(function() {
                $('.loading').remove();
            }, 500);
        });

        request.done(function(response) {
            states = response;

            html = '<option value="">' + js_settings.lang_select_select + '</option>';

            for (i = 0; i < states.length; i++) {
                html += '<option value="' + states[i]['id'] + '"';

                if (states[i]['id'] == $('input[data-js="state_id"]').val()) {
                    html += ' selected';
                }

                html += '>' + states[i]['name'] + '</option>';
            }

            $('select[name="state_id"]').html(html);

            $('select[name="state_id"]').trigger('change');
        });

        request.fail(function(jqXHR, textStatus, errorThrown) {
            if (console && console.log) {
                console.log(jqXHR.responseText);
            }
        });
    });

    $('#edit_comment_page select[name="country_id"]').trigger('change');

    if ($('#edit_comment_page').length) {
        var readURL = function(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    var content = '';

                    content += '<section class="upload_section">';
                    content += '    <img src="' + e.target.result + '" class="upload_image">';
                    content += '    <span class="upload_remove"><a data-upload-id="">' + $('.lang_link_remove').text() + '</a></span>';
                    content += '</section>';

                    $('.upload_msg_hasnt').after(content + ' ');
                }

                reader.readAsDataURL(input.files[0]);

                $('.upload_msg_has').show();
                $('.upload_msg_hasnt').hide();
            }
        }
    }

    $('body').on('change', '#edit_comment_page .upload_image_input', function() {
        readURL(this);
    });

    var upload_add_count = 0;

    $('#edit_comment_page .upload_add').click(function(e) {
        e.preventDefault();

        /* Tidy up any unused file inputs */
        $('.upload_image_input').each(function() {
            if ($(this).val() == '') {
                $(this).remove();
            }
        });

        $('.upload_msg_hasnt').after('<input name="upload_add_' + upload_add_count + '" hidden class="upload_image_input" type="file" accept=".png,.jpg,.jpeg,.gif">');

        upload_add_count++;

        $('.upload_image_input:first').click();
    });

    $('body').on('click', '#edit_comment_page .upload_remove a', function(e) {
        e.preventDefault();

        var upload_id = $(this).attr('data-upload-id');

        if (upload_id) {
            $('#edit_comment_page form').append('<input type="hidden" name="upload_remove[]" value="' + upload_id + '">');
        }

        $(this).closest('.upload_section').fadeOut(500, function() {
            if (!upload_id) {
                $(this).closest('.upload_section').next('.upload_image_input').remove();
            }

            $(this).closest('.upload_section').remove();

            if (!$('.upload_section').length) {
                $('.upload_msg_has').hide();
                $('.upload_msg_hasnt').show();
            }
        });
    });

    if ($('#edit_comment_page a.gallery').length) {
        $('#edit_comment_page a.gallery').colorbox({
            maxWidth: '80%',
            maxHeight: '50%',
            rel: 'gallery'
        })
    }

    /* Edit Spam */

    $('#edit_spam_page .button').click(function(e) {
        e.preventDefault();

        $('#spam_dialog').dialog({
            modal: true,
            height: 'auto',
            width: 'auto',
            resizable: false,
            draggable: false,
            center: true,
            buttons: {
                'Yes': function() {
                    $('form').submit();

                    $(this).dialog('close');
                },
                'No': function() {
                    $(this).dialog('close');
                }
            }
        });

        translate_buttons();

        $('#spam_dialog').dialog('open');
    });

    /* Settings Cache */

    $('#settings_cache_page select[name="cache_type"]').on('change', function() {
        var cache_type = $(this).val();

        if (cache_type == 'memcached') {
            $('input[name="cache_port"]').val('11211');
        }

        if (cache_type == 'redis') {
            $('input[name="cache_port"]').val('6379');
        }

        if (cache_type == 'memcached' || cache_type == 'redis') {
            $('.extra_section').show();
        } else {
            $('.extra_section').hide();
        }
    });

    $('#settings_cache_page select[name="cache_type"]').trigger('change');

    /* Settings Email Editor */

    $('#settings_email_editor_page .selection select').on('change', function() {
        var url = $(this).val();

        if (!url.match(/type=admin$/) && !url.match(/type=user$/)) {
            window.location.href = url;
        }
    });

    /* Settings Email Setup */

    $('#settings_email_setup_page select[name="transport_method"]').on('change', function() {
        var transport_method = $(this).val();

        if (transport_method == 'smtp') {
            $('.smtp_section').show();
        } else {
            $('.smtp_section').hide();
        }
    });

    $('#settings_email_setup_page select[name="transport_method"]').trigger('change');

    /* Settings Layout Comments */

    if ($('#settings_layout_comments_page #layout_settings_dialog').length) {
        $('#settings_layout_comments_page #layout_settings_dialog').dialog({
            modal: true,
            height: 'auto',
            width: 'auto',
            resizable: false,
            draggable: false,
            center: true,
            buttons: {
                'Stop': function() {
                    $.ajax({
                        url: 'index.php?route=settings/layout_comments/stopLayoutDetect',
                    })

                    $(this).dialog('close');
                },
                'Close': function() {
                    $(this).dialog('close');
                }
            }
        });

        translate_buttons();

        $('#layout_settings_dialog').dialog('open');
    }

    $('#settings_layout_comments_page select[name="pagination_type"]').on('change', function() {
        var pagination_type = $(this).val();

        if (pagination_type == 'multiple') {
            $('.range_section').show();
        } else {
            $('.range_section').hide();
        }
    });

    $('#settings_layout_comments_page select[name="pagination_type"]').trigger('change');

    $('#settings_layout_comments_page select[name="avatar_type"]').on('change', function() {
        var avatar_type = $(this).val();

        if (avatar_type == '' || avatar_type == 'login') {
            $('.avatar_gravatar_section').hide();
            $('.avatar_selection_section').hide();
            $('.avatar_upload_section').hide();
        } else if (avatar_type == 'gravatar') {
            $('.avatar_gravatar_section').show();
            $('.avatar_selection_section').hide();
            $('.avatar_upload_section').hide();
            if ($('select[name="gravatar_default"]').val() != 'custom') {
                $('.gravatar_custom_section').hide();
            }
        } else if (avatar_type == 'selection') {
            $('.avatar_gravatar_section').hide();
            $('.avatar_selection_section').show();
            $('.avatar_upload_section').hide();
        } else if (avatar_type == 'upload') {
            $('.avatar_gravatar_section').hide();
            $('.avatar_selection_section').hide();
            $('.avatar_upload_section').show();
        }
    });

    $('#settings_layout_comments_page select[name="avatar_type"]').trigger('change');

    $('#settings_layout_comments_page select[name="gravatar_default"]').on('change', function() {
        var gravatar_default = $(this).val();

        if (gravatar_default == 'custom') {
            $('.gravatar_custom_section').show();
        } else {
            $('.gravatar_custom_section').hide();
        }
    });

    $('#settings_layout_comments_page select[name="gravatar_default"]').trigger('change');

    /* Settings Layout Form */

    if ($('#settings_layout_form_page #layout_settings_dialog').length) {
        $('#settings_layout_form_page #layout_settings_dialog').dialog({
            modal: true,
            height: 'auto',
            width: 'auto',
            resizable: false,
            draggable: false,
            center: true,
            buttons: {
                'Stop': function() {
                    $.ajax({
                        url: 'index.php?route=settings/layout_form/stopLayoutDetect',
                    })

                    $(this).dialog('close');
                },
                'Close': function() {
                    $(this).dialog('close');
                }
            }
        });

        translate_buttons();

        $('#layout_settings_dialog').dialog('open');
    }

    $('#settings_layout_form_page select[name="default_country"]').bind('change', function() {
        var data = 'country_id=' + encodeURIComponent($('select[name="default_country"]').val());

        var request = $.ajax({
            type: 'POST',
            cache: false,
            url: 'index.php?route=edit/comment/getStates',
            data: data,
            dataType: 'json',
            beforeSend: function() {
                $('select[name="default_country"]').after('<img src="' + js_settings.loading + '" class="loading">');
            }
        });

        request.always(function() {
            setTimeout(function() {
                $('.loading').remove();
            }, 500);
        });

        request.done(function(response) {
            states = response;

            html = '<option value="">' + js_settings.lang_select_select + '</option>';

            for (i = 0; i < states.length; i++) {
                html += '<option value="' + states[i]['id'] + '"';

                if (states[i]['id'] == $('input[data-js="default_state"]').val()) {
                    html += ' selected';
                }

                html += '>' + states[i]['name'] + '</option>';
            }

            $('select[name="default_state"]').html(html);

            $('select[name="default_state"]').trigger('change');
        });

        request.fail(function(jqXHR, textStatus, errorThrown) {
            if (console && console.log) {
                console.log(jqXHR.responseText);
            }
        });
    });

    $('#settings_layout_form_page select[name="default_country"]').trigger('change');

    $('#settings_layout_form_page select[name="captcha_type"]').on('change', function() {
        var type = $(this).val();

        if (type == 'recaptcha') {
            $('.recaptcha_section').show();
            $('.captcha_section').hide();
        } else {
            $('.recaptcha_section').hide();
            $('.captcha_section').show();
        }
    });

    $('#settings_layout_form_page select[name="captcha_type"]').trigger('change');

    $('#settings_layout_form_page #sortable').sortable({
        update: function() {
            var order = $(this).sortable('toArray', {attribute: 'data-id'}).toString();

            $('input[name="order_fields"]').val(order);
        }
    });

    /* Report Access */

    $('#report_access_page #filter').click(function() {
        var url = 'index.php?route=report/access';

        var filter_username = $('input[name="filter_username"]').val();

        if (filter_username) {
            url += '&filter_username=' + encodeURIComponent(filter_username);
        }

        var filter_ip_address = $('input[name="filter_ip_address"]').val();

        if (filter_ip_address) {
            url += '&filter_ip_address=' + encodeURIComponent(filter_ip_address);
        }

        var filter_page = $('input[name="filter_page"]').val();

        if (filter_page) {
            url += '&filter_page=' + encodeURIComponent(filter_page);
        }

        var filter_date = $('input[name="filter_date"]').val();

        if (filter_date) {
            url += '&filter_date=' + encodeURIComponent(filter_date);
        }

        location = url;
    });

    $('#report_access_page input[name=\'filter_username\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=report/access/autocomplete&filter_username=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.username,
                            value: item.username
                        }
                    }));
                }
            });
        }
    });

    $('#report_access_page input[name=\'filter_ip_address\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=report/access/autocomplete&filter_ip_address=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.ip_address,
                            value: item.ip_address
                        }
                    }));
                }
            });
        }
    });

    $('#report_access_page input[name=\'filter_page\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=report/access/autocomplete&filter_page=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.page,
                            value: item.page
                        }
                    }));
                }
            });
        }
    });

    /* Report Version */

    $('#report_version_page #filter').click(function() {
        var url = 'index.php?route=report/version';

        var filter_version = $('input[name="filter_version"]').val();

        if (filter_version) {
            url += '&filter_version=' + encodeURIComponent(filter_version);
        }

        var filter_type = $('select[name="filter_type"]').val();

        if (filter_type) {
            url += '&filter_type=' + encodeURIComponent(filter_type);
        }

        var filter_date = $('input[name="filter_date"]').val();

        if (filter_date) {
            url += '&filter_date=' + encodeURIComponent(filter_date);
        }

        location = url;
    });

    $('#report_version_page input[name=\'filter_version\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=report/version/autocomplete&filter_version=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.version,
                            value: item.version
                        }
                    }));
                }
            });
        }
    });

    /* Report Viewers */

    $('#report_viewers_page #filter').click(function() {
        var url = 'index.php?route=report/viewers';

        var filter_type = $('input[name="filter_type"]').val();

        if (filter_type) {
            url += '&filter_type=' + encodeURIComponent(filter_type);
        }

        var filter_ip_address = $('input[name="filter_ip_address"]').val();

        if (filter_ip_address) {
            url += '&filter_ip_address=' + encodeURIComponent(filter_ip_address);
        }

        var filter_page_reference = $('input[name="filter_page_reference"]').val();

        if (filter_page_reference) {
            url += '&filter_page_reference=' + encodeURIComponent(filter_page_reference);
        }

        var filter_page_url = $('input[name="filter_page_url"]').val();

        if (filter_page_url) {
            url += '&filter_page_url=' + encodeURIComponent(filter_page_url);
        }

        location = url;
    });

    $('#report_viewers_page input[name=\'filter_type\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=report/viewers/autocomplete&filter_type=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.type,
                            value: item.type
                        }
                    }));
                }
            });
        }
    });

    $('#report_viewers_page input[name=\'filter_ip_address\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=report/viewers/autocomplete&filter_ip_address=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.ip_address,
                            value: item.ip_address
                        }
                    }));
                }
            });
        }
    });

    $('#report_viewers_page input[name=\'filter_page_reference\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=report/viewers/autocomplete&filter_page_reference=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.page_reference,
                            value: item.page_reference
                        }
                    }));
                }
            });
        }
    });

    $('#report_viewers_page input[name=\'filter_page_url\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=report/viewers/autocomplete&filter_page_url=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.page_url,
                            value: item.page_url
                        }
                    }));
                }
            });
        }
    });

    $('#report_viewers_page #refresh').click(function() {
        window.location.reload();
    });

    /* Tool Database Backup */

    $('#tool_database_backup_page #filter').click(function() {
        var url = 'index.php?route=tool/database_backup';

        var filter_description = $('input[name="filter_description"]').val();

        if (filter_description) {
            url += '&filter_description=' + encodeURIComponent(filter_description);
        }

        var filter_filename = $('input[name="filter_filename"]').val();

        if (filter_filename) {
            url += '&filter_filename=' + encodeURIComponent(filter_filename);
        }

        var filter_date = $('input[name="filter_date"]').val();

        if (filter_date) {
            url += '&filter_date=' + encodeURIComponent(filter_date);
        }

        location = url;
    });

    $('#tool_database_backup_page input[name=\'filter_description\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=tool/database_backup/autocomplete&filter_description=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.description,
                            value: item.description
                        }
                    }));
                }
            });
        }
    });

    $('#tool_database_backup_page input[name=\'filter_filename\']').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                cache: false,
                url: 'index.php?route=tool/database_backup/autocomplete&filter_filename=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.filename,
                            value: item.filename
                        }
                    }));
                }
            });
        }
    });

    /* Data List */

    $('#data_list_page .description a').click(function(e) {
        e.preventDefault();

        if ($('.guide').is(':hidden')) {
            $('.guide').slideDown('slow', function() {} );

            $('.description a').text(js_settings.lang_link_less);
        } else {
            $('.guide').slideUp('slow', function() {} );

            $('.description a').text(js_settings.lang_link_more);
        }

        return false;
    });

    $('#data_list_page .selection select').on('change', function() {
        window.location.href = $(this).val();
    });

    /* Extensions Modules */

    $('#extension_modules_page .install').click(function(e) {
        e.preventDefault();

        var id = $(this).data('id');

        var input = $('<input>').attr('type', 'hidden').attr('name', 'module').val(id);

        $('form').append($(input));

        $('form').attr('action', 'index.php?route=extension/modules/install');

        $('form').submit();
    });

    $('#extension_modules_page .uninstall').click(function(e) {
        e.preventDefault();

        var id = $(this).data('id');

        $('#uninstall_dialog').dialog({
            modal: true,
            height: 'auto',
            width: 'auto',
            resizable: false,
            draggable: false,
            center: true,
            buttons: {
                'Yes': function() {
                    var input = $('<input>').attr('type', 'hidden').attr('name', 'module').val(id);

                    $('form').append($(input));

                    $('form').attr('action', 'index.php?route=extension/modules/uninstall');

                    $('form').submit();

                    $(this).dialog('close');
                },
                'No': function() {
                    $(this).dialog('close');
                }
            }
        });

        translate_buttons();

        $('#uninstall_dialog').dialog('open');
    });

    /* Extra Fields module */

    $('#module_extra_fields_form_page .select_section').hide();
    $('#module_extra_fields_form_page .text_section').hide();

    $('#module_extra_fields_form_page select[name="type"]').on('change', function() {
        var type = $(this).val();

        if (type == 'select') {
            $('.select_section').show();
            $('.text_section').hide();
        } else {
            $('.select_section').hide();
            $('.text_section').show();
        }
    });

    $('#module_extra_fields_form_page select[name="type"]').trigger('change');

    /* Language Editor module */

    $('#module_language_editor_page .text_value').dblclick(function(e) {
        if ($(this).find('input').length == 0) {
            var text = $(this).text();

            text = text.replace(/"/g, '&quot;');
            text = text.replace(/&lt;/g, '&amp;lt;');
            text = text.replace(/&gt;/g, '&amp;gt;');

            var key = $(this).prev().text();

            $(this).html('<input name="' + key + '" type="text" value="' + text + '">');
        }
    });

    /* Rich Snippets module */

    $('#module_rich_snippets_page select[name="rich_snippets_type"]').on('change', function() {
        var rich_snippets_type = $(this).val();

        if (rich_snippets_type == 'other') {
            $('.other_section').show();
        } else {
            $('.other_section').hide();
        }
    });

    $('#module_rich_snippets_page select[name="rich_snippets_type"]').trigger('change');

    var property_row = $('#module_rich_snippets_page div[id^="property-row"]').length;

    $('#module_rich_snippets_page #add_property').click(function(e) {
        e.preventDefault();

        html =  '<div id="property-row' + property_row + '" class="fieldset">';
        html += '  <label>' + js_settings.lang_entry_property + '</label>';
        html += '  <input type="text" name="rich_snippets_property[' + property_row + '][name]" class="medium_plus" value="" placeholder="' + js_settings.lang_placeholder_name + '" maxlength="255">';
        html += '  <input type="text" name="rich_snippets_property[' + property_row + '][value]" class="medium_plus" value="" placeholder="' + js_settings.lang_placeholder_value + '" maxlength="255">';
        html += '  <a>' + js_settings.lang_link_remove + '</a>';
        html += '</div>';

        $('input[name="csrf_key"]').before(html);

        property_row++;
    });

    $('body').on('click', '#module_rich_snippets_page div[id^="property-row"] a', function() {
        $(this).closest('div').remove();
    });

    /* Extensions Themes */

    $('#extension_themes_page select[name="theme_frontend"]').change(function() {
        var theme = $('select[name="theme_frontend"]').val();

        var request = $.ajax({
            type: 'POST',
            cache: false,
            url: 'index.php?route=extension/themes/previewFrontend',
            data: 'theme=' + encodeURIComponent(theme),
            dataType: 'json',
            beforeSend: function() {
                $('select[name="theme_frontend"]').after('<span class="fa fa-circle-o-notch fa-spin"></span>');
            }
        });

        request.always(function() {
            $('.fa-spin').remove();
        });

        request.done(function(response) {
            $('#theme-preview-frontend').attr('src', response['preview']);

            $('#theme-preview-frontend').parent().attr('href', response['preview']);
        });

        request.fail(function(jqXHR, textStatus, errorThrown) {
            if (console && console.log) {
                console.log(jqXHR.responseText);
            }
        });
    });

    $('select[name="theme_frontend"]').trigger('change');

    $('#extension_themes_page select[name="theme_backend"]').change(function() {
        var theme = $('select[name="theme_backend"]').val();

        var request = $.ajax({
            type: 'POST',
            cache: false,
            url: 'index.php?route=extension/themes/previewBackend',
            data: 'theme=' + encodeURIComponent(theme),
            dataType: 'json',
            beforeSend: function() {
                $('select[name="theme_backend"]').after('<span class="fa fa-circle-o-notch fa-spin"></span>');
            }
        });

        request.always(function() {
            $('.fa-spin').remove();
        });

        request.done(function(response) {
            $('#theme-preview-backend').attr('src', response['preview']);

            $('#theme-preview-backend').parent().attr('href', response['preview']);
        });

        request.fail(function(jqXHR, textStatus, errorThrown) {
            if (console && console.log) {
                console.log(jqXHR.responseText);
            }
        });
    });

    $('select[name="theme_backend"]').trigger('change');

    if ($('#extension_themes_page a.gallery').length) {
        $('#extension_themes_page a.gallery').colorbox({
            maxHeight: '70%',
        })
    }

    $('#extension_themes_page #sortable').sortable({
        update: function() {
            var order = $(this).sortable('toArray', {attribute: 'data-id'}).toString();

            $('input[name="order_parts"]').val(order);
        }
    });

    /* Tool Upgrade */

    if ($('#tool_upgrade_page #start-upgrade').length) {
        var csrf_key = $('input[name="csrf_key"]').val();

        cmtx_start_upgrade(csrf_key);
    }

    /* Back Button */

    $('#edit_spam_page #back, #data_list_page #back').click(function(e) {
        e.preventDefault();

        window.history.back();
    });
});

function translate_buttons() {
    $('.ui-button:contains("Yes")').text(js_settings.lang_text_yes);
    $('.ui-button:contains("No")').text(js_settings.lang_text_no);
    $('.ui-button:contains("Stop")').text(js_settings.lang_dialog_stop);
    $('.ui-button:contains("Close")').text(js_settings.lang_dialog_close);
}

/* Upgrade */
function cmtx_start_upgrade(csrf_key) {
    var request = $.ajax({
        type: 'POST',
        cache: false,
        url: 'index.php?route=tool/upgrade/download',
        data: 'csrf_key=' + csrf_key,
        dataType: 'json'
    });

    request.done(function(response) {
        $.each(response['messages'], function(index, value) {
            $('#upgrade-progress').append('<p>' + value + '</p>');
        });

        if (response['error']) {
            $('#upgrade-progress').append('<p class="negative">' + response['error'] + '</p>');
        } else {
            var request = $.ajax({
                type: 'POST',
                cache: false,
                url: 'index.php?route=tool/upgrade/unpack',
                data: 'csrf_key=' + csrf_key,
                dataType: 'json'
            });

            request.done(function(response) {
                $.each(response['messages'], function(index, value) {
                    $('#upgrade-progress').append('<p>' + value + '</p>');
                });

                if (response['error']) {
                    $('#upgrade-progress').append('<p class="negative">' + response['error'] + '</p>');
                } else {
                    var request = $.ajax({
                        type: 'POST',
                        cache: false,
                        url: 'index.php?route=tool/upgrade/verify',
                        data: 'csrf_key=' + csrf_key,
                        dataType: 'json'
                    });

                    request.done(function(response) {
                        $.each(response['messages'], function(index, value) {
                            $('#upgrade-progress').append('<p>' + value + '</p>');
                        });

                        if (response['error']) {
                            $('#upgrade-progress').append('<p class="negative">' + response['error'] + '</p>');
                        } else {
                            var request = $.ajax({
                                type: 'POST',
                                cache: false,
                                url: 'index.php?route=tool/upgrade/requirements',
                                data: 'csrf_key=' + csrf_key,
                                dataType: 'json'
                            });

                            request.done(function(response) {
                                $.each(response['messages'], function(index, value) {
                                    $('#upgrade-progress').append('<p>' + value + '</p>');
                                });

                                if (response['error']) {
                                    $('#upgrade-progress').append('<p class="negative">' + response['error'] + '</p>');
                                } else {
                                    var request = $.ajax({
                                        type: 'POST',
                                        cache: false,
                                        url: 'index.php?route=tool/upgrade/install',
                                        data: 'csrf_key=' + csrf_key,
                                        dataType: 'json'
                                    });

                                    request.done(function(response) {
                                        $.each(response['messages'], function(index, value) {
                                            $('#upgrade-progress').append('<p>' + value + '</p>');
                                        });

                                        if (response['error']) {
                                            $('#upgrade-progress').append('<p class="negative">' + response['error'] + '</p>');
                                        } else {
                                            var request = $.ajax({
                                                type: 'POST',
                                                cache: false,
                                                url: 'index.php?route=tool/upgrade/database',
                                                data: 'csrf_key=' + csrf_key,
                                                dataType: 'json'
                                            });

                                            request.done(function(response) {
                                                $.each(response['messages'], function(index, value) {
                                                    $('#upgrade-progress').append('<p>' + value + '</p>');
                                                });

                                                if (response['error']) {
                                                    $('#upgrade-progress').append('<p class="negative">' + response['error'] + '</p>');
                                                } else {
                                                    var request = $.ajax({
                                                        type: 'POST',
                                                        cache: false,
                                                        url: 'index.php?route=tool/upgrade/clean',
                                                        data: 'csrf_key=' + csrf_key,
                                                        dataType: 'json'
                                                    });

                                                    request.done(function(response) {
                                                        $.each(response['messages'], function(index, value) {
                                                            $('#upgrade-progress').append('<p>' + value + '</p>');
                                                        });

                                                        if (response['error']) {
                                                            $('#upgrade-progress').append('<p class="negative">' + response['error'] + '</p>');
                                                        }

                                                        if (response['success']) {
                                                            $('#upgrade-progress').append('<p class="positive">' + response['success'] + '</p>');
                                                        }
                                                    });
                                                }
                                            });
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            });
        }
    });
}