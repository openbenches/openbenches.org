<?php
namespace Commentics;

class SettingsLayoutCommentsModel extends Model
{
    public function update($data)
    {
        /* General */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_comment_count']) ? 1 : 0) . "' WHERE `title` = 'show_comment_count'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['count_replies']) ? 1 : 0) . "' WHERE `title` = 'count_replies'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['comments_order']) . "' WHERE `title` = 'comments_order'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_says']) ? 1 : 0) . "' WHERE `title` = 'show_says'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['comments_position_1']) . "' WHERE `title` = 'comments_position_1'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['comments_position_2']) . "' WHERE `title` = 'comments_position_2'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['comments_position_3']) . "' WHERE `title` = 'comments_position_3'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['comments_position_4']) . "' WHERE `title` = 'comments_position_4'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['comments_position_5']) . "' WHERE `title` = 'comments_position_5'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['comments_position_6']) . "' WHERE `title` = 'comments_position_6'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['comments_position_7']) . "' WHERE `title` = 'comments_position_7'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['comments_position_8']) . "' WHERE `title` = 'comments_position_8'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['comments_position_9']) . "' WHERE `title` = 'comments_position_9'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['comments_position_10']) . "' WHERE `title` = 'comments_position_10'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['comments_position_11']) . "' WHERE `title` = 'comments_position_11'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['comments_position_12']) . "' WHERE `title` = 'comments_position_12'");

        /* Avatar */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['avatar_type']) . "' WHERE `title` = 'avatar_type'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['gravatar_default']) . "' WHERE `title` = 'gravatar_default'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['gravatar_custom']) . "' WHERE `title` = 'gravatar_custom'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['gravatar_size'] . "' WHERE `title` = 'gravatar_size'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['gravatar_audience']) . "' WHERE `title` = 'gravatar_audience'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['avatar_selection_attribution']) . "' WHERE `title` = 'avatar_selection_attribution'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['avatar_upload_min_posts'] . "' WHERE `title` = 'avatar_upload_min_posts'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['avatar_upload_min_days'] . "' WHERE `title` = 'avatar_upload_min_days'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (float) $data['avatar_upload_max_size'] . "' WHERE `title` = 'avatar_upload_max_size'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['avatar_upload_approve']) ? 1 : 0) . "' WHERE `title` = 'avatar_upload_approve'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['avatar_user_link']) ? 1 : 0) . "' WHERE `title` = 'avatar_user_link'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['avatar_link_days'] . "' WHERE `title` = 'avatar_link_days'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_level']) ? 1 : 0) . "' WHERE `title` = 'show_level'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['level_5'] . "' WHERE `title` = 'level_5'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['level_4'] . "' WHERE `title` = 'level_4'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['level_3'] . "' WHERE `title` = 'level_3'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['level_2'] . "' WHERE `title` = 'level_2'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['level_1'] . "' WHERE `title` = 'level_1'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['level_0'] . "' WHERE `title` = 'level_0'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_bio']) ? 1 : 0) . "' WHERE `title` = 'show_bio'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_badge_top_poster']) ? 1 : 0) . "' WHERE `title` = 'show_badge_top_poster'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_badge_most_likes']) ? 1 : 0) . "' WHERE `title` = 'show_badge_most_likes'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_badge_first_poster']) ? 1 : 0) . "' WHERE `title` = 'show_badge_first_poster'");

        /* Name */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_website']) ? 1 : 0) . "' WHERE `title` = 'show_website'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['website_new_window']) ? 1 : 0) . "' WHERE `title` = 'website_new_window'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['website_no_follow']) ? 1 : 0) . "' WHERE `title` = 'website_no_follow'");

        /* Town */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_town']) ? 1 : 0) . "' WHERE `title` = 'show_town'");

        /* State */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_state']) ? 1 : 0) . "' WHERE `title` = 'show_state'");

        /* Country */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_country']) ? 1 : 0) . "' WHERE `title` = 'show_country'");

        /* Headline */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_headline']) ? 1 : 0) . "' WHERE `title` = 'show_headline'");

        /* Rating */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_rating']) ? 1 : 0) . "' WHERE `title` = 'show_rating'");

        /* Date */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_date']) ? 1 : 0) . "' WHERE `title` = 'show_date'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['date_auto']) ? 1 : 0) . "' WHERE `title` = 'date_auto'");

        /* Like */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_like']) ? 1 : 0) . "' WHERE `title` = 'show_like'");

        /* Dislike */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_dislike']) ? 1 : 0) . "' WHERE `title` = 'show_dislike'");

        /* Share */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_share']) ? 1 : 0) . "' WHERE `title` = 'show_share'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['share_new_window']) ? 1 : 0) . "' WHERE `title` = 'share_new_window'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_share_digg']) ? 1 : 0) . "' WHERE `title` = 'show_share_digg'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_share_facebook']) ? 1 : 0) . "' WHERE `title` = 'show_share_facebook'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_share_linkedin']) ? 1 : 0) . "' WHERE `title` = 'show_share_linkedin'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_share_reddit']) ? 1 : 0) . "' WHERE `title` = 'show_share_reddit'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_share_twitter']) ? 1 : 0) . "' WHERE `title` = 'show_share_twitter'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_share_weibo']) ? 1 : 0) . "' WHERE `title` = 'show_share_weibo'");

        /* Flag */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_flag']) ? 1 : 0) . "' WHERE `title` = 'show_flag'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['flag_max_per_user'] . "' WHERE `title` = 'flag_max_per_user'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['flag_min_per_comment'] . "' WHERE `title` = 'flag_min_per_comment'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['flag_disapprove']) ? 1 : 0) . "' WHERE `title` = 'flag_disapprove'");

        /* Edit */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_edit']) ? 1 : 0) . "' WHERE `title` = 'show_edit'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['max_edits'] . "' WHERE `title` = 'max_edits'");

        /* Delete */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_delete']) ? 1 : 0) . "' WHERE `title` = 'show_delete'");

        /* Permalink */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_permalink']) ? 1 : 0) . "' WHERE `title` = 'show_permalink'");

        /* Reply */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_reply']) ? 1 : 0) . "' WHERE `title` = 'show_reply'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['quick_reply']) ? 1 : 0) . "' WHERE `title` = 'quick_reply'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['hide_replies']) ? 1 : 0) . "' WHERE `title` = 'hide_replies'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['reply_depth'] . "' WHERE `title` = 'reply_depth'");

        /* Average Rating */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_average_rating']) ? 1 : 0) . "' WHERE `title` = 'show_average_rating'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['average_rating_guest']) ? 1 : 0) . "' WHERE `title` = 'average_rating_guest'");

        /* Custom */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_custom']) ? 1 : 0) . "' WHERE `title` = 'show_custom'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['custom_content']) . "' WHERE `title` = 'custom_content'");

        /* Notify */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_notify']) ? 1 : 0) . "' WHERE `title` = 'show_notify'");

        /* Online */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_online']) ? 1 : 0) . "' WHERE `title` = 'show_online'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['online_refresh_enabled']) ? 1 : 0) . "' WHERE `title` = 'online_refresh_enabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['online_refresh_interval'] . "' WHERE `title` = 'online_refresh_interval'");

        /* Pagination */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_pagination']) ? 1 : 0) . "' WHERE `title` = 'show_pagination'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['pagination_type']) . "' WHERE `title` = 'pagination_type'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['pagination_amount'] . "' WHERE `title` = 'pagination_amount'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['pagination_range'] . "' WHERE `title` = 'pagination_range'");

        /* Page Number */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_page_number']) ? 1 : 0) . "' WHERE `title` = 'show_page_number'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['page_number_format']) . "' WHERE `title` = 'page_number_format'");

        /* RSS */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_rss']) ? 1 : 0) . "' WHERE `title` = 'show_rss'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['rss_new_window']) ? 1 : 0) . "' WHERE `title` = 'rss_new_window'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['rss_limit_enabled']) ? 1 : 0) . "' WHERE `title` = 'rss_limit_enabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['rss_limit_amount'] . "' WHERE `title` = 'rss_limit_amount'");

        /* Search */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_search']) ? 1 : 0) . "' WHERE `title` = 'show_search'");

        /* Social */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_social']) ? 1 : 0) . "' WHERE `title` = 'show_social'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['social_new_window']) ? 1 : 0) . "' WHERE `title` = 'social_new_window'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_social_digg']) ? 1 : 0) . "' WHERE `title` = 'show_social_digg'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_social_facebook']) ? 1 : 0) . "' WHERE `title` = 'show_social_facebook'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_social_linkedin']) ? 1 : 0) . "' WHERE `title` = 'show_social_linkedin'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_social_reddit']) ? 1 : 0) . "' WHERE `title` = 'show_social_reddit'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_social_twitter']) ? 1 : 0) . "' WHERE `title` = 'show_social_twitter'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_social_weibo']) ? 1 : 0) . "' WHERE `title` = 'show_social_weibo'");

        /* Sort By */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_sort_by']) ? 1 : 0) . "' WHERE `title` = 'show_sort_by'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_sort_by_1']) ? 1 : 0) . "' WHERE `title` = 'show_sort_by_1'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_sort_by_2']) ? 1 : 0) . "' WHERE `title` = 'show_sort_by_2'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_sort_by_3']) ? 1 : 0) . "' WHERE `title` = 'show_sort_by_3'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_sort_by_4']) ? 1 : 0) . "' WHERE `title` = 'show_sort_by_4'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_sort_by_5']) ? 1 : 0) . "' WHERE `title` = 'show_sort_by_5'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_sort_by_6']) ? 1 : 0) . "' WHERE `title` = 'show_sort_by_6'");

        /* Topic */

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['show_topic']) ? 1 : 0) . "' WHERE `title` = 'show_topic'");
    }

    public function getShares()
    {
        $shares = array();

        $shares['digg'] = $this->getShareImage('digg.png');

        $shares['facebook'] = $this->getShareImage('facebook.png');

        $shares['linkedin'] = $this->getShareImage('linkedin.png');

        $shares['reddit'] = $this->getShareImage('reddit.png');

        $shares['twitter'] = $this->getShareImage('twitter.png');

        $shares['weibo'] = $this->getShareImage('weibo.png');

        return $shares;
    }

    private function getShareImage($cmtx_image)
    {
        if (file_exists(CMTX_HTTP_ROOT . 'frontend/view/' . $this->setting->get('theme_frontend') . '/image/share/' . strtolower($cmtx_image))) {
            return CMTX_HTTP_ROOT . 'frontend/view/' . $this->setting->get('theme_frontend') . '/image/share/' . strtolower($cmtx_image);
        } else if (file_exists(CMTX_HTTP_ROOT . 'frontend/view/default/image/share/' . strtolower($cmtx_image))) {
            return CMTX_HTTP_ROOT . 'frontend/view/default/image/share/' . strtolower($cmtx_image);
        } else {
            die('<b>Error</b>: Could not load image ' . strtolower($cmtx_image) . '!');
        }
    }

    public function getSocials()
    {
        $socials = array();

        $socials['digg'] = $this->getSocialImage('digg.png');

        $socials['facebook'] = $this->getSocialImage('facebook.png');

        $socials['linkedin'] = $this->getSocialImage('linkedin.png');

        $socials['reddit'] = $this->getSocialImage('reddit.png');

        $socials['twitter'] = $this->getSocialImage('twitter.png');

        $socials['weibo'] = $this->getSocialImage('weibo.png');

        return $socials;
    }

    private function getSocialImage($cmtx_image)
    {
        if (file_exists(CMTX_HTTP_ROOT . 'frontend/view/' . $this->setting->get('theme_frontend') . '/image/social/' . strtolower($cmtx_image))) {
            return CMTX_HTTP_ROOT . 'frontend/view/' . $this->setting->get('theme_frontend') . '/image/social/' . strtolower($cmtx_image);
        } else if (file_exists(CMTX_HTTP_ROOT . 'frontend/view/default/image/social/' . strtolower($cmtx_image))) {
            return CMTX_HTTP_ROOT . 'frontend/view/default/image/social/' . strtolower($cmtx_image);
        } else {
            die('<b>Error</b>: Could not load image ' . strtolower($cmtx_image) . '!');
        }
    }

    public function checkLayoutSettings()
    {
        $this->setting->refresh();

        $layout_settings_enabled = $layout_settings_disabled = array();

        if ($this->setting->get('show_headline') && !$this->setting->get('enabled_headline')) {
            $layout_settings_enabled[] = $this->loadWord('settings/layout_comments', 'lang_subheading_headline');
        } else if (!$this->setting->get('show_headline') && $this->setting->get('enabled_headline')) {
            $layout_settings_disabled[] = $this->loadWord('settings/layout_comments', 'lang_subheading_headline');
        }

        if ($this->setting->get('show_rating') && !$this->setting->get('enabled_rating')) {
            $layout_settings_enabled[] = $this->loadWord('settings/layout_comments', 'lang_subheading_rating');
        } else if (!$this->setting->get('show_rating') && $this->setting->get('enabled_rating')) {
            $layout_settings_disabled[] = $this->loadWord('settings/layout_comments', 'lang_subheading_rating');
        }

        if ($this->setting->get('show_website') && !$this->setting->get('enabled_website')) {
            $layout_settings_enabled[] = $this->loadWord('settings/layout_comments', 'lang_subheading_website');
        } else if (!$this->setting->get('show_website') && $this->setting->get('enabled_website')) {
            $layout_settings_disabled[] = $this->loadWord('settings/layout_comments', 'lang_subheading_website');
        }

        if ($this->setting->get('show_town') && !$this->setting->get('enabled_town')) {
            $layout_settings_enabled[] = $this->loadWord('settings/layout_comments', 'lang_subheading_town');
        } else if (!$this->setting->get('show_town') && $this->setting->get('enabled_town')) {
            $layout_settings_disabled[] = $this->loadWord('settings/layout_comments', 'lang_subheading_town');
        }

        if ($this->setting->get('show_state') && !$this->setting->get('enabled_state')) {
            $layout_settings_enabled[] = $this->loadWord('settings/layout_comments', 'lang_subheading_state');
        } else if (!$this->setting->get('show_state') && $this->setting->get('enabled_state')) {
            $layout_settings_disabled[] = $this->loadWord('settings/layout_comments', 'lang_subheading_state');
        }

        if ($this->setting->get('show_country') && !$this->setting->get('enabled_country')) {
            $layout_settings_enabled[] = $this->loadWord('settings/layout_comments', 'lang_subheading_country');
        } else if (!$this->setting->get('show_country') && $this->setting->get('enabled_country')) {
            $layout_settings_disabled[] = $this->loadWord('settings/layout_comments', 'lang_subheading_country');
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
