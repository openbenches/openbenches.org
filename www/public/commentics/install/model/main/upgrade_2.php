<?php
namespace Commentics;

class MainUpgrade2Model extends Model
{
    public function upgrade($version)
    {
        $this->loadModel('main/install_2');

        if ($version == '3.0 -> 3.1') {
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'hide_replies', `value` = '1'");
        }

        if ($version == '3.1 -> 3.2') {
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'security', `title` = 'check_csrf', `value` = '1'");
        }

        if ($version == '3.2 -> 3.3') {
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'licence', `title` = 'licence', `value` = ''");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'licence', `title` = 'forum_user', `value` = ''");
            $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '1' WHERE `title` = 'enabled_powered_by'");

            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_badge_top_poster', `value` = '1'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_badge_most_likes', `value` = '1'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_badge_first_poster', `value` = '1'");

            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_email', `value` = '1'");

            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'unique_name_enabled', `value` = '0'");

            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'check_csrf'");

            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'mysqldump_path'");

            if (file_exists(CMTX_DIR_ROOT . 'frontend/php.ini')) {
                @unlink(CMTX_DIR_ROOT . 'frontend/php.ini');
            }
        }

        if ($version == '3.3 -> 3.4') {
            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'colorbox_source'");

            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'font_awesome_source'");

            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'notice', `title` = 'notice_tool_upgrade', `value` = '1'");

            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'system', `title` = 'last_task', `value` = ''");

            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'system', `title` = 'new_version_notified', `value` = '0'");

            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'theme', `title` = 'auto_detect', `value` = '0'");

            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'theme', `title` = 'optimize', `value` = '1'");

            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'pagination_type', `value` = 'multiple'");

            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'show_social_google'");

            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'show_social_stumbleupon'");

            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_social_weibo', `value` = '0'");

            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'show_share_google'");

            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'show_share_stumbleupon'");

            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'reply_indent'");

            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_share_weibo', `value` = '0'");

            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "emails` SET `type` = 'new_version', `subject` = 'New Version', `from_name` = '" . $this->db->escape($this->setting->get('site_name')) . "', `from_email` = 'comments@" . $this->db->escape($this->setting->get('site_domain')) . "', `reply_to` = 'no-reply@" . $this->db->escape($this->setting->get('site_domain')) . "', `text` = 'Hello [username],\r\n\r\nA newer version of Commentics is available.\r\n\r\nYour installed version is [installed version]. The newest version is [newest version].\r\n\r\nPlease upgrade at your earliest convenience.\r\n\r\nHere is the link to your admin panel:\r\n[admin link]\r\n\r\nRegards,\r\n\r\n[signature]', `html` = '<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td>Hello [username],</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">A newer version of Commentics is available.</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Your installed version is [installed version]. The newest version is [newest version].</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Please upgrade at your earliest convenience.</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Here is the link to your admin panel:</td>\r\n</tr>\r\n<tr>\r\n<td><a href=\"[admin link]\">[admin link]</a></td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Regards,</td>\r\n</tr>\r\n</table>\r\n\r\n[signature]', `language` = 'english', `date_modified` = NOW()");

            $this->model_main_install_2->createTableGeo();

