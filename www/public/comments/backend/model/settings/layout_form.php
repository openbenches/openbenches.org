<?php
namespace Commentics;

class SettingsLayoutFormModel extends Model
{
    public function update($data)
    {
        /* General */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_form']) ? 1 : 0) . "' WHERE `title` = 'enabled_form'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['hide_form']) ? 1 : 0) . "' WHERE `title` = 'hide_form'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['display_javascript_disabled']) ? 1 : 0) . "' WHERE `title` = 'display_javascript_disabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['display_required_symbol']) ? 1 : 0) . "' WHERE `title` = 'display_required_symbol'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['display_required_text']) ? 1 : 0) . "' WHERE `title` = 'display_required_text'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['order_fields']) . "' WHERE `title` = 'order_fields'");

        /* BB Code */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_bb_code']) ? 1 : 0) . "' WHERE `title` = 'enabled_bb_code'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_bb_code_bold']) ? 1 : 0) . "' WHERE `title` = 'enabled_bb_code_bold'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_bb_code_italic']) ? 1 : 0) . "' WHERE `title` = 'enabled_bb_code_italic'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_bb_code_underline']) ? 1 : 0) . "' WHERE `title` = 'enabled_bb_code_underline'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_bb_code_strike']) ? 1 : 0) . "' WHERE `title` = 'enabled_bb_code_strike'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_bb_code_superscript']) ? 1 : 0) . "' WHERE `title` = 'enabled_bb_code_superscript'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_bb_code_subscript']) ? 1 : 0) . "' WHERE `title` = 'enabled_bb_code_subscript'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_bb_code_code']) ? 1 : 0) . "' WHERE `title` = 'enabled_bb_code_code'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_bb_code_php']) ? 1 : 0) . "' WHERE `title` = 'enabled_bb_code_php'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_bb_code_quote']) ? 1 : 0) . "' WHERE `title` = 'enabled_bb_code_quote'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_bb_code_line']) ? 1 : 0) . "' WHERE `title` = 'enabled_bb_code_line'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_bb_code_bullet']) ? 1 : 0) . "' WHERE `title` = 'enabled_bb_code_bullet'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_bb_code_numeric']) ? 1 : 0) . "' WHERE `title` = 'enabled_bb_code_numeric'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_bb_code_link']) ? 1 : 0) . "' WHERE `title` = 'enabled_bb_code_link'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_bb_code_email']) ? 1 : 0) . "' WHERE `title` = 'enabled_bb_code_email'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_bb_code_image']) ? 1 : 0) . "' WHERE `title` = 'enabled_bb_code_image'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_bb_code_youtube']) ? 1 : 0) . "' WHERE `title` = 'enabled_bb_code_youtube'");

        /* Smilies */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_smilies']) ? 1 : 0) . "' WHERE `title` = 'enabled_smilies'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_smilies_smile']) ? 1 : 0) . "' WHERE `title` = 'enabled_smilies_smile'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_smilies_sad']) ? 1 : 0) . "' WHERE `title` = 'enabled_smilies_sad'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_smilies_huh']) ? 1 : 0) . "' WHERE `title` = 'enabled_smilies_huh'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_smilies_laugh']) ? 1 : 0) . "' WHERE `title` = 'enabled_smilies_laugh'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_smilies_mad']) ? 1 : 0) . "' WHERE `title` = 'enabled_smilies_mad'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_smilies_tongue']) ? 1 : 0) . "' WHERE `title` = 'enabled_smilies_tongue'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_smilies_cry']) ? 1 : 0) . "' WHERE `title` = 'enabled_smilies_cry'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_smilies_grin']) ? 1 : 0) . "' WHERE `title` = 'enabled_smilies_grin'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_smilies_wink']) ? 1 : 0) . "' WHERE `title` = 'enabled_smilies_wink'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_smilies_scared']) ? 1 : 0) . "' WHERE `title` = 'enabled_smilies_scared'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_smilies_cool']) ? 1 : 0) . "' WHERE `title` = 'enabled_smilies_cool'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_smilies_sleep']) ? 1 : 0) . "' WHERE `title` = 'enabled_smilies_sleep'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_smilies_blush']) ? 1 : 0) . "' WHERE `title` = 'enabled_smilies_blush'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_smilies_confused']) ? 1 : 0) . "' WHERE `title` = 'enabled_smilies_confused'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_smilies_shocked']) ? 1 : 0) . "' WHERE `title` = 'enabled_smilies_shocked'");

        /* Comment */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['default_comment']) . "' WHERE `title` = 'default_comment'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['comment_maximum_characters'] . "' WHERE `title` = 'comment_maximum_characters'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_counter']) ? 1 : 0) . "' WHERE `title` = 'enabled_counter'");

        /* Headline */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_headline']) ? 1 : 0) . "' WHERE `title` = 'enabled_headline'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['required_headline']) ? 1 : 0) . "' WHERE `title` = 'required_headline'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['default_headline']) . "' WHERE `title` = 'default_headline'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['headline_maximum_characters'] . "' WHERE `title` = 'headline_maximum_characters'");

        /* Upload */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_upload']) ? 1 : 0) . "' WHERE `title` = 'enabled_upload'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (float) $data['maximum_upload_size'] . "' WHERE `title` = 'maximum_upload_size'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['maximum_upload_amount'] . "' WHERE `title` = 'maximum_upload_amount'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (float) $data['maximum_upload_total'] . "' WHERE `title` = 'maximum_upload_total'");

        /* Rating */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_rating']) ? 1 : 0) . "' WHERE `title` = 'enabled_rating'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['required_rating']) ? 1 : 0) . "' WHERE `title` = 'required_rating'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['default_rating'] . "' WHERE `title` = 'default_rating'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['repeat_rating']) . "' WHERE `title` = 'repeat_rating'");

        /* Name */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['default_name']) . "' WHERE `title` = 'default_name'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['maximum_name'] . "' WHERE `title` = 'maximum_name'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['filled_name_cookie_action']) . "' WHERE `title` = 'filled_name_cookie_action'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['filled_name_login_action']) . "' WHERE `title` = 'filled_name_login_action'");

        /* Email */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_email']) ? 1 : 0) . "' WHERE `title` = 'enabled_email'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['required_email']) ? 1 : 0) . "' WHERE `title` = 'required_email'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['default_email']) . "' WHERE `title` = 'default_email'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['maximum_email'] . "' WHERE `title` = 'maximum_email'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['filled_email_cookie_action']) . "' WHERE `title` = 'filled_email_cookie_action'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['filled_email_login_action']) . "' WHERE `title` = 'filled_email_login_action'");

        /* Website */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_website']) ? 1 : 0) . "' WHERE `title` = 'enabled_website'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['required_website']) ? 1 : 0) . "' WHERE `title` = 'required_website'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['default_website']) . "' WHERE `title` = 'default_website'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['maximum_website'] . "' WHERE `title` = 'maximum_website'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['filled_website_cookie_action']) . "' WHERE `title` = 'filled_website_cookie_action'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['filled_website_login_action']) . "' WHERE `title` = 'filled_website_login_action'");

        /* Town */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_town']) ? 1 : 0) . "' WHERE `title` = 'enabled_town'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['required_town']) ? 1 : 0) . "' WHERE `title` = 'required_town'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['default_town']) . "' WHERE `title` = 'default_town'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['maximum_town'] . "' WHERE `title` = 'maximum_town'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['filled_town_cookie_action']) . "' WHERE `title` = 'filled_town_cookie_action'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['filled_town_login_action']) . "' WHERE `title` = 'filled_town_login_action'");

        /* State */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_state']) ? 1 : 0) . "' WHERE `title` = 'enabled_state'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['required_state']) ? 1 : 0) . "' WHERE `title` = 'required_state'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['default_state'] . "' WHERE `title` = 'default_state'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['filled_state_cookie_action']) . "' WHERE `title` = 'filled_state_cookie_action'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['filled_state_login_action']) . "' WHERE `title` = 'filled_state_login_action'");

        /* Country */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_country']) ? 1 : 0) . "' WHERE `title` = 'enabled_country'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['required_country']) ? 1 : 0) . "' WHERE `title` = 'required_country'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['default_country'] . "' WHERE `title` = 'default_country'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['filled_country_cookie_action']) . "' WHERE `title` = 'filled_country_cookie_action'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['filled_country_login_action']) . "' WHERE `title` = 'filled_country_login_action'");

        /* Question */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_question']) ? 1 : 0) . "' WHERE `title` = 'enabled_question'");

        /* Captcha */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_captcha']) ? 1 : 0) . "' WHERE `title` = 'enabled_captcha'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['captcha_type']) . "' WHERE `title` = 'captcha_type'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['recaptcha_public_key']) . "' WHERE `title` = 'recaptcha_public_key'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['recaptcha_private_key']) . "' WHERE `title` = 'recaptcha_private_key'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['recaptcha_theme']) . "' WHERE `title` = 'recaptcha_theme'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['recaptcha_size']) . "' WHERE `title` = 'recaptcha_size'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['captcha_width'] . "' WHERE `title` = 'captcha_width'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['captcha_height'] . "' WHERE `title` = 'captcha_height'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['captcha_length'] . "' WHERE `title` = 'captcha_length'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['captcha_lines'] . "' WHERE `title` = 'captcha_lines'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['captcha_circles'] . "' WHERE `title` = 'captcha_circles'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['captcha_squares'] . "' WHERE `title` = 'captcha_squares'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['captcha_dots'] . "' WHERE `title` = 'captcha_dots'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['captcha_text_color']) . "' WHERE `title` = 'captcha_text_color'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['captcha_back_color']) . "' WHERE `title` = 'captcha_back_color'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['captcha_line_color']) . "' WHERE `title` = 'captcha_line_color'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['captcha_circle_color']) . "' WHERE `title` = 'captcha_circle_color'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['captcha_square_color']) . "' WHERE `title` = 'captcha_square_color'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['captcha_dots_color']) . "' WHERE `title` = 'captcha_dots_color'");

        /* Notify */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_notify']) ? 1 : 0) . "' WHERE `title` = 'enabled_notify'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['default_notify']) ? 1 : 0) . "' WHERE `title` = 'default_notify'");

        /* Cookie */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_cookie']) ? 1 : 0) . "' WHERE `title` = 'enabled_cookie'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['default_cookie']) ? 1 : 0) . "' WHERE `title` = 'default_cookie'");

        /* Privacy */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_privacy']) ? 1 : 0) . "' WHERE `title` = 'enabled_privacy'");

        /* Terms */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_terms']) ? 1 : 0) . "' WHERE `title` = 'enabled_terms'");

        /* Preview */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_preview']) ? 1 : 0) . "' WHERE `title` = 'enabled_preview'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['agree_to_preview']) ? 1 : 0) . "' WHERE `title` = 'agree_to_preview'");

        /* Powered By */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['enabled_powered_by']) ? 1 : 0) . "' WHERE `title` = 'enabled_powered_by'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['powered_by_type']) . "' WHERE `title` = 'powered_by_type'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['powered_by_new_window']) ? 1 : 0) . "' WHERE `title` = 'powered_by_new_window'");
    }

    public function getBbCode()
    {
        $bb_code = array();

        $bb_code['bold'] = $this->getBBCodeImage('bold.png');

        $bb_code['italic'] = $this->getBBCodeImage('italic.png');

        $bb_code['underline'] = $this->getBBCodeImage('underline.png');

        $bb_code['strike'] = $this->getBBCodeImage('strike.png');

        $bb_code['superscript'] = $this->getBBCodeImage('superscript.png');

        $bb_code['subscript'] = $this->getBBCodeImage('subscript.png');

        $bb_code['code'] = $this->getBBCodeImage('code.png');

        $bb_code['php'] = $this->getBBCodeImage('php.png');

        $bb_code['quote'] = $this->getBBCodeImage('quote.png');

        $bb_code['line'] = $this->getBBCodeImage('line.png');

        $bb_code['bullet'] = $this->getBBCodeImage('bullet.png');

        $bb_code['numeric'] = $this->getBBCodeImage('numeric.png');

        $bb_code['link'] = $this->getBBCodeImage('link.png');

        $bb_code['email'] = $this->getBBCodeImage('email.png');

        $bb_code['image'] = $this->getBBCodeImage('image.png');

        $bb_code['youtube'] = $this->getBBCodeImage('youtube.png');

        return $bb_code;
    }

    private function getBBCodeImage($cmtx_image)
    {
        if (file_exists(CMTX_HTTP_ROOT . 'frontend/view/' . $this->setting->get('theme_frontend') . '/image/bb_code/' . strtolower($cmtx_image))) {
            return CMTX_HTTP_ROOT . 'frontend/view/' . $this->setting->get('theme_frontend') . '/image/bb_code/' . strtolower($cmtx_image);
        } else if (file_exists(CMTX_HTTP_ROOT . 'frontend/view/default/image/bb_code/' . strtolower($cmtx_image))) {
            return CMTX_HTTP_ROOT . 'frontend/view/default/image/bb_code/' . strtolower($cmtx_image);
        } else {
            die('<b>Error</b>: Could not load image ' . strtolower($cmtx_image) . '!');
        }
    }

    public function getSmilies()
    {
        $smilies = array();

        $smilies['smile'] = $this->getSmileyImage('smile.png');

        $smilies['sad'] = $this->getSmileyImage('sad.png');

        $smilies['huh'] = $this->getSmileyImage('huh.png');

        $smilies['laugh'] = $this->getSmileyImage('laugh.png');

        $smilies['mad'] = $this->getSmileyImage('mad.png');

        $smilies['tongue'] = $this->getSmileyImage('tongue.png');

        $smilies['cry'] = $this->getSmileyImage('cry.png');

        $smilies['grin'] = $this->getSmileyImage('grin.png');

        $smilies['wink'] = $this->getSmileyImage('wink.png');

        $smilies['scared'] = $this->getSmileyImage('scared.png');

        $smilies['cool'] = $this->getSmileyImage('cool.png');

        $smilies['sleep'] = $this->getSmileyImage('sleep.png');

        $smilies['blush'] = $this->getSmileyImage('blush.png');

        $smilies['confused'] = $this->getSmileyImage('confused.png');

        $smilies['shocked'] = $this->getSmileyImage('shocked.png');

        return $smilies;
    }

    private function getSmileyImage($cmtx_image)
    {
        if (file_exists(CMTX_HTTP_ROOT . 'frontend/view/' . $this->setting->get('theme_frontend') . '/image/smilies/' . strtolower($cmtx_image))) {
            return CMTX_HTTP_ROOT . 'frontend/view/' . $this->setting->get('theme_frontend') . '/image/smilies/' . strtolower($cmtx_image);
        } else if (file_exists(CMTX_HTTP_ROOT . 'frontend/view/default/image/smilies/' . strtolower($cmtx_image))) {
            return CMTX_HTTP_ROOT . 'frontend/view/default/image/smilies/' . strtolower($cmtx_image);
        } else {
            die('<b>Error</b>: Could not load image ' . strtolower($cmtx_image) . '!');
        }
    }

    public function checkLayoutSettings()
    {
        $this->setting->refresh();

        $layout_settings_enabled = $layout_settings_disabled = array();

        if ($this->setting->get('enabled_headline') && !$this->setting->get('show_headline')) {
            $layout_settings_enabled[] = $this->loadWord('settings/layout_form', 'lang_subheading_headline');
        } else if (!$this->setting->get('enabled_headline') && $this->setting->get('show_headline')) {
            $layout_settings_disabled[] = $this->loadWord('settings/layout_form', 'lang_subheading_headline');
        }

        if ($this->setting->get('enabled_rating') && !$this->setting->get('show_rating')) {
            $layout_settings_enabled[] = $this->loadWord('settings/layout_form', 'lang_subheading_rating');
        } else if (!$this->setting->get('enabled_rating') && $this->setting->get('show_rating')) {
            $layout_settings_disabled[] = $this->loadWord('settings/layout_form', 'lang_subheading_rating');
        }

        if ($this->setting->get('enabled_website') && !$this->setting->get('show_website')) {
            $layout_settings_enabled[] = $this->loadWord('settings/layout_form', 'lang_subheading_website');
        } else if (!$this->setting->get('enabled_website') && $this->setting->get('show_website')) {
            $layout_settings_disabled[] = $this->loadWord('settings/layout_form', 'lang_subheading_website');
        }

        if ($this->setting->get('enabled_town') && !$this->setting->get('show_town')) {
            $layout_settings_enabled[] = $this->loadWord('settings/layout_form', 'lang_subheading_town');
        } else if (!$this->setting->get('enabled_town') && $this->setting->get('show_town')) {
            $layout_settings_disabled[] = $this->loadWord('settings/layout_form', 'lang_subheading_town');
        }

        if ($this->setting->get('enabled_state') && !$this->setting->get('show_state')) {
            $layout_settings_enabled[] = $this->loadWord('settings/layout_form', 'lang_subheading_state');
        } else if (!$this->setting->get('enabled_state') && $this->setting->get('show_state')) {
            $layout_settings_disabled[] = $this->loadWord('settings/layout_form', 'lang_subheading_state');
        }

        if ($this->setting->get('enabled_country') && !$this->setting->get('show_country')) {
            $layout_settings_enabled[] = $this->loadWord('settings/layout_form', 'lang_subheading_country');
        } else if (!$this->setting->get('enabled_country') && $this->setting->get('show_country')) {
            $layout_settings_disabled[] = $this->loadWord('settings/layout_form', 'lang_subheading_country');
        }

        return array(
            'enabled'  => $layout_settings_enabled,
            'disabled' => $layout_settings_disabled
        );
    }

    public function stopLayoutDetect()
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '0' WHERE `title` = 'layout_detect'");
    }
}
