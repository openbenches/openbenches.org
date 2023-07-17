<?php
namespace Commentics;

class MainFormController extends Controller
{
    public function index()
    {
        $this->loadLanguage('main/form');

        $this->loadModel('main/form');

        if ($this->setting->get('enabled_form') && $this->page->isFormEnabled()) {
            $this->data['display_form'] = true;

            if (defined('CMTX_LOGGED_IN') && !CMTX_LOGGED_IN) {
                $this->data['display_form'] = false;

                $this->data['lang_error_form_disabled'] = $this->data['lang_error_logged_in'];
            }
        } else {
            $this->data['display_form'] = false;
        }

        if ($this->data['display_form']) {
            $ip_address = $this->user->getIpAddress();

            $this->data['commentics_url'] = $this->url->getCommenticsUrl();

            $hidden_data = '';

            $this->data['display_javascript_disabled'] = $this->setting->get('display_javascript_disabled');

            $this->data['display_required_text'] = $this->setting->get('display_required_text');

            $this->data['display_required_symbol'] = $this->setting->get('display_required_symbol');

            $cookie = array();

            $cookie['name'] = $cookie['email'] = $cookie['website'] = $cookie['town'] = $cookie['country'] = $cookie['state'] = '';

            if ($this->cookie->exists('Commentics-Form')) {
                $values = $this->cookie->get('Commentics-Form');

                $values = explode('|', $values);

                if (count($values) == 6) {
                    $cookie['name']    = $values[0];
                    $cookie['email']   = $values[1];
                    $cookie['website'] = $values[2];
                    $cookie['town']    = $values[3];
                    $cookie['country'] = $values[4];
                    $cookie['state']   = $values[5];
                }
            }

            /* BB Code */

            $this->data['enabled_bb_code'] = $this->setting->get('enabled_bb_code');

            if ($this->data['enabled_bb_code']) {
                $this->data['enabled_bb_code_bold']        = $this->setting->get('enabled_bb_code_bold');
                $this->data['enabled_bb_code_italic']      = $this->setting->get('enabled_bb_code_italic');
                $this->data['enabled_bb_code_underline']   = $this->setting->get('enabled_bb_code_underline');
                $this->data['enabled_bb_code_strike']      = $this->setting->get('enabled_bb_code_strike');
                $this->data['enabled_bb_code_superscript'] = $this->setting->get('enabled_bb_code_superscript');
                $this->data['enabled_bb_code_subscript']   = $this->setting->get('enabled_bb_code_subscript');
                $this->data['enabled_bb_code_code']        = $this->setting->get('enabled_bb_code_code');
                $this->data['enabled_bb_code_php']         = $this->setting->get('enabled_bb_code_php');
                $this->data['enabled_bb_code_quote']       = $this->setting->get('enabled_bb_code_quote');
                $this->data['enabled_bb_code_line']        = $this->setting->get('enabled_bb_code_line');
                $this->data['enabled_bb_code_bullet']      = $this->setting->get('enabled_bb_code_bullet');
                $this->data['enabled_bb_code_numeric']     = $this->setting->get('enabled_bb_code_numeric');
                $this->data['enabled_bb_code_link']        = $this->setting->get('enabled_bb_code_link');
                $this->data['enabled_bb_code_email']       = $this->setting->get('enabled_bb_code_email');
                $this->data['enabled_bb_code_image']       = $this->setting->get('enabled_bb_code_image');
                $this->data['enabled_bb_code_youtube']     = $this->setting->get('enabled_bb_code_youtube');
            } else {
                $this->data['enabled_bb_code_bullet']  = false;
                $this->data['enabled_bb_code_numeric'] = false;
                $this->data['enabled_bb_code_link']    = false;
                $this->data['enabled_bb_code_email']   = false;
                $this->data['enabled_bb_code_image']   = false;
                $this->data['enabled_bb_code_youtube'] = false;
            }

            /* Smilies */

            $this->data['enabled_smilies'] = $this->setting->get('enabled_smilies');

            if ($this->data['enabled_smilies']) {
                $this->data['enabled_smilies_smile']    = $this->setting->get('enabled_smilies_smile');
                $this->data['enabled_smilies_sad']      = $this->setting->get('enabled_smilies_sad');
                $this->data['enabled_smilies_huh']      = $this->setting->get('enabled_smilies_huh');
                $this->data['enabled_smilies_laugh']    = $this->setting->get('enabled_smilies_laugh');
                $this->data['enabled_smilies_mad']      = $this->setting->get('enabled_smilies_mad');
                $this->data['enabled_smilies_tongue']   = $this->setting->get('enabled_smilies_tongue');
                $this->data['enabled_smilies_cry']      = $this->setting->get('enabled_smilies_cry');
                $this->data['enabled_smilies_grin']     = $this->setting->get('enabled_smilies_grin');
                $this->data['enabled_smilies_wink']     = $this->setting->get('enabled_smilies_wink');
                $this->data['enabled_smilies_scared']   = $this->setting->get('enabled_smilies_scared');
                $this->data['enabled_smilies_cool']     = $this->setting->get('enabled_smilies_cool');
                $this->data['enabled_smilies_sleep']    = $this->setting->get('enabled_smilies_sleep');
                $this->data['enabled_smilies_blush']    = $this->setting->get('enabled_smilies_blush');
                $this->data['enabled_smilies_confused'] = $this->setting->get('enabled_smilies_confused');
                $this->data['enabled_smilies_shocked']  = $this->setting->get('enabled_smilies_shocked');
            }

            /* Comment */

            $this->data['comment'] = $this->setting->get('default_comment');

            $this->data['comment_symbol'] = ($this->setting->get('display_required_symbol') ? 'cmtx_required' : '');

            $this->data['comment_maximum_characters'] = $this->setting->get('comment_maximum_characters');

            $this->data['enabled_counter'] = $this->setting->get('enabled_counter');

            /* Headline */

            $this->data['enabled_headline'] = $this->setting->get('enabled_headline');

            $this->data['headline'] = $this->setting->get('default_headline');

            $this->data['headline_symbol'] = ($this->setting->get('display_required_symbol') && $this->setting->get('required_headline') ? 'cmtx_required' : '');

            $this->data['headline_maximum_characters'] = $this->setting->get('headline_maximum_characters');

            /* Upload */

            $this->data['enabled_upload'] = $this->setting->get('enabled_upload');

            /* Rating */

            if ($this->setting->get('repeat_rating') == 'hide' && $this->model_main_form->hasUserRated($this->page->getId(), $ip_address)) {
                $this->data['enabled_rating'] = false;
            } else {
                $this->data['enabled_rating'] = $this->setting->get('enabled_rating');
            }

            $this->data['rating_symbol'] = ($this->setting->get('display_required_symbol') && $this->setting->get('required_rating') ? 'cmtx_required' : '');

            $default_rating = $this->setting->get('default_rating');

            if ($default_rating == '1') {
                $this->data['rating_1_checked'] = 'checked';
            } else {
                $this->data['rating_1_checked'] = '';
            }

            if ($default_rating == '2') {
                $this->data['rating_2_checked'] = 'checked';
            } else {
                $this->data['rating_2_checked'] = '';
            }

            if ($default_rating == '3') {
                $this->data['rating_3_checked'] = 'checked';
            } else {
                $this->data['rating_3_checked'] = '';
            }

            if ($default_rating == '4') {
                $this->data['rating_4_checked'] = 'checked';
            } else {
                $this->data['rating_4_checked'] = '';
            }

            if ($default_rating == '5') {
                $this->data['rating_5_checked'] = 'checked';
            } else {
                $this->data['rating_5_checked'] = '';
            }

            /* Name */

            $this->data['enabled_name'] = true;

            $this->data['name_is_filled'] = false;

            $this->data['filled_name_action'] = 'normal';

            /* The precedence is login info, cookie and default */
            if ($this->user->getLogin('name')) {
                $this->data['name'] = $this->user->getLogin('name');

                $this->data['name_is_filled'] = true;

                $this->data['filled_name_action'] = $this->setting->get('filled_name_login_action');
            } else if ($cookie['name']) {
                $this->data['name'] = $cookie['name'];

                $this->data['name_is_filled'] = true;

                $this->data['filled_name_action'] = $this->setting->get('filled_name_cookie_action');
            } else {
                $this->data['name'] = $this->setting->get('default_name');
            }

            if ($this->data['name_is_filled'] && $this->data['filled_name_action'] == 'disable') {
                $this->data['name_readonly'] = 'readonly';
            } else {
                $this->data['name_readonly'] = '';
            }

            $this->data['name_symbol'] = ($this->setting->get('display_required_symbol') ? 'cmtx_required' : '');

            $this->data['maximum_name'] = $this->setting->get('maximum_name');

            /* Email */

            $this->data['enabled_email'] = $this->setting->get('enabled_email');

            $this->data['email_is_filled'] = false;

            $this->data['filled_email_action'] = 'normal';

            if ($this->user->getLogin('email')) {
                $this->data['email'] = $this->user->getLogin('email');

                $this->data['email_is_filled'] = true;

                $this->data['filled_email_action'] = $this->setting->get('filled_email_login_action');
            } else if ($cookie['email']) {
                $this->data['email'] = $cookie['email'];

                $this->data['email_is_filled'] = true;

                $this->data['filled_email_action'] = $this->setting->get('filled_email_cookie_action');
            } else {
                $this->data['email'] = $this->setting->get('default_email');
            }

            if ($this->data['email_is_filled'] && $this->data['filled_email_action'] == 'disable') {
                $this->data['email_readonly'] = 'readonly';
            } else {
                $this->data['email_readonly'] = '';
            }

            $this->data['email_symbol'] = ($this->setting->get('display_required_symbol') && $this->setting->get('required_email') ? 'cmtx_required' : '');

            $this->data['maximum_email'] = $this->setting->get('maximum_email');

            /* User */

            if ($this->data['name_is_filled'] && $this->data['filled_name_action'] == 'hide') {
                $this->data['enabled_name'] = false;

                $hidden_data .= '&cmtx_name=' . $this->url->encode($this->data['name']);
            }

            if ($this->data['email_is_filled'] && $this->data['filled_email_action'] == 'hide') {
                $this->data['enabled_email'] = false;

                $hidden_data .= '&cmtx_email=' . $this->url->encode($this->data['email']);
            }

            $user_columns = (int) $this->data['enabled_name'] + (int) $this->data['enabled_email'];

            if ($user_columns) {
                $user_row_visible = true;
                $this->data['user_row_visible'] = '';
            } else {
                $user_row_visible = false;
                $this->data['user_row_visible'] = 'cmtx_hide';
            }

            if ($user_columns == 2) {
                $this->data['name_spacing'] = 'cmtx_name_spacing';
            } else {
                $this->data['name_spacing'] = '';
            }

            $this->data['cmtx_wait_for_comment'] = '';
            $this->data['cmtx_wait_for_user'] = '';

            if ($this->setting->get('hide_form')) {
                $this->data['cmtx_wait_for_comment'] = 'cmtx_wait_for_comment';

                if ($user_row_visible) {
                    $this->data['cmtx_wait_for_user'] = 'cmtx_wait_for_user';
                }
            }

            /* We don't want the user to have to click into the name/email fields if they're already filled */

            if (($this->data['enabled_name'] && $this->data['name_is_filled']) && (!$this->data['enabled_email'] || $this->data['enabled_email'] && $this->data['email_is_filled'])) {
                $this->data['cmtx_wait_for_user'] = '';
            }

            if ((!$this->data['enabled_name']) && (!$this->data['enabled_email'] || $this->data['enabled_email'] && $this->data['email_is_filled'])) {
                $this->data['cmtx_wait_for_user'] = '';
            }

            switch ($user_columns) {
                case '1':
                    $this->data['user_column_size'] = '12';
                    break;
                case '2':
                    $this->data['user_column_size'] = '6';
                    break;
                default:
                    $this->data['user_column_size'] = '6';
            }

            /* Website */

            $this->data['enabled_website'] = $this->setting->get('enabled_website');

            $this->data['website_symbol'] = ($this->setting->get('display_required_symbol') && $this->setting->get('required_website') ? 'cmtx_required' : '');

            $this->data['website_is_filled'] = false;

            $this->data['filled_website_action'] = 'normal';

            if ($this->user->getLogin('website')) {
                $this->data['website'] = $this->user->getLogin('website');

                $this->data['website_is_filled'] = true;

                $this->data['filled_website_action'] = $this->setting->get('filled_website_login_action');
            } else if ($cookie['website']) {
                $this->data['website'] = $cookie['website'];

                $this->data['website_is_filled'] = true;

                $this->data['filled_website_action'] = $this->setting->get('filled_website_cookie_action');
            } else {
                $this->data['website'] = $this->setting->get('default_website');
            }

            if ($this->data['website_is_filled'] && $this->data['filled_website_action'] == 'disable') {
                $this->data['website_readonly'] = 'readonly';
            } else {
                $this->data['website_readonly'] = '';
            }

            $this->data['maximum_website'] = $this->setting->get('maximum_website');

            if ($this->data['website_is_filled'] && $this->data['filled_website_action'] == 'hide') {
                $this->data['enabled_website'] = false;

                $hidden_data .= '&cmtx_website=' . $this->url->encode($this->data['website']);
            }

            /* Town */

            $this->data['enabled_town'] = $this->setting->get('enabled_town');

            $this->data['town_symbol'] = ($this->setting->get('display_required_symbol') && $this->setting->get('required_town') ? 'cmtx_required' : '');

            $this->data['town_is_filled'] = false;

            $this->data['filled_town_action'] = 'normal';

            if ($this->user->getLogin('town')) {
                $this->data['town'] = $this->user->getLogin('town');

                $this->data['town_is_filled'] = true;

                $this->data['filled_town_action'] = $this->setting->get('filled_town_login_action');
            } else if ($cookie['town']) {
                $this->data['town'] = $cookie['town'];

                $this->data['town_is_filled'] = true;

                $this->data['filled_town_action'] = $this->setting->get('filled_town_cookie_action');
            } else {
                $this->data['town'] = $this->setting->get('default_town');
            }

            if ($this->data['town_is_filled'] && $this->data['filled_town_action'] == 'disable') {
                $this->data['town_readonly'] = 'readonly';
            } else {
                $this->data['town_readonly'] = '';
            }

            $this->data['maximum_town'] = $this->setting->get('maximum_town');

            /* Country */

            $this->data['enabled_country'] = $this->setting->get('enabled_country');

            $this->data['country_symbol'] = ($this->setting->get('display_required_symbol') && $this->setting->get('required_country') ? 'cmtx_required' : '');

            $this->data['countries'] = array();

            $this->data['country_is_filled'] = false;

            $this->data['filled_country_action'] = 'normal';

            if ($this->user->getLogin('country')) {
                $this->data['country_id'] = $this->user->getLogin('country');

                $this->data['country_is_filled'] = true;

                $this->data['filled_country_action'] = $this->setting->get('filled_country_login_action');
            } else if ($cookie['country']) {
                $this->data['country_id'] = $cookie['country'];

                $this->data['country_is_filled'] = true;

                $this->data['filled_country_action'] = $this->setting->get('filled_country_cookie_action');
            } else {
                $this->data['country_id'] = $this->setting->get('default_country');
            }

            if ($this->data['country_is_filled'] && $this->data['filled_country_action'] == 'disable') {
                $this->data['country_disabled'] = 'disabled';
            } else {
                $this->data['country_disabled'] = '';
            }

            /* State */

            $this->data['enabled_state'] = $this->setting->get('enabled_state');

            $this->data['state_symbol'] = ($this->setting->get('display_required_symbol') && $this->setting->get('required_state') ? 'cmtx_required' : '');

            $this->data['states'] = array();

            $this->data['state_is_filled'] = false;

            $this->data['filled_state_action'] = 'normal';

            if ($this->user->getLogin('state')) {
                $this->data['state_id'] = $this->user->getLogin('state');

                $this->data['state_is_filled'] = true;

                $this->data['filled_state_action'] = $this->setting->get('filled_state_login_action');
            } else if ($cookie['state']) {
                $this->data['state_id'] = $cookie['state'];

                $this->data['state_is_filled'] = true;

                $this->data['filled_state_action'] = $this->setting->get('filled_state_cookie_action');
            } else {
                $this->data['state_id'] = $this->setting->get('default_state');
            }

            if ($this->data['state_is_filled'] && $this->data['filled_state_action'] == 'disable') {
                $this->data['state_disabled'] = 'disabled';
            } else {
                $this->data['state_disabled'] = '';
            }

            /* Question */

            $this->data['question'] = false;

            $this->data['answer_symbol'] = ($this->setting->get('display_required_symbol') ? 'cmtx_required' : '');

            if ($this->setting->get('enabled_question')) {
                $question = $this->model_main_form->getQuestion();

                if ($question) {
                    $this->session->data['cmtx_question_id_' . $this->page->getId()] = $question['id'];

                    $this->data['question'] = $question['question'];
                }
            }

            /* Extra fields */

            $this->data['fields'] = array();

            $fields = explode(',', $this->setting->get('order_fields'));

            foreach ($fields as $field) {
                /* Try to extract ID portion in case it's an extra field (such as field_1, field_2 etc) */
                $field_id = $this->variable->substr($field, 6, strlen($field));

                /* If we found an ID and it's an int then it's an extra field and we need to add more info to it */
                if ($field_id && $this->validation->isInt($field_id)) {
                    $field_info = $this->model_main_form->getExtraField($field_id);

                    if ($field_info) {
                        $field_info['template'] = 'extra';
                        $field_info['values'] = explode(',', $field_info['values']);
                        $field_info['symbol'] = ($this->setting->get('display_required_symbol') && $field_info['is_required'] ? 'cmtx_required' : '');

                        $this->data['fields'][$field] = $field_info;
                    }
                } else {
                    $this->data['fields'][$field] = array('template' => $field);
                }
            }

            /* ReCaptcha */

            $this->data['recaptcha'] = false;

            if ($this->setting->get('enabled_captcha') && $this->setting->get('captcha_type') == 'recaptcha' && (bool) ini_get('allow_url_fopen')) {
                $this->data['recaptcha'] = true;

                $this->data['recaptcha_public_key'] = $this->setting->get('recaptcha_public_key');

                $this->data['recaptcha_theme'] = $this->setting->get('recaptcha_theme');

                $this->data['recaptcha_size'] = $this->setting->get('recaptcha_size');
            }

            /* Captcha */

            $this->data['captcha'] = false;

            $this->data['captcha_url'] = $this->setting->get('commentics_url') . 'frontend/index.php?route=main/form/captcha&page_id=' . $this->page->getId();

            if ($this->setting->get('enabled_captcha') && $this->setting->get('captcha_type') == 'image' && extension_loaded('gd') && function_exists('imagettftext') && is_callable('imagettftext')) {
                $this->data['captcha'] = true;

                $this->data['maximum_captcha'] = $this->setting->get('captcha_length');
            }

            /* Notify */

            $this->data['enabled_notify'] = $this->setting->get('enabled_notify');

            $default_notify = $this->setting->get('default_notify');

            if ($default_notify) {
                $this->data['notify_checked'] = 'checked';
            } else {
                $this->data['notify_checked'] = '';
            }

            /* Cookie */

            $this->data['enabled_cookie'] = $this->setting->get('enabled_cookie');

            $default_cookie = $this->setting->get('default_cookie');

            if ($default_cookie) {
                $this->data['cookie_checked'] = 'checked';
            } else {
                $this->data['cookie_checked'] = '';
            }

            /* Privacy */

            $this->data['enabled_privacy'] = $this->setting->get('enabled_privacy');

            /* Terms */

            $this->data['enabled_terms'] = $this->setting->get('enabled_terms');

            /* Preview */

            $this->data['enabled_preview'] = $this->setting->get('enabled_preview');

            /* Powered By */

            $this->data['enabled_powered_by'] = $this->setting->get('enabled_powered_by');

            if ($this->data['enabled_powered_by']) {
                if ($this->setting->get('powered_by_type') == 'text') {
                    $this->data['powered_by'] = sprintf($this->data['lang_text_powered_by'], 'https://commentics.com', $this->setting->get('powered_by_new_window') ? 'target="_blank"' : '');
                } else {
                    $this->data['powered_by'] = '<a href="https://commentics.com" title="Commentics" ' . ($this->setting->get('powered_by_new_window') ? 'target="_blank"' : '') . '><img src="' . $this->loadImage('commentics/powered_by.png') . '"></a>';
                }
            }

            /* Misc */

            /* Maintenance mode */
            if ($this->setting->get('maintenance_mode')) {
                $this->data['maintenance_mode_admin'] = true;
            } else {
                $this->data['maintenance_mode_admin'] = false;
            }

            /* Is this an administrator? */
            $is_admin = $this->user->isAdmin();

            if ($is_admin) {
                $this->data['cmtx_admin_button'] = 'cmtx_admin_button';
            } else {
                $this->data['cmtx_admin_button'] = '';
            }

            $this->data['quick_reply'] = $this->setting->get('quick_reply');

            $this->data['show_edit'] = $this->setting->get('show_edit');

            $this->data['page_id'] = $this->page->getId();

            $this->data['iframe'] = (int) $this->page->isIFrame();

            $this->data['time'] = time();

            /* Unset that the Captcha is complete */
            unset($this->session->data['cmtx_captcha_complete_' . $this->page->getId()]);

            if ($this->setting->get('enabled_town') && $this->data['town_is_filled'] && $this->data['filled_town_action'] == 'hide') {
                $this->data['enabled_town'] = false;

                $hidden_data .= '&cmtx_town=' . $this->url->encode($this->data['town']);
            }

            if ($this->setting->get('enabled_country') && $this->data['country_is_filled'] && $this->data['filled_country_action'] == 'hide') {
                $this->data['enabled_country'] = false;

                $hidden_data .= '&cmtx_country=' . $this->url->encode($this->data['country_id']);
            }

            if ($this->setting->get('enabled_state') && $this->data['state_is_filled'] && $this->data['filled_state_action'] == 'hide') {
                $this->data['enabled_state'] = false;

                $hidden_data .= '&cmtx_state=' . $this->url->encode($this->data['state_id']);
            }

            $geo_columns = (int) $this->data['enabled_town'] + (int) $this->data['enabled_country'] + (int) $this->data['enabled_state'];

            if ($geo_columns) {
                $geo_row_visible = true;
            } else {
                $geo_row_visible = false;
            }

            if (!$geo_row_visible) {
                $this->data['geo_row_visible'] = 'cmtx_hide';
            } else {
                $this->data['geo_row_visible'] = $this->data['cmtx_wait_for_user'];
            }

            switch ($geo_columns) {
                case '1':
                    $this->data['geo_column_size'] = '12';
                    break;
                case '2':
                    $this->data['geo_column_size'] = '6';
                    break;
                case '3':
                    $this->data['geo_column_size'] = '4';
                    break;
                default:
                    $this->data['geo_column_size'] = '4';
            }

            /* Avatar provided by login information */
            if ($this->user->getLogin('avatar')) {
                $hidden_data .= '&cmtx_avatar=' . $this->url->encode($this->user->getLogin('avatar'));
            }

            if ($this->user->getLogin('name') || $this->user->getLogin('email')) {
                $hidden_data .= '&cmtx_login=1';
            } else {
                $hidden_data .= '&cmtx_login=0';
            }

            $this->data['lang_tag_bb_code_bold']        = $this->data['lang_tag_bb_code_bold_start'] . '|' . $this->data['lang_tag_bb_code_bold_end'];
            $this->data['lang_tag_bb_code_italic']      = $this->data['lang_tag_bb_code_italic_start'] . '|' . $this->data['lang_tag_bb_code_italic_end'];
            $this->data['lang_tag_bb_code_underline']   = $this->data['lang_tag_bb_code_underline_start'] . '|' . $this->data['lang_tag_bb_code_underline_end'];
            $this->data['lang_tag_bb_code_strike']      = $this->data['lang_tag_bb_code_strike_start'] . '|' . $this->data['lang_tag_bb_code_strike_end'];
            $this->data['lang_tag_bb_code_superscript'] = $this->data['lang_tag_bb_code_superscript_start'] . '|' . $this->data['lang_tag_bb_code_superscript_end'];
            $this->data['lang_tag_bb_code_subscript']   = $this->data['lang_tag_bb_code_subscript_start'] . '|' . $this->data['lang_tag_bb_code_subscript_end'];
            $this->data['lang_tag_bb_code_code']        = $this->data['lang_tag_bb_code_code_start'] . '|' . $this->data['lang_tag_bb_code_code_end'];
            $this->data['lang_tag_bb_code_php']         = $this->data['lang_tag_bb_code_php_start'] . '|' . $this->data['lang_tag_bb_code_php_end'];
            $this->data['lang_tag_bb_code_quote']       = $this->data['lang_tag_bb_code_quote_start'] . '|' . $this->data['lang_tag_bb_code_quote_end'];
            $this->data['lang_tag_bb_code_bullet']      = $this->data['lang_tag_bb_code_bullet_1'] . '|' . $this->data['lang_tag_bb_code_bullet_2'] . '|' . $this->data['lang_tag_bb_code_bullet_3'] . '|' . $this->data['lang_tag_bb_code_bullet_4'];
            $this->data['lang_tag_bb_code_numeric']     = $this->data['lang_tag_bb_code_numeric_1'] . '|' . $this->data['lang_tag_bb_code_numeric_2'] . '|' . $this->data['lang_tag_bb_code_numeric_3'] . '|' . $this->data['lang_tag_bb_code_numeric_4'];
            $this->data['lang_tag_bb_code_link']        = $this->data['lang_tag_bb_code_link_1'] . '|' . $this->data['lang_tag_bb_code_link_2'] . '|' . $this->data['lang_tag_bb_code_link_3'] . '|' . $this->data['lang_tag_bb_code_link_4'];
            $this->data['lang_tag_bb_code_email']       = $this->data['lang_tag_bb_code_email_1'] . '|' . $this->data['lang_tag_bb_code_email_2'] . '|' . $this->data['lang_tag_bb_code_email_3'] . '|' . $this->data['lang_tag_bb_code_email_4'];
            $this->data['lang_tag_bb_code_image']       = $this->data['lang_tag_bb_code_image_1'] . '|' . $this->data['lang_tag_bb_code_image_2'];
            $this->data['lang_tag_bb_code_youtube']     = $this->data['lang_tag_bb_code_youtube_1'] . '|' . $this->data['lang_tag_bb_code_youtube_2'];

            $this->data['lang_text_drag_and_drop']      = sprintf($this->data['lang_text_drag_and_drop'], $this->setting->get('maximum_upload_amount'));

            $this->data['hidden_data'] = str_replace('&', '&amp;', $hidden_data);

            /* These are passed to common.js via the template */
            $this->data['cmtx_js_settings_form'] = array(
                'commentics_url'           => $this->url->getCommenticsUrl(),
                'page_id'                  => (int) $this->page->getId(),
                'enabled_country'          => (bool) $this->data['enabled_country'],
                'country_id'               => (int) $this->data['country_id'],
                'enabled_state'            => (bool) $this->data['enabled_state'],
                'state_id'                 => (int) $this->data['state_id'],
                'enabled_upload'           => (bool) $this->data['enabled_upload'],
                'maximum_upload_amount'    => (int) $this->setting->get('maximum_upload_amount'),
                'maximum_upload_size'      => (float) $this->setting->get('maximum_upload_size'),
                'maximum_upload_total'     => (float) $this->setting->get('maximum_upload_total'),
                'captcha'                  => (bool) $this->data['captcha'],
                'captcha_url'              => $this->data['captcha_url'],
                'cmtx_wait_for_comment'    => $this->data['cmtx_wait_for_comment'],
                'lang_error_file_num'      => $this->data['lang_error_file_num'],
                'lang_error_file_size'     => $this->data['lang_error_file_size'],
                'lang_error_file_total'    => $this->data['lang_error_file_total'],
                'lang_error_file_type'     => $this->data['lang_error_file_type'],
                'lang_text_loading'        => $this->data['lang_text_loading'],
                'lang_placeholder_country' => $this->data['lang_placeholder_country'],
                'lang_placeholder_state'   => $this->data['lang_placeholder_state'],
                'lang_text_country_first'  => $this->data['lang_text_country_first'],
                'lang_button_submit'       => $this->data['lang_button_submit'],
                'lang_button_preview'      => $this->data['lang_button_preview'],
                'lang_button_remove'       => $this->data['lang_button_remove'],
                'lang_button_processing'   => $this->data['lang_button_processing']
            );

            $this->data['cmtx_js_settings_form'] = json_encode($this->data['cmtx_js_settings_form']);
        }

        return $this->data;
    }