            $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "countries`");

            $countries = $this->db->rows($query);

            foreach ($countries as $country) {
                $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = '" . $country['name'] . "', `country_code` = '" . $country['code'] . "', `language` = 'english', `date_added` = NOW()");
            }

            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "countries` DROP `name`");
        }

        if ($version == '3.4 -> 4.0') {
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'maximum_upload_total', `value` = '5'");

            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'jquery_ui_source'");

            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'notice_settings_admin_detection'");

            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'recaptcha_type'");
            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'recaptcha_language'");

            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'cache', `title` = 'cache_type', `value` = ''");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'cache', `title` = 'cache_time', `value` = '86400'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'cache', `title` = 'cache_host', `value` = '127.0.0.1'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'cache', `title` = 'cache_port', `value` = '11211'");

            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'notice', `title` = 'notice_layout_comments_online', `value` = '1'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'notice', `title` = 'notice_settings_viewers', `value` = '1'");

            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "admins` DROP `detect_admin`");
            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "admins` DROP `detect_method`");

            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "viewers` ADD `page_id` int(10) unsigned NOT NULL default '0'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_online', `value` = '1'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'online_refresh_enabled', `value` = '0'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'online_refresh_interval', `value` = '60'");

            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'viewers_refresh'");
            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'viewers_interval'");

            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "pages` ADD `site_id` int(10) unsigned NOT NULL default '1'");

            $query = $this->db->query("SELECT `value` FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'site_name'");
            $result = $this->db->row($query);
            $site_name = $result['value'];

            $query = $this->db->query("SELECT `value` FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'site_domain'");
            $result = $this->db->row($query);
            $site_domain = $result['value'];

            $query = $this->db->query("SELECT `value` FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'site_url'");
            $result = $this->db->row($query);
            $site_url = $result['value'];

            $this->model_main_install_2->createTableSites($site_name, $site_domain, $site_url);

            $this->model_main_install_2->createTableGeo();

            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'rss_title'");
            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'rss_link'");
            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'rss_image_enabled'");
            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'rss_image_url'");
            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'rss_image_width'");
            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'rss_image_height'");

            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'scroll_reply'");
            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'scroll_speed'");

            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'show_read_more'");
            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'read_more_limit'");

            $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "emails` WHERE `type` = 'comment_success'");
            $result = $this->db->row($query);
            $from_name = $result['from_name'];
            $from_email = $result['from_email'];
            $reply_to = $result['reply_to'];

            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'email', `title` = 'from_name', `value` = '" . $this->db->escape($from_name) . "'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'email', `title` = 'from_email', `value` = '" . $this->db->escape($from_email) . "'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'email', `title` = 'reply_email', `value` = '" . $this->db->escape($reply_to) . "'");

            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "emails` DROP `from_name`");
            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "emails` DROP `from_email`");
            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "emails` DROP `reply_to`");

            /* Move any XML files from /system/modification/xml/ to /system/modification/ */
            if (file_exists(CMTX_DIR_MODIFICATION . 'xml/')) {
                $files = glob(CMTX_DIR_MODIFICATION . 'xml/*.xml');

                if ($files) {
                    foreach ($files as $file) {
                        @rename($file, CMTX_DIR_MODIFICATION . pathinfo($file, PATHINFO_BASENAME));
                    }
                }
            }

            /* Delete /system/modification/ folders */
            remove_directory(CMTX_DIR_MODIFICATION . 'cache/');
            remove_directory(CMTX_DIR_MODIFICATION . 'xml/');

            remove_directory(CMTX_DIR_3RDPARTY . 'filer/');
            remove_directory(CMTX_DIR_3RDPARTY . 'read_more/');

            remove_directory(CMTX_DIR_SYSTEM . 'database/');

            @unlink(CMTX_DIR_ROOT . 'frontend/view/default/javascript/common-jqui.min.js');
            @unlink(CMTX_DIR_ROOT . 'frontend/view/default/javascript/common-jq-jqui.min.js');
            @unlink(CMTX_DIR_ROOT . 'frontend/view/default/javascript/jquery/jquery-ui.min.js');
            @unlink(CMTX_DIR_ROOT . 'frontend/view/default/stylesheet/sass/partial/_jfiler.scss');

            $backend_folder = $this->getBackendFolder();
            @unlink(CMTX_DIR_ROOT . $backend_folder . '/controller/layout_comments/comment.php');
            @unlink(CMTX_DIR_ROOT . $backend_folder . '/controller/settings/admin_detection.php');
            @unlink(CMTX_DIR_ROOT . $backend_folder . '/model/layout_comments/comment.php');
            @unlink(CMTX_DIR_ROOT . $backend_folder . '/model/settings/admin_detection.php');
            @unlink(CMTX_DIR_ROOT . $backend_folder . '/view/default/language/english/layout_comments/comment.php');
            @unlink(CMTX_DIR_ROOT . $backend_folder . '/view/default/language/english/settings/admin_detection.php');
            @unlink(CMTX_DIR_ROOT . $backend_folder . '/view/default/template/layout_comments/comment.tpl');
            @unlink(CMTX_DIR_ROOT . $backend_folder . '/view/default/template/settings/admin_detection.tpl');

            @unlink(CMTX_DIR_LIBRARY . 'db.php');
            @unlink(CMTX_DIR_CACHE . 'common_footer.tpl');
            @unlink(CMTX_DIR_CACHE . 'common_header.tpl');
            @unlink(CMTX_DIR_CACHE . 'main_comment.tpl');
            @unlink(CMTX_DIR_CACHE . 'main_comments.tpl');
            @unlink(CMTX_DIR_CACHE . 'main_form.tpl');
            @unlink(CMTX_DIR_CACHE . 'main_page.tpl');
            @unlink(CMTX_DIR_CACHE . 'main_user.tpl');
            @unlink(CMTX_DIR_CACHE . 'part_average_rating.tpl');
            @unlink(CMTX_DIR_CACHE . 'part_notify.tpl');
            @unlink(CMTX_DIR_CACHE . 'part_page_number.tpl');
            @unlink(CMTX_DIR_CACHE . 'part_pagination.tpl');
            @unlink(CMTX_DIR_CACHE . 'part_rss.tpl');
            @unlink(CMTX_DIR_CACHE . 'part_search.tpl');
            @unlink(CMTX_DIR_CACHE . 'part_social.tpl');
            @unlink(CMTX_DIR_CACHE . 'part_sort_by.tpl');
            @unlink(CMTX_DIR_CACHE . 'part_topic.tpl');
        }

        if ($version == '4.0 -> 4.1') {
            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'notice_layout_comments_online'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'notice', `title` = 'notice_manage_countries', `value` = '1'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'notice', `title` = 'notice_add_question', `value` = '1'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'notice', `title` = 'notice_edit_question', `value` = '1'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'system', `title` = 'purpose', `value` = 'comment'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'average_rating_guest', `value` = '1'");

            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "data` WHERE `type` = 'admin_tips'");

            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "comments` ADD `headline` varchar(250) NOT NULL default ''");

            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_headline', `value` = '0'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'default_headline', `value` = ''");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'required_headline', `value` = '1'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_headline', `value` = '0'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'headline_minimum_characters', `value` = '2'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'headline_minimum_words', `value` = '1'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'headline_maximum_characters', `value` = '50'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'detect_link_in_headline_enabled', `value` = '1'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'link_in_headline_action', `value` = 'approve'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'banned_websites_as_headline_enabled', `value` = '1'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'banned_websites_as_headline_action', `value` = 'approve'");

            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'admin_panel', `title` = 'admin_detect', `value` = '0'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'admin_panel', `title` = 'system_detect', `value` = '1'");

            $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `title` = 'gravatar_audience' WHERE `title` = 'gravatar_rating'");

            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_custom', `value` = '0'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'custom_content', `value` = ''");

            $backend_folder = $this->getBackendFolder();
            remove_directory(CMTX_DIR_ROOT . $backend_folder . '/controller/layout_comments/');
            remove_directory(CMTX_DIR_ROOT . $backend_folder . '/controller/layout_form/');
            remove_directory(CMTX_DIR_ROOT . $backend_folder . '/model/layout_comments/');
            remove_directory(CMTX_DIR_ROOT . $backend_folder . '/model/layout_form/');
            remove_directory(CMTX_DIR_ROOT . $backend_folder . '/view/default/language/english/layout_comments/');
            remove_directory(CMTX_DIR_ROOT . $backend_folder . '/view/default/language/english/layout_form/');
            remove_directory(CMTX_DIR_ROOT . $backend_folder . '/view/default/template/layout_comments/');
            remove_directory(CMTX_DIR_ROOT . $backend_folder . '/view/default/template/layout_form/');
        }

        if ($version == '4.1 -> 4.2') {
            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'notify_approve'");

            if ($this->setting->get('show_gravatar')) {
                $avatar_type = 'gravatar';
            } else {
                $avatar_type = '';
            }

            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'avatar_type', `value` = '" . $this->db->escape($avatar_type) . "'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'avatar_selection_attribution', `value` = ''");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'avatar_upload_min_posts', `value` = '0'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'avatar_upload_min_days', `value` = '0'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'avatar_upload_max_size', `value` = '0.3'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'avatar_upload_approve', `value` = '1'");
            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'show_gravatar'");

            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'admin_panel', `title` = 'layout_detect', `value` = '1'");

            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'avatar_user_link', `value` = '1'");

            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'email', `title` = 'smtp_timeout', `value` = '5'");
            $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = 'php' WHERE `title` = 'transport_method' AND (`value` = 'php-basic' OR `value` = 'sendmail')");
            $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = 'SSL' WHERE `title` = 'smtp_encrypt' AND `value` = 'off'");
            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'sendmail_path'");

            remove_directory(CMTX_DIR_3RDPARTY . 'swift_mailer/');

            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "users` CHANGE `token` `token` varchar(250) NOT NULL default ''");
            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "users` ADD `avatar_id` int(10) unsigned NOT NULL default '0'");
            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "users` ADD `avatar_pending_id` int(10) unsigned NOT NULL default '0'");
            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "users` ADD `avatar_selected` varchar(250) NOT NULL default ''");

            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "uploads` CHANGE `folder` `folder` varchar(250) NOT NULL default ''");

            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "subscriptions` CHANGE `token` `token` varchar(250) NOT NULL default ''");

            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "attempts` ADD `type` varchar(250) NOT NULL default ''");

            if ($this->db->numRows($this->db->query("SELECT `module` FROM `" . CMTX_DB_PREFIX . "modules` WHERE `module` = 'rich_snippets'"))) {
                $this->db->query("CREATE TABLE IF NOT EXISTS `" . CMTX_DB_PREFIX . "rich_snippets_properties` (
                    `id` int(10) unsigned NOT NULL auto_increment,
                    `name` varchar(250) NOT NULL default '',
                    `value` varchar(250) NOT NULL default '',
                    PRIMARY KEY (`id`)
                ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            }

            /* Convert the database to utf8mb4 to allow for all characters */
            $this->db->query("ALTER DATABASE `" . CMTX_DB_DATABASE . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

            /* Convert the tables (and all applicable columns) */
            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "access` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "admins` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "attempts` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "backups` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "bans` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "comments` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "countries` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "data` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "emails` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "geo` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "logins` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "modules` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "pages` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "questions` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "ratings` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "reporters` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "settings` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "sites` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "states` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "subscriptions` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "uploads` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "users` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "version` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "viewers` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "voters` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        }

        if ($version == '4.2 -> 4.3') {
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'language', `title` = 'rtl', `value` = '0'");

            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'avatar_link_days', `value` = '30'");

            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "users` ADD `avatar_login` varchar(250) NOT NULL default ''");

            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'order_fields', `value` = 'comment,headline,upload,rating,user,website,geo,question'");

            $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = 'form,comments' WHERE `title` = 'order_parts' AND `value` = '1,2'");
            $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = 'comments,form' WHERE `title` = 'order_parts' AND `value` = '2,1'");

            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'notice', `title` = 'notice_extra_fields', `value` = '1'");
        }

        if ($version == '4.3 -> 4.4') {
            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` LIKE 'securimage%'");
            $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = 'image' WHERE `title` = 'captcha_type' AND `value` = 'securimage'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'captcha_width', `value` = '215'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'captcha_height', `value` = '80'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'captcha_length', `value` = '6'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'captcha_lines', `value` = '3'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'captcha_circles', `value` = '3'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'captcha_squares', `value` = '2'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'captcha_dots', `value` = '20'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'captcha_text_color', `value` = '#616161'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'captcha_back_color', `value` = '#FFFFFF'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'captcha_line_color', `value` = '#00FF00'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'captcha_circle_color', `value` = '#FF0000'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'captcha_square_color', `value` = '#0000FF'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'captcha_dots_color', `value` = '#616161'");

            remove_directory(CMTX_DIR_3RDPARTY . 'securimage/');

            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'warning', `title` = 'warning_manage_pages', `value` = '1'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'admin_panel', `title` = 'empty_pages', `value` = '0'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'admin_panel', `title` = 'version_detect', `value` = '1'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'count_replies', `value` = '0'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_edit', `value` = '0'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_delete', `value` = '0'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'unique_email_enabled', `value` = '1'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'quick_reply', `value` = '0'");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'max_edits', `value` = '3'");

            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "comments` ADD `original_comment` text NOT NULL");
            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "comments` ADD `number_edits` int(10) unsigned NOT NULL default '0'");
            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "comments` ADD `session_id` varchar(250) NOT NULL default ''");

            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "admins` ADD `receive_email_edit` tinyint(1) unsigned NOT NULL default '1'");
            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "admins` ADD `receive_email_delete` tinyint(1) unsigned NOT NULL default '1'");

            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "emails` SET `type` = 'edit', `subject` = '[username], a comment was edited!', `text` = 'Hello [username],\r\n\r\nA comment has been edited on the page [page reference], located at the following address:\r\n[comment url]\r\n\r\nThe edited comment was made by [poster] and was as follows:\r\n\r\n************************\r\n\r\n[comment]\r\n\r\n************************\r\n\r\nHere is the link to your admin panel:\r\n[admin link]\r\n\r\nRegards,\r\n\r\n[signature]', `html` = '<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td>Hello [username],</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">A comment has been edited on the page [page reference], located at the following address:</td>\r\n</tr>\r\n<tr>\r\n<td><a href=\"[comment url]\">[comment url]</a></td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">The edited comment was made by [poster] and was as follows:</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding-top:20px\">************************</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding-top:10px\">[comment]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding-top:20px\">************************</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Here is the link to your admin panel:</td>\r\n</tr>\r\n<tr>\r\n<td><a href=\"[admin link]\">[admin link]</a></td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Regards,</td>\r\n</tr>\r\n</table>\r\n\r\n[signature]', `language` = 'english', `date_modified` = NOW()");
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "emails` SET `type` = 'delete', `subject` = '[username], a comment was deleted!', `text` = 'Hello [username],\r\n\r\nA comment has been deleted on the page [page reference], located at the following address:\r\n[comment url]\r\n\r\nThe deleted comment was made by [poster] and was as follows:\r\n\r\n************************\r\n\r\n[comment]\r\n\r\n************************\r\n\r\nHere is the link to your admin panel:\r\n[admin link]\r\n\r\nRegards,\r\n\r\n[signature]', `html` = '<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td>Hello [username],</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">A comment has been deleted on the page [page reference], located at the following address:</td>\r\n</tr>\r\n<tr>\r\n<td><a href=\"[comment url]\">[comment url]</a></td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">The deleted comment was made by [poster] and was as follows:</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding-top:20px\">************************</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding-top:10px\">[comment]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding-top:20px\">************************</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Here is the link to your admin panel:</td>\r\n</tr>\r\n<tr>\r\n<td><a href=\"[admin link]\">[admin link]</a></td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Regards,</td>\r\n</tr>\r\n</table>\r\n\r\n[signature]', `language` = 'english', `date_modified` = NOW()");

            $this->model_main_install_2->createTableDeleted();
        }
    }

    public function getInstalledVersion()
    {
        $query = $this->db->query("SELECT `version` FROM `" . CMTX_DB_PREFIX . "version` ORDER BY `date_added` DESC LIMIT 1");

        $result = $this->db->row($query);

        return $result['version'];
    }

    public function setVersion()
    {
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "version` SET `version` = '" . $this->db->escape(CMTX_VERSION) . "', `type` = 'Upgrade', `date_added` = NOW()");

        if (version_compare(CMTX_VERSION, 3.4, '>')) {
            $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '0' WHERE `title` = 'new_version_notified'");
        }
    }

    public function getBackendFolder()
    {
        $query = $this->db->query("SELECT `value` FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'backend_folder'");

        $result = $this->db->row($query);

        return $result['value'];
    }
}
