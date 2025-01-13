$(document).ready(function() {
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

    if ($('#cmtx_js_settings_install_1').length) {
        cmtx_js_settings_install_1 = JSON.parse($('#cmtx_js_settings_install_1').text());

        $('#install_1_page .form').submit(function(e) {
            $('.field_error').remove();

            if ($('.password_1').val().length < 5) {
                $('.password_1').next().after('<div class="field_error">' + cmtx_js_settings_install_1.lang_error_password_length + '</div>');
            } else if ($('.password_1').val() != $('.password_2').val()) {
                $('.password_1').next().after('<div class="field_error">' + cmtx_js_settings_install_1.lang_error_password_mismatch + '</div>');
                $('.password_2').next().after('<div class="field_error">' + cmtx_js_settings_install_1.lang_error_password_mismatch + '</div>');
            }

            if ($('.site_name').val() == 'My Site') {
                $('.site_name').next().after('<div class="field_error">' + cmtx_js_settings_install_1.lang_error_site_name + '</div>');
            }

            if ($('.field_error').length) {
                e.preventDefault();
            } else {
                $('.form').submit();
            }
        });
    }
});