    public function getCountries()
    {
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');

            $json = array();

            $countries = $this->geo->getCountries();

            foreach ($countries as $country) {
                $json[] = array(
                    'id'   => $country['id'],
                    'name' => $country['name']
                );
            }

            echo json_encode($json);
        }
    }

    public function getStates()
    {
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');

            $json = array();

            if (isset($this->request->post['country_id']) && $this->request->post['country_id']) {
                $country_id = $this->request->post['country_id'];
            } else if ($this->setting->get('default_country')) {
                $country_id = $this->setting->get('default_country');
            } else {
                $country_id = '164'; // United States
            }

            $states = $this->geo->getStatesByCountryId($country_id);

            foreach ($states as $state) {
                $json[] = array(
                    'id'   => $state['id'],
                    'name' => $state['name']
                );
            }

            echo json_encode($json);
        }
    }

    public function captcha()
    {
        if ($this->setting->get('enabled_captcha') && $this->setting->get('captcha_type') == 'image' && extension_loaded('gd') && function_exists('imagettftext') && is_callable('imagettftext')) {
            if (isset($this->request->get['page_id'])) {
                $page_id = (int) $this->request->get['page_id'];

                if ($this->page->pageExists($page_id)) {
                    $this->loadModel('main/form');

                    $captcha_length = $this->setting->get('captcha_length');

                    $captcha_string = $this->variable->random($captcha_length, true);

                    $this->session->data['cmtx_captcha_answer_' . $page_id] = $captcha_string;

                    // Create the image
                    $image = imagecreatetruecolor($this->setting->get('captcha_width'), $this->setting->get('captcha_height'));

                    // Dimensions
                    $width = imagesx($image);
                    $height = imagesy($image);

                    // Background color
                    $color = $this->model_main_form->hexColorAllocate($image, $this->setting->get('captcha_back_color'));
                    imagefilledrectangle($image, 0, 0, $width, $height, $color);

                    // Draw lines
                    $color = $this->model_main_form->hexColorAllocate($image, $this->setting->get('captcha_line_color'));
                    for ($i = 0; $i < $this->setting->get('captcha_lines'); $i++) {
                        imagesetthickness($image, rand(2, 10));
                        imageline($image, ceil(rand(5, 145)), ceil(rand(0, 35)), ceil(rand(5, 145)), ceil(rand(0, 35)), $color);
                    }

                    // Draw circles
                    $color = $this->model_main_form->hexColorAllocate($image, $this->setting->get('captcha_circle_color'), true);
                    for ($i = 0; $i < $this->setting->get('captcha_circles'); $i++) {
                        imagefilledellipse($image, ceil(rand(5, 145)), ceil(rand(0, 35)), 30, 30, $color);
                    }

                    // Draw squares
                    $color = $this->model_main_form->hexColorAllocate($image, $this->setting->get('captcha_square_color'), true);
                    for ($i = 0; $i < $this->setting->get('captcha_squares'); $i++) {
                        imagesetthickness($image, rand(2, 4));
                        imagerectangle($image, rand(-10, 190), rand(-10, 10), rand(-10, 190), rand(40, 60), $color);
                    }

                    // Draw dots
                    $color = $this->model_main_form->hexColorAllocate($image, $this->setting->get('captcha_dots_color'));
                    for ($i = 0; $i < $this->setting->get('captcha_dots'); $i++) {
                        $x = mt_rand(0, $width);
                        $y = mt_rand(0, $height);
                        $size = mt_rand(1, 5);
                        imagefilledarc($image, $x, $y, $size, $size, 0, mt_rand(180,360), $color, IMG_ARC_PIE);
                    }

                    // Draw characters
                    $color = $this->model_main_form->hexColorAllocate($image, $this->setting->get('captcha_text_color'));
                    $size = 20; // the font size in points
                    $font = $this->loadFont('ahgbold.ttf'); // load font
                    $letter_space = 170 / $captcha_length;
                    $initial = 25;
                    for ($i = 0; $i < $captcha_length; $i++) {
                        $angle = rand(-15, 15); // the angle in degrees
                        $x = $initial + (int) ($i * $letter_space); // the x coordinate of the character
                        $y = rand(35, 55); // the y coordinate of the character
                        imagettftext($image, $size, $angle, $x, $y, $color, $font, $captcha_string[$i]);
                    }

                    $this->response->addHeader('Content-type: image/png');

                    imagepng($image);
                    imagedestroy($image);
                }
            }
        }
    }

    public function submit()
    {
        if ($this->request->isAjax()) {
            $this->loadLanguage('main/form');

            $this->loadModel('main/form');

            $this->response->addHeader('Content-Type: application/json');

            $json = array();

            /* Is this an administrator? */
            $is_admin = $this->user->isAdmin();

            if ($this->setting->get('maintenance_mode') && !$is_admin) {
                $json['result']['error'] = $this->setting->get('maintenance_message');
            } else {
                if ($this->setting->get('enabled_form')) {
                    $page_id = $this->page->getId();

                    if ($page_id) {
                        $page = $this->page->getPage($page_id);

                        if ($page['is_form_enabled']) {
                            $ip_address = $this->user->getIpAddress();

                            if ($this->user->isBanned($ip_address)) {
                                $json['result']['error'] = $this->data['lang_error_banned'];
                            } else {
                                /* Let the model access the language */
                                $this->model_main_form->data = $this->data;

                                /* Is this a preview? */
                                if ($this->setting->get('enabled_preview') && isset($this->request->post['cmtx_type']) && $this->request->post['cmtx_type'] == 'preview') {
                                    $is_preview = true;
                                } else {
                                    $is_preview = false;
                                }

                                /* Check for flooding (delay) */
                                $this->model_main_form->validateFloodingDelay($is_admin, $page_id);

                                /* Check for flooding (maximum) */
                                $this->model_main_form->validateFloodingMaximum($is_admin, $page_id);

                                /* Check referrer */
                                $this->model_main_form->validateReferrer();

                                /* Check honeypot */
                                $this->model_main_form->validateHoneypot();

                                /* Check time */
                                $this->model_main_form->validateTime();

                                /* Comment */
                                $this->model_main_form->validateComment($is_preview);

                                /* Headline */
                                $this->model_main_form->validateHeadline($is_preview);

                                /* Name */
                                $this->model_main_form->validateName($is_admin);

                                /* Email */
                                $this->model_main_form->validateEmail($is_admin);

                                /* User */
                                $user = $this->model_main_form->validateUser();

                                /* Rating */
                                $this->model_main_form->validateRating($page_id);

                                /* Website */
                                $this->model_main_form->validateWebsite($is_admin);

                                /* Town */
                                $this->model_main_form->validateTown($is_admin);

                                /* Country */
                                $this->model_main_form->validateCountry();

                                /* State */
                                $this->model_main_form->validateState();

                                /* Question */
                                $this->model_main_form->validateQuestion();

                                /* Extra fields */
                                $this->model_main_form->validateExtraFields($is_preview);

                                /* ReCaptcha */
                                $this->model_main_form->validateReCaptcha();

                                /* Image Captcha */
                                $this->model_main_form->validateImageCaptcha();

                                /* Captcha */
                                $this->model_main_form->validateCaptcha();

                                /* Privacy */
                                $this->model_main_form->validatePrivacy($is_preview);

                                /* Terms */
                                $this->model_main_form->validateTerms($is_preview);

                                /* Reply */
                                $this->model_main_form->validateReply();

                                /* Upload */
                                $this->model_main_form->validateUpload($is_preview);

                                /* Avatar provided by login information */
                                if ($this->setting->get('avatar_type') == 'login' && isset($this->request->post['cmtx_avatar']) && $this->validation->isUrl($this->request->post['cmtx_avatar']) && $this->request->post['cmtx_email']) {
                                    $avatar_login = $this->request->post['cmtx_avatar'];
                                } else {
                                    $avatar_login = '';
                                }
                            }
                        } else {
                            $json['result']['error'] = $this->data['lang_error_form_disabled'];
                        }
                    } else {
                        $json['result']['error'] = $this->data['lang_error_page_invalid'];
                    }
                } else {
                    $json['result']['error'] = $this->data['lang_error_form_disabled'];
                }
            }

            $json = array_merge($json, $this->model_main_form->getJson());

            if ($json && (isset($json['result']['error']) || isset($json['error']))) {
                if (isset($json['result']['error'])) {
                    $json['error'] = '';
                } else {
                    $json['result']['error'] = $this->data['lang_error_review'];
                }
            } else {
                $approve = $this->model_main_form->approve;
                $uploads = $this->model_main_form->uploads;
                $extra_fields = $this->model_main_form->extra_fields;

                if ($is_preview) {
                    $this->loadLanguage('main/comments');

                    $this->loadModel('main/comments');

                    $reply_depth = 0;

                    $show_bio = false;

                    $avatar_type        = $this->setting->get('avatar_type');
                    $show_level         = $this->setting->get('show_level');
                    $show_rating        = $this->setting->get('show_rating');
                    $show_website       = $this->setting->get('show_website');
                    $website_new_window = 'target="_blank"';
                    $website_no_follow  = '';
                    $show_says          = $this->setting->get('show_says');
                    $show_headline      = $this->setting->get('show_headline');
                    $show_date          = $this->setting->get('show_date');
                    $date_auto          = $this->setting->get('date_auto');

                    if ($avatar_login) {
                        $avatar = $avatar_login;
                    } else if ($user) {
                        $avatar = $this->user->getAvatar($user['id']);
                    } else {
                        $avatar = $this->user->getAvatar(0);
                    }

                    $location = '';

                    if ($this->setting->get('show_town') && $this->request->post['cmtx_town']) {
                        $location .= $this->request->post['cmtx_town'] . ', ';
                    }

                    if ($this->setting->get('show_state') && $this->request->post['cmtx_state']) {
                        $state = $this->geo->getState($this->request->post['cmtx_state']);

                        $location .= $state['name'] . ', ';
                    }

                    if ($this->setting->get('show_country') && $this->request->post['cmtx_country']) {
                        $country = $this->geo->getCountry($this->request->post['cmtx_country']);

                        $location .= $country['name'] . ', ';
                    }

                    $location = rtrim($location, ', ');

                    $ratings = array(0, 1, 2, 3, 4);

                    $comment_post = $this->request->post['cmtx_comment'];

                    if ($this->setting->get('enabled_smilies')) {
                        $comment_post = $this->model_main_comments->convertSmilies($comment_post);
                    }

                    $comment_post = $this->model_main_comments->purifyComment($comment_post);

                    $date_added = $this->data['lang_text_today'];

                    if ($this->setting->get('date_auto')) {
                        $date_added_title = $this->data['lang_text_timeago_second'] . ' ' . $this->data['lang_text_timeago_ago'];
                    } else {
                        $date_added_title = '';
                    }

                    $comment = array(
                        'id'               => 0,
                        'avatar'           => $avatar,
                        'level'            => $this->data['lang_text_preview'],
                        'name'             => $this->request->post['cmtx_name'],
                        'website'          => $this->request->post['cmtx_website'],
                        'location'         => $location,
                        'is_sticky'        => false,
                        'rating'           => $this->request->post['cmtx_rating'],
                        'comment'          => $comment_post,
                        'headline'         => $this->request->post['cmtx_headline'],
                        'is_admin'         => $is_admin,
                        'date_added'       => $date_added,
                        'date_added_title' => $date_added_title,
                        'uploads'          => $uploads,
                        'extra_fields'     => $extra_fields,
                        'reply'            => false,
                        'reply_id'         => array(),
                        'number_edits'     => 0
                    );

                    extract($this->data);

                    ob_start();

                    require $this->loadTemplate('main/comment');

                    $json['result']['preview'] = ob_get_clean();
                } else {
                    if ($user) {
                        $user_token = $user['token'];

                        $user_id = $user['id'];

                        if ($this->setting->get('avatar_user_link') && $this->request->post['cmtx_email']) {
                            if (in_array($this->setting->get('avatar_type'), array('gravatar', 'selection', 'upload'))) {
                                $this->loadModel('main/user');

                                if ($this->setting->get('avatar_link_days') && $this->model_main_user->numDaysSinceUserAdded($user['date_added']) <= $this->setting->get('avatar_link_days')) {
                                    $json['user_link'] = sprintf($this->data['lang_text_user_link'], $this->setting->get('commentics_url') . 'frontend/index.php?route=main/user&u-t=' . $user_token);
                                }
                            }
                        }
                    } else {
                        $user_token = $this->user->createToken();

                        $user_id = $this->user->createUser($this->request->post['cmtx_name'], $this->request->post['cmtx_email'], $user_token, $ip_address);

                        if ($this->setting->get('avatar_user_link')) {
                            if (in_array($this->setting->get('avatar_type'), array('selection', 'upload')) || ($this->setting->get('avatar_type') == 'gravatar' && $this->request->post['cmtx_email'])) {
                                $json['user_link'] = sprintf($this->data['lang_text_user_link'], $this->setting->get('commentics_url') . 'frontend/index.php?route=main/user&u-t=' . $user_token);
                            }
                        }
                    }

                    /* Determine if the comment needs to be approved by the administrator */
                    $approve = $this->model_main_form->needsApproval($is_admin, $user, $page, $ip_address);

                    $comment_id = $this->comment->createComment($user_id, $page_id, $this->request->post['cmtx_website'], $this->request->post['cmtx_town'], $this->request->post['cmtx_state'], $this->request->post['cmtx_country'], $this->request->post['cmtx_rating'], $this->request->post['cmtx_reply_to'], $this->request->post['cmtx_headline'], $this->request->post['cmtx_original_comment'], $this->request->post['cmtx_comment'], $ip_address, $approve, $this->model_main_form->getNotes(), $is_admin, $uploads, $extra_fields);

                    $this->comment->deleteCache($comment_id);

                    if ($this->request->post['cmtx_rating']) {
                        $this->cache->delete('getaveragerating_pageid' . $page_id);
                    }

                    if ($this->setting->get('enabled_question')) {
                        $question = $this->model_main_form->getQuestion();

                        if ($question) {
                            $this->session->data['cmtx_question_id_' . $this->page->getId()] = $question['id'];

                            $json['question'] = $question['question'];
                        }
                    }

                    if ($this->setting->get('enabled_notify') && isset($this->request->post['cmtx_notify']) && $this->setting->get('enabled_email') && $this->request->post['cmtx_email'] && !$is_admin) {
                        if (!$this->model_main_form->subscriptionExists($user_id, $page_id) && !$this->model_main_form->userHasSubscriptionAttempt($user_id) && !$this->model_main_form->ipHasSubscriptionAttempt($ip_address)) {
                            $subscription_token = $this->user->createToken();

                            $subscription_id = $this->model_main_form->addSubscription($user_id, $page_id, $subscription_token, $ip_address);

                            $this->notify->subscriberConfirmation($this->setting->get('notify_format'), $this->request->post['cmtx_name'], $this->request->post['cmtx_email'], $page['reference'], $page['url'], $user_token, $subscription_token);
                        }
                    }

                    if ($this->setting->get('enabled_cookie') && (isset($this->request->post['cmtx_cookie']) || (!isset($this->request->post['cmtx_cookie']) && $this->setting->get('form_cookie')))) {
                        $values = array(
                            $this->security->decode($this->request->post['cmtx_name']),
                            $this->security->decode($this->request->post['cmtx_email']),
                            $this->security->decode($this->request->post['cmtx_website']),
                            $this->security->decode($this->request->post['cmtx_town']),
                            $this->security->decode($this->request->post['cmtx_country']),
                            $this->security->decode($this->request->post['cmtx_state'])
                        );

                        $values = implode('|', $values);

                        $this->cookie->set('Commentics-Form', $values, 60 * 60 * 24 * $this->setting->get('form_cookie_days') + time());
                    }

                    /* Notify admins of comment */
                    if (!$is_admin) {
                        if ($approve) {
                            $this->notify->adminNotifyCommentApprove($comment_id);
                        } else {
                            $this->notify->adminNotifyCommentSuccess($comment_id);
                        }
                    }

                    /* Notify subscribers of comment */
                    if ($this->setting->get('enabled_notify') && ($is_admin || (!$this->setting->get('approve_notifications') && !$approve))) {
                        $this->notify->subscriberNotification($comment_id);
                    }

                    /* Unset that the Captcha is complete so the user has to pass it again */
                    unset($this->session->data['cmtx_captcha_complete_' . $this->page->getId()]);

                    /* Save avatar provided by login information */
                    if ($avatar_login) {
                        $this->user->saveAvatarLogin($user_id, $avatar_login);
                    }

                    if ($approve) {
                        $json['result']['approve'] = true;
                        $json['result']['success'] = $this->data['lang_text_comment_approve'];
                    } else {
                        $json['result']['approve'] = false;
                        $json['result']['success'] = $this->data['lang_text_comment_success'];
                    }
                }
            }

            echo json_encode($json);
        }
    }

    public function edit()
    {
        if ($this->request->isAjax()) {
            $this->loadLanguage('main/form');

            $this->loadModel('main/form');

            $this->response->addHeader('Content-Type: application/json');

            $json = array();

            if (!$this->setting->get('show_edit')) { // check if feature enabled
                $json['result']['error'] = $this->data['lang_error_disabled'];
            } else {
                /* Is this an administrator? */
                $is_admin = $this->user->isAdmin();

                if ($this->setting->get('maintenance_mode') && !$is_admin) {
                    $json['result']['error'] = $this->setting->get('maintenance_message');
                } else {
                    if ($this->setting->get('enabled_form')) {
                        $page_id = $this->page->getId();

                        if ($page_id) {
                            $page = $this->page->getPage($page_id);

                            if ($page['is_form_enabled']) {
                                $ip_address = $this->user->getIpAddress();

                                if (isset($this->request->post['cmtx_comment_id'])) {
                                    $comment_id = $this->request->post['cmtx_comment_id'];
                                } else {
                                    $comment_id = 0;
                                }

                                $comment = $this->comment->getComment($comment_id);

                                if ($comment) {
                                    if ($this->user->ownComment($comment)) {
                                        if ($comment['number_edits'] < $this->setting->get('max_edits')) {
                                            if ($this->user->isBanned($ip_address)) {
                                                $json['result']['error'] = $this->data['lang_error_banned'];
                                            } else {
                                                /* Let the model access the language */
                                                $this->model_main_form->data = $this->data;

                                                /* Comment */
                                                $this->model_main_form->validateComment(false);
                                            }
                                        } else {
                                            $json['result']['error'] = $this->data['lang_error_max_edits'];
                                        }
                                    } else {
                                        $json['result']['error'] = $this->data['lang_error_own_comment'];
                                    }
                                } else {
                                    $json['result']['error'] = $this->data['lang_error_no_comment'];
                                }
                            } else {
                                $json['result']['error'] = $this->data['lang_error_form_disabled'];
                            }
                        } else {
                            $json['result']['error'] = $this->data['lang_error_page_invalid'];
                        }
                    } else {
                        $json['result']['error'] = $this->data['lang_error_form_disabled'];
                    }
                }

                $json = array_merge($json, $this->model_main_form->getJson());

                if ($json && (isset($json['result']['error']) || isset($json['error']))) {
                    if (isset($json['result']['error'])) {
                        $json['error'] = '';
                    } else {
                        $json['result']['error'] = $this->data['lang_error_review'];
                    }
                } else {
                    $user = $this->user->getUserByCommentId($comment_id);

                    /* Determine if the comment needs to be approved by the administrator */
                    $approve = $this->model_main_form->needsApproval($is_admin, $user, $page, $ip_address);

                    $this->comment->editComment($comment_id, $this->request->post['cmtx_original_comment'], $this->request->post['cmtx_comment'], $approve, $this->model_main_form->getNotes());

                    $this->comment->deleteCache($comment_id);

                    /* Notify admins of edit */
                    if (!$is_admin) {
                        $this->notify->adminNotifyCommentEdit($comment_id);
                    }

                    if ($approve) {
                        $json['result']['approve'] = true;
                        $json['result']['success'] = $this->data['lang_text_comment_approve'];
                    } else {
                        $json['result']['approve'] = false;
                        $json['result']['success'] = $this->data['lang_text_comment_edited'];
                    }
                }
            }

            echo json_encode($json);
        }
    }

    public function reply()
    {
        if ($this->request->isAjax()) {
            $this->loadLanguage('main/form');

            $this->loadModel('main/form');

            $this->response->addHeader('Content-Type: application/json');

            $json = array();

            if (!$this->setting->get('show_reply') || !$this->setting->get('quick_reply')) { // check if feature enabled
                $json['result']['error'] = $this->data['lang_error_disabled'];
            } else {
                /* Is this an administrator? */
                $is_admin = $this->user->isAdmin();

                if ($this->setting->get('maintenance_mode') && !$is_admin) {
                    $json['result']['error'] = $this->setting->get('maintenance_message');
                } else {
                    if ($this->setting->get('enabled_form')) {
                        $page_id = $this->page->getId();

                        if ($page_id) {
                            $page = $this->page->getPage($page_id);

                            if ($page['is_form_enabled']) {
                                $ip_address = $this->user->getIpAddress();

                                if ($this->user->isBanned($ip_address)) {
                                    $json['result']['error'] = $this->data['lang_error_banned'];
                                } else {
                                    /* Let the model access the language */
                                    $this->model_main_form->data = $this->data;

                                    /* Check for flooding (delay) */
                                    $this->model_main_form->validateFloodingDelay($is_admin, $page_id);

                                    /* Check for flooding (maximum) */
                                    $this->model_main_form->validateFloodingMaximum($is_admin, $page_id);

                                    /* Check referrer */
                                    $this->model_main_form->validateReferrer();

                                    /* Check honeypot */
                                    $this->model_main_form->validateHoneypot();

                                    /* Check time */
                                    $this->model_main_form->validateTime();

                                    /* Comment */
                                    $this->model_main_form->validateComment(false);

                                    /* Name */
                                    $this->model_main_form->validateName($is_admin);

                                    /* Email */
                                    $this->model_main_form->validateEmail($is_admin);

                                    /* User */
                                    $user = $this->model_main_form->validateUser();

                                    /* Reply */
                                    $this->model_main_form->validateReply(true);
                                }
                            } else {
                                $json['result']['error'] = $this->data['lang_error_form_disabled'];
                            }
                        } else {
                            $json['result']['error'] = $this->data['lang_error_page_invalid'];
                        }
                    } else {
                        $json['result']['error'] = $this->data['lang_error_form_disabled'];
                    }
                }

                $json = array_merge($json, $this->model_main_form->getJson());

                if ($json && (isset($json['result']['error']) || isset($json['error']))) {
                    if (isset($json['result']['error'])) {
                        $json['error'] = '';
                    } else {
                        $json['result']['error'] = $this->data['lang_error_review'];
                    }
                } else {
                    if ($user) {
                        $user_token = $user['token'];

                        $user_id = $user['id'];
                    } else {
                        $user_token = $this->user->createToken();

                        $user_id = $this->user->createUser($this->request->post['cmtx_name'], $this->request->post['cmtx_email'], $user_token, $ip_address);
                    }

                    /* Determine if the comment needs to be approved by the administrator */
                    $approve = $this->model_main_form->needsApproval($is_admin, $user, $page, $ip_address);

                    $comment_id = $this->comment->createComment($user_id, $page_id, '', '', '', 0, 0, $this->request->post['cmtx_reply_to'], '', $this->request->post['cmtx_original_comment'], $this->request->post['cmtx_comment'], $ip_address, $approve, $this->model_main_form->getNotes(), $is_admin, array(), array());

                    $this->comment->deleteCache($comment_id);

                    /* Notify admins of comment */
                    if (!$is_admin) {
                        if ($approve) {
                            $this->notify->adminNotifyCommentApprove($comment_id);
                        } else {
                            $this->notify->adminNotifyCommentSuccess($comment_id);
                        }
                    }

                    /* Notify subscribers of comment */
                    if ($this->setting->get('enabled_notify') && ($is_admin || (!$this->setting->get('approve_notifications') && !$approve))) {
                        $this->notify->subscriberNotification($comment_id);
                    }

                    if ($approve) {
                        $json['result']['approve'] = true;
                        $json['result']['success'] = $this->data['lang_text_comment_approve'];
                    } else {
                        $json['result']['approve'] = false;
                        $json['result']['success'] = $this->data['lang_text_comment_success'];
                    }
                }
            }

            echo json_encode($json);
        }
    }
}
