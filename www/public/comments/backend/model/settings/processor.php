<?php
namespace Commentics;

class SettingsProcessorModel extends Model
{
    public function update($data)
    {
        /* Name */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['one_name_enabled']) ? 1 : 0) . "' WHERE `title` = 'one_name_enabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['fix_name_enabled']) ? 1 : 0) . "' WHERE `title` = 'fix_name_enabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['unique_name_enabled']) ? 1 : 0) . "' WHERE `title` = 'unique_name_enabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['detect_link_in_name_enabled']) ? 1 : 0) . "' WHERE `title` = 'detect_link_in_name_enabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['link_in_name_action']) . "' WHERE `title` = 'link_in_name_action'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['reserved_names_enabled']) ? 1 : 0) . "' WHERE `title` = 'reserved_names_enabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['reserved_names_action']) . "' WHERE `title` = 'reserved_names_action'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['dummy_names_enabled']) ? 1 : 0) . "' WHERE `title` = 'dummy_names_enabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['dummy_names_action']) . "' WHERE `title` = 'dummy_names_action'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['banned_names_enabled']) ? 1 : 0) . "' WHERE `title` = 'banned_names_enabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['banned_names_action']) . "' WHERE `title` = 'banned_names_action'");

        /* Email */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['unique_email_enabled']) ? 1 : 0) . "' WHERE `title` = 'unique_email_enabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['reserved_emails_enabled']) ? 1 : 0) . "' WHERE `title` = 'reserved_emails_enabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['reserved_emails_action']) . "' WHERE `title` = 'reserved_emails_action'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['dummy_emails_enabled']) ? 1 : 0) . "' WHERE `title` = 'dummy_emails_enabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['dummy_emails_action']) . "' WHERE `title` = 'dummy_emails_action'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['banned_emails_enabled']) ? 1 : 0) . "' WHERE `title` = 'banned_emails_enabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['banned_emails_action']) . "' WHERE `title` = 'banned_emails_action'");

        /* Town */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['fix_town_enabled']) ? 1 : 0) . "' WHERE `title` = 'fix_town_enabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['detect_link_in_town_enabled']) ? 1 : 0) . "' WHERE `title` = 'detect_link_in_town_enabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['link_in_town_action']) . "' WHERE `title` = 'link_in_town_action'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['reserved_towns_enabled']) ? 1 : 0) . "' WHERE `title` = 'reserved_towns_enabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['reserved_towns_action']) . "' WHERE `title` = 'reserved_towns_action'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['dummy_towns_enabled']) ? 1 : 0) . "' WHERE `title` = 'dummy_towns_enabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['dummy_towns_action']) . "' WHERE `title` = 'dummy_towns_action'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['banned_towns_enabled']) ? 1 : 0) . "' WHERE `title` = 'banned_towns_enabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['banned_towns_action']) . "' WHERE `title` = 'banned_towns_action'");

        /* Website */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['approve_websites']) ? 1 : 0) . "' WHERE `title` = 'approve_websites'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['validate_website_ping']) ? 1 : 0) . "' WHERE `title` = 'validate_website_ping'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['reserved_websites_enabled']) ? 1 : 0) . "' WHERE `title` = 'reserved_websites_enabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['reserved_websites_action']) . "' WHERE `title` = 'reserved_websites_action'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['dummy_websites_enabled']) ? 1 : 0) . "' WHERE `title` = 'dummy_websites_enabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['dummy_websites_action']) . "' WHERE `title` = 'dummy_websites_action'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['banned_websites_as_website_enabled']) ? 1 : 0) . "' WHERE `title` = 'banned_websites_as_website_enabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['banned_websites_as_website_action']) . "' WHERE `title` = 'banned_websites_as_website_action'");

        /* Comment */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['approve_images']) ? 1 : 0) . "' WHERE `title` = 'approve_images'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['approve_videos']) ? 1 : 0) . "' WHERE `title` = 'approve_videos'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['approve_uploads']) ? 1 : 0) . "' WHERE `title` = 'approve_uploads'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['comment_convert_links']) ? 1 : 0) . "' WHERE `title` = 'comment_convert_links'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['comment_convert_emails']) ? 1 : 0) . "' WHERE `title` = 'comment_convert_emails'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['comment_links_new_window']) ? 1 : 0) . "' WHERE `title` = 'comment_links_new_window'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['comment_links_nofollow']) ? 1 : 0) . "' WHERE `title` = 'comment_links_nofollow'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['comment_minimum_characters'] . "' WHERE `title` = 'comment_minimum_characters'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['comment_minimum_words'] . "' WHERE `title` = 'comment_minimum_words'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['comment_maximum_characters'] . "' WHERE `title` = 'comment_maximum_characters'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['comment_maximum_lines'] . "' WHERE `title` = 'comment_maximum_lines'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['comment_maximum_smilies'] . "' WHERE `title` = 'comment_maximum_smilies'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['comment_long_word'] . "' WHERE `title` = 'comment_long_word'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['comment_line_breaks']) ? 1 : 0) . "' WHERE `title` = 'comment_line_breaks'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['detect_link_in_comment_enabled']) ? 1 : 0) . "' WHERE `title` = 'detect_link_in_comment_enabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['link_in_comment_action']) . "' WHERE `title` = 'link_in_comment_action'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['banned_websites_as_comment_enabled']) ? 1 : 0) . "' WHERE `title` = 'banned_websites_as_comment_enabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['banned_websites_as_comment_action']) . "' WHERE `title` = 'banned_websites_as_comment_action'");

        /* Headline */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['headline_minimum_characters'] . "' WHERE `title` = 'headline_minimum_characters'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['headline_minimum_words'] . "' WHERE `title` = 'headline_minimum_words'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['headline_maximum_characters'] . "' WHERE `title` = 'headline_maximum_characters'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['detect_link_in_headline_enabled']) ? 1 : 0) . "' WHERE `title` = 'detect_link_in_headline_enabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['link_in_headline_action']) . "' WHERE `title` = 'link_in_headline_action'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['banned_websites_as_headline_enabled']) ? 1 : 0) . "' WHERE `title` = 'banned_websites_as_headline_enabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['banned_websites_as_headline_action']) . "' WHERE `title` = 'banned_websites_as_headline_action'");

        /* Notify */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['notify_type']) . "' WHERE `title` = 'notify_type'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['notify_format']) . "' WHERE `title` = 'notify_format'");

        /* Cookie */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['form_cookie']) ? 1 : 0) . "' WHERE `title` = 'form_cookie'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['form_cookie_days'] . "' WHERE `title` = 'form_cookie_days'");

        /* Other */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['check_capitals_enabled']) ? 1 : 0) . "' WHERE `title` = 'check_capitals_enabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['check_capitals_percentage'] . "' WHERE `title` = 'check_capitals_percentage'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['check_capitals_action']) . "' WHERE `title` = 'check_capitals_action'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['check_repeats_enabled']) ? 1 : 0) . "' WHERE `title` = 'check_repeats_enabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['check_repeats_amount'] . "' WHERE `title` = 'check_repeats_amount'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['check_repeats_action']) . "' WHERE `title` = 'check_repeats_action'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['spam_words_enabled']) ? 1 : 0) . "' WHERE `title` = 'spam_words_enabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['spam_words_action']) . "' WHERE `title` = 'spam_words_action'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['mild_swear_words_enabled']) ? 1 : 0) . "' WHERE `title` = 'mild_swear_words_enabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['mild_swear_words_action']) . "' WHERE `title` = 'mild_swear_words_action'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['strong_swear_words_enabled']) ? 1 : 0) . "' WHERE `title` = 'strong_swear_words_enabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['strong_swear_words_action']) . "' WHERE `title` = 'strong_swear_words_action'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['swear_word_masking']) . "' WHERE `title` = 'swear_word_masking'");
    }
}
