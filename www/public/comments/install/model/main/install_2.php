<?php
namespace Commentics;

class MainInstall2Model extends Model
{
    public function install()
    {
        $username          = $this->request->post['username'];
        $password          = password_hash($this->request->post['password_1'], PASSWORD_DEFAULT);
        $email             = $this->request->post['email'];
        $site_id           = $this->variable->random();
        $site_name         = $this->request->post['site_name'];
        $time_zone         = $this->request->post['time_zone'];
        $ssl_certificate   = $this->request->post['ssl_certificate'];
        $purpose           = $this->request->post['purpose'];
        $ip_address        = $this->user->getIpAddress();
        $cookie_key        = $this->variable->random();
        $security_key      = $this->variable->random();
        $session_key       = $this->variable->random();
        $encryption_key    = $this->variable->random();
        $site_domain       = str_ireplace('www.', '', parse_url($this->url->decode($this->url->getPageUrl()), PHP_URL_HOST));
        $site_url          = 'http' . ($this->url->isHttps() ? 's' : '') . '://' . parse_url($this->url->decode($this->url->getPageUrl()), PHP_URL_HOST);
        $commentics_folder = $this->getCommenticsFolder();
        $commentics_url    = str_ireplace('install/index.php?route=install_2', '', $this->url->decode($this->url->getPageUrl()));
        $backend_folder    = $this->getBackendFolder();
        $version           = CMTX_VERSION;

        $this->db->query("ALTER DATABASE `" . CMTX_DB_DATABASE . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        $this->createTableAccess();
        $this->createTableAdmins($username, $password, $email, $ip_address, $cookie_key);
        $this->createTableAttempts();
        $this->createTableBackups();
        $this->createTableBans();
        $this->createTableComments();
        $this->createTableCountries();
        $this->createTableData();
        $this->createTableDeleted();
        $this->createTableEmails();
        $this->createTableGeo();
        $this->createTableLogins();
        $this->createTableModules();
        $this->createTablePages();
        $this->createTableQuestions();
        $this->createTableRatings();
        $this->createTableReporters();
        $this->createTableSettings($site_name, $site_domain, $site_url, $security_key, $session_key, $encryption_key, $site_id, $time_zone, $commentics_folder, $commentics_url, $backend_folder, $ssl_certificate, $purpose);
        $this->createTableSites($site_name, $site_domain, $site_url);
        $this->createTableStates();
        $this->createTableSubscriptions();
        $this->createTableUploads();
        $this->createTableUsers();
        $this->createTableVersion($version);
        $this->createTableViewers();
        $this->createTableVoters();

        if ($purpose != 'comment') {
            $this->email->changePurpose('comment', $purpose);
        }
    }

    public function createTableAccess()
    {
        /********************************************** CREATE TABLE 'access' ********************************************/
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . CMTX_DB_PREFIX . "access` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `username` varchar(250) NOT NULL default '',
            `ip_address` varchar(250) NOT NULL default '',
            `page` varchar(250) NOT NULL default '',
            `date_added` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        /*****************************************************************************************************************/
    }

    public function createTableAdmins($username, $password, $email, $ip_address, $cookie_key)
    {
        /********************************************** CREATE TABLE 'admins' ********************************************/
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . CMTX_DB_PREFIX . "admins` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `username` varchar(250) NOT NULL default '',
            `password` varchar(250) NOT NULL default '',
            `email` varchar(250) NOT NULL default '',
            `ip_address` varchar(250) NOT NULL default '',
            `cookie_key` varchar(250) NOT NULL default '',
            `receive_email_ban` tinyint(1) unsigned NOT NULL default '1',
            `receive_email_comment_approve` tinyint(1) unsigned NOT NULL default '1',
            `receive_email_comment_success` tinyint(1) unsigned NOT NULL default '1',
            `receive_email_flag` tinyint(1) unsigned NOT NULL default '1',
            `receive_email_edit` tinyint(1) unsigned NOT NULL default '1',
            `receive_email_delete` tinyint(1) unsigned NOT NULL default '1',
            `login_attempts` tinyint(1) unsigned NOT NULL default '0',
            `resets` tinyint(1) unsigned NOT NULL default '0',
            `last_login` datetime NOT NULL,
            `restrict_pages` tinyint(1) unsigned NOT NULL default '0',
            `viewable_pages` text NOT NULL,
            `modifiable_pages` text NOT NULL,
            `format` varchar(250) NOT NULL default 'html',
            `is_super` tinyint(1) unsigned NOT NULL default '0',
            `is_enabled` tinyint(1) unsigned NOT NULL default '1',
            `date_modified` datetime NOT NULL,
            `date_added` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "admins` SET `username` = '" . $this->db->escape($username) . "', `password` = '" . $this->db->escape($password) . "', `email` = '" . $this->db->escape($email) . "', `ip_address` = '" . $this->db->escape($ip_address) . "', `cookie_key` = '" . $this->db->escape($cookie_key) . "', `receive_email_ban` = '1', `receive_email_comment_approve` = '1', `receive_email_comment_success` = '1', `receive_email_flag` = '1', `login_attempts` = '0', `resets` = '0', `last_login` = NOW(), `restrict_pages` = '0', `viewable_pages` = '', `modifiable_pages` = '', `format` = 'html', `is_super` = '1', `is_enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        /*****************************************************************************************************************/
    }

    public function createTableAttempts()
    {
        /********************************************** CREATE TABLE 'attempts' ******************************************/
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . CMTX_DB_PREFIX . "attempts` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `type` varchar(250) NOT NULL default '',
            `ip_address` varchar(250) NOT NULL default '',
            `amount` int(10) unsigned NOT NULL default '0',
            `date_added` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        /*****************************************************************************************************************/
    }

    public function createTableBackups()
    {
        /********************************************** CREATE TABLE 'backups' *******************************************/
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . CMTX_DB_PREFIX . "backups` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `description` varchar(250) NOT NULL default '',
            `filename` varchar(250) NOT NULL default '',
            `size` int(10) unsigned NOT NULL default '0',
            `date_added` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        /*****************************************************************************************************************/
    }

    public function createTableBans()
    {
        /********************************************** CREATE TABLE 'bans' **********************************************/
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . CMTX_DB_PREFIX . "bans` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `ip_address` varchar(250) NOT NULL default '',
            `reason` varchar(250) NOT NULL default '',
            `unban` tinyint(1) unsigned NOT NULL default '0',
            `date_modified` datetime NOT NULL,
            `date_added` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        /*****************************************************************************************************************/
    }

    public function createTableComments()
    {
        /********************************************** CREATE TABLE 'comments' ******************************************/
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . CMTX_DB_PREFIX . "comments` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `user_id` int(10) unsigned NOT NULL default '0',
            `page_id` int(10) unsigned NOT NULL default '0',
            `website` varchar(250) NOT NULL default '',
            `town` varchar(250) NOT NULL default '',
            `state_id` int(10) NOT NULL default '0',
            `country_id` int(10) NOT NULL default '0',
            `rating` tinyint(1) unsigned NOT NULL default '0',
            `reply_to` int(10) unsigned NOT NULL default '0',
            `headline` varchar(250) NOT NULL default '',
            `original_comment` text NOT NULL,
            `comment` text NOT NULL,
            `reply` text NOT NULL,
            `ip_address` varchar(250) NOT NULL default '',
            `is_approved` tinyint(1) unsigned NOT NULL default '1',
            `notes` text NOT NULL,
            `is_admin` tinyint(1) unsigned NOT NULL default '0',
            `is_sent` tinyint(1) unsigned NOT NULL default '0',
            `sent_to` int(10) unsigned NOT NULL default '0',
            `likes` int(10) unsigned NOT NULL default '0',
            `dislikes` int(10) unsigned NOT NULL default '0',
            `reports` int(10) unsigned NOT NULL default '0',
            `is_sticky` tinyint(1) unsigned NOT NULL default '0',
            `is_locked` tinyint(1) unsigned NOT NULL default '0',
            `is_verified` tinyint(1) unsigned NOT NULL default '0',
            `number_edits` int(10) unsigned NOT NULL default '0',
            `session_id` varchar(250) NOT NULL default '',
            `date_modified` datetime NOT NULL,
            `date_added` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        /*****************************************************************************************************************/
    }

    public function createTableCountries()
    {
        /********************************************** CREATE TABLE 'countries' *****************************************/
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . CMTX_DB_PREFIX . "countries` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `code` varchar(3) NOT NULL default '',
            `top` tinyint(1) unsigned NOT NULL default '0',
            `enabled` tinyint(1) unsigned NOT NULL default '1',
            `date_modified` datetime NOT NULL,
            `date_added` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'AFG', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'ALB', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'DZA', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'AND', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'AGO', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'ATG', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'ARG', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'ARM', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'AUS', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'AUT', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'AZE', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'BHS', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'BHR', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'BGD', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'BRB', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'BLR', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'BEL', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'BLZ', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'BEN', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'BTN', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'BOL', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'BIH', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'BWA', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'BRA', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'BRN', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'BGR', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'BFA', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'BDI', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'KHM', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'CMR', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'CAN', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'CPV', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'CAF', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'TCD', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'CHL', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'CHN', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'COL', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'COM', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'COG', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'COD', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'CRI', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'HRV', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'CUB', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'CYP', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'CZE', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'DNK', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'DJI', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'DMA', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'DOM', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'TLS', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'ECU', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'EGY', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'SLV', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'GNQ', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'ERI', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'EST', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'SWZ', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'ETH', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'FJI', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'FIN', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'FRA', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'GAB', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'GMB', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'GEO', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'DEU', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'GHA', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'GRC', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'GRD', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'GTM', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'GIN', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'GNB', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'GUY', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'HTI', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'HND', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'HKG', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'HUN', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'ISL', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'IND', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'IDN', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'IRN', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'IRQ', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'IRL', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'ISR', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'ITA', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'CIV', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'JAM', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'JPN', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'JOR', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'KAZ', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'KEN', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'KIR', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'UNK', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'KWT', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'KGZ', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'LAO', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'LVA', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'LBN', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'LSO', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'LBR', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'LBY', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'LIE', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'LTU', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'LUX', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'MDG', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'MWI', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'MYS', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'MDV', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'MLI', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'MLT', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'MHL', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'MRT', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'MUS', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'MEX', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'FSM', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'MDA', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'MCO', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'MNG', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'MNE', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'MAR', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'MOZ', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'MMR', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'NAM', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'NRU', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'NPL', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'NLD', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'NZL', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'NIC', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'NER', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'NGA', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'PRK', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'MKD', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'NOR', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'OMN', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'OST', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'PAK', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'PLW', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'PSE', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'PAN', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'PNG', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'PRY', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'PER', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'PHL', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'POL', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'PRT', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'QAT', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'ROM', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'RUS', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'RWA', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'KNA', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'LCA', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'VCT', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'WSM', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'SMR', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'STP', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'SAU', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'SEN', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'SRB', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'SYC', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'SLE', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'SGP', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'SVK', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'SVN', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'SLB', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'SOM', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'ZAF', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'KOR', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'SSD', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'ESP', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'LKA', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'SDN', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'SUR', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'SWE', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'CHE', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'SYR', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'TJK', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'TZA', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'THA', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'TGO', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'TON', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'TTO', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'TUN', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'TUR', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'TKM', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'TUV', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'UGA', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'UKR', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'ARE', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'GBR', `top` = '1', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'USA', `top` = '1', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'URY', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'UZB', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'VUT', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'VAT', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'VEN', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'VNM', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'YEM', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'ZMB', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = 'ZWE', `top` = '0', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        /*****************************************************************************************************************/
    }

    public function createTableData()
    {
        /********************************************** CREATE TABLE 'data' **********************************************/
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . CMTX_DB_PREFIX . "data` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `type` varchar(250) NOT NULL default '',
            `text` text NOT NULL,
            `modified_by` varchar(250) NOT NULL default '',
            `date_modified` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "data` SET `type` = 'admin_notes', `text` = '', `modified_by` = '', `date_modified` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "data` SET `type` = 'banned_emails', `text` = '', `modified_by` = '', `date_modified` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "data` SET `type` = 'banned_names', `text` = '', `modified_by` = '', `date_modified` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "data` SET `type` = 'banned_towns', `text` = '', `modified_by` = '', `date_modified` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "data` SET `type` = 'banned_websites', `text` = '', `modified_by` = '', `date_modified` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "data` SET `type` = 'detect_links', `text` = 'www\r\n\r\nhttp\r\n\r\n.aero\r\n.asia\r\n.biz\r\n.cat\r\n.co\r\n.com\r\n.coop\r\n.edu\r\n.gov\r\n.info\r\n.int\r\n.jobs\r\n.me\r\n.mil\r\n.mobi\r\n.museum\r\n.name\r\n.net\r\n.org\r\n.pro\r\n.tel\r\n.travel\r\n.tv\r\n\r\n.ar\r\n.au\r\n.br\r\n.ca\r\n.ch\r\n.cn\r\n.de\r\n.es\r\n.eu\r\n.fr\r\n.it\r\n.jp\r\n.nl\r\n.no\r\n.ru\r\n.se\r\n.uk\r\n.us', `modified_by` = '', `date_modified` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "data` SET `type` = 'dummy_emails', `text` = 'domain.\r\nexample.\r\ntest.', `modified_by` = '', `date_modified` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "data` SET `type` = 'dummy_names', `text` = 'test\r\ntester\r\ntesting', `modified_by` = '', `date_modified` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "data` SET `type` = 'dummy_towns', `text` = 'test\r\ntester\r\ntesting', `modified_by` = '', `date_modified` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "data` SET `type` = 'dummy_websites', `text` = 'domain.\r\nexample.\r\ntest.', `modified_by` = '', `date_modified` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "data` SET `type` = 'mild_swear_words', `text` = 'arse\r\narses\r\nass\r\nasses\r\nbollocks\r\ncrap', `modified_by` = '', `date_modified` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "data` SET `type` = 'reserved_emails', `text` = '', `modified_by` = '', `date_modified` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "data` SET `type` = 'reserved_names', `text` = 'admin\r\nadmins\r\nadministrator\r\nadministrators\r\nauthor\r\nauthors\r\ndeveloper\r\ndevelopers\r\nmoderator\r\nmoderators\r\nowner\r\nowners\r\nsupport\r\nteam', `modified_by` = '', `date_modified` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "data` SET `type` = 'reserved_towns', `text` = '', `modified_by` = '', `date_modified` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "data` SET `type` = 'reserved_websites', `text` = '', `modified_by` = '', `date_modified` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "data` SET `type` = 'signature_html', `text` = '<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">[site name]</td>\r\n</tr>\r\n<tr>\r\n<td><a href=\"[site url]\">[site url]</a></td>\r\n</tr>\r\n</table>', `modified_by` = '', `date_modified` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "data` SET `type` = 'signature_text', `text` = '[site name]\r\n[site url]', `modified_by` = '', `date_modified` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "data` SET `type` = 'spam_words', `text` = 'ambien\r\ncapsule\r\ncapsules\r\ncialis\r\nherbal\r\nlevitra\r\nmedication\r\nmedications\r\npharma\r\npharmaceutical\r\npharmacy\r\npill\r\npills\r\nprescription\r\ntablet\r\ntablets\r\nviagra\r\n\r\nanal\r\nclit\r\nclits\r\nenlarge\r\nenlarger\r\nenlarges\r\nerect\r\nerection\r\nfetish\r\nhormone\r\nhormones\r\nintimacy\r\nintimate\r\nnaked\r\nnude\r\norgasm\r\norgasms\r\npenis\r\nporn\r\nporno\r\npornography\r\nsex\r\nsexual\r\nsexy\r\nvagina\r\n\r\ncasino\r\ncasinos\r\nholdem\r\ngamble\r\ngambling\r\nlottery\r\npoker\r\nslots\r\n\r\nact now\r\nact today\r\nbest price\r\nbest prices\r\nbuy cheap\r\nbuy now\r\nbuy today\r\ncall now\r\ncall today\r\ncash bonus\r\ncheap price\r\ncheap prices\r\ncheapest price\r\ncheapest prices\r\ndiscount\r\ndiscounts\r\ndiscounted\r\ngreat price\r\ngreat prices\r\nhigh quality\r\nlow price\r\nlow prices\r\nlowest price\r\nlowest prices\r\norder now\r\norder today\r\nsave almost\r\nsave \r\nearly\r\nsave up to\r\nwholesale\r\n\r\nreplica\r\nrolex\r\n\r\nhair loss\r\nweight loss\r\n\r\nbankrupt\r\nbankruptcy\r\n\r\ncvv\r\ncvv2\r\n\r\nsim card\r\nsim cards', `modified_by` = '', `date_modified` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "data` SET `type` = 'strong_swear_words', `text` = 'arse hole\r\narsehole\r\narse holes\r\narseholes\r\nass hole\r\nasshole\r\nass holes\r\nassholes\r\nbastard\r\nbastards\r\nbitch\r\nbitches\r\nblow job\r\nblowjob\r\nblow jobs\r\nblowjobs\r\nbull shit\r\nbullshit\r\nchinc\r\nchincs\r\nchink\r\nchinks\r\ncock sucker\r\ncock suckers\r\ncock\r\ncocks\r\ncocksucker\r\ncocksuckers\r\ncum\r\ncums\r\ncunt\r\ncunts\r\ndick\r\ndicks\r\ndick head\r\ndickhead\r\ndick heads\r\ndickheads\r\ndike\r\ndikes\r\ndildo\r\ndildos\r\ndyke\r\ndykes\r\nfaggot\r\nfaggots\r\nfuc\r\nfuck\r\nfucka\r\nfucked\r\nfucker\r\nfuckers\r\nfuckin\r\nfucking\r\nfucks\r\nfuk\r\nfuks\r\ngook\r\ngooks\r\nhand job\r\nhandjob\r\nhand jobs\r\nhandjobs\r\njackarse\r\njackarses\r\njackass\r\njackasses\r\njap\r\njaps\r\nmothafucka\r\nmothafucker\r\nmother fuckers\r\nmotherfuckers\r\nmother fucker\r\nmotherfucker\r\nnigga\r\nniggas\r\nnigger\r\nniggers\r\nniglet\r\nniglets\r\npaki\r\npakis\r\npiss\r\npissed\r\npoof\r\npoofs\r\nprick\r\npricks\r\npussies\r\npussy\r\npussys\r\nshit\r\nshite\r\nshits\r\nslut\r\nsluts\r\ntit\r\ntits\r\ntwat\r\ntwats\r\nwank\r\nwanks\r\nwanker\r\nwankers\r\nwanking\r\nwhore\r\nwhores\r\nwop\r\nwops', `modified_by` = '', `date_modified` = NOW()");
        /*****************************************************************************************************************/
    }

    public function createTableDeleted()
    {
        /********************************************** CREATE TABLE 'deleted' *******************************************/
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . CMTX_DB_PREFIX . "deleted` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `user_id` int(10) unsigned NOT NULL default '0',
            `comment_id` int(10) unsigned NOT NULL default '0',
            `page_id` int(10) unsigned NOT NULL default '0',
            `ip_address` varchar(250) NOT NULL default '',
            `date_added` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        /*****************************************************************************************************************/
    }

    public function createTableEmails()
    {
        /********************************************** CREATE TABLE 'emails' ********************************************/
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . CMTX_DB_PREFIX . "emails` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `type` varchar(250) NOT NULL default '',
            `subject` varchar(250) NOT NULL default '',
            `text` text NOT NULL,
            `html` text NOT NULL,
            `language` varchar(250) NOT NULL default '',
            `date_modified` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "emails` SET `type` = 'ban', `subject` = '[username], there&#039;s a new ban!', `text` = 'Hello [username],\r\n\r\nA new user, with the IP address [ip address], has been banned for the following reason:\r\n- [reason]\r\n\r\nHere is the link to your admin panel:\r\n[admin link]\r\n\r\nRegards,\r\n\r\n[signature]', `html` = '<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td>Hello [username],</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">A new user, with the IP address [ip address], has been banned for the following reason:</td>\r\n</tr>\r\n<tr>\r\n<td>- [reason]</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Here is the link to your admin panel:</td>\r\n</tr>\r\n<tr>\r\n<td><a href=\"[admin link]\">[admin link]</a></td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Regards,</td>\r\n</tr>\r\n</table>\r\n\r\n[signature]', `language` = 'english', `date_modified` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "emails` SET `type` = 'comment_approve', `subject` = 'Comment Approve', `text` = 'Hello [username],\r\n\r\nA new comment has been posted on the page [page reference], located at the following address:\r\n[comment url]\r\n\r\nThe comment was made by [poster] and was as follows:\r\n\r\n************************\r\n\r\n[comment]\r\n\r\n************************\r\n\r\nThis comment requires approval due to the following reason(s):\r\n[reason]\r\n\r\nHere is the link to your admin panel:\r\n[admin link]\r\n\r\nRegards,\r\n\r\n[signature]', `html` = '<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td>Hello [username],</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">A new comment has been posted on the page [page reference], located at the following address:</td>\r\n</tr>\r\n<tr>\r\n<td><a href=\"[comment url]\">[comment url]</a></td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">The comment was made by [poster] and was as follows:</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding-top:20px\">************************</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding-top:10px\">[comment]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding-top:20px\">************************</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">This comment requires approval due to the following reason(s):</td>\r\n</tr>\r\n<tr>\r\n<td>[reason]</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Here is the link to your admin panel:</td>\r\n</tr>\r\n<tr>\r\n<td><a href=\"[admin link]\">[admin link]</a></td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Regards,</td>\r\n</tr>\r\n</table>\r\n\r\n[signature]', `language` = 'english', `date_modified` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "emails` SET `type` = 'comment_success', `subject` = 'Comment Success', `text` = 'Hello [username],\r\n\r\nA new comment has been posted on the page [page reference], located at the following address:\r\n[comment url]\r\n\r\nThe comment was made by [poster] and was as follows:\r\n\r\n************************\r\n\r\n[comment]\r\n\r\n************************\r\n\r\nThis comment does not require approval.\r\n\r\nHere is the link to your admin panel:\r\n[admin link]\r\n\r\nRegards,\r\n\r\n[signature]', `html` = '<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td>Hello [username],</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">A new comment has been posted on the page [page reference], located at the following address:</td>\r\n</tr>\r\n<tr>\r\n<td><a href=\"[comment url]\">[comment url]</a></td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">The comment was made by [poster] and was as follows:</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding-top:20px\">************************</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding-top:10px\">[comment]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding-top:20px\">************************</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">This comment does not require approval.</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Here is the link to your admin panel:</td>\r\n</tr>\r\n<tr>\r\n<td><a href=\"[admin link]\">[admin link]</a></td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Regards,</td>\r\n</tr>\r\n</table>\r\n\r\n[signature]', `language` = 'english', `date_modified` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "emails` SET `type` = 'flag', `subject` = '[username], a comment is flagged!', `text` = 'Hello [username],\r\n\r\nA new comment has been flagged on the page [page reference], located at the following address:\r\n[comment url]\r\n\r\nThe flagged comment was made by [poster] and was as follows:\r\n\r\n************************\r\n\r\n[comment]\r\n\r\n************************\r\n\r\nHere is the link to your admin panel:\r\n[admin link]\r\n\r\nRegards,\r\n\r\n[signature]', `html` = '<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td>Hello [username],</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">A new comment has been flagged on the page [page reference], located at the following address:</td>\r\n</tr>\r\n<tr>\r\n<td><a href=\"[comment url]\">[comment url]</a></td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">The flagged comment was made by [poster] and was as follows:</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding-top:20px\">************************</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding-top:10px\">[comment]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding-top:20px\">************************</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Here is the link to your admin panel:</td>\r\n</tr>\r\n<tr>\r\n<td><a href=\"[admin link]\">[admin link]</a></td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Regards,</td>\r\n</tr>\r\n</table>\r\n\r\n[signature]', `language` = 'english', `date_modified` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "emails` SET `type` = 'edit', `subject` = '[username], a comment was edited!', `text` = 'Hello [username],\r\n\r\nA comment has been edited on the page [page reference], located at the following address:\r\n[comment url]\r\n\r\nThe edited comment was made by [poster] and was as follows:\r\n\r\n************************\r\n\r\n[comment]\r\n\r\n************************\r\n\r\nHere is the link to your admin panel:\r\n[admin link]\r\n\r\nRegards,\r\n\r\n[signature]', `html` = '<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td>Hello [username],</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">A comment has been edited on the page [page reference], located at the following address:</td>\r\n</tr>\r\n<tr>\r\n<td><a href=\"[comment url]\">[comment url]</a></td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">The edited comment was made by [poster] and was as follows:</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding-top:20px\">************************</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding-top:10px\">[comment]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding-top:20px\">************************</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Here is the link to your admin panel:</td>\r\n</tr>\r\n<tr>\r\n<td><a href=\"[admin link]\">[admin link]</a></td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Regards,</td>\r\n</tr>\r\n</table>\r\n\r\n[signature]', `language` = 'english', `date_modified` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "emails` SET `type` = 'delete', `subject` = '[username], a comment was deleted!', `text` = 'Hello [username],\r\n\r\nA comment has been deleted on the page [page reference], located at the following address:\r\n[comment url]\r\n\r\nThe deleted comment was made by [poster] and was as follows:\r\n\r\n************************\r\n\r\n[comment]\r\n\r\n************************\r\n\r\nHere is the link to your admin panel:\r\n[admin link]\r\n\r\nRegards,\r\n\r\n[signature]', `html` = '<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td>Hello [username],</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">A comment has been deleted on the page [page reference], located at the following address:</td>\r\n</tr>\r\n<tr>\r\n<td><a href=\"[comment url]\">[comment url]</a></td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">The deleted comment was made by [poster] and was as follows:</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding-top:20px\">************************</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding-top:10px\">[comment]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding-top:20px\">************************</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Here is the link to your admin panel:</td>\r\n</tr>\r\n<tr>\r\n<td><a href=\"[admin link]\">[admin link]</a></td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Regards,</td>\r\n</tr>\r\n</table>\r\n\r\n[signature]', `language` = 'english', `date_modified` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "emails` SET `type` = 'new_version', `subject` = 'New Version', `text` = 'Hello [username],\r\n\r\nA newer version of Commentics is available.\r\n\r\nYour installed version is [installed version]. The newest version is [newest version].\r\n\r\nPlease upgrade at your earliest convenience.\r\n\r\nHere is the link to your admin panel:\r\n[admin link]\r\n\r\nRegards,\r\n\r\n[signature]', `html` = '<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td>Hello [username],</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">A newer version of Commentics is available.</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Your installed version is [installed version]. The newest version is [newest version].</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Please upgrade at your earliest convenience.</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Here is the link to your admin panel:</td>\r\n</tr>\r\n<tr>\r\n<td><a href=\"[admin link]\">[admin link]</a></td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Regards,</td>\r\n</tr>\r\n</table>\r\n\r\n[signature]', `language` = 'english', `date_modified` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "emails` SET `type` = 'password_reset', `subject` = 'Password Reset', `text` = 'Hello [username],\r\n\r\nYour login details are listed below:\r\n\r\nUsername: [username]\r\nPassword: [password]\r\n\r\nHere is the link to your admin panel:\r\n[admin link]\r\n\r\nRegards,\r\n\r\n[signature]', `html` = '<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td>Hello [username],</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Your login details are listed below:</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\"><b>Username:</b></td><td style=\"padding-top:15px; padding-left:5px\">[username]</td>\r\n</tr>\r\n<tr>\r\n<td><b>Password:</b></td><td style=\"padding-left:5px\">[password]</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Here is the link to your admin panel:</td>\r\n</tr>\r\n<tr>\r\n<td><a href=\"[admin link]\">[admin link]</a></td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Regards,</td>\r\n</tr>\r\n</table>\r\n\r\n[signature]', `language` = 'english', `date_modified` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "emails` SET `type` = 'setup_test', `subject` = 'Setup Test', `text` = 'Hello [username],\r\n\r\nThis is a test email generated by the \'Settings -> Email -> Setup\' page.\r\n\r\nIf you have received this email, you have the correct email settings.\r\n\r\nHere is the link to your admin panel:\r\n[admin link]\r\n\r\nRegards,\r\n\r\n[signature]', `html` = '<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td>Hello [username],</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">This is a test email generated by the \'Settings -> Email -> Setup\' page.</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding-top:15px\">If you have received this email, you have the correct email settings.</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Here is the link to your admin panel:</td>\r\n</tr>\r\n<tr>\r\n<td><a href=\"[admin link]\">[admin link]</a></td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Regards,</td>\r\n</tr>\r\n</table>\r\n\r\n[signature]', `language` = 'english', `date_modified` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "emails` SET `type` = 'subscriber_confirmation', `subject` = 'Subscription Confirmation', `text` = 'Hello [name],\r\n\r\nYou have requested a subscription to the page [page reference], located at the following address:\r\n[page url]\r\n\r\nPlease confirm this subscription by clicking the link below:\r\n[confirmation link]\r\n\r\nIf you did not request this subscription, there is nothing that you need to do.\r\n\r\nYou will not receive anymore emails of this type.\r\n\r\nRegards,\r\n\r\n[signature]', `html` = '<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td>Hello [name],</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">You have requested a subscription to the page [page reference], located at the following address:</td>\r\n</tr>\r\n<tr>\r\n<td><a href=\"[page url]\">[page url]</a></td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Please confirm this subscription by clicking the link below:</td>\r\n</tr>\r\n<tr>\r\n<td><a href=\"[confirmation link]\">[confirmation link]</a></td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">If you did not request this subscription, there is nothing that you need to do.</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">You will not receive anymore emails of this type.</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Regards,</td>\r\n</tr>\r\n</table>\r\n\r\n[signature]', `language` = 'english', `date_modified` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "emails` SET `type` = 'subscriber_notification_admin', `subject` = '[name], the Admin has posted!', `text` = 'Hello [name],\r\n\r\nThe admin has posted a comment on the page [page reference], located at the following address:\r\n[comment url]\r\n\r\nThe comment was made by [poster] and was as follows:\r\n\r\n************************\r\n\r\n[comment]\r\n\r\n************************\r\n\r\nRegards,\r\n\r\n[signature]\r\n\r\nTo manage your subscription, click the link below:\r\n[user url]', `html` = '<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td>Hello [name],</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">The admin has posted a comment on the page [page reference], located at the following address:</td>\r\n</tr>\r\n<tr>\r\n<td><a href=\"[comment url]\">[comment url]</a></td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">The comment was made by [poster] and was as follows:</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding-top:20px\">************************</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding-top:10px\">[comment]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding-top:20px\">************************</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Regards,</td>\r\n</tr>\r\n</table>\r\n\r\n[signature]\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">To manage your subscription, click the link below:</td>\r\n</tr>\r\n<tr>\r\n<td><a href=\"[user url]\">[user url]</a></td>\r\n</tr>\r\n</table>', `language` = 'english', `date_modified` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "emails` SET `type` = 'subscriber_notification_basic', `subject` = '[name], there\'s a new comment!', `text` = 'Hello [name],\r\n\r\nA new comment has been posted on the page [page reference], located at the following address:\r\n[comment url]\r\n\r\nThe comment was made by [poster] and was as follows:\r\n\r\n************************\r\n\r\n[comment]\r\n\r\n************************\r\n\r\nRegards,\r\n\r\n[signature]\r\n\r\nTo manage your subscription, click the link below:\r\n[user url]', `html` = '<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td>Hello [name],</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">A new comment has been posted on the page [page reference], located at the following address:</td>\r\n</tr>\r\n<tr>\r\n<td><a href=\"[comment url]\">[comment url]</a></td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">The comment was made by [poster] and was as follows:</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding-top:20px\">************************</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding-top:10px\">[comment]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding-top:20px\">************************</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Regards,</td>\r\n</tr>\r\n</table>\r\n\r\n[signature]\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">To manage your subscription, click the link below:</td>\r\n</tr>\r\n<tr>\r\n<td><a href=\"[user url]\">[user url]</a></td>\r\n</tr>\r\n</table>', `language` = 'english', `date_modified` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "emails` SET `type` = 'subscriber_notification_reply', `subject` = '[name], you have a reply!', `text` = 'Hello [name],\r\n\r\nYou have a reply on the page [page reference], located at the following address:\r\n[comment url]\r\n\r\nThe comment was made by [poster] and was as follows:\r\n\r\n************************\r\n\r\n[comment]\r\n\r\n************************\r\n\r\nRegards,\r\n\r\n[signature]\r\n\r\nTo manage your subscription, click the link below:\r\n[user url]', `html` = '<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td>Hello [name],</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">You have a reply on the page [page reference], located at the following address:</td>\r\n</tr>\r\n<tr>\r\n<td><a href=\"[comment url]\">[comment url]</a></td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">The comment was made by [poster] and was as follows:</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding-top:20px\">************************</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding-top:10px\">[comment]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding-top:20px\">************************</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Regards,</td>\r\n</tr>\r\n</table>\r\n\r\n[signature]\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">To manage your subscription, click the link below:</td>\r\n</tr>\r\n<tr>\r\n<td><a href=\"[user url]\">[user url]</a></td>\r\n</tr>\r\n</table>', `language` = 'english', `date_modified` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "emails` SET `type` = 'user_comment_approved', `subject` = '[name], your comment is approved!', `text` = 'Hello [name],\r\n\r\nYour comment is now approved on the page [page reference], located at the following address:\r\n[comment url]\r\n\r\nThe comment that you posted was as follows:\r\n\r\n************************\r\n\r\n[comment]\r\n\r\n************************\r\n\r\nRegards,\r\n\r\n[signature]\r\n\r\nTo manage your preferences, click the link below:\r\n[user url]', `html` = '<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td>Hello [name],</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Your comment is now approved on the page [page reference], located at the following address:</td>\r\n</tr>\r\n<tr>\r\n<td><a href=\"[comment url]\">[comment url]</a></td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">The comment that you posted was as follows:</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding-top:20px\">************************</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding-top:10px\">[comment]</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding-top:20px\">************************</td>\r\n</tr>\r\n</table>\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">Regards,</td>\r\n</tr>\r\n</table>\r\n\r\n[signature]\r\n\r\n<table style=\"border-collapse:collapse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td style=\"padding-top:15px\">To manage your preferences, click the link below:</td>\r\n</tr>\r\n<tr>\r\n<td><a href=\"[user url]\">[user url]</a></td>\r\n</tr>\r\n</table>', `language` = 'english', `date_modified` = NOW()");
        /*****************************************************************************************************************/
    }

    public function createTableGeo()
    {
        /********************************************** CREATE TABLE 'geo' ***********************************************/
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . CMTX_DB_PREFIX . "geo` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `name` varchar(250) NOT NULL default '',
            `country_code` varchar(3) NOT NULL default '',
            `language` varchar(250) NOT NULL default '',
            `date_added` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Afghanistan', `country_code` = 'AFG', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Albania', `country_code` = 'ALB', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Algeria', `country_code` = 'DZA', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Andorra', `country_code` = 'AND', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Angola', `country_code` = 'AGO', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Antigua and Barbuda', `country_code` = 'ATG', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Argentina', `country_code` = 'ARG', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Armenia', `country_code` = 'ARM', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Australia', `country_code` = 'AUS', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Austria', `country_code` = 'AUT', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Azerbaijan', `country_code` = 'AZE', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Bahamas', `country_code` = 'BHS', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Bahrain', `country_code` = 'BHR', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Bangladesh', `country_code` = 'BGD', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Barbados', `country_code` = 'BRB', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Belarus', `country_code` = 'BLR', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Belgium', `country_code` = 'BEL', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Belize', `country_code` = 'BLZ', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Benin', `country_code` = 'BEN', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Bhutan', `country_code` = 'BTN', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Bolivia', `country_code` = 'BOL', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Bosnia and Herzegovina', `country_code` = 'BIH', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Botswana', `country_code` = 'BWA', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Brazil', `country_code` = 'BRA', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Brunei', `country_code` = 'BRN', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Bulgaria', `country_code` = 'BGR', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Burkina Faso', `country_code` = 'BFA', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Burundi', `country_code` = 'BDI', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Cambodia', `country_code` = 'KHM', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Cameroon', `country_code` = 'CMR', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Canada', `country_code` = 'CAN', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Cape Verde', `country_code` = 'CPV', `language` = 'english', `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Central African Republic', `country_code` = 'CAF', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Chad', `country_code` = 'TCD', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Chile', `country_code` = 'CHL', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'China', `country_code` = 'CHN', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Colombia', `country_code` = 'COL', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Comoros', `country_code` = 'COM', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Congo, Republic', `country_code` = 'COG', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Congo, Democratic Republic', `country_code` = 'COD', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Costa Rica', `country_code` = 'CRI', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Croatia', `country_code` = 'HRV', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Cuba', `country_code` = 'CUB', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Cyprus', `country_code` = 'CYP', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Czech Republic', `country_code` = 'CZE', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Denmark', `country_code` = 'DNK', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Djibouti', `country_code` = 'DJI', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Dominica', `country_code` = 'DMA', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Dominican Republic', `country_code` = 'DOM', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'East Timor', `country_code` = 'TLS', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Ecuador', `country_code` = 'ECU', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Egypt', `country_code` = 'EGY', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'El Salvador', `country_code` = 'SLV', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Equatorial Guinea', `country_code` = 'GNQ', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Eritrea', `country_code` = 'ERI', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Estonia', `country_code` = 'EST', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Eswatini', `country_code` = 'SWZ', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Ethiopia', `country_code` = 'ETH', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Fiji', `country_code` = 'FJI', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Finland', `country_code` = 'FIN', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'France', `country_code` = 'FRA', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Gabon', `country_code` = 'GAB', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Gambia', `country_code` = 'GMB', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Georgia', `country_code` = 'GEO', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Germany', `country_code` = 'DEU', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Ghana', `country_code` = 'GHA', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Greece', `country_code` = 'GRC', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Grenada', `country_code` = 'GRD', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Guatemala', `country_code` = 'GTM', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Guinea', `country_code` = 'GIN', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Guinea-Bissau', `country_code` = 'GNB', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Guyana', `country_code` = 'GUY', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Haiti', `country_code` = 'HTI', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Honduras', `country_code` = 'HND', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Hungary', `country_code` = 'HUN', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Iceland', `country_code` = 'ISL', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'India', `country_code` = 'IND', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Indonesia', `country_code` = 'IDN', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Iran', `country_code` = 'IRN', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Iraq', `country_code` = 'IRQ', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Ireland', `country_code` = 'IRL', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Israel', `country_code` = 'ISR', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Italy', `country_code` = 'ITA', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Ivory Coast', `country_code` = 'CIV', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Jamaica', `country_code` = 'JAM', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Japan', `country_code` = 'JPN', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Jordan', `country_code` = 'JOR', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Kazakhstan', `country_code` = 'KAZ', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Kenya', `country_code` = 'KEN', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Kiribati', `country_code` = 'KIR', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Kosovo', `country_code` = 'UNK', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Kuwait', `country_code` = 'KWT', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Kyrgyzstan', `country_code` = 'KGZ', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Laos', `country_code` = 'LAO', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Latvia', `country_code` = 'LVA', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Lebanon', `country_code` = 'LBN', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Lesotho', `country_code` = 'LSO', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Liberia', `country_code` = 'LBR', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Libya', `country_code` = 'LBY', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Liechtenstein', `country_code` = 'LIE', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Lithuania', `country_code` = 'LTU', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Luxembourg', `country_code` = 'LUX', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Madagascar', `country_code` = 'MDG', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Malawi', `country_code` = 'MWI', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Malaysia', `country_code` = 'MYS', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Maldives', `country_code` = 'MDV', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Mali', `country_code` = 'MLI', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Malta', `country_code` = 'MLT', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Marshall Islands', `country_code` = 'MHL', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Mauritania', `country_code` = 'MRT', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Mauritius', `country_code` = 'MUS', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Mexico', `country_code` = 'MEX', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Micronesia', `country_code` = 'FSM', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Moldova', `country_code` = 'MDA', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Monaco', `country_code` = 'MCO', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Mongolia', `country_code` = 'MNG', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Montenegro', `country_code` = 'MNE', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Morocco', `country_code` = 'MAR', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Mozambique', `country_code` = 'MOZ', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Myanmar', `country_code` = 'MMR', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Namibia', `country_code` = 'NAM', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Nauru', `country_code` = 'NRU', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Nepal', `country_code` = 'NPL', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Netherlands', `country_code` = 'NLD', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'New Zealand', `country_code` = 'NZL', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Nicaragua', `country_code` = 'NIC', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Niger', `country_code` = 'NER', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Nigeria', `country_code` = 'NGA', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'North Korea', `country_code` = 'PRK', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'North Macedonia', `country_code` = 'MKD', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Norway', `country_code` = 'NOR', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Oman', `country_code` = 'OMN', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Overseas Territory', `country_code` = 'OST', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Pakistan', `country_code` = 'PAK', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Palau', `country_code` = 'PLW', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Palestine', `country_code` = 'PSE', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Panama', `country_code` = 'PAN', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Papua New Guinea', `country_code` = 'PNG', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Paraguay', `country_code` = 'PRY', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Peru', `country_code` = 'PER', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Philippines', `country_code` = 'PHL', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Poland', `country_code` = 'POL', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Portugal', `country_code` = 'PRT', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Qatar', `country_code` = 'QAT', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Romania', `country_code` = 'ROM', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Russia', `country_code` = 'RUS', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Rwanda', `country_code` = 'RWA', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Saint Kitts and Nevis', `country_code` = 'KNA', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Saint Lucia', `country_code` = 'LCA', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Saint Vincent and Grenadines', `country_code` = 'VCT', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Samoa', `country_code` = 'WSM', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'San Marino', `country_code` = 'SMR', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'São Tomé and Príncipe', `country_code` = 'STP', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Saudi Arabia', `country_code` = 'SAU', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Senegal', `country_code` = 'SEN', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Serbia', `country_code` = 'SRB', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Seychelles', `country_code` = 'SYC', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Sierra Leone', `country_code` = 'SLE', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Singapore', `country_code` = 'SGP', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Slovakia', `country_code` = 'SVK', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Slovenia', `country_code` = 'SVN', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Solomon Islands', `country_code` = 'SLB', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Somalia', `country_code` = 'SOM', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'South Africa', `country_code` = 'ZAF', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'South Korea', `country_code` = 'KOR', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'South Sudan', `country_code` = 'SSD', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Spain', `country_code` = 'ESP', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Sri Lanka', `country_code` = 'LKA', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Sudan', `country_code` = 'SDN', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Suriname', `country_code` = 'SUR', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Sweden', `country_code` = 'SWE', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Switzerland', `country_code` = 'CHE', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Syria', `country_code` = 'SYR', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Tajikistan', `country_code` = 'TJK', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Tanzania', `country_code` = 'TZA', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Thailand', `country_code` = 'THA', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Togo', `country_code` = 'TGO', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Tonga', `country_code` = 'TON', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Trinidad and Tobago', `country_code` = 'TTO', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Tunisia', `country_code` = 'TUN', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Turkey', `country_code` = 'TUR', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Turkmenistan', `country_code` = 'TKM', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Tuvalu', `country_code` = 'TUV', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Uganda', `country_code` = 'UGA', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Ukraine', `country_code` = 'UKR', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'United Arab Emirates', `country_code` = 'ARE', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'UK', `country_code` = 'GBR', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'US', `country_code` = 'USA', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Uruguay', `country_code` = 'URY', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Uzbekistan', `country_code` = 'UZB', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Vanuatu', `country_code` = 'VUT', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Venezuela', `country_code` = 'VEN', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Vietnam', `country_code` = 'VNM', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Yemen', `country_code` = 'YEM', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Zambia', `country_code` = 'ZMB', `language` = 'english', `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = 'Zimbabwe', `country_code` = 'ZWE', `language` = 'english', `date_added` = NOW()");
        /*****************************************************************************************************************/
    }

    public function createTableLogins()
    {
        /********************************************** CREATE TABLE 'logins' ********************************************/
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . CMTX_DB_PREFIX . "logins` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `date_modified` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "logins` SET `id` = '1', `date_modified` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "logins` SET `id` = '2', `date_modified` = NOW()");
        /*****************************************************************************************************************/
    }

    public function createTableModules()
    {
        /********************************************** CREATE TABLE 'modules' *******************************************/
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . CMTX_DB_PREFIX . "modules` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `module` varchar(250) NOT NULL default '',
            `date_added` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        /*****************************************************************************************************************/
    }

    public function createTablePages()
    {
        /********************************************** CREATE TABLE 'pages' *********************************************/
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . CMTX_DB_PREFIX . "pages` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `site_id` int(10) unsigned NOT NULL default '1',
            `identifier` varchar(250) NOT NULL default '',
            `reference` varchar(250) NOT NULL default '',
            `url` varchar(1000) NOT NULL default '',
            `moderate` varchar(250) NOT NULL default 'default',
            `is_form_enabled` tinyint(1) unsigned NOT NULL default '1',
            `date_modified` datetime NOT NULL,
            `date_added` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        /*****************************************************************************************************************/
    }

    public function createTableQuestions()
    {
        /********************************************** CREATE TABLE 'questions' *****************************************/
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . CMTX_DB_PREFIX . "questions` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `question` varchar(250) NOT NULL default '',
            `answer` varchar(250) NOT NULL default '',
            `language` varchar(250) NOT NULL default '',
            `date_modified` datetime NOT NULL,
            `date_added` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "questions` SET `question` = 'Enter the third letter of the word castle.', `answer` = 's', `language` = 'english', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "questions` SET `question` = 'Enter the word shark backwards.', `answer` = 'krahs', `language` = 'english', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "questions` SET `question` = 'What is the opposite word of weak?', `answer` = 'strong', `language` = 'english', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "questions` SET `question` = 'Is it true or false that green is a number?', `answer` = 'false', `language` = 'english', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "questions` SET `question` = 'How many letters are in the word two?', `answer` = '3|three', `language` = 'english', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "questions` SET `question` = 'Which is darker: black or white?', `answer` = 'black', `language` = 'english', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "questions` SET `question` = 'Enter the last letter of the word satellite.', `answer` = 'e', `language` = 'english', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "questions` SET `question` = 'What is the opposite word of small?', `answer` = 'big', `language` = 'english', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "questions` SET `question` = 'Out of 56, 14 or 27, which is the smallest?', `answer` = '14|fourteen', `language` = 'english', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "questions` SET `question` = 'Enter the word hand backwards.', `answer` = 'dnah', `language` = 'english', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "questions` SET `question` = 'Type the numbers for four hundred seventy-two.', `answer` = '472', `language` = 'english', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "questions` SET `question` = 'Enter the fifth word of this sentence.', `answer` = 'of', `language` = 'english', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "questions` SET `question` = 'Enter the third word of this sentence.', `answer` = 'third', `language` = 'english', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "questions` SET `question` = 'What is the sum of 1 + 2 + 3?', `answer` = '6|six', `language` = 'english', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "questions` SET `question` = 'Enter the word table backwards.', `answer` = 'elbat', `language` = 'english', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "questions` SET `question` = 'What is the day after Friday?', `answer` = 'saturday', `language` = 'english', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "questions` SET `question` = 'Is ice cream hot or cold?', `answer` = 'cold', `language` = 'english', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "questions` SET `question` = 'What is the next number: 10, 12, 14, ..?', `answer` = '16|sixteen', `language` = 'english', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "questions` SET `question` = 'What is the fifth month of the year?', `answer` = 'may', `language` = 'english', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "questions` SET `question` = 'Type the word for the number 9.', `answer` = 'nine', `language` = 'english', `date_modified` = NOW(), `date_added` = NOW()");
        /*****************************************************************************************************************/
    }

    public function createTableRatings()
    {
        /********************************************** CREATE TABLE 'ratings' *******************************************/
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . CMTX_DB_PREFIX . "ratings` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `page_id` int(10) unsigned NOT NULL default '0',
            `rating` tinyint(1) unsigned NOT NULL default '0',
            `ip_address` varchar(250) NOT NULL default '',
            `date_added` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        /*****************************************************************************************************************/
    }

    public function createTableReporters()
    {
        /********************************************** CREATE TABLE 'reporters' *****************************************/
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . CMTX_DB_PREFIX . "reporters` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `comment_id` int(10) unsigned NOT NULL default '0',
            `ip_address` varchar(250) NOT NULL default '',
            `date_added` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        /*****************************************************************************************************************/
    }

    public function createTableSettings($site_name, $site_domain, $site_url, $security_key, $session_key, $encryption_key, $site_id, $time_zone, $commentics_folder, $commentics_url, $backend_folder, $ssl_certificate, $purpose)
    {
        /********************************************** CREATE TABLE 'settings' ******************************************/
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . CMTX_DB_PREFIX . "settings` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `category` varchar(250) NOT NULL default '',
            `title` varchar(250) NOT NULL default '',
            `value` varchar(250) NOT NULL default '',
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'admin_panel', `title` = 'checklist_complete', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'admin_panel', `title` = 'admin_detect', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'admin_panel', `title` = 'version_detect', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'admin_panel', `title` = 'system_detect', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'admin_panel', `title` = 'layout_detect', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'admin_panel', `title` = 'empty_pages', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'approval', `title` = 'approve_comments', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'approval', `title` = 'approve_notifications', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'approval', `title` = 'trust_previous_users', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'cache', `title` = 'cache_type', `value` = ''");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'cache', `title` = 'cache_time', `value` = '86400'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'cache', `title` = 'cache_host', `value` = '127.0.0.1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'cache', `title` = 'cache_port', `value` = ''");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'commentics', `title` = 'enabled_powered_by', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'commentics', `title` = 'powered_by_type', `value` = 'text'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'commentics', `title` = 'powered_by_new_window', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_average_rating', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'average_rating_guest', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_comment_count', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'count_replies', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_topic', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_headline', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_website', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_town', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_state', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_country', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_says', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_rating', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_date', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_like', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_dislike', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_share', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_flag', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_edit', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_delete', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_permalink', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_reply', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_rss', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_pagination', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'pagination_type', `value` = 'button'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'pagination_amount', `value` = '5'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'pagination_range', `value` = '2'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_page_number', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'page_number_format', `value` = 'Page X of Y'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'flag_max_per_user', `value` = '3'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'flag_min_per_comment', `value` = '2'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'flag_disapprove', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'date_auto', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'max_edits', `value` = '3'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'quick_reply', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'hide_replies', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'reply_depth', `value` = '5'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_sort_by', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_sort_by_1', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_sort_by_2', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_sort_by_3', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_sort_by_4', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_sort_by_5', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_sort_by_6', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'avatar_type', `value` = 'upload'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'avatar_selection_attribution', `value` = ''");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'avatar_upload_min_posts', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'avatar_upload_min_days', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'avatar_upload_max_size', `value` = '0.3'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'avatar_upload_approve', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'gravatar_default', `value` = 'mm'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'gravatar_custom', `value` = ''");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'gravatar_size', `value` = '72'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'gravatar_audience', `value` = 'g'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'avatar_user_link', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'avatar_link_days', `value` = '30'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_level', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'level_5', `value` = '50'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'level_4', `value` = '40'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'level_3', `value` = '30'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'level_2', `value` = '20'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'level_1', `value` = '10'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'level_0', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_bio', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_badge_top_poster', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_badge_most_likes', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_badge_first_poster', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_social', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'social_new_window', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_social_digg', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_social_facebook', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_social_linkedin', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_social_reddit', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_social_twitter', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_social_weibo', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_search', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_custom', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'custom_content', `value` = ''");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_notify', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_online', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'online_refresh_enabled', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'online_refresh_interval', `value` = '60'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'comments_order', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'share_new_window', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_share_digg', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_share_facebook', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_share_linkedin', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_share_reddit', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_share_twitter', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'show_share_weibo', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'website_new_window', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'website_no_follow', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'comments_position_1', `value` = 'sort_by'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'comments_position_2', `value` = 'search'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'comments_position_3', `value` = 'topic'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'comments_position_4', `value` = 'average_rating'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'comments_position_5', `value` = 'social'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'comments_position_6', `value` = 'notify'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'comments_position_7', `value` = ''");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'comments_position_8', `value` = 'rss'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'comments_position_9', `value` = ''");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'comments_position_10', `value` = ''");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'comments_position_11', `value` = ''");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'comments', `title` = 'comments_position_12', `value` = ''");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'email', `title` = 'transport_method', `value` = 'php'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'email', `title` = 'smtp_host', `value` = 'smtp.example.com'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'email', `title` = 'smtp_port', `value` = '25'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'email', `title` = 'smtp_encrypt', `value` = 'SSL'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'email', `title` = 'smtp_timeout', `value` = '5'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'email', `title` = 'smtp_username', `value` = ''");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'email', `title` = 'smtp_password', `value` = ''");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'email', `title` = 'from_name', `value` = '" . $this->db->escape($site_name) . "'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'email', `title` = 'from_email', `value` = 'comments@" . $this->db->escape($site_domain) . "'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'email', `title` = 'reply_email', `value` = 'no-reply@" . $this->db->escape($site_domain) . "'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'error_reporting', `title` = 'error_reporting_frontend', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'error_reporting', `title` = 'error_reporting_backend', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'error_reporting', `title` = 'error_reporting_method', `value` = 'log'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_form', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'hide_form', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'display_javascript_disabled', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'display_required_symbol', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'display_required_text', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'order_fields', `value` = 'comment,headline,upload,rating,user,website,geo,question'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_bb_code', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_smilies', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_counter', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_headline', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_upload', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_rating', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_email', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_website', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_town', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_state', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_country', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_question', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_captcha', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_notify', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_cookie', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_privacy', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_terms', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_preview', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'required_email', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'required_headline', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'required_website', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'required_town', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'required_state', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'required_country', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'required_rating', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'default_name', `value` = ''");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'default_email', `value` = ''");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'default_website', `value` = ''");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'default_town', `value` = ''");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'default_state', `value` = ''");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'default_country', `value` = ''");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'default_rating', `value` = ''");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'default_comment', `value` = ''");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'default_headline', `value` = ''");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'default_notify', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'default_cookie', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'default_privacy', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'default_terms', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'filled_name_cookie_action', `value` = 'hide'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'filled_name_login_action', `value` = 'hide'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'filled_email_cookie_action', `value` = 'hide'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'filled_email_login_action', `value` = 'hide'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'filled_website_cookie_action', `value` = 'hide'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'filled_website_login_action', `value` = 'hide'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'filled_town_cookie_action', `value` = 'hide'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'filled_town_login_action', `value` = 'hide'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'filled_state_cookie_action', `value` = 'hide'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'filled_state_login_action', `value` = 'hide'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'filled_country_cookie_action', `value` = 'hide'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'filled_country_login_action', `value` = 'hide'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'maximum_name', `value` = '30'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'maximum_email', `value` = '250'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'maximum_website', `value` = '250'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'maximum_town', `value` = '30'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'maximum_question', `value` = '30'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_bb_code_bold', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_bb_code_italic', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_bb_code_underline', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_bb_code_strike', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_bb_code_superscript', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_bb_code_subscript', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_bb_code_code', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_bb_code_php', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_bb_code_quote', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_bb_code_line', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_bb_code_bullet', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_bb_code_numeric', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_bb_code_link', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_bb_code_email', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_bb_code_image', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_bb_code_youtube', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_smilies_smile', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_smilies_sad', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_smilies_huh', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_smilies_laugh', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_smilies_mad', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_smilies_tongue', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_smilies_cry', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_smilies_grin', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_smilies_wink', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_smilies_scared', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_smilies_cool', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_smilies_sleep', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_smilies_blush', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_smilies_confused', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'enabled_smilies_shocked', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'agree_to_preview', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'repeat_rating', `value` = 'normal'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'captcha_type', `value` = 'image'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'recaptcha_public_key', `value` = ''");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'recaptcha_private_key', `value` = ''");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'recaptcha_theme', `value` = 'light'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'form', `title` = 'recaptcha_size', `value` = 'normal'");
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
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'language', `title` = 'language_frontend', `value` = 'english'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'language', `title` = 'language_backend', `value` = 'english'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'language', `title` = 'language_install', `value` = 'english'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'language', `title` = 'rtl', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'maintenance', `title` = 'maintenance_mode', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'maintenance', `title` = 'maintenance_message', `value` = 'We\'re currently in maintenance.'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'notice', `title` = 'notice_manage_admins', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'notice', `title` = 'notice_manage_bans', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'notice', `title` = 'notice_manage_comments', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'notice', `title` = 'notice_manage_countries', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'notice', `title` = 'notice_manage_pages', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'notice', `title` = 'notice_manage_questions', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'notice', `title` = 'notice_manage_subscriptions', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'notice', `title` = 'notice_edit_comment', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'notice', `title` = 'notice_edit_spam', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'notice', `title` = 'notice_settings_email_editor', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'notice', `title` = 'notice_settings_viewers', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'notice', `title` = 'notice_tool_upgrade', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'notice', `title` = 'notice_add_question', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'notice', `title` = 'notice_edit_question', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'notice', `title` = 'notice_extra_fields', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'one_name_enabled', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'fix_name_enabled', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'unique_name_enabled', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'detect_link_in_name_enabled', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'link_in_name_action', `value` = 'error'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'reserved_names_enabled', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'reserved_names_action', `value` = 'error'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'dummy_names_enabled', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'dummy_names_action', `value` = 'error'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'banned_names_enabled', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'banned_names_action', `value` = 'ban'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'unique_email_enabled', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'reserved_emails_enabled', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'reserved_emails_action', `value` = 'error'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'dummy_emails_enabled', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'dummy_emails_action', `value` = 'error'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'banned_emails_enabled', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'banned_emails_action', `value` = 'ban'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'approve_websites', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'validate_website_ping', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'reserved_websites_enabled', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'reserved_websites_action', `value` = 'error'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'dummy_websites_enabled', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'dummy_websites_action', `value` = 'error'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'banned_websites_as_website_enabled', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'banned_websites_as_website_action', `value` = 'ban'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'banned_websites_as_comment_enabled', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'banned_websites_as_comment_action', `value` = 'approve'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'banned_websites_as_headline_enabled', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'banned_websites_as_headline_action', `value` = 'approve'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'reserved_towns_enabled', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'reserved_towns_action', `value` = 'error'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'dummy_towns_enabled', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'dummy_towns_action', `value` = 'error'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'banned_towns_enabled', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'banned_towns_action', `value` = 'ban'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'fix_town_enabled', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'detect_link_in_town_enabled', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'link_in_town_action', `value` = 'error'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'headline_minimum_characters', `value` = '2'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'headline_minimum_words', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'headline_maximum_characters', `value` = '50'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'comment_minimum_characters', `value` = '2'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'comment_minimum_words', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'comment_maximum_characters', `value` = '1000'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'comment_maximum_lines', `value` = '50'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'comment_maximum_smilies', `value` = '5'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'comment_convert_links', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'comment_convert_emails', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'comment_links_new_window', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'comment_links_nofollow', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'comment_line_breaks', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'comment_long_word', `value` = '999'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'swear_word_masking', `value` = '*****'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'check_capitals_enabled', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'check_capitals_percentage', `value` = '50'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'check_capitals_action', `value` = 'error'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'mild_swear_words_enabled', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'mild_swear_words_action', `value` = 'mask'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'strong_swear_words_enabled', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'strong_swear_words_action', `value` = 'mask_approve'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'spam_words_enabled', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'spam_words_action', `value` = 'approve'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'detect_link_in_comment_enabled', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'link_in_comment_action', `value` = 'approve'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'detect_link_in_headline_enabled', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'link_in_headline_action', `value` = 'approve'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'approve_images', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'approve_videos', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'approve_uploads', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'check_repeats_enabled', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'check_repeats_amount', `value` = '5'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'check_repeats_action', `value` = 'error'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'flood_control_delay_enabled', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'flood_control_delay_time', `value` = '60'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'flood_control_delay_all_pages', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'flood_control_maximum_enabled', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'flood_control_maximum_amount', `value` = '10'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'flood_control_maximum_period', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'flood_control_maximum_all_pages', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'maximum_upload_size', `value` = '5'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'maximum_upload_amount', `value` = '3'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'maximum_upload_total', `value` = '5'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'notify_type', `value` = 'all'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'notify_format', `value` = 'html'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'form_cookie', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'processor', `title` = 'form_cookie_days', `value` = '365'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'rss', `title` = 'rss_new_window', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'rss', `title` = 'rss_limit_enabled', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'rss', `title` = 'rss_limit_amount', `value` = '30'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'security', `title` = 'ban_cookie_days', `value` = '30'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'security', `title` = 'security_key', `value` = '" . $this->db->escape($security_key) . "'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'security', `title` = 'session_key', `value` = '" . $this->db->escape($session_key) . "'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'security', `title` = 'encryption_key', `value` = '" . $this->db->escape($encryption_key) . "'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'security', `title` = 'check_referrer', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'security', `title` = 'check_config', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'security', `title` = 'check_honeypot', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'security', `title` = 'check_time', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'security', `title` = 'check_ip_address', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'system', `title` = 'site_id', `value` = '" . $this->db->escape($site_id) . "'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'system', `title` = 'site_name', `value` = '" . $this->db->escape($site_name) . "'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'system', `title` = 'site_domain', `value` = '" . $this->db->escape($site_domain) . "'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'system', `title` = 'site_url', `value` = '" . $this->db->escape($site_url) . "'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'system', `title` = 'time_zone', `value` = '" . $this->db->escape($time_zone) . "'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'system', `title` = 'commentics_folder', `value` = '" . $this->db->escape($commentics_folder) . "'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'system', `title` = 'commentics_url', `value` = '" . $this->db->escape($commentics_url) . "'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'system', `title` = 'backend_folder', `value` = '" . $this->db->escape($backend_folder) . "'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'system', `title` = 'ssl_certificate', `value` = '" . (int) $ssl_certificate . "'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'system', `title` = 'use_wysiwyg', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'system', `title` = 'display_parsing', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'system', `title` = 'is_demo', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'system', `title` = 'limit_results', `value` = '15'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'system', `title` = 'delay_pages', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'system', `title` = 'lower_pages', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'system', `title` = 'admin_cookie_days', `value` = '365'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'system', `title` = 'optimize_date', `value` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'system', `title` = 'last_call', `value` = ''");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'system', `title` = 'last_task', `value` = ''");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'system', `title` = 'new_version_notified', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'system', `title` = 'purpose', `value` = '" . $this->db->escape($purpose) . "'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'licence', `title` = 'licence', `value` = ''");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'licence', `title` = 'forum_user', `value` = ''");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'tasks', `title` = 'task_enabled_delete_bans', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'tasks', `title` = 'days_to_delete_bans', `value` = '30'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'tasks', `title` = 'task_enabled_delete_comments', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'tasks', `title` = 'days_to_delete_comments', `value` = '365'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'tasks', `title` = 'task_enabled_delete_reporters', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'tasks', `title` = 'days_to_delete_reporters', `value` = '30'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'tasks', `title` = 'task_enabled_delete_subscriptions', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'tasks', `title` = 'days_to_delete_subscriptions', `value` = '7'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'tasks', `title` = 'task_enabled_delete_voters', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'tasks', `title` = 'days_to_delete_voters', `value` = '30'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'theme', `title` = 'theme_frontend', `value` = 'default'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'theme', `title` = 'theme_backend', `value` = 'default'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'theme', `title` = 'theme_install', `value` = 'default'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'theme', `title` = 'auto_detect', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'theme', `title` = 'optimize', `value` = '1'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'theme', `title` = 'jquery_source', `value` = ''");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'theme', `title` = 'order_parts', `value` = 'form,comments'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'viewers', `title` = 'viewers_enabled', `value` = '0'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'viewers', `title` = 'viewers_timeout', `value` = '1200'");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'warning', `title` = 'warning_manage_pages', `value` = '1'");
        /*****************************************************************************************************************/
    }

    public function createTableSites($site_name, $site_domain, $site_url)
    {
        /********************************************** CREATE TABLE 'sites' *********************************************/
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . CMTX_DB_PREFIX . "sites` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `name` varchar(250) NOT NULL default '',
            `domain` varchar(250) NOT NULL default '',
            `url` varchar(250) NOT NULL default '',
            `iframe_enabled` tinyint(1) unsigned NOT NULL default '1',
            `new_pages` tinyint(1) unsigned NOT NULL default '1',
            `from_name` varchar(250) NOT NULL default '',
            `from_email` varchar(250) NOT NULL default '',
            `reply_email` varchar(250) NOT NULL default '',
            `date_modified` datetime NOT NULL,
            `date_added` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "sites` SET `id` = '1', `name` = '" . $this->db->escape($site_name) . "', `domain` = '" . $this->db->escape($site_domain) . "', `url` = '" . $this->db->escape($site_url) . "', `iframe_enabled` = '1', `new_pages` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        /*****************************************************************************************************************/
    }

    public function createTableStates()
    {
        /********************************************** CREATE TABLE 'states' ********************************************/
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . CMTX_DB_PREFIX . "states` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `name` varchar(250) NOT NULL default '',
            `country_code` varchar(3) NOT NULL default '',
            `enabled` tinyint(1) unsigned NOT NULL default '1',
            `date_modified` datetime NOT NULL,
            `date_added` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        // Afghanistan
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Badakhshan') . "', `country_code` = 'AFG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Badghis') . "', `country_code` = 'AFG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Baghlan') . "', `country_code` = 'AFG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Balkh') . "', `country_code` = 'AFG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bamyan') . "', `country_code` = 'AFG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Daykundi') . "', `country_code` = 'AFG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Farah') . "', `country_code` = 'AFG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Faryab') . "', `country_code` = 'AFG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ghazni') . "', `country_code` = 'AFG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ghor') . "', `country_code` = 'AFG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Helmand') . "', `country_code` = 'AFG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Herat') . "', `country_code` = 'AFG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jowzjan') . "', `country_code` = 'AFG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kabul') . "', `country_code` = 'AFG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kandahar') . "', `country_code` = 'AFG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kapisa') . "', `country_code` = 'AFG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Khost') . "', `country_code` = 'AFG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kunar') . "', `country_code` = 'AFG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kunduz') . "', `country_code` = 'AFG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Laghman') . "', `country_code` = 'AFG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Logar') . "', `country_code` = 'AFG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Maidan Wardak') . "', `country_code` = 'AFG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nangarhar') . "', `country_code` = 'AFG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nimruz') . "', `country_code` = 'AFG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nuristan') . "', `country_code` = 'AFG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Paktia') . "', `country_code` = 'AFG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Paktika') . "', `country_code` = 'AFG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Samangan') . "', `country_code` = 'AFG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sar-e Pol') . "', `country_code` = 'AFG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Takhar') . "', `country_code` = 'AFG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Urozgan') . "', `country_code` = 'AFG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zabol') . "', `country_code` = 'AFG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Albania
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Berat') . "', `country_code` = 'ALB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dibër') . "', `country_code` = 'ALB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Durrës') . "', `country_code` = 'ALB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Elbasan') . "', `country_code` = 'ALB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Fier') . "', `country_code` = 'ALB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gjirokastër') . "', `country_code` = 'ALB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Korçë') . "', `country_code` = 'ALB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kukës') . "', `country_code` = 'ALB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lezhë') . "', `country_code` = 'ALB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Shkodër') . "', `country_code` = 'ALB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tirana') . "', `country_code` = 'ALB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vlorë') . "', `country_code` = 'ALB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Algeria
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Adrar') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Aïn Defla') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Aïn Témouchent') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Algiers') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Annaba') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Batna') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Béchar') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Béjaïa') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Biskra') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Blida') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bordj Bou Arréridj') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bouira') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Boumerdès') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chlef') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Constantine') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Djelfa') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('El Bayadh') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('El Oued') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('El Tarf') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ghardaïa') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Guelma') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Illizi') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jijel') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Khenchela') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Laghouat') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mascara') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Médéa') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mila') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mostaganem') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('M\'Sila') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Naama') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Oran') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ouargla') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Oum el-Bouaghi') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Relizane') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saïda') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sétif') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sidi Bel Abbes') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Skikda') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Souk Ahras') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tamanghasset') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tébessa') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tiaret') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tindouf') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tipasa') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tissemsilt') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tizi Ouzou') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tlemcen') . "', `country_code` = 'DZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Andorra
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Andorra la Vella') . "', `country_code` = 'AND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Canillo') . "', `country_code` = 'AND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Encamp') . "', `country_code` = 'AND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('La Massana') . "', `country_code` = 'AND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Les Escaldes-Engordany') . "', `country_code` = 'AND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ordino') . "', `country_code` = 'AND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sant Julià de Lòria') . "', `country_code` = 'AND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Angola
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bengo') . "', `country_code` = 'AGO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Benguela') . "', `country_code` = 'AGO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bié') . "', `country_code` = 'AGO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cabinda') . "', `country_code` = 'AGO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cuando Cubango') . "', `country_code` = 'AGO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cuanza Norte') . "', `country_code` = 'AGO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cuanza Sul') . "', `country_code` = 'AGO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cunene') . "', `country_code` = 'AGO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Huambo') . "', `country_code` = 'AGO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Huila') . "', `country_code` = 'AGO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Luanda') . "', `country_code` = 'AGO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lunda Norte') . "', `country_code` = 'AGO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lunda Sul') . "', `country_code` = 'AGO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Malanje') . "', `country_code` = 'AGO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Moxico') . "', `country_code` = 'AGO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Namibe') . "', `country_code` = 'AGO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Uige') . "', `country_code` = 'AGO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zaire') . "', `country_code` = 'AGO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Antigua and Barbuda
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Barbuda') . "', `country_code` = 'ATG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Redonda') . "', `country_code` = 'ATG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint George') . "', `country_code` = 'ATG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint John') . "', `country_code` = 'ATG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Mary') . "', `country_code` = 'ATG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Paul') . "', `country_code` = 'ATG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Peter') . "', `country_code` = 'ATG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Philip') . "', `country_code` = 'ATG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Argentina
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Buenos Aires') . "', `country_code` = 'ARG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Catamarca') . "', `country_code` = 'ARG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chaco') . "', `country_code` = 'ARG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chubut') . "', `country_code` = 'ARG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Córdoba') . "', `country_code` = 'ARG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Corrientes') . "', `country_code` = 'ARG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Distrito Federal') . "', `country_code` = 'ARG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Entre Rios') . "', `country_code` = 'ARG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Formosa') . "', `country_code` = 'ARG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jujuy') . "', `country_code` = 'ARG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('La Pampa') . "', `country_code` = 'ARG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('La Rioja') . "', `country_code` = 'ARG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mendoza') . "', `country_code` = 'ARG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Misiones') . "', `country_code` = 'ARG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Neuquén') . "', `country_code` = 'ARG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rio Negro') . "', `country_code` = 'ARG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Salta') . "', `country_code` = 'ARG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('San Juan') . "', `country_code` = 'ARG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('San Luis') . "', `country_code` = 'ARG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Santa Cruz') . "', `country_code` = 'ARG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Santa Fe') . "', `country_code` = 'ARG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Santiago del Estero') . "', `country_code` = 'ARG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tierra del Fuego') . "', `country_code` = 'ARG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tucumán') . "', `country_code` = 'ARG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Armenia
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Aragatsotn') . "', `country_code` = 'ARM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ararat') . "', `country_code` = 'ARM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Armavir') . "', `country_code` = 'ARM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gegharkunik') . "', `country_code` = 'ARM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kotayk') . "', `country_code` = 'ARM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lori') . "', `country_code` = 'ARM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Shirak') . "', `country_code` = 'ARM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Syunik') . "', `country_code` = 'ARM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tavush') . "', `country_code` = 'ARM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vayots Dzor') . "', `country_code` = 'ARM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Yerevan') . "', `country_code` = 'ARM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Australia
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Australian Capital Territory') . "', `country_code` = 'AUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('New South Wales') . "', `country_code` = 'AUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Northern Territory') . "', `country_code` = 'AUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Queensland') . "', `country_code` = 'AUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('South Australia') . "', `country_code` = 'AUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tasmania') . "', `country_code` = 'AUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Victoria') . "', `country_code` = 'AUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Western Australia') . "', `country_code` = 'AUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Austria
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Burgenland') . "', `country_code` = 'AUT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kärnten') . "', `country_code` = 'AUT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Niederösterreich') . "', `country_code` = 'AUT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Oberösterreich') . "', `country_code` = 'AUT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Salzburg') . "', `country_code` = 'AUT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Steiermark') . "', `country_code` = 'AUT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tirol') . "', `country_code` = 'AUT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vorarlberg') . "', `country_code` = 'AUT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Wien') . "', `country_code` = 'AUT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Azerbaijan
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Absheron') . "', `country_code` = 'AZE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Aran') . "', `country_code` = 'AZE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Daglig-Shirvan') . "', `country_code` = 'AZE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ganja-Qazakh') . "', `country_code` = 'AZE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kalbajar-Lachin') . "', `country_code` = 'AZE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lankaran') . "', `country_code` = 'AZE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nakhchivan') . "', `country_code` = 'AZE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Quba-Khachmaz') . "', `country_code` = 'AZE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Shaki-Zaqatala') . "', `country_code` = 'AZE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Yukhari-Karabakh') . "', `country_code` = 'AZE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Bahamas
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Abaco') . "', `country_code` = 'BHS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Acklins') . "', `country_code` = 'BHS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Andros') . "', `country_code` = 'BHS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Berry Islands') . "', `country_code` = 'BHS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bimini') . "', `country_code` = 'BHS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cat Island') . "', `country_code` = 'BHS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Crooked Island') . "', `country_code` = 'BHS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Eleuthera') . "', `country_code` = 'BHS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Exuma and Cays') . "', `country_code` = 'BHS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Grand Bahama') . "', `country_code` = 'BHS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Harbour Island') . "', `country_code` = 'BHS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Inagua') . "', `country_code` = 'BHS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Long Island') . "', `country_code` = 'BHS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mayaguana') . "', `country_code` = 'BHS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('New Providence') . "', `country_code` = 'BHS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ragged') . "', `country_code` = 'BHS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rum Cay') . "', `country_code` = 'BHS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('San Salvador') . "', `country_code` = 'BHS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Spanish Wells') . "', `country_code` = 'BHS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Bahrain
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Capital') . "', `country_code` = 'BHR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Muharraq') . "', `country_code` = 'BHR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Northern') . "', `country_code` = 'BHR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Southern') . "', `country_code` = 'BHR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Bangladesh
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Barisal') . "', `country_code` = 'BGD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chittagong') . "', `country_code` = 'BGD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dhaka') . "', `country_code` = 'BGD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Khulna') . "', `country_code` = 'BGD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mymensingh') . "', `country_code` = 'BGD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rajshahi') . "', `country_code` = 'BGD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rangpur') . "', `country_code` = 'BGD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sylhet') . "', `country_code` = 'BGD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Barbados
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Christ Church') . "', `country_code` = 'BRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Andrew') . "', `country_code` = 'BRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint George') . "', `country_code` = 'BRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint James') . "', `country_code` = 'BRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint John') . "', `country_code` = 'BRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Joseph') . "', `country_code` = 'BRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Lucy') . "', `country_code` = 'BRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Michael') . "', `country_code` = 'BRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Peter') . "', `country_code` = 'BRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Philip') . "', `country_code` = 'BRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Thomas') . "', `country_code` = 'BRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Belarus
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Brest') . "', `country_code` = 'BLR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gomel') . "', `country_code` = 'BLR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Grodno') . "', `country_code` = 'BLR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Minsk') . "', `country_code` = 'BLR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mogilev') . "', `country_code` = 'BLR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vitebsk') . "', `country_code` = 'BLR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Belgium
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Antwerp') . "', `country_code` = 'BEL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('East Flanders') . "', `country_code` = 'BEL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Flemish Brabant') . "', `country_code` = 'BEL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hainaut') . "', `country_code` = 'BEL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Liège') . "', `country_code` = 'BEL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Limburg') . "', `country_code` = 'BEL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Luxembourg') . "', `country_code` = 'BEL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Namur') . "', `country_code` = 'BEL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Walloon Brabant') . "', `country_code` = 'BEL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('West Flanders') . "', `country_code` = 'BEL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Belize
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Belize') . "', `country_code` = 'BLZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cayo') . "', `country_code` = 'BLZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Corozal') . "', `country_code` = 'BLZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Orange Walk') . "', `country_code` = 'BLZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Stann Creek') . "', `country_code` = 'BLZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Toledo') . "', `country_code` = 'BLZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Benin
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Alibori') . "', `country_code` = 'BEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Atakira') . "', `country_code` = 'BEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Atlantique') . "', `country_code` = 'BEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Borgou') . "', `country_code` = 'BEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Collines') . "', `country_code` = 'BEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Donga') . "', `country_code` = 'BEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kouffo') . "', `country_code` = 'BEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Littoral') . "', `country_code` = 'BEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mono') . "', `country_code` = 'BEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ouémé') . "', `country_code` = 'BEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Plateau') . "', `country_code` = 'BEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zou') . "', `country_code` = 'BEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Bhutan
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bumthang') . "', `country_code` = 'BTN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chukha') . "', `country_code` = 'BTN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dagana') . "', `country_code` = 'BTN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gasa') . "', `country_code` = 'BTN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Haa') . "', `country_code` = 'BTN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lhuntse') . "', `country_code` = 'BTN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mongar') . "', `country_code` = 'BTN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Paro') . "', `country_code` = 'BTN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pemagatshel') . "', `country_code` = 'BTN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Punakha') . "', `country_code` = 'BTN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Samdrup Jongkhar') . "', `country_code` = 'BTN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Samtse') . "', `country_code` = 'BTN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sarpang') . "', `country_code` = 'BTN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Thimphu') . "', `country_code` = 'BTN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Trashigang') . "', `country_code` = 'BTN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Trashiyangste') . "', `country_code` = 'BTN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Trongsa') . "', `country_code` = 'BTN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tsirang') . "', `country_code` = 'BTN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Wangdue Phodrang') . "', `country_code` = 'BTN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zhemgang') . "', `country_code` = 'BTN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Bolivia
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Beni') . "', `country_code` = 'BOL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chuquisaca') . "', `country_code` = 'BOL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cochabamba') . "', `country_code` = 'BOL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('La Paz') . "', `country_code` = 'BOL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Oruro') . "', `country_code` = 'BOL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pando') . "', `country_code` = 'BOL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Potosi') . "', `country_code` = 'BOL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Santa Cruz') . "', `country_code` = 'BOL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tarija') . "', `country_code` = 'BOL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Bosnia and Herzegovina
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bosnia-Podrinje Canton Goražde') . "', `country_code` = 'BIH', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Canton 10') . "', `country_code` = 'BIH', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Central Bosnia Canton') . "', `country_code` = 'BIH', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Federation of Bosnia and Herzegovina') . "', `country_code` = 'BIH', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Herzegovina-Neretva Canton') . "', `country_code` = 'BIH', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Posavina Canton') . "', `country_code` = 'BIH', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sarajevo Canton') . "', `country_code` = 'BIH', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tuzla Canton') . "', `country_code` = 'BIH', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Una-Sana Canton') . "', `country_code` = 'BIH', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('West Herzegovina Canton') . "', `country_code` = 'BIH', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zenica-Doboj Canton') . "', `country_code` = 'BIH', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Botswana
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Central') . "', `country_code` = 'BWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ghanzi') . "', `country_code` = 'BWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kgalagadi') . "', `country_code` = 'BWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kgatleng') . "', `country_code` = 'BWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kweneng') . "', `country_code` = 'BWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North East') . "', `country_code` = 'BWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North West') . "', `country_code` = 'BWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('South East') . "', `country_code` = 'BWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Southern') . "', `country_code` = 'BWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Brazil
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Acre') . "', `country_code` = 'BRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Alagoas') . "', `country_code` = 'BRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Amapá') . "', `country_code` = 'BRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Amazonas') . "', `country_code` = 'BRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bahia') . "', `country_code` = 'BRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ceará') . "', `country_code` = 'BRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Distrito Federal') . "', `country_code` = 'BRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Espírito Santo') . "', `country_code` = 'BRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Goiás') . "', `country_code` = 'BRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Maranhão') . "', `country_code` = 'BRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mato Grosso') . "', `country_code` = 'BRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mato Grosso do Sul') . "', `country_code` = 'BRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Minas Gerais') . "', `country_code` = 'BRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pará') . "', `country_code` = 'BRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Paraíba') . "', `country_code` = 'BRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Paraná') . "', `country_code` = 'BRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pernambuco') . "', `country_code` = 'BRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Piauí') . "', `country_code` = 'BRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rio de Janeiro') . "', `country_code` = 'BRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rio Grande do Norte') . "', `country_code` = 'BRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rio Grande do Sul') . "', `country_code` = 'BRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rondônia') . "', `country_code` = 'BRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Roraima') . "', `country_code` = 'BRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Santa Catarina') . "', `country_code` = 'BRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('São Paulo') . "', `country_code` = 'BRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sergipe') . "', `country_code` = 'BRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tocantins') . "', `country_code` = 'BRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Brunei
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Belait') . "', `country_code` = 'BRN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Brunei-Muara') . "', `country_code` = 'BRN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Temburong') . "', `country_code` = 'BRN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tutong') . "', `country_code` = 'BRN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Bulgaria
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Blagoevgrad') . "', `country_code` = 'BGR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Burgas') . "', `country_code` = 'BGR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dobrich') . "', `country_code` = 'BGR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gabrovo') . "', `country_code` = 'BGR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Haskovo') . "', `country_code` = 'BGR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kardzhali') . "', `country_code` = 'BGR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kyustendil') . "', `country_code` = 'BGR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lovech') . "', `country_code` = 'BGR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Montana') . "', `country_code` = 'BGR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pazardzhik') . "', `country_code` = 'BGR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pernik') . "', `country_code` = 'BGR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pleven') . "', `country_code` = 'BGR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Plovdiv') . "', `country_code` = 'BGR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Razgrad') . "', `country_code` = 'BGR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ruse') . "', `country_code` = 'BGR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Shumen') . "', `country_code` = 'BGR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Silistra') . "', `country_code` = 'BGR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sliven') . "', `country_code` = 'BGR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Smolyan') . "', `country_code` = 'BGR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sofia') . "', `country_code` = 'BGR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sofia City') . "', `country_code` = 'BGR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Stara Zagora') . "', `country_code` = 'BGR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Targovishte') . "', `country_code` = 'BGR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Varna') . "', `country_code` = 'BGR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Veliko Tarnovo') . "', `country_code` = 'BGR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vidin') . "', `country_code` = 'BGR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vratsa') . "', `country_code` = 'BGR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Yambol') . "', `country_code` = 'BGR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Burkina Faso
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Boucle du Mouhoun') . "', `country_code` = 'BFA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cascades') . "', `country_code` = 'BFA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Centre') . "', `country_code` = 'BFA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Centre-Est') . "', `country_code` = 'BFA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Centre-Nord') . "', `country_code` = 'BFA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Centre-Ouest') . "', `country_code` = 'BFA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Centre-Sud') . "', `country_code` = 'BFA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Est') . "', `country_code` = 'BFA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hauts-Bassins') . "', `country_code` = 'BFA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nord') . "', `country_code` = 'BFA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Plateau-Central') . "', `country_code` = 'BFA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sahel') . "', `country_code` = 'BFA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sud-Ouest') . "', `country_code` = 'BFA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Burundi
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bubanza') . "', `country_code` = 'BDI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bujumbura Mairie') . "', `country_code` = 'BDI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bujumbura Rural') . "', `country_code` = 'BDI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bururi') . "', `country_code` = 'BDI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cankuzo') . "', `country_code` = 'BDI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cibitoke') . "', `country_code` = 'BDI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gitega') . "', `country_code` = 'BDI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Karuzi') . "', `country_code` = 'BDI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kayanza') . "', `country_code` = 'BDI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kirundo') . "', `country_code` = 'BDI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Makamba') . "', `country_code` = 'BDI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Muramvya') . "', `country_code` = 'BDI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Muyinga') . "', `country_code` = 'BDI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mwaro') . "', `country_code` = 'BDI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ngozi') . "', `country_code` = 'BDI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rumonge') . "', `country_code` = 'BDI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rutana') . "', `country_code` = 'BDI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ruyigi') . "', `country_code` = 'BDI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Cambodia
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Banteay Meanchey') . "', `country_code` = 'KHM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Battambang') . "', `country_code` = 'KHM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kampong Cham') . "', `country_code` = 'KHM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kampong Chhnang') . "', `country_code` = 'KHM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kampong Speu') . "', `country_code` = 'KHM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kampong Thom') . "', `country_code` = 'KHM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kampot') . "', `country_code` = 'KHM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kandal') . "', `country_code` = 'KHM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kep') . "', `country_code` = 'KHM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Koh Kong') . "', `country_code` = 'KHM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kratié') . "', `country_code` = 'KHM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mondulkiri') . "', `country_code` = 'KHM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Oddar Meanchey') . "', `country_code` = 'KHM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pailin') . "', `country_code` = 'KHM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Phnom Penh') . "', `country_code` = 'KHM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Preah Sihanouk') . "', `country_code` = 'KHM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Preah Vihear') . "', `country_code` = 'KHM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Prey Veng') . "', `country_code` = 'KHM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pursat') . "', `country_code` = 'KHM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ratanakiri') . "', `country_code` = 'KHM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Siem Reap') . "', `country_code` = 'KHM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Stung Treng') . "', `country_code` = 'KHM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Svay Rieng') . "', `country_code` = 'KHM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Takéo') . "', `country_code` = 'KHM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tboung Khmum') . "', `country_code` = 'KHM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Cameroon
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Adamawa') . "', `country_code` = 'CMR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Centre') . "', `country_code` = 'CMR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('East') . "', `country_code` = 'CMR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Far North') . "', `country_code` = 'CMR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Littoral') . "', `country_code` = 'CMR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North') . "', `country_code` = 'CMR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Northwest') . "', `country_code` = 'CMR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('South') . "', `country_code` = 'CMR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Southwest') . "', `country_code` = 'CMR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('West') . "', `country_code` = 'CMR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Canada
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Alberta') . "', `country_code` = 'CAN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('British Columbia') . "', `country_code` = 'CAN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Manitoba') . "', `country_code` = 'CAN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('New Brunswick') . "', `country_code` = 'CAN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Newfoundland and Labrador') . "', `country_code` = 'CAN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Northwest Territories') . "', `country_code` = 'CAN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nova Scotia') . "', `country_code` = 'CAN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ontario') . "', `country_code` = 'CAN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Prince Edward Island') . "', `country_code` = 'CAN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Quebec') . "', `country_code` = 'CAN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saskatchewan') . "', `country_code` = 'CAN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Cape Verde
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Barlavento') . "', `country_code` = 'CPV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sotavento') . "', `country_code` = 'CPV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Central African Republic
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bamingui-Bangoran') . "', `country_code` = 'CAF', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Basse-Kotto') . "', `country_code` = 'CAF', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Haute-Kotto') . "', `country_code` = 'CAF', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Haut-Mbomou') . "', `country_code` = 'CAF', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kemo') . "', `country_code` = 'CAF', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lobaye') . "', `country_code` = 'CAF', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mambere-KadeÔ') . "', `country_code` = 'CAF', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mbomou') . "', `country_code` = 'CAF', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nana-Mambere') . "', `country_code` = 'CAF', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ombella-M\'Poko') . "', `country_code` = 'CAF', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ouaka') . "', `country_code` = 'CAF', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ouham') . "', `country_code` = 'CAF', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ouham-Pende') . "', `country_code` = 'CAF', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vakaga') . "', `country_code` = 'CAF', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nana-Grebizi') . "', `country_code` = 'CAF', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sangha-Mbaere') . "', `country_code` = 'CAF', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bangui') . "', `country_code` = 'CAF', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Chad
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bahr El Gazel') . "', `country_code` = 'TCD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Batha') . "', `country_code` = 'TCD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Borkou') . "', `country_code` = 'TCD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chari-Baguirmi') . "', `country_code` = 'TCD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ennedi-Est') . "', `country_code` = 'TCD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ennedi-Ouest') . "', `country_code` = 'TCD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Guéra') . "', `country_code` = 'TCD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hadjer-Lamis') . "', `country_code` = 'TCD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kanem') . "', `country_code` = 'TCD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lac') . "', `country_code` = 'TCD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Logone Occidental') . "', `country_code` = 'TCD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Logone Oriental') . "', `country_code` = 'TCD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mandoul') . "', `country_code` = 'TCD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mayo-Kebbi Est') . "', `country_code` = 'TCD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mayo-Kebbi Ouest') . "', `country_code` = 'TCD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Moyen-Chari') . "', `country_code` = 'TCD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('N\'Djamena') . "', `country_code` = 'TCD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ouaddai') . "', `country_code` = 'TCD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Salamat') . "', `country_code` = 'TCD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sila') . "', `country_code` = 'TCD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tandjilé') . "', `country_code` = 'TCD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tibesti') . "', `country_code` = 'TCD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Wadi Fira') . "', `country_code` = 'TCD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Chile
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Antofagasta') . "', `country_code` = 'CHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Araucanía') . "', `country_code` = 'CHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Arica y Parinacota') . "', `country_code` = 'CHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Atacama') . "', `country_code` = 'CHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Aysén del General Carlos Ibáñez del Campo') . "', `country_code` = 'CHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bío Bío') . "', `country_code` = 'CHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Coquimbo') . "', `country_code` = 'CHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Libertador General Bernardo O\'Higgins') . "', `country_code` = 'CHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Los Lagos') . "', `country_code` = 'CHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Los Ríos') . "', `country_code` = 'CHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Magallanes y la Antártica Chilena') . "', `country_code` = 'CHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Maule') . "', `country_code` = 'CHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Santiago Metropolitan') . "', `country_code` = 'CHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tarapacá') . "', `country_code` = 'CHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Valparaíso') . "', `country_code` = 'CHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // China
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Anhui') . "', `country_code` = 'CHN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Beijing') . "', `country_code` = 'CHN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chongqing') . "', `country_code` = 'CHN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Fujian') . "', `country_code` = 'CHN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gansu') . "', `country_code` = 'CHN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Guangdong') . "', `country_code` = 'CHN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Guangxi') . "', `country_code` = 'CHN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Guizhou') . "', `country_code` = 'CHN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hainan') . "', `country_code` = 'CHN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hebei') . "', `country_code` = 'CHN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Heilongjiang') . "', `country_code` = 'CHN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Henan') . "', `country_code` = 'CHN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hong Kong') . "', `country_code` = 'CHN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hubei') . "', `country_code` = 'CHN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hunan') . "', `country_code` = 'CHN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Inner Mongolia') . "', `country_code` = 'CHN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jiangsu') . "', `country_code` = 'CHN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jiangxi') . "', `country_code` = 'CHN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jilin') . "', `country_code` = 'CHN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Liaoning') . "', `country_code` = 'CHN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Macau') . "', `country_code` = 'CHN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ningxia') . "', `country_code` = 'CHN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Qinghai') . "', `country_code` = 'CHN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Shaanxi') . "', `country_code` = 'CHN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Shandong') . "', `country_code` = 'CHN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Shanghai') . "', `country_code` = 'CHN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Shanxi') . "', `country_code` = 'CHN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sichuan') . "', `country_code` = 'CHN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
		$this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Taiwan') . "', `country_code` = 'CHN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
		$this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tianjin') . "', `country_code` = 'CHN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tibet') . "', `country_code` = 'CHN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Xinjiang') . "', `country_code` = 'CHN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Yunnan') . "', `country_code` = 'CHN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zhejiang') . "', `country_code` = 'CHN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Colombia
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Amazonas') . "', `country_code` = 'COL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Antioquia') . "', `country_code` = 'COL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Arauca') . "', `country_code` = 'COL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Atlántico') . "', `country_code` = 'COL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bogotá') . "', `country_code` = 'COL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bolívar') . "', `country_code` = 'COL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Boyacá') . "', `country_code` = 'COL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Caldas') . "', `country_code` = 'COL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Caquetá') . "', `country_code` = 'COL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Casanare') . "', `country_code` = 'COL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cauca') . "', `country_code` = 'COL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cesar') . "', `country_code` = 'COL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chocó') . "', `country_code` = 'COL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Córdoba') . "', `country_code` = 'COL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cundinamarca') . "', `country_code` = 'COL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Guainía') . "', `country_code` = 'COL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Guajira') . "', `country_code` = 'COL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Huila') . "', `country_code` = 'COL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('La Guajira') . "', `country_code` = 'COL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Magdalena') . "', `country_code` = 'COL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Meta') . "', `country_code` = 'COL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nariño') . "', `country_code` = 'COL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Norte de Santander') . "', `country_code` = 'COL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Putumayo') . "', `country_code` = 'COL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Quindío') . "', `country_code` = 'COL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Risaralda') . "', `country_code` = 'COL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('San Andrés y Providencia  ') . "', `country_code` = 'COL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Santander') . "', `country_code` = 'COL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sucre') . "', `country_code` = 'COL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tolima') . "', `country_code` = 'COL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Valle del Cauca') . "', `country_code` = 'COL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vaupés') . "', `country_code` = 'COL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vichada') . "', `country_code` = 'COL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Comoros
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Anjouan') . "', `country_code` = 'COM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Grande Comore') . "', `country_code` = 'COM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mayotte') . "', `country_code` = 'COM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mohéli') . "', `country_code` = 'COM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Congo, Republic
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bouenza') . "', `country_code` = 'COG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Brazzaville') . "', `country_code` = 'COG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cuvette') . "', `country_code` = 'COG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cuvette-Ouest') . "', `country_code` = 'COG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kouilou') . "', `country_code` = 'COG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lékoumou') . "', `country_code` = 'COG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Likouala') . "', `country_code` = 'COG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Niari') . "', `country_code` = 'COG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Plateaux') . "', `country_code` = 'COG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pointe-Noire') . "', `country_code` = 'COG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pool') . "', `country_code` = 'COG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sangha') . "', `country_code` = 'COG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Congo, Democratic Republic
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Équateur') . "', `country_code` = 'COD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Haut-Katanga') . "', `country_code` = 'COD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Haut-Lomami') . "', `country_code` = 'COD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Haut-Uele') . "', `country_code` = 'COD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ituri') . "', `country_code` = 'COD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kasaï') . "', `country_code` = 'COD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kasaï-Central') . "', `country_code` = 'COD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kasaï-Oriental') . "', `country_code` = 'COD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kinshasa') . "', `country_code` = 'COD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kongo Central') . "', `country_code` = 'COD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kwango') . "', `country_code` = 'COD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kwilu') . "', `country_code` = 'COD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lomami') . "', `country_code` = 'COD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lualaba') . "', `country_code` = 'COD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mai-Ndombe') . "', `country_code` = 'COD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Maniema') . "', `country_code` = 'COD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mongala') . "', `country_code` = 'COD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nord-Ubangi') . "', `country_code` = 'COD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North Kivu') . "', `country_code` = 'COD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sankuru	Lusambo') . "', `country_code` = 'COD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('South Kivu') . "', `country_code` = 'COD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sud-Ubangi') . "', `country_code` = 'COD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tanganyika') . "', `country_code` = 'COD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tshopo') . "', `country_code` = 'COD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tshuapa') . "', `country_code` = 'COD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Costa Rica
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Alajuela') . "', `country_code` = 'CRI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cartago') . "', `country_code` = 'CRI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Guanacaste') . "', `country_code` = 'CRI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Heredia') . "', `country_code` = 'CRI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Limón') . "', `country_code` = 'CRI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Puntarenas') . "', `country_code` = 'CRI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('San José') . "', `country_code` = 'CRI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Croatia
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bjelovar-Bilogora') . "', `country_code` = 'HRV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Brod-Posavina') . "', `country_code` = 'HRV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dubrovnik-Neretva') . "', `country_code` = 'HRV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Istria') . "', `country_code` = 'HRV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Karlovac') . "', `country_code` = 'HRV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Koprivnica-Križevci') . "', `country_code` = 'HRV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Krapina-Zagorje') . "', `country_code` = 'HRV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lika-Senj') . "', `country_code` = 'HRV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Medimurje') . "', `country_code` = 'HRV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Osijek-Baranja') . "', `country_code` = 'HRV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Požega-Slavonia') . "', `country_code` = 'HRV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Primorje-Gorski Kotar') . "', `country_code` = 'HRV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Šibenik-Knin') . "', `country_code` = 'HRV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sisak-Moslavina') . "', `country_code` = 'HRV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Split-Dalmatia') . "', `country_code` = 'HRV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Varaždin') . "', `country_code` = 'HRV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Virovitica-Podravina') . "', `country_code` = 'HRV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vukovar-Srijem') . "', `country_code` = 'HRV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zadar') . "', `country_code` = 'HRV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zagreb County') . "', `country_code` = 'HRV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Cuba
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Artemisa') . "', `country_code` = 'CUB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Camagüey') . "', `country_code` = 'CUB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ciego de Ávila') . "', `country_code` = 'CUB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cienfuegos') . "', `country_code` = 'CUB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Granma') . "', `country_code` = 'CUB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Guantánamo') . "', `country_code` = 'CUB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Holguín') . "', `country_code` = 'CUB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Isla de la Juventud') . "', `country_code` = 'CUB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('La Habana') . "', `country_code` = 'CUB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Las Tunas') . "', `country_code` = 'CUB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Matanzas') . "', `country_code` = 'CUB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mayabeque') . "', `country_code` = 'CUB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pinar del Río') . "', `country_code` = 'CUB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sancti Spíritus') . "', `country_code` = 'CUB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Santiago de Cuba') . "', `country_code` = 'CUB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Villa Clara') . "', `country_code` = 'CUB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Cyprus
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Famagusta') . "', `country_code` = 'CYP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kyrenia') . "', `country_code` = 'CYP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Larnaca') . "', `country_code` = 'CYP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Limassol') . "', `country_code` = 'CYP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nicosia') . "', `country_code` = 'CYP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Paphos') . "', `country_code` = 'CYP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Czech Republic
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hlavní mesto') . "', `country_code` = 'CZE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jihomoravský') . "', `country_code` = 'CZE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jihoceský') . "', `country_code` = 'CZE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Karlovarský') . "', `country_code` = 'CZE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kraj Vysocina') . "', `country_code` = 'CZE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Královéhradecký') . "', `country_code` = 'CZE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Liberecký') . "', `country_code` = 'CZE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Moravskoslezský') . "', `country_code` = 'CZE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Olomoucký') . "', `country_code` = 'CZE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pardubický') . "', `country_code` = 'CZE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Plzenský') . "', `country_code` = 'CZE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Stredoceský') . "', `country_code` = 'CZE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ústecký') . "', `country_code` = 'CZE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zlínský') . "', `country_code` = 'CZE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Denmark
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hovedstaden') . "', `country_code` = 'DNK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Midtjylland') . "', `country_code` = 'DNK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nordjylland') . "', `country_code` = 'DNK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sjælland') . "', `country_code` = 'DNK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Syddanmark') . "', `country_code` = 'DNK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Djibouti
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ali Sabieh') . "', `country_code` = 'DJI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dikhil') . "', `country_code` = 'DJI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Djibouti') . "', `country_code` = 'DJI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Obock') . "', `country_code` = 'DJI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tadjourah') . "', `country_code` = 'DJI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Dominica
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Andrew') . "', `country_code` = 'DMA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint David') . "', `country_code` = 'DMA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint George') . "', `country_code` = 'DMA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint John') . "', `country_code` = 'DMA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Joseph') . "', `country_code` = 'DMA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Luke') . "', `country_code` = 'DMA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Mark') . "', `country_code` = 'DMA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Patrick') . "', `country_code` = 'DMA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Paul') . "', `country_code` = 'DMA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Peter') . "', `country_code` = 'DMA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Dominican Republic
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Azua') . "', `country_code` = 'DOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Baoruco') . "', `country_code` = 'DOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Barahona') . "', `country_code` = 'DOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dajabón') . "', `country_code` = 'DOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Distrito Nacional') . "', `country_code` = 'DOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Duarte') . "', `country_code` = 'DOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('El Seibo') . "', `country_code` = 'DOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Elías Piña') . "', `country_code` = 'DOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Espaillat') . "', `country_code` = 'DOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hato Mayor') . "', `country_code` = 'DOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hermanas Mirabal') . "', `country_code` = 'DOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Independencia') . "', `country_code` = 'DOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('La Altagracia') . "', `country_code` = 'DOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('La Romana') . "', `country_code` = 'DOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('La Vega') . "', `country_code` = 'DOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('María Trinidad Sánchez') . "', `country_code` = 'DOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Monseñor Nouel') . "', `country_code` = 'DOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Monte Cristi') . "', `country_code` = 'DOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Monte Plata') . "', `country_code` = 'DOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pedernales') . "', `country_code` = 'DOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Peravia') . "', `country_code` = 'DOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Puerto Plata') . "', `country_code` = 'DOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Samaná') . "', `country_code` = 'DOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sánchez Ramírez') . "', `country_code` = 'DOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('San Cristóbal') . "', `country_code` = 'DOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('San José de Ocoa') . "', `country_code` = 'DOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('San Juan') . "', `country_code` = 'DOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('San Pedro de Macorís') . "', `country_code` = 'DOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Santiago') . "', `country_code` = 'DOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Santiago Rodríguez') . "', `country_code` = 'DOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Santo Domingo') . "', `country_code` = 'DOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Valverde') . "', `country_code` = 'DOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // East Timor
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Aileu') . "', `country_code` = 'TLS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ainaro') . "', `country_code` = 'TLS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Atauro') . "', `country_code` = 'TLS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Baucau') . "', `country_code` = 'TLS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bobonaro') . "', `country_code` = 'TLS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Covalima') . "', `country_code` = 'TLS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dili') . "', `country_code` = 'TLS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ermera') . "', `country_code` = 'TLS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lautém') . "', `country_code` = 'TLS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Liquiçá') . "', `country_code` = 'TLS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Manatuto') . "', `country_code` = 'TLS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Manufahi') . "', `country_code` = 'TLS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Oecusse') . "', `country_code` = 'TLS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Viqueque') . "', `country_code` = 'TLS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Ecuador
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Azuay') . "', `country_code` = 'ECU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bolivar') . "', `country_code` = 'ECU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cañar') . "', `country_code` = 'ECU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Carchi') . "', `country_code` = 'ECU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chimborazo') . "', `country_code` = 'ECU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cotopaxi') . "', `country_code` = 'ECU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('El Oro') . "', `country_code` = 'ECU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Esmeraldas') . "', `country_code` = 'ECU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Galápagos') . "', `country_code` = 'ECU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Guayas') . "', `country_code` = 'ECU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Imbabura') . "', `country_code` = 'ECU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Loja') . "', `country_code` = 'ECU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Los Rios') . "', `country_code` = 'ECU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Manabí') . "', `country_code` = 'ECU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Morona Santiago') . "', `country_code` = 'ECU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Napo') . "', `country_code` = 'ECU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Orellana') . "', `country_code` = 'ECU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pastaza') . "', `country_code` = 'ECU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pichincha') . "', `country_code` = 'ECU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Santa Elena') . "', `country_code` = 'ECU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Santo Domingo de los Tsáchilas') . "', `country_code` = 'ECU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sucumbíos') . "', `country_code` = 'ECU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tungurahua') . "', `country_code` = 'ECU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zamora-Chinchipe') . "', `country_code` = 'ECU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Egypt
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Alexandria') . "', `country_code` = 'EGY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Aswan') . "', `country_code` = 'EGY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Asyut') . "', `country_code` = 'EGY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Beheira') . "', `country_code` = 'EGY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Beni Suef') . "', `country_code` = 'EGY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cairo') . "', `country_code` = 'EGY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dakahlia') . "', `country_code` = 'EGY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Damietta') . "', `country_code` = 'EGY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Faiyum') . "', `country_code` = 'EGY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gharbia') . "', `country_code` = 'EGY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Giza') . "', `country_code` = 'EGY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ismailia') . "', `country_code` = 'EGY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kafr el-Sheikh') . "', `country_code` = 'EGY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Luxor') . "', `country_code` = 'EGY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Matruh') . "', `country_code` = 'EGY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Minya') . "', `country_code` = 'EGY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Monufia') . "', `country_code` = 'EGY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('New Valley') . "', `country_code` = 'EGY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North Sinai') . "', `country_code` = 'EGY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Port Said') . "', `country_code` = 'EGY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Qalyubia') . "', `country_code` = 'EGY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Qena') . "', `country_code` = 'EGY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Red Sea') . "', `country_code` = 'EGY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sharqia') . "', `country_code` = 'EGY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sohag') . "', `country_code` = 'EGY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('South Sinai') . "', `country_code` = 'EGY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Suez') . "', `country_code` = 'EGY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // El Salvador
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ahuachapán') . "', `country_code` = 'SLV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cabañas') . "', `country_code` = 'SLV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chalatenango') . "', `country_code` = 'SLV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cuscatlán') . "', `country_code` = 'SLV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('La Libertad') . "', `country_code` = 'SLV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('La Paz') . "', `country_code` = 'SLV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('La Unión') . "', `country_code` = 'SLV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Morazán') . "', `country_code` = 'SLV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('San Miguel') . "', `country_code` = 'SLV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('San Salvador') . "', `country_code` = 'SLV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('San Vicente') . "', `country_code` = 'SLV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Santa Ana') . "', `country_code` = 'SLV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sonsonate') . "', `country_code` = 'SLV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Usulután') . "', `country_code` = 'SLV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Equatorial Guinea
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Annobón') . "', `country_code` = 'GNQ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bioko Norte') . "', `country_code` = 'GNQ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bioko Sur') . "', `country_code` = 'GNQ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Centro Sur') . "', `country_code` = 'GNQ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kié-Ntem') . "', `country_code` = 'GNQ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Litoral') . "', `country_code` = 'GNQ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Wele-Nzas') . "', `country_code` = 'GNQ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Eritrea
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Anseba') . "', `country_code` = 'ERI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Debub') . "', `country_code` = 'ERI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Maekel') . "', `country_code` = 'ERI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gash-Barka') . "', `country_code` = 'ERI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Northern Red Sea') . "', `country_code` = 'ERI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Southern Red Sea') . "', `country_code` = 'ERI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Estonia
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Harju') . "', `country_code` = 'EST', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hiiu') . "', `country_code` = 'EST', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ida-Viru') . "', `country_code` = 'EST', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Järva') . "', `country_code` = 'EST', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jõgeva') . "', `country_code` = 'EST', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lääne') . "', `country_code` = 'EST', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lääne-Viru') . "', `country_code` = 'EST', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pärnu') . "', `country_code` = 'EST', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Põlva') . "', `country_code` = 'EST', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rapla') . "', `country_code` = 'EST', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saare') . "', `country_code` = 'EST', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tartu') . "', `country_code` = 'EST', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Valga') . "', `country_code` = 'EST', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Viljandi') . "', `country_code` = 'EST', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Võru') . "', `country_code` = 'EST', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Eswatini
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hhohho') . "', `country_code` = 'SWZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lubombo') . "', `country_code` = 'SWZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Manzini') . "', `country_code` = 'SWZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Shiselweni') . "', `country_code` = 'SWZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Ethiopia
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Addis Ababa') . "', `country_code` = 'ETH', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Afar') . "', `country_code` = 'ETH', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Amhara') . "', `country_code` = 'ETH', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Benishangul-Gumuz') . "', `country_code` = 'ETH', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dire Dawa') . "', `country_code` = 'ETH', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gambela') . "', `country_code` = 'ETH', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Harari') . "', `country_code` = 'ETH', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Oromia') . "', `country_code` = 'ETH', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Somali') . "', `country_code` = 'ETH', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Southern Nations') . "', `country_code` = 'ETH', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tigray') . "', `country_code` = 'ETH', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Fiji
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ba') . "', `country_code` = 'FJI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bua') . "', `country_code` = 'FJI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cakaudrove') . "', `country_code` = 'FJI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kadavu') . "', `country_code` = 'FJI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lau') . "', `country_code` = 'FJI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lomaiviti') . "', `country_code` = 'FJI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Macuata') . "', `country_code` = 'FJI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nadroga-Navosa') . "', `country_code` = 'FJI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Namosi') . "', `country_code` = 'FJI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ra') . "', `country_code` = 'FJI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rewa') . "', `country_code` = 'FJI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Serua') . "', `country_code` = 'FJI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tailevu') . "', `country_code` = 'FJI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Finland
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ahvenanmaa') . "', `country_code` = 'FIN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Etelä-Karjala') . "', `country_code` = 'FIN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Etelä-Pohjanmaa') . "', `country_code` = 'FIN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Etelä-Savo') . "', `country_code` = 'FIN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kainuu') . "', `country_code` = 'FIN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kanta-Häme') . "', `country_code` = 'FIN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Keski-Pohjanmaa') . "', `country_code` = 'FIN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Keski-Suomi') . "', `country_code` = 'FIN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kymenlaakso') . "', `country_code` = 'FIN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lappi') . "', `country_code` = 'FIN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Päijät-Häme') . "', `country_code` = 'FIN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pirkanmaa') . "', `country_code` = 'FIN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pohjanmaa') . "', `country_code` = 'FIN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pohjois-Karjala') . "', `country_code` = 'FIN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pohjois-Pohjanmaa') . "', `country_code` = 'FIN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pohjois-Savo') . "', `country_code` = 'FIN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Satakunta') . "', `country_code` = 'FIN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Uusimaa') . "', `country_code` = 'FIN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Varsinais-Suomi') . "', `country_code` = 'FIN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // France
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Alsace-Champagne-Ardenne-Lorraine') . "', `country_code` = 'FRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Aquitaine-Limousin-Poitou-Charentes') . "', `country_code` = 'FRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Auvergne-Rhône-Alpes') . "', `country_code` = 'FRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bretagne') . "', `country_code` = 'FRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bourgogne-Franche-Comté') . "', `country_code` = 'FRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Centre-Val de Loire') . "', `country_code` = 'FRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Corse') . "', `country_code` = 'FRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Languedoc-Roussillon-Midi-Pyrénées') . "', `country_code` = 'FRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Normandie') . "', `country_code` = 'FRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nord-Pas-de-Calais-Picardie') . "', `country_code` = 'FRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Île-de-France') . "', `country_code` = 'FRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Provence-Alpes-Côte d\'Azur') . "', `country_code` = 'FRA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Gabon
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Estuaire') . "', `country_code` = 'GAB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Haut-Ogooué') . "', `country_code` = 'GAB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Moyen-Ogooué') . "', `country_code` = 'GAB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ngounié') . "', `country_code` = 'GAB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nyanga') . "', `country_code` = 'GAB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ogooué-Ivindo') . "', `country_code` = 'GAB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ogooué-Lolo') . "', `country_code` = 'GAB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ogooué-Maritime') . "', `country_code` = 'GAB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Woleu-Ntem') . "', `country_code` = 'GAB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Gambia
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Banjul') . "', `country_code` = 'GMB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Central River') . "', `country_code` = 'GMB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lower River') . "', `country_code` = 'GMB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North Bank') . "', `country_code` = 'GMB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Upper River') . "', `country_code` = 'GMB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Western') . "', `country_code` = 'GMB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Georgia
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Abkhazia') . "', `country_code` = 'GEO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ajaria') . "', `country_code` = 'GEO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Guria') . "', `country_code` = 'GEO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Imereti') . "', `country_code` = 'GEO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kakheti') . "', `country_code` = 'GEO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kvemo Kartli') . "', `country_code` = 'GEO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mtskheta-Mtianeti') . "', `country_code` = 'GEO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Racha Lechkhumi and Kvemo Svanet') . "', `country_code` = 'GEO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Samegrelo-Zemo Svaneti') . "', `country_code` = 'GEO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Samtskhe-Javakheti') . "', `country_code` = 'GEO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Shida Kartli') . "', `country_code` = 'GEO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tbilisi') . "', `country_code` = 'GEO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Germany
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Baden-Württemberg') . "', `country_code` = 'DEU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bavaria') . "', `country_code` = 'DEU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Berlin') . "', `country_code` = 'DEU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Brandenburg') . "', `country_code` = 'DEU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bremen') . "', `country_code` = 'DEU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hamburg') . "', `country_code` = 'DEU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hesse') . "', `country_code` = 'DEU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lower Saxony') . "', `country_code` = 'DEU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mecklenburg-Vorpommern') . "', `country_code` = 'DEU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North Rhine-Westphalia') . "', `country_code` = 'DEU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rhineland-Palatinate') . "', `country_code` = 'DEU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saarland') . "', `country_code` = 'DEU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saxony') . "', `country_code` = 'DEU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saxony-Anhalt') . "', `country_code` = 'DEU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Schleswig-Holstein') . "', `country_code` = 'DEU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Thuringia') . "', `country_code` = 'DEU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Ghana
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ashanti') . "', `country_code` = 'GHA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Brong-Ahafo') . "', `country_code` = 'GHA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Central') . "', `country_code` = 'GHA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Eastern') . "', `country_code` = 'GHA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Greater Accra') . "', `country_code` = 'GHA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Northern') . "', `country_code` = 'GHA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Upper East') . "', `country_code` = 'GHA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Upper West') . "', `country_code` = 'GHA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Volta') . "', `country_code` = 'GHA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Western') . "', `country_code` = 'GHA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Greece
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Attica') . "', `country_code` = 'GRC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Central Greece') . "', `country_code` = 'GRC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Central Macedonia') . "', `country_code` = 'GRC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Crete') . "', `country_code` = 'GRC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Eastern Macedonia and Thrace') . "', `country_code` = 'GRC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Epirus') . "', `country_code` = 'GRC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ionian Islands') . "', `country_code` = 'GRC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North Aegean') . "', `country_code` = 'GRC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Peloponnese') . "', `country_code` = 'GRC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('South Aegean') . "', `country_code` = 'GRC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Thessaly') . "', `country_code` = 'GRC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Western Greece') . "', `country_code` = 'GRC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Western Macedonia') . "', `country_code` = 'GRC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Grenada
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Andrew') . "', `country_code` = 'GTM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint David') . "', `country_code` = 'GTM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint George') . "', `country_code` = 'GTM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint John') . "', `country_code` = 'GTM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Mark') . "', `country_code` = 'GTM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Patrick') . "', `country_code` = 'GTM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        

        // Guatemala
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Alta Verapaz') . "', `country_code` = 'GTM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Baja Verapaz') . "', `country_code` = 'GTM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chimaltenango') . "', `country_code` = 'GTM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chiquimula') . "', `country_code` = 'GTM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('El Progreso') . "', `country_code` = 'GTM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Escuintla') . "', `country_code` = 'GTM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Guatemala') . "', `country_code` = 'GTM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Huehuetenango') . "', `country_code` = 'GTM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Izabal') . "', `country_code` = 'GTM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jalapa') . "', `country_code` = 'GTM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jutiapa') . "', `country_code` = 'GTM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Petén') . "', `country_code` = 'GTM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Quetzaltenango') . "', `country_code` = 'GTM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Quiché') . "', `country_code` = 'GTM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Retalhuleu') . "', `country_code` = 'GTM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sacatepéquez') . "', `country_code` = 'GTM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('San Marcos') . "', `country_code` = 'GTM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Santa Rosa') . "', `country_code` = 'GTM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sololá') . "', `country_code` = 'GTM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Suchitepéquez') . "', `country_code` = 'GTM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Totonicapán') . "', `country_code` = 'GTM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zacapa') . "', `country_code` = 'GTM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Guinea
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Boké') . "', `country_code` = 'GIN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Conakry') . "', `country_code` = 'GIN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Faranah') . "', `country_code` = 'GIN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kankan') . "', `country_code` = 'GIN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kindia') . "', `country_code` = 'GIN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Labé') . "', `country_code` = 'GIN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mamou') . "', `country_code` = 'GIN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nzérékoré') . "', `country_code` = 'GIN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Guinea-Bissau
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bafatá') . "', `country_code` = 'GNB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Biombo') . "', `country_code` = 'GNB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bissau') . "', `country_code` = 'GNB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bolama') . "', `country_code` = 'GNB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cacheu') . "', `country_code` = 'GNB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gabú') . "', `country_code` = 'GNB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Oio') . "', `country_code` = 'GNB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Quinara') . "', `country_code` = 'GNB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tombalí') . "', `country_code` = 'GNB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Guyana
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Barima-Waini') . "', `country_code` = 'GUY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cuyuni-Mazaruni') . "', `country_code` = 'GUY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Demerara-Mahaica') . "', `country_code` = 'GUY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('East Berbice-Corentyne') . "', `country_code` = 'GUY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Essequibo Islands-West Demerara') . "', `country_code` = 'GUY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mahaica-Berbice') . "', `country_code` = 'GUY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pomeroon-Supenaam') . "', `country_code` = 'GUY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Potaro-Siparuni') . "', `country_code` = 'GUY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Upper Demerara-Berbice') . "', `country_code` = 'GUY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Upper Takutu-Upper Essequibo') . "', `country_code` = 'GUY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Haiti
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Artibonite') . "', `country_code` = 'HTI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Centre') . "', `country_code` = 'HTI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Grand\'Anse') . "', `country_code` = 'HTI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nippes') . "', `country_code` = 'HTI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nord') . "', `country_code` = 'HTI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nord-Est') . "', `country_code` = 'HTI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nord-Ouest') . "', `country_code` = 'HTI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ouest') . "', `country_code` = 'HTI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sud') . "', `country_code` = 'HTI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sud-Est') . "', `country_code` = 'HTI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Honduras
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Atlántida') . "', `country_code` = 'HND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Choluteca') . "', `country_code` = 'HND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Colón') . "', `country_code` = 'HND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Comayagua') . "', `country_code` = 'HND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Copán') . "', `country_code` = 'HND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cortés') . "', `country_code` = 'HND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('El Paraíso') . "', `country_code` = 'HND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Francisco Morazán') . "', `country_code` = 'HND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gracias a Dios') . "', `country_code` = 'HND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Intibucá') . "', `country_code` = 'HND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Islas de la Bahía') . "', `country_code` = 'HND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('La Paz') . "', `country_code` = 'HND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lempira') . "', `country_code` = 'HND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ocotepeque') . "', `country_code` = 'HND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Olancho') . "', `country_code` = 'HND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Santa Bárbara') . "', `country_code` = 'HND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Valle') . "', `country_code` = 'HND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Yoro') . "', `country_code` = 'HND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Hungary
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Central Hungary') . "', `country_code` = 'HUN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Central Transdanubia') . "', `country_code` = 'HUN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Northern Great Plain') . "', `country_code` = 'HUN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Northern Hungary') . "', `country_code` = 'HUN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Southern Great Plain') . "', `country_code` = 'HUN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Southern Transdanubia') . "', `country_code` = 'HUN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Western Transdanubia') . "', `country_code` = 'HUN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Iceland
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Capital Region') . "', `country_code` = 'ISL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Eastern Region') . "', `country_code` = 'ISL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Northeastern Region') . "', `country_code` = 'ISL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Northwestern Region') . "', `country_code` = 'ISL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Southern Peninsula') . "', `country_code` = 'ISL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Southern Region') . "', `country_code` = 'ISL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Western Region') . "', `country_code` = 'ISL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Westfjords') . "', `country_code` = 'ISL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // India
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Andhra Pradesh') . "', `country_code` = 'IND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Arunachal Pradesh') . "', `country_code` = 'IND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Assam') . "', `country_code` = 'IND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bihar') . "', `country_code` = 'IND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chhattisgarh') . "', `country_code` = 'IND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Goa') . "', `country_code` = 'IND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gujarat') . "', `country_code` = 'IND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Haryana') . "', `country_code` = 'IND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Himachal Pradesh') . "', `country_code` = 'IND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jammu and Kashmir') . "', `country_code` = 'IND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jharkhand') . "', `country_code` = 'IND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Karnataka') . "', `country_code` = 'IND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kerala') . "', `country_code` = 'IND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Madhya Pradesh') . "', `country_code` = 'IND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Maharashtra') . "', `country_code` = 'IND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Manipur') . "', `country_code` = 'IND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Meghalaya') . "', `country_code` = 'IND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mizoram') . "', `country_code` = 'IND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nagaland') . "', `country_code` = 'IND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Odisha') . "', `country_code` = 'IND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Punjab') . "', `country_code` = 'IND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rajasthan') . "', `country_code` = 'IND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sikkim') . "', `country_code` = 'IND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tamil Nadu') . "', `country_code` = 'IND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Telangana') . "', `country_code` = 'IND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tripura') . "', `country_code` = 'IND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Uttar Pradesh') . "', `country_code` = 'IND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Uttarakhand') . "', `country_code` = 'IND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('West Bengal') . "', `country_code` = 'IND', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Indonesia
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bali') . "', `country_code` = 'IDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bangka–Belitung Islands') . "', `country_code` = 'IDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Banten') . "', `country_code` = 'IDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bengkulu') . "', `country_code` = 'IDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Central Java') . "', `country_code` = 'IDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Central Kalimantan') . "', `country_code` = 'IDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Central Sulawesi') . "', `country_code` = 'IDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('East Java') . "', `country_code` = 'IDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('East Kalimantan') . "', `country_code` = 'IDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('East Nusa Tenggara') . "', `country_code` = 'IDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gorontalo') . "', `country_code` = 'IDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jakarta Special Capital Region') . "', `country_code` = 'IDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jambi') . "', `country_code` = 'IDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lampung') . "', `country_code` = 'IDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Maluku') . "', `country_code` = 'IDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North Kalimantan') . "', `country_code` = 'IDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North Maluku') . "', `country_code` = 'IDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North Sulawesi') . "', `country_code` = 'IDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North Sumatra') . "', `country_code` = 'IDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Riau') . "', `country_code` = 'IDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Riau Islands') . "', `country_code` = 'IDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('South Kalimantan') . "', `country_code` = 'IDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('South Sulawesi') . "', `country_code` = 'IDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('South Sumatra') . "', `country_code` = 'IDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Southeast Sulawesi') . "', `country_code` = 'IDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Special Region of Aceh') . "', `country_code` = 'IDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Special Region of Papua') . "', `country_code` = 'IDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Special Region of West Papua') . "', `country_code` = 'IDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Special Region of Yogyakarta') . "', `country_code` = 'IDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('West Java') . "', `country_code` = 'IDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('West Kalimantan') . "', `country_code` = 'IDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('West Nusa Tenggara') . "', `country_code` = 'IDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('West Sulawesi') . "', `country_code` = 'IDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('West Sumatra') . "', `country_code` = 'IDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Iran
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Alborz') . "', `country_code` = 'IRN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ardabil') . "', `country_code` = 'IRN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Azerbaijan East') . "', `country_code` = 'IRN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Azerbaijan West') . "', `country_code` = 'IRN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bushehr') . "', `country_code` = 'IRN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chahar Mahaal and Bakhtiari') . "', `country_code` = 'IRN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Fars') . "', `country_code` = 'IRN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gilan') . "', `country_code` = 'IRN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Golestan') . "', `country_code` = 'IRN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hamadan') . "', `country_code` = 'IRN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hormozgan') . "', `country_code` = 'IRN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ilam') . "', `country_code` = 'IRN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Isfahan') . "', `country_code` = 'IRN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kerman') . "', `country_code` = 'IRN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kermanshah') . "', `country_code` = 'IRN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Khorasan North') . "', `country_code` = 'IRN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Khorasan Razavi') . "', `country_code` = 'IRN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Khorasan South') . "', `country_code` = 'IRN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Khuzestan') . "', `country_code` = 'IRN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kohgiluyeh and Boyer-Ahmad') . "', `country_code` = 'IRN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kurdistan') . "', `country_code` = 'IRN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lorestan') . "', `country_code` = 'IRN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Markazi') . "', `country_code` = 'IRN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mazandaran') . "', `country_code` = 'IRN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Qazvin') . "', `country_code` = 'IRN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Qom') . "', `country_code` = 'IRN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Semnan') . "', `country_code` = 'IRN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sistan and Baluchestan') . "', `country_code` = 'IRN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tehran') . "', `country_code` = 'IRN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Yazd') . "', `country_code` = 'IRN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zanjan') . "', `country_code` = 'IRN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Iraq
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Al Anbar') . "', `country_code` = 'IRQ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Al Diwaniyah') . "', `country_code` = 'IRQ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Babil') . "', `country_code` = 'IRQ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Baghdad') . "', `country_code` = 'IRQ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Basra') . "', `country_code` = 'IRQ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dhi Qar') . "', `country_code` = 'IRQ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Diyala') . "', `country_code` = 'IRQ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dohuk') . "', `country_code` = 'IRQ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Erbil') . "', `country_code` = 'IRQ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Halabja') . "', `country_code` = 'IRQ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Karbala') . "', `country_code` = 'IRQ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kirkuk') . "', `country_code` = 'IRQ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Maysan') . "', `country_code` = 'IRQ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Muthana') . "', `country_code` = 'IRQ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Najaf') . "', `country_code` = 'IRQ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nineveh') . "', `country_code` = 'IRQ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saladin') . "', `country_code` = 'IRQ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sulaymaniyah') . "', `country_code` = 'IRQ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Wasit') . "', `country_code` = 'IRQ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Ireland
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Antrim') . "', `country_code` = 'IRL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Armagh') . "', `country_code` = 'IRL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Carlow') . "', `country_code` = 'IRL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cavan') . "', `country_code` = 'IRL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Clare') . "', `country_code` = 'IRL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cork') . "', `country_code` = 'IRL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Donegal') . "', `country_code` = 'IRL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Down') . "', `country_code` = 'IRL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dublin') . "', `country_code` = 'IRL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dún Laoghaire Rathdown') . "', `country_code` = 'IRL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Fermanagh') . "', `country_code` = 'IRL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Fingal') . "', `country_code` = 'IRL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Galway') . "', `country_code` = 'IRL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kerry') . "', `country_code` = 'IRL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kildare') . "', `country_code` = 'IRL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kilkenny') . "', `country_code` = 'IRL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Laois') . "', `country_code` = 'IRL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Leitrim') . "', `country_code` = 'IRL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Limerick') . "', `country_code` = 'IRL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Londonderry') . "', `country_code` = 'IRL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Longford') . "', `country_code` = 'IRL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Louth') . "', `country_code` = 'IRL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mayo') . "', `country_code` = 'IRL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Meath') . "', `country_code` = 'IRL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Monaghan') . "', `country_code` = 'IRL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Offaly') . "', `country_code` = 'IRL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Roscommon') . "', `country_code` = 'IRL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sligo') . "', `country_code` = 'IRL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('South Dublin') . "', `country_code` = 'IRL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tipperary') . "', `country_code` = 'IRL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tyrone') . "', `country_code` = 'IRL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Waterford') . "', `country_code` = 'IRL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Westmeath') . "', `country_code` = 'IRL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Wexford') . "', `country_code` = 'IRL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Wicklow') . "', `country_code` = 'IRL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Israel
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ezor Yehuda VeShomron') . "', `country_code` = 'ISR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mehoz HaDarom') . "', `country_code` = 'ISR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mehoz HaMerkaz') . "', `country_code` = 'ISR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mehoz HaTzafon') . "', `country_code` = 'ISR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mehoz Heifa') . "', `country_code` = 'ISR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mehoz Tel Aviv') . "', `country_code` = 'ISR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mehoz Yerushalayim') . "', `country_code` = 'ISR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Italy
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Abruzzo') . "', `country_code` = 'ITA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Aosta Valley') . "', `country_code` = 'ITA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Apulia') . "', `country_code` = 'ITA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Basilicata') . "', `country_code` = 'ITA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Calabria') . "', `country_code` = 'ITA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Campania') . "', `country_code` = 'ITA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Emilia-Romagna') . "', `country_code` = 'ITA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Friuli-Venezia Giulia') . "', `country_code` = 'ITA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lazio') . "', `country_code` = 'ITA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Liguria') . "', `country_code` = 'ITA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lombardy') . "', `country_code` = 'ITA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Marche') . "', `country_code` = 'ITA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Molise') . "', `country_code` = 'ITA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Piedmont') . "', `country_code` = 'ITA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sardinia') . "', `country_code` = 'ITA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sicily') . "', `country_code` = 'ITA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Trentino-South Tyrol') . "', `country_code` = 'ITA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tuscany') . "', `country_code` = 'ITA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Umbria') . "', `country_code` = 'ITA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Veneto') . "', `country_code` = 'ITA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Ivory Coast
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Abidjan') . "', `country_code` = 'CIV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bas-Sassandra') . "', `country_code` = 'CIV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Comoé') . "', `country_code` = 'CIV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Denguélé') . "', `country_code` = 'CIV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gôh-Djiboua') . "', `country_code` = 'CIV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lacs') . "', `country_code` = 'CIV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lagunes') . "', `country_code` = 'CIV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Montagnes') . "', `country_code` = 'CIV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sassandra-Marahoué') . "', `country_code` = 'CIV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Savanes') . "', `country_code` = 'CIV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vallée du Bandama') . "', `country_code` = 'CIV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Woroba') . "', `country_code` = 'CIV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Yamoussoukro') . "', `country_code` = 'CIV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zanzan') . "', `country_code` = 'CIV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Jamaica
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Clarendon') . "', `country_code` = 'JAM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hanover') . "', `country_code` = 'JAM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kingston Parish') . "', `country_code` = 'JAM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Manchester') . "', `country_code` = 'JAM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Portland') . "', `country_code` = 'JAM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Andrew') . "', `country_code` = 'JAM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Ann') . "', `country_code` = 'JAM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Catherine') . "', `country_code` = 'JAM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Elizabeth') . "', `country_code` = 'JAM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint James') . "', `country_code` = 'JAM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Mary') . "', `country_code` = 'JAM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Thomas') . "', `country_code` = 'JAM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Trelawny') . "', `country_code` = 'JAM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Westmoreland') . "', `country_code` = 'JAM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Japan
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Aichi') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Akita') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Aomori') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chiba') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ehime') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Fukui') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Fukuoka') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Fukushima') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gifu') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gunma') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hiroshima') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hokkaido') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hyogo') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ibaraki') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ishikawa') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Iwate') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kagawa') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kagoshima') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kanagawa') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kochi') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kumamoto') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kyoto') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mie') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Miyagi') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Miyazaki') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nagano') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nagasaki') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nara') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Niigata') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Oita') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Okayama') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Okinawa') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Osaka') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saga') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saitama') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Shiga') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Shimane') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Shizuoka') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tochigi') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tokushima') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tottori') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Toyama') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tokyo') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Wakayama') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Yamagata') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Yamaguchi') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Yamanashi') . "', `country_code` = 'JPN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Jordan
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ajlun') . "', `country_code` = 'JOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Amman') . "', `country_code` = 'JOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Aqaba') . "', `country_code` = 'JOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Balqa') . "', `country_code` = 'JOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Irbid') . "', `country_code` = 'JOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jarash') . "', `country_code` = 'JOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Karak') . "', `country_code` = 'JOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ma`an') . "', `country_code` = 'JOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Madaba') . "', `country_code` = 'JOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mafraq') . "', `country_code` = 'JOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tafilah') . "', `country_code` = 'JOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zarqa') . "', `country_code` = 'JOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Kazakhstan
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Akmola Region') . "', `country_code` = 'KAZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Aktobe Region') . "', `country_code` = 'KAZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Almaty') . "', `country_code` = 'KAZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Almaty Region') . "', `country_code` = 'KAZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Astana') . "', `country_code` = 'KAZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Atyrau Region') . "', `country_code` = 'KAZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Baikonur') . "', `country_code` = 'KAZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('East Kazakhstan Region') . "', `country_code` = 'KAZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jambyl Region') . "', `country_code` = 'KAZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Karaganda Region') . "', `country_code` = 'KAZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kostanay Region') . "', `country_code` = 'KAZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kyzylorda Region') . "', `country_code` = 'KAZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mangystau Region') . "', `country_code` = 'KAZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North Kazakhstan Region') . "', `country_code` = 'KAZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pavlodar Region') . "', `country_code` = 'KAZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('South Kazakhstan Region') . "', `country_code` = 'KAZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('West Kazakhstan Region') . "', `country_code` = 'KAZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Kenya
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Baringo') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bomet') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bungoma') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Busia') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Elgeyo-Marakwet') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Embu') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Garissa') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Homa Bay') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Isiolo') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kajiado') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kakamega') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kericho') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kiambu') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kilifi') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kirinyaga') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kisii') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kisumu') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kitui') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kwale') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Laikipia') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lamu') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Machakos') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Makueni') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mandera') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Marsabit') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Meru') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Migori') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mombasa') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Murang\'a') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nairobi') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nakuru') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nandi') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Narok') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nyamira') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nyandarua') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nyeri') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Samburu') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Siaya') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Taita-Taveta') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tana River') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tharaka-Nithi') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Trans Nzoia') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Turkana') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Uasin Gishu') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vihiga') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Wajir') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('West Pokot') . "', `country_code` = 'KEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Kiribati
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Abaiang') . "', `country_code` = 'KIR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Butaritari') . "', `country_code` = 'KIR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Makin') . "', `country_code` = 'KIR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Marakei') . "', `country_code` = 'KIR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North Tarawa') . "', `country_code` = 'KIR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        

        // Kosovo
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ferizaj') . "', `country_code` = 'UNK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gjakova') . "', `country_code` = 'UNK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gjilan') . "', `country_code` = 'UNK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mitrovica') . "', `country_code` = 'UNK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pec') . "', `country_code` = 'UNK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pristina') . "', `country_code` = 'UNK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Prizren') . "', `country_code` = 'UNK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Kuwait
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ahmadi') . "', `country_code` = 'KWT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Al Asimah') . "', `country_code` = 'KWT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Farwaniya') . "', `country_code` = 'KWT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hawalli') . "', `country_code` = 'KWT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jahra') . "', `country_code` = 'KWT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mubarak Al-Kabeer') . "', `country_code` = 'KWT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Kyrgyzstan
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Batken') . "', `country_code` = 'KGZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bishkek City') . "', `country_code` = 'KGZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chuy Region') . "', `country_code` = 'KGZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Issyk Kul Region') . "', `country_code` = 'KGZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jalal-Abad Region') . "', `country_code` = 'KGZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Naryn Region') . "', `country_code` = 'KGZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Osh City') . "', `country_code` = 'KGZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Osh Region') . "', `country_code` = 'KGZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Talas Region') . "', `country_code` = 'KGZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Laos
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Attapu') . "', `country_code` = 'LAO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bokèo') . "', `country_code` = 'LAO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bolikhamxai') . "', `country_code` = 'LAO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Champasak') . "', `country_code` = 'LAO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Houaphan') . "', `country_code` = 'LAO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Khammouan') . "', `country_code` = 'LAO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Louangphabang') . "', `country_code` = 'LAO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Luang Namtha') . "', `country_code` = 'LAO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Oudômxai') . "', `country_code` = 'LAO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Phôngsali') . "', `country_code` = 'LAO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Salavan') . "', `country_code` = 'LAO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Savannakhét') . "', `country_code` = 'LAO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vientiane') . "', `country_code` = 'LAO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Xaignabouli') . "', `country_code` = 'LAO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Xiangkhouang') . "', `country_code` = 'LAO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Xékong') . "', `country_code` = 'LAO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Latvia
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kurzeme') . "', `country_code` = 'LVA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Latgale') . "', `country_code` = 'LVA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vidzeme') . "', `country_code` = 'LVA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zemgale') . "', `country_code` = 'LVA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Lebanon
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Beirut') . "', `country_code` = 'LBN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Beqaa') . "', `country_code` = 'LBN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mount Lebanon') . "', `country_code` = 'LBN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nabatiye') . "', `country_code` = 'LBN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North') . "', `country_code` = 'LBN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('South') . "', `country_code` = 'LBN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Lesotho
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Berea') . "', `country_code` = 'LSO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Butha-Buthe') . "', `country_code` = 'LSO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Leribe') . "', `country_code` = 'LSO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mafeteng') . "', `country_code` = 'LSO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Maseru') . "', `country_code` = 'LSO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mohale\'s Hoek') . "', `country_code` = 'LSO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mokhotlong') . "', `country_code` = 'LSO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Qacha\'s Nek') . "', `country_code` = 'LSO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Quthing') . "', `country_code` = 'LSO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Thaba-Tseka') . "', `country_code` = 'LSO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Liberia
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bomi') . "', `country_code` = 'LBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bong') . "', `country_code` = 'LBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gbarpolu') . "', `country_code` = 'LBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Grand Bassa') . "', `country_code` = 'LBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Grand Cape Mount') . "', `country_code` = 'LBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Grand Gedeh') . "', `country_code` = 'LBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Grand Kru') . "', `country_code` = 'LBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lofa') . "', `country_code` = 'LBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Margibi') . "', `country_code` = 'LBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Maryland') . "', `country_code` = 'LBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Montserrado') . "', `country_code` = 'LBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nimba') . "', `country_code` = 'LBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('River Gee') . "', `country_code` = 'LBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rivercess') . "', `country_code` = 'LBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sinoe') . "', `country_code` = 'LBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Libya
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Al Wahat') . "', `country_code` = 'LBY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Benghazi') . "', `country_code` = 'LBY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Butnan') . "', `country_code` = 'LBY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Derna') . "', `country_code` = 'LBY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ghat') . "', `country_code` = 'LBY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jabal al Akhdar') . "', `country_code` = 'LBY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jabal al Gharbi') . "', `country_code` = 'LBY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jafara') . "', `country_code` = 'LBY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jufra') . "', `country_code` = 'LBY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kufra') . "', `country_code` = 'LBY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Marj') . "', `country_code` = 'LBY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Misrata') . "', `country_code` = 'LBY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Murqub') . "', `country_code` = 'LBY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Murzuq') . "', `country_code` = 'LBY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nalut') . "', `country_code` = 'LBY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nuqat al Khams') . "', `country_code` = 'LBY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sabha') . "', `country_code` = 'LBY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sirte') . "', `country_code` = 'LBY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tripoli') . "', `country_code` = 'LBY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Wadi al Hayaa') . "', `country_code` = 'LBY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Wadi al Shatii') . "', `country_code` = 'LBY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zawiya') . "', `country_code` = 'LBY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Liechtenstein
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Balzers') . "', `country_code` = 'LIE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Eschen') . "', `country_code` = 'LIE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gamprin') . "', `country_code` = 'LIE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mauren') . "', `country_code` = 'LIE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Planken') . "', `country_code` = 'LIE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ruggell') . "', `country_code` = 'LIE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Schaan') . "', `country_code` = 'LIE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Schellenberg') . "', `country_code` = 'LIE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Triesen') . "', `country_code` = 'LIE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Triesenberg') . "', `country_code` = 'LIE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vaduz') . "', `country_code` = 'LIE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Lithuania
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Alytus') . "', `country_code` = 'LTU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kaunas') . "', `country_code` = 'LTU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Klaipeda') . "', `country_code` = 'LTU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Marijampole') . "', `country_code` = 'LTU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Panevežys') . "', `country_code` = 'LTU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Taurage') . "', `country_code` = 'LTU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Telšiai') . "', `country_code` = 'LTU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Utena') . "', `country_code` = 'LTU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vilnius') . "', `country_code` = 'LTU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Šiauliai') . "', `country_code` = 'LTU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Luxembourg
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Capellen') . "', `country_code` = 'LUX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Clervaux') . "', `country_code` = 'LUX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Diekirch') . "', `country_code` = 'LUX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Echternach') . "', `country_code` = 'LUX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Esch-sur-Alzette') . "', `country_code` = 'LUX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Grevenmacher') . "', `country_code` = 'LUX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Luxembourg') . "', `country_code` = 'LUX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mersch') . "', `country_code` = 'LUX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Redange-sur-Attert') . "', `country_code` = 'LUX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Remich') . "', `country_code` = 'LUX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vianden') . "', `country_code` = 'LUX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Wiltz') . "', `country_code` = 'LUX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        

        // Madagascar
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Alaotra-Mangoro') . "', `country_code` = 'MDG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Amoron\'i Mania') . "', `country_code` = 'MDG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Analamanga') . "', `country_code` = 'MDG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Analanjirofo') . "', `country_code` = 'MDG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Androy') . "', `country_code` = 'MDG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Anosy') . "', `country_code` = 'MDG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Atsimo-Andrefana') . "', `country_code` = 'MDG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Atsimo-Atsinanana') . "', `country_code` = 'MDG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Atsinanana') . "', `country_code` = 'MDG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Betsiboka') . "', `country_code` = 'MDG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Boeny') . "', `country_code` = 'MDG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bongolava') . "', `country_code` = 'MDG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Diana') . "', `country_code` = 'MDG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ihorombe') . "', `country_code` = 'MDG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Itasy') . "', `country_code` = 'MDG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Matsiatra Ambony') . "', `country_code` = 'MDG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Melaky') . "', `country_code` = 'MDG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Menabe') . "', `country_code` = 'MDG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sava') . "', `country_code` = 'MDG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sofia') . "', `country_code` = 'MDG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vakinankaratra') . "', `country_code` = 'MDG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vatovavy-Fitovinany') . "', `country_code` = 'MDG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Malawi
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Balaka') . "', `country_code` = 'MWI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Blantyre') . "', `country_code` = 'MWI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chikwawa') . "', `country_code` = 'MWI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chiradzulu') . "', `country_code` = 'MWI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chitipa') . "', `country_code` = 'MWI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dedza') . "', `country_code` = 'MWI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dowa') . "', `country_code` = 'MWI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Karonga') . "', `country_code` = 'MWI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kasungu') . "', `country_code` = 'MWI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Likoma') . "', `country_code` = 'MWI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lilongwe') . "', `country_code` = 'MWI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Machinga') . "', `country_code` = 'MWI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mangochi') . "', `country_code` = 'MWI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mchinji') . "', `country_code` = 'MWI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mulanje') . "', `country_code` = 'MWI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mwanza') . "', `country_code` = 'MWI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mzimba') . "', `country_code` = 'MWI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Neno') . "', `country_code` = 'MWI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nkhata Bay') . "', `country_code` = 'MWI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nkhotakota') . "', `country_code` = 'MWI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nsanje') . "', `country_code` = 'MWI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ntcheu') . "', `country_code` = 'MWI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ntchisi') . "', `country_code` = 'MWI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Phalombe') . "', `country_code` = 'MWI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rumphi') . "', `country_code` = 'MWI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Salima') . "', `country_code` = 'MWI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Thyolo') . "', `country_code` = 'MWI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zomba') . "', `country_code` = 'MWI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Malaysia
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Federal Territory of Kuala Lumpur') . "', `country_code` = 'MYS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Federal Territory of Labuan') . "', `country_code` = 'MYS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Federal Territory of Putrajaya') . "', `country_code` = 'MYS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Johor Darul Ta\'zim') . "', `country_code` = 'MYS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kedah Darul Aman') . "', `country_code` = 'MYS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kelantan Darul Naim') . "', `country_code` = 'MYS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Malacca') . "', `country_code` = 'MYS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Negeri Sembilan Darul Khusus') . "', `country_code` = 'MYS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pahang Darul Makmur') . "', `country_code` = 'MYS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Penang') . "', `country_code` = 'MYS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Perak Darul Ridzuan') . "', `country_code` = 'MYS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Perlis Indera Kayangan') . "', `country_code` = 'MYS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sabah') . "', `country_code` = 'MYS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sarawak') . "', `country_code` = 'MYS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Selangor Darul Ehsan') . "', `country_code` = 'MYS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Terengganu Darul Iman') . "', `country_code` = 'MYS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Maldives
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Central Atolls') . "', `country_code` = 'MDV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Northern Atolls') . "', `country_code` = 'MDV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Southern Atolls') . "', `country_code` = 'MDV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        

        // Mali
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bamako') . "', `country_code` = 'MLI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gao') . "', `country_code` = 'MLI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kayes') . "', `country_code` = 'MLI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kidal') . "', `country_code` = 'MLI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Koulikoro') . "', `country_code` = 'MLI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mopti') . "', `country_code` = 'MLI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sikasso') . "', `country_code` = 'MLI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ségou') . "', `country_code` = 'MLI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tombouctou') . "', `country_code` = 'MLI', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Malta
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Central Region') . "', `country_code` = 'MLT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gozo Region') . "', `country_code` = 'MLT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Northern Region') . "', `country_code` = 'MLT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('South Eastern Region') . "', `country_code` = 'MLT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('South Region') . "', `country_code` = 'MLT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        

        // Marshall Islands
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ailinglaplap Atoll') . "', `country_code` = 'MHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ailuk Atoll') . "', `country_code` = 'MHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Arno Atoll') . "', `country_code` = 'MHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Aur Atoll') . "', `country_code` = 'MHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ebon Atoll') . "', `country_code` = 'MHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Enewetok Atoll') . "', `country_code` = 'MHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jabat Island') . "', `country_code` = 'MHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jaluit Atoll') . "', `country_code` = 'MHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kili Island') . "', `country_code` = 'MHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kwajalein Atoll') . "', `country_code` = 'MHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lae Atoll') . "', `country_code` = 'MHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lib Island') . "', `country_code` = 'MHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Likiep Atoll') . "', `country_code` = 'MHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Majuro Atoll') . "', `country_code` = 'MHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Maloelap Atoll') . "', `country_code` = 'MHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mejit Island') . "', `country_code` = 'MHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mili Atoll') . "', `country_code` = 'MHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Namorik Atoll') . "', `country_code` = 'MHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Namu Atoll') . "', `country_code` = 'MHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rongelap Atoll') . "', `country_code` = 'MHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ujae Atoll') . "', `country_code` = 'MHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Utirik Atoll') . "', `country_code` = 'MHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Wotho Atoll') . "', `country_code` = 'MHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Wotje Atoll') . "', `country_code` = 'MHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        

        // Mauritania
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Adrar') . "', `country_code` = 'MRT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Assaba') . "', `country_code` = 'MRT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Brakna') . "', `country_code` = 'MRT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dakhlet Nouadhibou') . "', `country_code` = 'MRT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gorgol') . "', `country_code` = 'MRT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Guidimaka') . "', `country_code` = 'MRT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hodh Ech Chargui') . "', `country_code` = 'MRT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hodh El Gharbi') . "', `country_code` = 'MRT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Inchiri') . "', `country_code` = 'MRT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nouakchott-Nord') . "', `country_code` = 'MRT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nouakchott-Ouest') . "', `country_code` = 'MRT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nouakchott-Sud') . "', `country_code` = 'MRT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tagant') . "', `country_code` = 'MRT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tiris Zemmour') . "', `country_code` = 'MRT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Trarza') . "', `country_code` = 'MRT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Mauritius
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Flacq') . "', `country_code` = 'MUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Grand Port') . "', `country_code` = 'MUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Moka') . "', `country_code` = 'MUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pamplemousses') . "', `country_code` = 'MUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Plaines Wilhems') . "', `country_code` = 'MUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Port Louis') . "', `country_code` = 'MUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rivière du Rempart') . "', `country_code` = 'MUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rivière Noire') . "', `country_code` = 'MUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Savanne') . "', `country_code` = 'MUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        

        // Mexico
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Aguascalientes') . "', `country_code` = 'MEX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Baja California') . "', `country_code` = 'MEX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Baja California Sur') . "', `country_code` = 'MEX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Campeche') . "', `country_code` = 'MEX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chiapas') . "', `country_code` = 'MEX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chihuahua') . "', `country_code` = 'MEX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Coahuila') . "', `country_code` = 'MEX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Colima') . "', `country_code` = 'MEX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Durango') . "', `country_code` = 'MEX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Guanajuato') . "', `country_code` = 'MEX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Guerrero') . "', `country_code` = 'MEX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hidalgo') . "', `country_code` = 'MEX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jalisco') . "', `country_code` = 'MEX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Michoacán') . "', `country_code` = 'MEX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Morelos') . "', `country_code` = 'MEX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('México') . "', `country_code` = 'MEX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nayarit') . "', `country_code` = 'MEX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nuevo León') . "', `country_code` = 'MEX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Oaxaca') . "', `country_code` = 'MEX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Puebla') . "', `country_code` = 'MEX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Querétaro') . "', `country_code` = 'MEX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Quintana Roo') . "', `country_code` = 'MEX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('San Luis Potosí') . "', `country_code` = 'MEX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sinaloa') . "', `country_code` = 'MEX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sonora') . "', `country_code` = 'MEX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tabasco') . "', `country_code` = 'MEX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tamaulipas') . "', `country_code` = 'MEX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tlaxcala') . "', `country_code` = 'MEX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Veracruz') . "', `country_code` = 'MEX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Yucatán') . "', `country_code` = 'MEX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zacatecas') . "', `country_code` = 'MEX', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Micronesia
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chuuk') . "', `country_code` = 'FSM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kosrae') . "', `country_code` = 'FSM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pohnpei') . "', `country_code` = 'FSM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Yap') . "', `country_code` = 'FSM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        

        // Moldova
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Anenii Noi') . "', `country_code` = 'MDA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Basarabeasca') . "', `country_code` = 'MDA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Briceni') . "', `country_code` = 'MDA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cahul') . "', `country_code` = 'MDA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cantemir') . "', `country_code` = 'MDA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cimislia') . "', `country_code` = 'MDA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Criuleni') . "', `country_code` = 'MDA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Calarasi') . "', `country_code` = 'MDA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Causeni') . "', `country_code` = 'MDA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Donduseni') . "', `country_code` = 'MDA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Drochia') . "', `country_code` = 'MDA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dubasari') . "', `country_code` = 'MDA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Edinet') . "', `country_code` = 'MDA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Floresti') . "', `country_code` = 'MDA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Falesti') . "', `country_code` = 'MDA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Glodeni') . "', `country_code` = 'MDA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hîncesti') . "', `country_code` = 'MDA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ialoveni') . "', `country_code` = 'MDA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Leova') . "', `country_code` = 'MDA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nisporeni') . "', `country_code` = 'MDA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ocnita') . "', `country_code` = 'MDA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Orhei') . "', `country_code` = 'MDA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rezina') . "', `country_code` = 'MDA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rîscani') . "', `country_code` = 'MDA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Soroca') . "', `country_code` = 'MDA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Straseni') . "', `country_code` = 'MDA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sîngerei') . "', `country_code` = 'MDA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Taraclia') . "', `country_code` = 'MDA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Telenesti') . "', `country_code` = 'MDA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ungheni') . "', `country_code` = 'MDA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Soldanesti') . "', `country_code` = 'MDA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Stefan Voda') . "', `country_code` = 'MDA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Monaco
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Fonteveille') . "', `country_code` = 'MCO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('La Condamine') . "', `country_code` = 'MCO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Monaco Ville') . "', `country_code` = 'MCO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Monte Carlo') . "', `country_code` = 'MCO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Monteghetti') . "', `country_code` = 'MCO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Mongolia
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Arkhangai') . "', `country_code` = 'MNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bayan-Ölgii') . "', `country_code` = 'MNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bayankhongor') . "', `country_code` = 'MNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bulgan') . "', `country_code` = 'MNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Darkhan-Uul') . "', `country_code` = 'MNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dornod') . "', `country_code` = 'MNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dornogovi') . "', `country_code` = 'MNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dundgovi') . "', `country_code` = 'MNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Govi-Altai') . "', `country_code` = 'MNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Govisümber') . "', `country_code` = 'MNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Khentii') . "', `country_code` = 'MNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Khovd') . "', `country_code` = 'MNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Khövsgöl') . "', `country_code` = 'MNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ömnögovi') . "', `country_code` = 'MNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Orkhon') . "', `country_code` = 'MNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Övörkhangai') . "', `country_code` = 'MNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Selenge') . "', `country_code` = 'MNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sükhbaatar') . "', `country_code` = 'MNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Töv') . "', `country_code` = 'MNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ulaanbaatar') . "', `country_code` = 'MNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Uvs') . "', `country_code` = 'MNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zavkhan') . "', `country_code` = 'MNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Montenegro
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Andrijevica') . "', `country_code` = 'MNE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bar') . "', `country_code` = 'MNE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Berane') . "', `country_code` = 'MNE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bijelo Polje') . "', `country_code` = 'MNE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Budva') . "', `country_code` = 'MNE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cetinje') . "', `country_code` = 'MNE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Danilovgrad') . "', `country_code` = 'MNE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gusinje') . "', `country_code` = 'MNE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Herceg Novi') . "', `country_code` = 'MNE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kolašin') . "', `country_code` = 'MNE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kotor') . "', `country_code` = 'MNE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mojkovac') . "', `country_code` = 'MNE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nikšic') . "', `country_code` = 'MNE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Petnjica') . "', `country_code` = 'MNE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Plav') . "', `country_code` = 'MNE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pljevlja') . "', `country_code` = 'MNE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Plužine') . "', `country_code` = 'MNE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Podgorica') . "', `country_code` = 'MNE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rožaje') . "', `country_code` = 'MNE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tivat') . "', `country_code` = 'MNE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ulcinj') . "', `country_code` = 'MNE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Šavnik') . "', `country_code` = 'MNE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Žabljak') . "', `country_code` = 'MNE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Morocco
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Béni Mellal-Khénifra') . "', `country_code` = 'MAR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Casablanca-Settat') . "', `country_code` = 'MAR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dakhla-Oued Ed-Dahab') . "', `country_code` = 'MAR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Draâ-Tafilalet') . "', `country_code` = 'MAR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Fès-Meknès') . "', `country_code` = 'MAR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Guelmim-Oued Noun') . "', `country_code` = 'MAR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Laâyoune-Sakia El Hamra') . "', `country_code` = 'MAR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Marrakech-Safi') . "', `country_code` = 'MAR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Oriental') . "', `country_code` = 'MAR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rabat-Salé-Kénitra') . "', `country_code` = 'MAR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Souss-Massa') . "', `country_code` = 'MAR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tanger-Tetouan-Al Hoceima') . "', `country_code` = 'MAR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Mozambique
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cabo Delgado') . "', `country_code` = 'MOZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gaza') . "', `country_code` = 'MOZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Inhambane') . "', `country_code` = 'MOZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Manica') . "', `country_code` = 'MOZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Maputo') . "', `country_code` = 'MOZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Maputo City') . "', `country_code` = 'MOZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nampula') . "', `country_code` = 'MOZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Niassa') . "', `country_code` = 'MOZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sofala') . "', `country_code` = 'MOZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tete') . "', `country_code` = 'MOZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zambezia') . "', `country_code` = 'MOZ', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Myanmar
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ayeyarwaddy') . "', `country_code` = 'MMR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bago') . "', `country_code` = 'MMR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chin') . "', `country_code` = 'MMR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kachin') . "', `country_code` = 'MMR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kayah') . "', `country_code` = 'MMR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kayin') . "', `country_code` = 'MMR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Magwe') . "', `country_code` = 'MMR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mandalay') . "', `country_code` = 'MMR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mon') . "', `country_code` = 'MMR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rakhine') . "', `country_code` = 'MMR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sagaing') . "', `country_code` = 'MMR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Shan') . "', `country_code` = 'MMR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Thaninthayi') . "', `country_code` = 'MMR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Yangon') . "', `country_code` = 'MMR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Namibia
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Caprivi') . "', `country_code` = 'NAM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Erongo') . "', `country_code` = 'NAM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hardap') . "', `country_code` = 'NAM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Karas') . "', `country_code` = 'NAM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kavango West') . "', `country_code` = 'NAM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kavango West') . "', `country_code` = 'NAM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kavango East') . "', `country_code` = 'NAM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Khomas') . "', `country_code` = 'NAM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kunene') . "', `country_code` = 'NAM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ohangwena') . "', `country_code` = 'NAM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Omaheke') . "', `country_code` = 'NAM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Omusati') . "', `country_code` = 'NAM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Oshana') . "', `country_code` = 'NAM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Oshikoto') . "', `country_code` = 'NAM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Otjozondjupa') . "', `country_code` = 'NAM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        

        // Nauru
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Aiwo') . "', `country_code` = 'NRU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Anabar') . "', `country_code` = 'NRU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Anetan') . "', `country_code` = 'NRU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Anibare') . "', `country_code` = 'NRU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Baiti') . "', `country_code` = 'NRU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Boe') . "', `country_code` = 'NRU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Denigomodu') . "', `country_code` = 'NRU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ewa') . "', `country_code` = 'NRU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ijuw') . "', `country_code` = 'NRU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Meneng') . "', `country_code` = 'NRU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nibok') . "', `country_code` = 'NRU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Uaboe') . "', `country_code` = 'NRU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Yaren') . "', `country_code` = 'NRU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        

        // Nepal
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bagmati') . "', `country_code` = 'NPL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bheri') . "', `country_code` = 'NPL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dhawalagiri') . "', `country_code` = 'NPL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gandaki') . "', `country_code` = 'NPL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Janakpur') . "', `country_code` = 'NPL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Karnali') . "', `country_code` = 'NPL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Koshi') . "', `country_code` = 'NPL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lumbini') . "', `country_code` = 'NPL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mahakali') . "', `country_code` = 'NPL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mechi') . "', `country_code` = 'NPL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Narayani') . "', `country_code` = 'NPL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rapti') . "', `country_code` = 'NPL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sagarmatha') . "', `country_code` = 'NPL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Seti') . "', `country_code` = 'NPL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Netherlands
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Drenthe') . "', `country_code` = 'NLD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Flevoland') . "', `country_code` = 'NLD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Fryslân') . "', `country_code` = 'NLD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gelderland') . "', `country_code` = 'NLD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Groningen') . "', `country_code` = 'NLD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Limburg') . "', `country_code` = 'NLD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North Brabant') . "', `country_code` = 'NLD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North Holland') . "', `country_code` = 'NLD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Overijssel') . "', `country_code` = 'NLD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('South Holland') . "', `country_code` = 'NLD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Utrecht') . "', `country_code` = 'NLD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zeeland') . "', `country_code` = 'NLD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // New Zealand
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Auckland') . "', `country_code` = 'NZL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bay of Plenty') . "', `country_code` = 'NZL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Canterbury') . "', `country_code` = 'NZL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gisborne') . "', `country_code` = 'NZL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hawke\'s Bay') . "', `country_code` = 'NZL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Manawatu-Wanganui') . "', `country_code` = 'NZL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Marlborough') . "', `country_code` = 'NZL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nelson') . "', `country_code` = 'NZL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Northland') . "', `country_code` = 'NZL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Otago') . "', `country_code` = 'NZL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Southland') . "', `country_code` = 'NZL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Taranaki') . "', `country_code` = 'NZL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tasman') . "', `country_code` = 'NZL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Waikato') . "', `country_code` = 'NZL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Wellington') . "', `country_code` = 'NZL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('West Coast') . "', `country_code` = 'NZL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Nicaragua
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Boaco') . "', `country_code` = 'NIC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Carazo') . "', `country_code` = 'NIC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chinandega') . "', `country_code` = 'NIC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chontales') . "', `country_code` = 'NIC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Estelí') . "', `country_code` = 'NIC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Granada') . "', `country_code` = 'NIC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jinotega') . "', `country_code` = 'NIC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('León') . "', `country_code` = 'NIC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Madriz') . "', `country_code` = 'NIC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Managua') . "', `country_code` = 'NIC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Masaya') . "', `country_code` = 'NIC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Matagalpa') . "', `country_code` = 'NIC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North Caribbean Coast') . "', `country_code` = 'NIC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nueva Segovia') . "', `country_code` = 'NIC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rivas') . "', `country_code` = 'NIC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Río San Juan') . "', `country_code` = 'NIC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('South Caribbean Coast') . "', `country_code` = 'NIC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Niger
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Agadez') . "', `country_code` = 'NER', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Diffa') . "', `country_code` = 'NER', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dosso') . "', `country_code` = 'NER', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Maradi') . "', `country_code` = 'NER', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Niamey') . "', `country_code` = 'NER', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tahoua') . "', `country_code` = 'NER', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tillabéri') . "', `country_code` = 'NER', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zinder') . "', `country_code` = 'NER', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Nigeria
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Abia') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Adamawa') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Akwa Ibom') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Anambra') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bauchi') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bayelsa') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Benue') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Borno') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cross River') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Delta') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ebonyi') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Edo') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ekiti') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Enugu') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gombe') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Imo') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jigawa') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kaduna') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kano') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Katsina') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kebbi') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kogi') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kwara') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lagos') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nasarawa') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Niger') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ogun') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ondo') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Osun') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Oyo') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Plateau') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rivers') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sokoto') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Taraba') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Yobe') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zamfara') . "', `country_code` = 'NGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // North Korea
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chagang') . "', `country_code` = 'PRK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kangwon') . "', `country_code` = 'PRK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North Hamgyong') . "', `country_code` = 'PRK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North Hwanghae') . "', `country_code` = 'PRK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North Pyongan') . "', `country_code` = 'PRK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ryanggang') . "', `country_code` = 'PRK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('South Hamgyong') . "', `country_code` = 'PRK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('South Hwanghae') . "', `country_code` = 'PRK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('South Pyongan') . "', `country_code` = 'PRK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // North Macedonia
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Eastern') . "', `country_code` = 'MKD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Northeastern') . "', `country_code` = 'MKD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pelagonia') . "', `country_code` = 'MKD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Polog') . "', `country_code` = 'MKD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Skopje') . "', `country_code` = 'MKD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Southeastern') . "', `country_code` = 'MKD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Southwestern') . "', `country_code` = 'MKD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vardar') . "', `country_code` = 'MKD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        

        // Norway
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ayeyarwaddy') . "', `country_code` = 'NOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Aust-Agder') . "', `country_code` = 'NOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Buskerud') . "', `country_code` = 'NOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Finnmark') . "', `country_code` = 'NOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hedmark') . "', `country_code` = 'NOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hordaland') . "', `country_code` = 'NOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Oslo') . "', `country_code` = 'NOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Møre og Romsdal') . "', `country_code` = 'NOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nord-Trøndelag') . "', `country_code` = 'NOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nordland') . "', `country_code` = 'NOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Oppland') . "', `country_code` = 'NOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rogaland') . "', `country_code` = 'NOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sogn og Fjordane') . "', `country_code` = 'NOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sør-Trøndelag') . "', `country_code` = 'NOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Telemark') . "', `country_code` = 'NOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Troms') . "', `country_code` = 'NOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vest-Agder') . "', `country_code` = 'NOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vestfold') . "', `country_code` = 'NOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Østfold') . "', `country_code` = 'NOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Oman
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ad Dakhiliyah') . "', `country_code` = 'OMN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ad Dhahirah') . "', `country_code` = 'OMN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Al Batinah North') . "', `country_code` = 'OMN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Al Batinah South') . "', `country_code` = 'OMN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Al Buraimi') . "', `country_code` = 'OMN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Al Wusta') . "', `country_code` = 'OMN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ash Sharqiyah North') . "', `country_code` = 'OMN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ash Sharqiyah South') . "', `country_code` = 'OMN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dhofar') . "', `country_code` = 'OMN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Musandam') . "', `country_code` = 'OMN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Muscat') . "', `country_code` = 'OMN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Overseas Territory
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Anguilla') . "', `country_code` = 'OST', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Aruba') . "', `country_code` = 'OST', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bermuda') . "', `country_code` = 'OST', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Falklands') . "', `country_code` = 'OST', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Faroe Islands') . "', `country_code` = 'OST', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gibraltar') . "', `country_code` = 'OST', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Greenland') . "', `country_code` = 'OST', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Guadeloupe') . "', `country_code` = 'OST', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Guam') . "', `country_code` = 'OST', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Guernsey') . "', `country_code` = 'OST', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Isle of Man') . "', `country_code` = 'OST', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jersey') . "', `country_code` = 'OST', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Martinique') . "', `country_code` = 'OST', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Montserrat') . "', `country_code` = 'OST', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('New Caledonia') . "', `country_code` = 'OST', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Puerto Rico') . "', `country_code` = 'OST', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Réunion') . "', `country_code` = 'OST', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Svalbard') . "', `country_code` = 'OST', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        

        // Pakistan
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Azad Jammu and Kashmir') . "', `country_code` = 'PAK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Balochistan') . "', `country_code` = 'PAK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Federally Administered Tribal Areas') . "', `country_code` = 'PAK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gilgit-Baltistan') . "', `country_code` = 'PAK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Islamabad Capital Territory') . "', `country_code` = 'PAK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Khyber Pakhtunkhwa') . "', `country_code` = 'PAK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Punjab') . "', `country_code` = 'PAK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sindh') . "', `country_code` = 'PAK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Palau
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Aimeliik') . "', `country_code` = 'PLW', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Airai') . "', `country_code` = 'PLW', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Angaur') . "', `country_code` = 'PLW', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hatohobei') . "', `country_code` = 'PLW', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kayangel') . "', `country_code` = 'PLW', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Koror') . "', `country_code` = 'PLW', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Melekeok') . "', `country_code` = 'PLW', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ngaraard') . "', `country_code` = 'PLW', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ngarchelong') . "', `country_code` = 'PLW', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ngardmau') . "', `country_code` = 'PLW', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ngatpang') . "', `country_code` = 'PLW', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ngchesar') . "', `country_code` = 'PLW', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ngeremlengui') . "', `country_code` = 'PLW', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");    
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ngiwal') . "', `country_code` = 'PLW', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Peleliu') . "', `country_code` = 'PLW', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sonsorol') . "', `country_code` = 'PLW', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        

        // Palestine
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bethlehem') . "', `country_code` = 'PSE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Deir al-Balah') . "', `country_code` = 'PSE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gaza') . "', `country_code` = 'PSE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hebron') . "', `country_code` = 'PSE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jenin') . "', `country_code` = 'PSE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jericho') . "', `country_code` = 'PSE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jerusalem') . "', `country_code` = 'PSE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Khan Yunis') . "', `country_code` = 'PSE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nablus') . "', `country_code` = 'PSE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North Gaza') . "', `country_code` = 'PSE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Qalqilya') . "', `country_code` = 'PSE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rafah') . "', `country_code` = 'PSE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ramallah and Al-Bireh') . "', `country_code` = 'PSE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Salfit') . "', `country_code` = 'PSE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tubas') . "', `country_code` = 'PSE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tulkarm') . "', `country_code` = 'PSE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Panama
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bocas del Toro') . "', `country_code` = 'PAN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chiriquí') . "', `country_code` = 'PAN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Coclé') . "', `country_code` = 'PAN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Colón') . "', `country_code` = 'PAN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Darién') . "', `country_code` = 'PAN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Herrera') . "', `country_code` = 'PAN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Los Santos') . "', `country_code` = 'PAN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Panamá') . "', `country_code` = 'PAN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Panamá Oeste') . "', `country_code` = 'PAN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Veraguas') . "', `country_code` = 'PAN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Papua New Guinea
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bougainville') . "', `country_code` = 'PNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Central') . "', `country_code` = 'PNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('East New Britain') . "', `country_code` = 'PNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('East Sepik') . "', `country_code` = 'PNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Eastern Highlands') . "', `country_code` = 'PNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Enga') . "', `country_code` = 'PNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gulf') . "', `country_code` = 'PNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hela') . "', `country_code` = 'PNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jiwaka') . "', `country_code` = 'PNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Madang') . "', `country_code` = 'PNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Manus') . "', `country_code` = 'PNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Milne Bay') . "', `country_code` = 'PNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Morobe') . "', `country_code` = 'PNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('National Capital District') . "', `country_code` = 'PNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('New Ireland') . "', `country_code` = 'PNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Oro') . "', `country_code` = 'PNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sandaun') . "', `country_code` = 'PNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Simbu') . "', `country_code` = 'PNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Southern Highlands') . "', `country_code` = 'PNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('West New Britain') . "', `country_code` = 'PNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Western') . "', `country_code` = 'PNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Western Highlands') . "', `country_code` = 'PNG', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Paraguay
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Distrito Capital') . "', `country_code` = 'PRY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Central') . "', `country_code` = 'PRY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Alto Paraguay') . "', `country_code` = 'PRY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Alto Paraná') . "', `country_code` = 'PRY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Amambay') . "', `country_code` = 'PRY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Boquerón') . "', `country_code` = 'PRY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Caaguazú') . "', `country_code` = 'PRY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Caazapá') . "', `country_code` = 'PRY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Canindeyú') . "', `country_code` = 'PRY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Concepción') . "', `country_code` = 'PRY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cordillera') . "', `country_code` = 'PRY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Guairá') . "', `country_code` = 'PRY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Itapúa') . "', `country_code` = 'PRY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Misiones') . "', `country_code` = 'PRY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ñeembucú') . "', `country_code` = 'PRY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Paraguarí') . "', `country_code` = 'PRY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Presidente Hayes') . "', `country_code` = 'PRY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('San Pedro') . "', `country_code` = 'PRY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Peru
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Amazonas') . "', `country_code` = 'PER', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ancash') . "', `country_code` = 'PER', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Apurímac') . "', `country_code` = 'PER', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Arequipa') . "', `country_code` = 'PER', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ayacucho') . "', `country_code` = 'PER', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cajamarca') . "', `country_code` = 'PER', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Callao') . "', `country_code` = 'PER', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cuzco') . "', `country_code` = 'PER', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Huancavelica') . "', `country_code` = 'PER', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Huánuco') . "', `country_code` = 'PER', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ica') . "', `country_code` = 'PER', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Junín') . "', `country_code` = 'PER', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('La Libertad') . "', `country_code` = 'PER', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lambayeque') . "', `country_code` = 'PER', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lima') . "', `country_code` = 'PER', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Loreto') . "', `country_code` = 'PER', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Madre de Dios') . "', `country_code` = 'PER', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Moquegua') . "', `country_code` = 'PER', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pasco') . "', `country_code` = 'PER', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Piura') . "', `country_code` = 'PER', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Puno') . "', `country_code` = 'PER', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('San Martín') . "', `country_code` = 'PER', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tacna') . "', `country_code` = 'PER', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tumbes') . "', `country_code` = 'PER', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ucayali') . "', `country_code` = 'PER', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Philippines
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bicol') . "', `country_code` = 'PHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cagayan') . "', `country_code` = 'PHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Calabarzon') . "', `country_code` = 'PHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Caraga') . "', `country_code` = 'PHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Central Luzon') . "', `country_code` = 'PHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Central Visayas') . "', `country_code` = 'PHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cordillera') . "', `country_code` = 'PHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Davao') . "', `country_code` = 'PHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Eastern Visayas') . "', `country_code` = 'PHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ilocos') . "', `country_code` = 'PHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mimaropa') . "', `country_code` = 'PHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mindanao') . "', `country_code` = 'PHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('National Capital Region') . "', `country_code` = 'PHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Negros Island Region') . "', `country_code` = 'PHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Northern Mindanao') . "', `country_code` = 'PHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Soccsksargen') . "', `country_code` = 'PHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Western Visayas') . "', `country_code` = 'PHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zamboanga Peninsula') . "', `country_code` = 'PHL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Poland
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Greater Poland') . "', `country_code` = 'POL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kuyavian-Pomeranian') . "', `country_code` = 'POL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lesser Poland') . "', `country_code` = 'POL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lódz') . "', `country_code` = 'POL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lower Silesian') . "', `country_code` = 'POL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lublin') . "', `country_code` = 'POL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lubusz') . "', `country_code` = 'POL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Masovian') . "', `country_code` = 'POL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Opole') . "', `country_code` = 'POL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Podlaskie') . "', `country_code` = 'POL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pomeranian') . "', `country_code` = 'POL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Silesian') . "', `country_code` = 'POL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Subcarpathian') . "', `country_code` = 'POL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Swietokrzyskie') . "', `country_code` = 'POL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Warmian-Masurian') . "', `country_code` = 'POL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('West Pomeranian') . "', `country_code` = 'POL', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Portugal
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Aveiro') . "', `country_code` = 'PRT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Azores Islands') . "', `country_code` = 'PRT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Beja') . "', `country_code` = 'PRT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Braga') . "', `country_code` = 'PRT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bragança') . "', `country_code` = 'PRT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Castelo Branco') . "', `country_code` = 'PRT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Coimbra') . "', `country_code` = 'PRT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Faro') . "', `country_code` = 'PRT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Guarda') . "', `country_code` = 'PRT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Leiria') . "', `country_code` = 'PRT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lisbon') . "', `country_code` = 'PRT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Madeira Islands') . "', `country_code` = 'PRT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Portalegre') . "', `country_code` = 'PRT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Porto') . "', `country_code` = 'PRT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Santarém') . "', `country_code` = 'PRT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Setúbal') . "', `country_code` = 'PRT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Viana do Castelo') . "', `country_code` = 'PRT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vila Real') . "', `country_code` = 'PRT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Viseu') . "', `country_code` = 'PRT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Évora') . "', `country_code` = 'PRT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Qatar
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ad Dawhah') . "', `country_code` = 'QAT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Al Daayen') . "', `country_code` = 'QAT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Al Khor') . "', `country_code` = 'QAT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Al Wakrah') . "', `country_code` = 'QAT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ar Rayyan') . "', `country_code` = 'QAT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Madinat ash Shamal') . "', `country_code` = 'QAT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Umm Salal') . "', `country_code` = 'QAT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Romania
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Alba') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Arad') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Arges') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bacau') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bihor') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bistrita-Nasaud') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Botosani') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Brasov') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Braila') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bucuresti') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Buzau') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Caras-Severin') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cluj') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Constanta') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Covasna') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Calarasi') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dolj') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dâmbovita') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Galati') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Giurgiu') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gorj') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Harghita') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hunedoara') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ialomita') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Iasi') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ilfov') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Maramures') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mehedinti') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mures') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Neamt') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Olt') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Prahova') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Satu Mare') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sibiu') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Suceava') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Salaj') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Teleorman') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Timis') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tulcea') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vaslui') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vrancea') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vâlcea') . "', `country_code` = 'ROM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Russia
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Adygea') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Altai Krai') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Altai Republic') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Amur Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Arkhangelsk Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Astrakhan Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bashkortostan') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Belgorod Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bryansk Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Buryatia') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chechen Republic') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chelyabinsk Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chukotka Autonomous Okrug') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chuvash Republic') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Crimea') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dagestan') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ingushetia') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Irkutsk Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ivanovo Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jewish Autonomous Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kabardino-Balkar Republic') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kaliningrad Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kalmykia') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kaluga Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kamchatka Krai') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Karachay-Cherkess Republic') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Karelia') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kemerovo Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Khabarovsk Krai') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Khakassia') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Khanty–Mansi Autonomous Okrug – Yugra') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kirov Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Komi Republic') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kostroma Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Krasnodar Krai') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Krasnoyarsk Krai') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kurgan Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kursk Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Leningrad Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lipetsk Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Magadan Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mari El Republic') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mordovia') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Moscow Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Murmansk Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nenets Autonomous Okrug') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nizhny Novgorod Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North Ossetia-Alania') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Novgorod Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Novosibirsk Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Omsk Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Orenburg Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Oryol Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Penza Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Perm Krai') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Primorsky Krai') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pskov Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rostov Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ryazan Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Petersburg') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sakha Republic') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sakhalin Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Samara Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saratov Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sevastopol') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Smolensk Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Stavropol Krai') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sverdlovsk Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tambov Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tatarstan') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tomsk Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tula Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tuva Republic') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tver Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tyumen Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Udmurt Republic') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ulyanovsk Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vladimir Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Volgograd Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vologda Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Voronezh Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Yamalo-Nenets Autonomous Okrug') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Yaroslavl Oblast') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zabaykalsky Krai') . "', `country_code` = 'RUS', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Rwanda
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bugesera') . "', `country_code` = 'RWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Burera') . "', `country_code` = 'RWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gakenke') . "', `country_code` = 'RWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gasabo') . "', `country_code` = 'RWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gatsibo') . "', `country_code` = 'RWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gicumbi') . "', `country_code` = 'RWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gisagara') . "', `country_code` = 'RWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Huye') . "', `country_code` = 'RWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kamonyi') . "', `country_code` = 'RWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Karongi') . "', `country_code` = 'RWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kayonza') . "', `country_code` = 'RWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kicukiro') . "', `country_code` = 'RWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kirehe') . "', `country_code` = 'RWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Muhanga') . "', `country_code` = 'RWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Musanze') . "', `country_code` = 'RWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ngoma') . "', `country_code` = 'RWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ngororero') . "', `country_code` = 'RWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nyabihu') . "', `country_code` = 'RWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nyagatare') . "', `country_code` = 'RWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nyamagabe') . "', `country_code` = 'RWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nyamasheke') . "', `country_code` = 'RWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nyanza') . "', `country_code` = 'RWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nyarugenge') . "', `country_code` = 'RWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nyaruguru') . "', `country_code` = 'RWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rubavu') . "', `country_code` = 'RWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ruhango') . "', `country_code` = 'RWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rulindo') . "', `country_code` = 'RWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rusizi') . "', `country_code` = 'RWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rutsiro') . "', `country_code` = 'RWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rwamagana') . "', `country_code` = 'RWA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Saint Kitts and Nevis
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Christ Church Nichola Town') . "', `country_code` = 'KNA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Anne Sandy Point') . "', `country_code` = 'KNA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint George Basseterre') . "', `country_code` = 'KNA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint George Gingerland') . "', `country_code` = 'KNA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint James Windward') . "', `country_code` = 'KNA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint John Capisterre') . "', `country_code` = 'KNA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint John Figtree') . "', `country_code` = 'KNA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Mary Cayon') . "', `country_code` = 'KNA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Paul Capisterre') . "', `country_code` = 'KNA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Paul Charlestown') . "', `country_code` = 'KNA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Peter Basseterre') . "', `country_code` = 'KNA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Thomas Lowland') . "', `country_code` = 'KNA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Thomas Middle Island') . "', `country_code` = 'KNA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Trinity Palmetto Point') . "', `country_code` = 'KNA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        

        // Saint Lucia
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Anse la Raye') . "', `country_code` = 'LCA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Canaries') . "', `country_code` = 'LCA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Castries') . "', `country_code` = 'LCA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Choiseul') . "', `country_code` = 'LCA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dennery') . "', `country_code` = 'LCA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gros Islet') . "', `country_code` = 'LCA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Laborie') . "', `country_code` = 'LCA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Micoud') . "', `country_code` = 'LCA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Soufrière') . "', `country_code` = 'LCA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vieux Fort') . "', `country_code` = 'LCA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        

        // Saint Vincent and the Grenadines
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Charlotte Parish') . "', `country_code` = 'VCT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Grenadines Parish') . "', `country_code` = 'VCT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Andrew Parish') . "', `country_code` = 'VCT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint David Parish') . "', `country_code` = 'VCT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint George Parish') . "', `country_code` = 'VCT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Patrick Parish') . "', `country_code` = 'VCT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        

        // Samoa
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Eastern') . "', `country_code` = 'WSM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Manu\'a') . "', `country_code` = 'WSM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Western') . "', `country_code` = 'WSM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // San Marino
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Acquaviva') . "', `country_code` = 'SMR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Borgo Maggiore') . "', `country_code` = 'SMR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chiesanuova') . "', `country_code` = 'SMR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Domagnano') . "', `country_code` = 'SMR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Faetano') . "', `country_code` = 'SMR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Fiorentino') . "', `country_code` = 'SMR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Montegiardino') . "', `country_code` = 'SMR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('San Marino') . "', `country_code` = 'SMR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Serravalle') . "', `country_code` = 'SMR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // São Tomé and Príncipe
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Água Grande') . "', `country_code` = 'STP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cantagalo') . "', `country_code` = 'STP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Caué') . "', `country_code` = 'STP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lembá') . "', `country_code` = 'STP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lobata') . "', `country_code` = 'STP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mé-Zóchi') . "', `country_code` = 'STP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Príncipe ') . "', `country_code` = 'STP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        

        // Saudi Arabia
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('\'Asir') . "', `country_code` = 'SAU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bahah') . "', `country_code` = 'SAU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Eastern Province') . "', `country_code` = 'SAU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ha\'il') . "', `country_code` = 'SAU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jawf') . "', `country_code` = 'SAU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jizan') . "', `country_code` = 'SAU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Madinah') . "', `country_code` = 'SAU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Makkah') . "', `country_code` = 'SAU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Najran') . "', `country_code` = 'SAU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Northern Borders') . "', `country_code` = 'SAU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Qassim') . "', `country_code` = 'SAU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Riyadh') . "', `country_code` = 'SAU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tabuk') . "', `country_code` = 'SAU', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Senegal
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dakar') . "', `country_code` = 'SEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Diourbel') . "', `country_code` = 'SEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Fatick') . "', `country_code` = 'SEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kaffrine') . "', `country_code` = 'SEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kaolack') . "', `country_code` = 'SEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kolda') . "', `country_code` = 'SEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kédougou') . "', `country_code` = 'SEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Louga') . "', `country_code` = 'SEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Matam') . "', `country_code` = 'SEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint-Louis') . "', `country_code` = 'SEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sédhiou') . "', `country_code` = 'SEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tambacounda') . "', `country_code` = 'SEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Thiès') . "', `country_code` = 'SEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ziguinchor') . "', `country_code` = 'SEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Serbia
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bor') . "', `country_code` = 'SRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Branicevo') . "', `country_code` = 'SRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Central Banat') . "', `country_code` = 'SRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jablanica') . "', `country_code` = 'SRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kolubara') . "', `country_code` = 'SRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kosovo District') . "', `country_code` = 'SRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kosovo-Pomoravlje') . "', `country_code` = 'SRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kosovska Mitrovica') . "', `country_code` = 'SRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Macva') . "', `country_code` = 'SRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Moravica') . "', `country_code` = 'SRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nišava') . "', `country_code` = 'SRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North Backa') . "', `country_code` = 'SRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North Banat') . "', `country_code` = 'SRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pcinja') . "', `country_code` = 'SRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pec') . "', `country_code` = 'SRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pirot') . "', `country_code` = 'SRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Podunavlje') . "', `country_code` = 'SRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pomoravlje') . "', `country_code` = 'SRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Prizren') . "', `country_code` = 'SRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rasina') . "', `country_code` = 'SRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Raška') . "', `country_code` = 'SRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('South Backa') . "', `country_code` = 'SRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('South Banat') . "', `country_code` = 'SRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Srem') . "', `country_code` = 'SRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Šumadija') . "', `country_code` = 'SRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Toplica') . "', `country_code` = 'SRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('West Backa') . "', `country_code` = 'SRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zajecar') . "', `country_code` = 'SRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zlatibor') . "', `country_code` = 'SRB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Seychelles
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Anse aux Pins') . "', `country_code` = 'SYC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Anse Boileau') . "', `country_code` = 'SYC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Anse Étoile') . "', `country_code` = 'SYC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Anse Royale') . "', `country_code` = 'SYC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Au Cap') . "', `country_code` = 'SYC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Baie Lazare') . "', `country_code` = 'SYC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Baie Sainte Anne') . "', `country_code` = 'SYC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Beau Vallon') . "', `country_code` = 'SYC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bel Air') . "', `country_code` = 'SYC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Belombre') . "', `country_code` = 'SYC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cascade') . "', `country_code` = 'SYC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('English River') . "', `country_code` = 'SYC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Glacis') . "', `country_code` = 'SYC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Grand\' Anse Mahé') . "', `country_code` = 'SYC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Grand\' Anse Praslin') . "', `country_code` = 'SYC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('La Digue and Inner Islands') . "', `country_code` = 'SYC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Les Mamelles') . "', `country_code` = 'SYC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mont Buxton') . "', `country_code` = 'SYC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mont Fleuri') . "', `country_code` = 'SYC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Outer Islands') . "', `country_code` = 'SYC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Plaisance') . "', `country_code` = 'SYC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pointe La Rue') . "', `country_code` = 'SYC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Port Glaud') . "', `country_code` = 'SYC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Roche Caïman') . "', `country_code` = 'SYC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Louis') . "', `country_code` = 'SYC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Takamaka') . "', `country_code` = 'SYC', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        

        // Sierra Leone
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bo') . "', `country_code` = 'SLE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bombali') . "', `country_code` = 'SLE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bonthe') . "', `country_code` = 'SLE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kailahun') . "', `country_code` = 'SLE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kambia') . "', `country_code` = 'SLE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kenema') . "', `country_code` = 'SLE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Koinadugu') . "', `country_code` = 'SLE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kono') . "', `country_code` = 'SLE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Moyamba') . "', `country_code` = 'SLE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Port Loko') . "', `country_code` = 'SLE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pujehun') . "', `country_code` = 'SLE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tonkolili') . "', `country_code` = 'SLE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Western Rural') . "', `country_code` = 'SLE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Western Urban') . "', `country_code` = 'SLE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Singapore
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Central Region') . "', `country_code` = 'SGP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('East Region') . "', `country_code` = 'SGP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North Region') . "', `country_code` = 'SGP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North-East Region') . "', `country_code` = 'SGP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('West Region') . "', `country_code` = 'SGP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Slovakia
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Banská Bystrica') . "', `country_code` = 'SVK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bratislava') . "', `country_code` = 'SVK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Košice') . "', `country_code` = 'SVK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nitra') . "', `country_code` = 'SVK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Prešov') . "', `country_code` = 'SVK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Trencín') . "', `country_code` = 'SVK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Trnava') . "', `country_code` = 'SVK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Žilina') . "', `country_code` = 'SVK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Slovenia
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Carinthia') . "', `country_code` = 'SVN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Central Sava') . "', `country_code` = 'SVN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Central Slovenia') . "', `country_code` = 'SVN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Coastal–Karst') . "', `country_code` = 'SVN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Drava') . "', `country_code` = 'SVN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gorizia') . "', `country_code` = 'SVN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Littoral–Inner Carniola') . "', `country_code` = 'SVN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lower Sava') . "', `country_code` = 'SVN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mura') . "', `country_code` = 'SVN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Savinja') . "', `country_code` = 'SVN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Southeast Slovenia') . "', `country_code` = 'SVN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Upper Carniola') . "', `country_code` = 'SVN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Solomon Islands
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Capital Territory') . "', `country_code` = 'SLB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Central Province') . "', `country_code` = 'SLB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Choiseul Province') . "', `country_code` = 'SLB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Guadalcanal Province') . "', `country_code` = 'SLB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Isabel Province') . "', `country_code` = 'SLB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Makira-Ulawa Province') . "', `country_code` = 'SLB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Malaita Province') . "', `country_code` = 'SLB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rennell and Bellona Province') . "', `country_code` = 'SLB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Temotu Province') . "', `country_code` = 'SLB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Western Province') . "', `country_code` = 'SLB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        

        // Somalia
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Awdal') . "', `country_code` = 'SOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bakool') . "', `country_code` = 'SOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Banaadir') . "', `country_code` = 'SOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bari') . "', `country_code` = 'SOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bay') . "', `country_code` = 'SOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Galguduud') . "', `country_code` = 'SOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gedo') . "', `country_code` = 'SOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hiran') . "', `country_code` = 'SOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lower Juba') . "', `country_code` = 'SOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lower Shebelle') . "', `country_code` = 'SOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Middle Juba') . "', `country_code` = 'SOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Middle Shebelle') . "', `country_code` = 'SOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mudug') . "', `country_code` = 'SOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nugal') . "', `country_code` = 'SOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sanaag') . "', `country_code` = 'SOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sool') . "', `country_code` = 'SOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Togdheer') . "', `country_code` = 'SOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Woqooyi Galbeed') . "', `country_code` = 'SOM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // South Africa
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Eastern Cape') . "', `country_code` = 'ZAF', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Free State') . "', `country_code` = 'ZAF', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gauteng') . "', `country_code` = 'ZAF', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('KwaZulu-Natal') . "', `country_code` = 'ZAF', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Limpopo') . "', `country_code` = 'ZAF', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mpumalanga') . "', `country_code` = 'ZAF', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North West') . "', `country_code` = 'ZAF', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Northern Cape') . "', `country_code` = 'ZAF', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Western Cape') . "', `country_code` = 'ZAF', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // South Korea
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gangwon') . "', `country_code` = 'KOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gyeonggi') . "', `country_code` = 'KOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jeju') . "', `country_code` = 'KOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North Chungcheong') . "', `country_code` = 'KOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North Gyeongsang') . "', `country_code` = 'KOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North Jeolla') . "', `country_code` = 'KOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('South Chungcheong') . "', `country_code` = 'KOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('South Gyeongsang') . "', `country_code` = 'KOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('South Jeolla') . "', `country_code` = 'KOR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // South Sudan
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Central Equatoria') . "', `country_code` = 'SSD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Eastern Equatoria') . "', `country_code` = 'SSD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jonglei') . "', `country_code` = 'SSD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lakes') . "', `country_code` = 'SSD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Northern Bahr el Ghazal') . "', `country_code` = 'SSD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Unity') . "', `country_code` = 'SSD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Upper Nile') . "', `country_code` = 'SSD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Warrap') . "', `country_code` = 'SSD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Western Bahr el Ghazal') . "', `country_code` = 'SSD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Western Equatoria') . "', `country_code` = 'SSD', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Spain
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('A Coruña') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Álava') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Albacete') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Alicante') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Almería') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Asturias') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ávila') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Badajoz') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Balearic Islands') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Barcelona') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Biscay') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Burgos') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cáceres') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cádiz') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cantabria') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Castellón') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ciudad Real') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Córdoba') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cuenca') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gipuzkoa') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Girona') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Granada') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Guadalajara') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Huelva') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Huesca') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jaén') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('La Rioja') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Las Palmas') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('León') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lleida') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lugo') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Madrid') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Málaga') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Murcia') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Navarre') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ourense') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Palencia') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pontevedra') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Salamanca') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Santa Cruz de Tenerife') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Segovia') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Seville') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Soria') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tarragona') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Teruel') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Toledo') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Valencia') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Valladolid') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zamora') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zaragoza') . "', `country_code` = 'ESP', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Sri Lanka
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ampara') . "', `country_code` = 'LKA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Anuradhapura') . "', `country_code` = 'LKA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Badulla') . "', `country_code` = 'LKA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Batticaloa') . "', `country_code` = 'LKA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Colombo') . "', `country_code` = 'LKA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Galle') . "', `country_code` = 'LKA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gampaha') . "', `country_code` = 'LKA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hambantota') . "', `country_code` = 'LKA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jaffna') . "', `country_code` = 'LKA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kalutara') . "', `country_code` = 'LKA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kandy') . "', `country_code` = 'LKA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kegalle') . "', `country_code` = 'LKA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kilinochchi') . "', `country_code` = 'LKA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kurunegala') . "', `country_code` = 'LKA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mannar') . "', `country_code` = 'LKA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Matale') . "', `country_code` = 'LKA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Matara') . "', `country_code` = 'LKA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Monaragala') . "', `country_code` = 'LKA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mullaitivu') . "', `country_code` = 'LKA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nuwara Eliya') . "', `country_code` = 'LKA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Polonnaruwa') . "', `country_code` = 'LKA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Puttalam') . "', `country_code` = 'LKA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ratnapura') . "', `country_code` = 'LKA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Trincomalee') . "', `country_code` = 'LKA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vavuniya') . "', `country_code` = 'LKA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Sudan
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Al Jazirah') . "', `country_code` = 'SDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Al Qadarif') . "', `country_code` = 'SDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Blue Nile') . "', `country_code` = 'SDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Central Darfur') . "', `country_code` = 'SDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('East Darfur') . "', `country_code` = 'SDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kassala') . "', `country_code` = 'SDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Khartoum') . "', `country_code` = 'SDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North Darfur') . "', `country_code` = 'SDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North Kurdufan') . "', `country_code` = 'SDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Northern') . "', `country_code` = 'SDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Red Sea') . "', `country_code` = 'SDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('River Nile') . "', `country_code` = 'SDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sennar') . "', `country_code` = 'SDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('South Darfur') . "', `country_code` = 'SDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('South Kurdufan') . "', `country_code` = 'SDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('West Darfur') . "', `country_code` = 'SDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('West Kurdufan') . "', `country_code` = 'SDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('White Nile') . "', `country_code` = 'SDN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Suriname
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Brokopondo') . "', `country_code` = 'SUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Commewijne') . "', `country_code` = 'SUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Coronie') . "', `country_code` = 'SUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Marowijne') . "', `country_code` = 'SUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nickerie') . "', `country_code` = 'SUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Para') . "', `country_code` = 'SUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Paramaribo') . "', `country_code` = 'SUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saramacca') . "', `country_code` = 'SUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sipaliwini') . "', `country_code` = 'SUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Wanica') . "', `country_code` = 'SUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Sweden
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Blekinge') . "', `country_code` = 'SWE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dalarna') . "', `country_code` = 'SWE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gävleborg') . "', `country_code` = 'SWE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gotland') . "', `country_code` = 'SWE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Halland') . "', `country_code` = 'SWE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jämtland') . "', `country_code` = 'SWE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jönköping') . "', `country_code` = 'SWE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kalmar') . "', `country_code` = 'SWE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kronoberg') . "', `country_code` = 'SWE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Norrbotten') . "', `country_code` = 'SWE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Örebro') . "', `country_code` = 'SWE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Östergötland') . "', `country_code` = 'SWE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Skåne') . "', `country_code` = 'SWE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Södermanland') . "', `country_code` = 'SWE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Stockholm') . "', `country_code` = 'SWE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Uppsala') . "', `country_code` = 'SWE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Värmland') . "', `country_code` = 'SWE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Västerbotten') . "', `country_code` = 'SWE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Västernorrland') . "', `country_code` = 'SWE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Västmanland') . "', `country_code` = 'SWE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Västra Götaland') . "', `country_code` = 'SWE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Switzerland
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Aargau') . "', `country_code` = 'CHE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Appenzell Ausserrhoden') . "', `country_code` = 'CHE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Appenzell Innerrhoden') . "', `country_code` = 'CHE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Basel-Landschaft') . "', `country_code` = 'CHE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Basel-Stadt') . "', `country_code` = 'CHE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bern') . "', `country_code` = 'CHE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Fribourg') . "', `country_code` = 'CHE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Geneva') . "', `country_code` = 'CHE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Glarus') . "', `country_code` = 'CHE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Graubünden') . "', `country_code` = 'CHE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jura') . "', `country_code` = 'CHE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Luzern') . "', `country_code` = 'CHE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Neuchâtel') . "', `country_code` = 'CHE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nidwalden') . "', `country_code` = 'CHE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Obwalden') . "', `country_code` = 'CHE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Schaffhausen') . "', `country_code` = 'CHE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Schwyz') . "', `country_code` = 'CHE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Solothurn') . "', `country_code` = 'CHE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('St. Gallen') . "', `country_code` = 'CHE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Thurgau') . "', `country_code` = 'CHE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ticino') . "', `country_code` = 'CHE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Uri') . "', `country_code` = 'CHE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Valais') . "', `country_code` = 'CHE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vaud') . "', `country_code` = 'CHE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zug') . "', `country_code` = 'CHE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zürich') . "', `country_code` = 'CHE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Syria
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Aleppo') . "', `country_code` = 'SYR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Al-Hasakah') . "', `country_code` = 'SYR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ar-Raqqah') . "', `country_code` = 'SYR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('As-Suwayda') . "', `country_code` = 'SYR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Damascus') . "', `country_code` = 'SYR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Daraa') . "', `country_code` = 'SYR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Deir ez-Zor') . "', `country_code` = 'SYR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hama') . "', `country_code` = 'SYR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Homs') . "', `country_code` = 'SYR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Idlib') . "', `country_code` = 'SYR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Latakia') . "', `country_code` = 'SYR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Quneitra') . "', `country_code` = 'SYR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rif Dimashq') . "', `country_code` = 'SYR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tartus') . "', `country_code` = 'SYR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Tajikistan
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dushanbe') . "', `country_code` = 'TJK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gorno-Badakhshan') . "', `country_code` = 'TJK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Khatlon') . "', `country_code` = 'TJK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Republican Subordination') . "', `country_code` = 'TJK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sughd') . "', `country_code` = 'TJK', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Tanzania
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Arusha') . "', `country_code` = 'TZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dar es Salaam') . "', `country_code` = 'TZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dodoma') . "', `country_code` = 'TZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Geita') . "', `country_code` = 'TZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Iringa') . "', `country_code` = 'TZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kagera') . "', `country_code` = 'TZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Katavi') . "', `country_code` = 'TZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kigoma') . "', `country_code` = 'TZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kilimanjaro') . "', `country_code` = 'TZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lindi') . "', `country_code` = 'TZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Manyara') . "', `country_code` = 'TZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mara') . "', `country_code` = 'TZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mbeya') . "', `country_code` = 'TZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mjini Magharibi') . "', `country_code` = 'TZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Morogoro') . "', `country_code` = 'TZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mtwara') . "', `country_code` = 'TZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mwanza') . "', `country_code` = 'TZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Njombe') . "', `country_code` = 'TZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pemba North') . "', `country_code` = 'TZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pemba South') . "', `country_code` = 'TZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pwani') . "', `country_code` = 'TZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rukwa') . "', `country_code` = 'TZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ruvuma') . "', `country_code` = 'TZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Shinyanga') . "', `country_code` = 'TZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Simiyu') . "', `country_code` = 'TZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Singida') . "', `country_code` = 'TZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tabora') . "', `country_code` = 'TZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tanga') . "', `country_code` = 'TZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zanzibar North') . "', `country_code` = 'TZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zanzibar South') . "', `country_code` = 'TZA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Thailand
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Amnat Charoen') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mukdahan') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Phitsanulok') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sa Kaeo') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ang Thong') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Phra Nakhon Si Ayutthaya') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bangkok') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bueng Kan') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Buriram') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chachoengsao') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chainat') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chaiyaphum') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chanthaburi') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chiang Mai') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chiang Rai') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chonburi') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chumphon') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kalasin') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kamphaeng Phet') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kanchanaburi') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Khon Kaen') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Krabi') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lampang') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lamphun') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Loei Province') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lopburi Province') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mae Hong Son') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Maha Sarakham') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nakhon Nayok') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nakhon Pathom') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nakhon Phanom') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nakhon Ratchasima') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nakhon Sawan') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nakhon Si Thammarat') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nan') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Narathiwat') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nong Bua Lamphu') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nong Khai') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nonthaburi') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pathum Thani') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pattani') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Phang Nga') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Phatthalung') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Phayao') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Phetchabun') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Phetchaburi') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Phichit') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Phrae') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Phuket') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Prachinburi') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Prachuap Khiri Khan') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ranong') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ratchaburi') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rayong') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Roi Et') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sakon Nakhon') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Samut Prakan') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Samut Sakhon') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Samut Songkhram') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saraburi') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Satun') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sing Buri') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sisaket') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Songkhla') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sukhothai') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Suphan Buri') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Surat Thani') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Surin') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tak') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Trang') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Trat') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ubon Ratchathani') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Udon Thani') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Uttaradit') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Yala') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Uthai Thani') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Yasothon') . "', `country_code` = 'THA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Togo
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Centrale') . "', `country_code` = 'TGO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kara') . "', `country_code` = 'TGO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Maritime') . "', `country_code` = 'TGO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Plateaux') . "', `country_code` = 'TGO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Savanes') . "', `country_code` = 'TGO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Tonga
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tongatapu') . "', `country_code` = 'TON', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vava\'u') . "', `country_code` = 'TON', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ha\'apai') . "', `country_code` = 'TON', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('\'Eua') . "', `country_code` = 'TON', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ongo Niua') . "', `country_code` = 'TON', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        

        // Trinidad and Tobago
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Caroni') . "', `country_code` = 'TTO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mayaro') . "', `country_code` = 'TTO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nariva') . "', `country_code` = 'TTO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Andrew') . "', `country_code` = 'TTO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint David') . "', `country_code` = 'TTO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint George') . "', `country_code` = 'TTO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Saint Patrick') . "', `country_code` = 'TTO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Victoria') . "', `country_code` = 'TTO', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Tunisia
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ariana') . "', `country_code` = 'TUN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ben Arous') . "', `country_code` = 'TUN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bizerte') . "', `country_code` = 'TUN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Béja') . "', `country_code` = 'TUN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gabès') . "', `country_code` = 'TUN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gafsa') . "', `country_code` = 'TUN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jendouba') . "', `country_code` = 'TUN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kairouan') . "', `country_code` = 'TUN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kasserine') . "', `country_code` = 'TUN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kebili') . "', `country_code` = 'TUN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kef') . "', `country_code` = 'TUN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mahdia') . "', `country_code` = 'TUN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Manouba') . "', `country_code` = 'TUN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Medenine') . "', `country_code` = 'TUN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Monastir') . "', `country_code` = 'TUN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nabeul') . "', `country_code` = 'TUN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sfax') . "', `country_code` = 'TUN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sidi Bouzid') . "', `country_code` = 'TUN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Siliana') . "', `country_code` = 'TUN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sousse') . "', `country_code` = 'TUN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tataouine') . "', `country_code` = 'TUN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tozeur') . "', `country_code` = 'TUN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tunis') . "', `country_code` = 'TUN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zaghouan') . "', `country_code` = 'TUN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Turkey
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Adana') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Adiyaman') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Afyonkarahisar') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Agri') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Aksaray') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Amasya') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ankara') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Antalya') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ardahan') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Artvin') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Aydin') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Balikesir') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bartin') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Batman') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bayburt') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bilecik') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bingöl') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bitlis') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bolu') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Burdur') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bursa') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Çanakkale') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Çankiri') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Çorum') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Denizli') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Diyarbakir') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Düzce') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Edirne') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Elazig') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Erzincan') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Erzurum') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Eskisehir') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gaziantep') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Giresun') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gümüshane') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hakkâri') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hatay') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Igdir') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Isparta') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Istanbul') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Izmir') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kahramanmaras') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Karabük') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Karaman') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kars') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kastamonu') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kayseri') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kilis') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kirikkale') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kirklareli') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kirsehir') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kocaeli') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Konya') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kütahya') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Malatya') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Manisa') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mardin') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mersin') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mugla') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mus') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nevsehir') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nigde') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ordu') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Osmaniye') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rize') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sakarya') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Samsun') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sanliurfa') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Siirt') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sinop') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sirnak') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sivas') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tekirdag') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tokat') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Trabzon') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tunceli') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Usak') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Van') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Yalova') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Yozgat') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zonguldak') . "', `country_code` = 'TUR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Turkmenistan
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ahal') . "', `country_code` = 'TKM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Asgabat') . "', `country_code` = 'TKM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Balkan') . "', `country_code` = 'TKM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dasoguz') . "', `country_code` = 'TKM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lebap') . "', `country_code` = 'TKM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mary') . "', `country_code` = 'TKM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Tuvalu
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Funafuti') . "', `country_code` = 'TUV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nanumanga') . "', `country_code` = 'TUV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nanumea') . "', `country_code` = 'TUV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Niulakita') . "', `country_code` = 'TUV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Niutao') . "', `country_code` = 'TUV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nui') . "', `country_code` = 'TUV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nukufetau') . "', `country_code` = 'TUV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nukulaelae') . "', `country_code` = 'TUV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vaitupu') . "', `country_code` = 'TUV', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        

        // Uganda
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Central') . "', `country_code` = 'UGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Eastern') . "', `country_code` = 'UGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Northern') . "', `country_code` = 'UGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Western') . "', `country_code` = 'UGA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Ukraine
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cherkasy') . "', `country_code` = 'UKR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chernihiv') . "', `country_code` = 'UKR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Chernivtsi') . "', `country_code` = 'UKR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dnipropetrovsk') . "', `country_code` = 'UKR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Donetsk') . "', `country_code` = 'UKR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ivano-Frankivsk') . "', `country_code` = 'UKR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kharkiv') . "', `country_code` = 'UKR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kherson') . "', `country_code` = 'UKR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Khmelnytskyi') . "', `country_code` = 'UKR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kiev') . "', `country_code` = 'UKR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kirovohrad') . "', `country_code` = 'UKR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Luhansk') . "', `country_code` = 'UKR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lviv') . "', `country_code` = 'UKR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mykolaiv') . "', `country_code` = 'UKR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Odessa') . "', `country_code` = 'UKR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Poltava') . "', `country_code` = 'UKR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rivne') . "', `country_code` = 'UKR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sumy') . "', `country_code` = 'UKR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ternopil') . "', `country_code` = 'UKR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vinnytsia') . "', `country_code` = 'UKR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Volyn') . "', `country_code` = 'UKR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zakarpattia') . "', `country_code` = 'UKR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zaporizhia') . "', `country_code` = 'UKR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zhytomyr') . "', `country_code` = 'UKR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // United Arab Emirates
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Abu Dhabi') . "', `country_code` = 'ARE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ajman') . "', `country_code` = 'ARE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dubai') . "', `country_code` = 'ARE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Fujairah') . "', `country_code` = 'ARE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ras Al Khaimah') . "', `country_code` = 'ARE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sharjah') . "', `country_code` = 'ARE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Umm Al Quwain') . "', `country_code` = 'ARE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // United Kingdom
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Aberdeen City') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Aberdeenshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Anglesey') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Angus') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Argyll and Bute') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bedfordshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Berkshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Blaenau Gwent') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bridgend') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bristol') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Buckinghamshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Caerphilly') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cambridgeshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cardiff') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Carmarthenshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ceredigion') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cheshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Clackmannanshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Conwy') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cornwall') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('County Antrim') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('County Armagh') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('County Down') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('County Fermanagh') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('County Londonderry') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('County Tyrone') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cumbria') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Denbighshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Derbyshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Devon') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dorset') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dumfries and Galloway') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dundee') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Durham') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('East Ayrshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('East Dunbartonshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('East Lothian') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('East Renfrewshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('East Sussex') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('East Yorkshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Edinburgh') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Essex') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Falkirk') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Fife') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Flintshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Glasgow') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gloucestershire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Greater London') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Greater Manchester') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Gwynedd') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hampshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Herefordshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hertfordshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Highlands') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Inverclyde') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Isle of Wight') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kent') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lancashire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Leicestershire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lincolnshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Merseyside') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Merthyr Tydfil') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Midlothian') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Monmouthshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Moray') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Neath Port Talbot') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Newport') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Norfolk') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North Ayrshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North Lanarkshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North Yorkshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Northamptonshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Northumberland') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nottinghamshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Orkney Islands') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Outer Hebrides') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Oxfordshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pembrokeshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Perth and Kinross') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Renfrewshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rhondda Cynon Taff') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rutland') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Scottish Borders') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Shetland Islands') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Shropshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Somerset') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('South Ayrshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('South Lanarkshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('South Yorkshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Staffordshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Stirling') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Suffolk') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Surrey') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Swansea') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Torfaen') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tyne and Wear') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vale of Glamorgan') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Warwickshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('West Dunbartonshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('West Lothian') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('West Midlands') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('West Sussex') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('West Yorkshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Western Isles') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Wiltshire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Worcestershire') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Wrexham') . "', `country_code` = 'GBR', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // United States
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Alabama') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Alaska') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Arizona') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Arkansas') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('California') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Colorado') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Connecticut') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Delaware') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Florida') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Georgia') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hawaii') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Idaho') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Illinois') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Indiana') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Iowa') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kansas') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Kentucky') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Louisiana') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Maine') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Maryland') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Massachusetts') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Michigan') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Minnesota') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mississippi') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Missouri') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Montana') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nebraska') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Nevada') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('New Hampshire') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('New Jersey') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('New Mexico') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('New York') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North Carolina') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North Dakota') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ohio') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Oklahoma') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Oregon') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Pennsylvania') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rhode Island') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('South Carolina') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('South Dakota') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tennessee') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Texas') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Utah') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Vermont') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Virginia') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Washington') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('West Virginia') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Wisconsin') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Wyoming') . "', `country_code` = 'USA', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Uruguay
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Artigas') . "', `country_code` = 'URY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Canelones') . "', `country_code` = 'URY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Cerro Largo') . "', `country_code` = 'URY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Colonia') . "', `country_code` = 'URY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Durazno') . "', `country_code` = 'URY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Flores') . "', `country_code` = 'URY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Florida') . "', `country_code` = 'URY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lavalleja') . "', `country_code` = 'URY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Maldonado') . "', `country_code` = 'URY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Montevideo') . "', `country_code` = 'URY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Paysandú') . "', `country_code` = 'URY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rivera') . "', `country_code` = 'URY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Rocha') . "', `country_code` = 'URY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Río Negro') . "', `country_code` = 'URY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Salto') . "', `country_code` = 'URY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('San José') . "', `country_code` = 'URY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Soriano') . "', `country_code` = 'URY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tacuarembó') . "', `country_code` = 'URY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Treinta y Tres') . "', `country_code` = 'URY', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Uzbekistan
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Andijan') . "', `country_code` = 'UZB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bukhara') . "', `country_code` = 'UZB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Fergana') . "', `country_code` = 'UZB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Jizzakh') . "', `country_code` = 'UZB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Karakalpakstan') . "', `country_code` = 'UZB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Namangan') . "', `country_code` = 'UZB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Navoiy') . "', `country_code` = 'UZB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Qashqadaryo') . "', `country_code` = 'UZB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Samarqand') . "', `country_code` = 'UZB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sirdaryo') . "', `country_code` = 'UZB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Surxondaryo') . "', `country_code` = 'UZB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tashkent') . "', `country_code` = 'UZB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Xorazm') . "', `country_code` = 'UZB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Vanuatu
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Malampa') . "', `country_code` = 'VUT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Penama') . "', `country_code` = 'VUT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sanma') . "', `country_code` = 'VUT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Shefa') . "', `country_code` = 'VUT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Tafea') . "', `country_code` = 'VUT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Torba') . "', `country_code` = 'VUT', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");        

        // Venezuela
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Andean') . "', `country_code` = 'VEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Capital') . "', `country_code` = 'VEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Central') . "', `country_code` = 'VEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Central-Western') . "', `country_code` = 'VEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Eastern') . "', `country_code` = 'VEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Guayana') . "', `country_code` = 'VEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Insular') . "', `country_code` = 'VEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Llanos') . "', `country_code` = 'VEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('South-Western') . "', `country_code` = 'VEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Zulian') . "', `country_code` = 'VEN', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Vietnam
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Central Highlands') . "', `country_code` = 'VNM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mekong River Delta') . "', `country_code` = 'VNM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North Central Coast') . "', `country_code` = 'VNM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Northeast') . "', `country_code` = 'VNM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Northwest') . "', `country_code` = 'VNM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Red River Delta') . "', `country_code` = 'VNM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('South Central Coast') . "', `country_code` = 'VNM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Southeast') . "', `country_code` = 'VNM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Yemen
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('\'Adan') . "', `country_code` = 'YEM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('\'Amran') . "', `country_code` = 'YEM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Abyan') . "', `country_code` = 'YEM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ad Dali') . "', `country_code` = 'YEM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Al Bayda\'') . "', `country_code` = 'YEM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Al Hudaydah') . "', `country_code` = 'YEM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Al Jawf') . "', `country_code` = 'YEM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Al Mahrah') . "', `country_code` = 'YEM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Al Mahwit') . "', `country_code` = 'YEM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Amanat Al Asimah') . "', `country_code` = 'YEM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Dhamar') . "', `country_code` = 'YEM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hadramaut') . "', `country_code` = 'YEM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Hajjah') . "', `country_code` = 'YEM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ibb') . "', `country_code` = 'YEM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lahij') . "', `country_code` = 'YEM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ma\'rib') . "', `country_code` = 'YEM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Raymah') . "', `country_code` = 'YEM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sa\'dah') . "', `country_code` = 'YEM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Sana\'a') . "', `country_code` = 'YEM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Shabwah') . "', `country_code` = 'YEM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Socotra') . "', `country_code` = 'YEM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Ta\'izz') . "', `country_code` = 'YEM', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Zambia
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Central') . "', `country_code` = 'ZMB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Copperbelt') . "', `country_code` = 'ZMB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Eastern') . "', `country_code` = 'ZMB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Luapula') . "', `country_code` = 'ZMB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Lusaka') . "', `country_code` = 'ZMB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Muchinga') . "', `country_code` = 'ZMB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('North-Western') . "', `country_code` = 'ZMB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Northern') . "', `country_code` = 'ZMB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Southern') . "', `country_code` = 'ZMB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Western') . "', `country_code` = 'ZMB', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");

        // Zimbabwe
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Bulawayo') . "', `country_code` = 'ZWE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Harare') . "', `country_code` = 'ZWE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Manicaland') . "', `country_code` = 'ZWE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mashonaland Central') . "', `country_code` = 'ZWE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mashonaland East') . "', `country_code` = 'ZWE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Mashonaland West') . "', `country_code` = 'ZWE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Masvingo') . "', `country_code` = 'ZWE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Matabeleland North') . "', `country_code` = 'ZWE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Matabeleland South') . "', `country_code` = 'ZWE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->prepareState('Midlands') . "', `country_code` = 'ZWE', `enabled` = '1', `date_modified` = NOW(), `date_added` = NOW()");
        /*****************************************************************************************************************/
    }

    public function createTableSubscriptions()
    {
        /********************************************** CREATE TABLE 'subscriptions' *************************************/
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . CMTX_DB_PREFIX . "subscriptions` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `user_id` int(10) unsigned NOT NULL default '0',
            `page_id` int(10) unsigned NOT NULL default '0',
            `token` varchar(250) NOT NULL default '',
            `is_confirmed` tinyint(1) unsigned NOT NULL default '0',
            `ip_address` varchar(250) NOT NULL default '',
            `date_modified` datetime NOT NULL,
            `date_added` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        /*****************************************************************************************************************/
    }

    public function createTableUploads()
    {
        /********************************************** CREATE TABLE 'uploads' *******************************************/
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . CMTX_DB_PREFIX . "uploads` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `user_id` int(10) unsigned NOT NULL default '0',
            `comment_id` int(10) unsigned NOT NULL default '0',
            `folder` varchar(250) NOT NULL default '',
            `filename` varchar(250) NOT NULL default '',
            `extension` varchar(10) NOT NULL default '',
            `mime_type` varchar(250) NOT NULL default '',
            `file_size` int(10) unsigned NOT NULL default '0',
            `date_added` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        /*****************************************************************************************************************/
    }

    public function createTableUsers()
    {
        /********************************************** CREATE TABLE 'users' *********************************************/
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . CMTX_DB_PREFIX . "users` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `avatar_id` int(10) unsigned NOT NULL default '0',
            `avatar_pending_id` int(10) unsigned NOT NULL default '0',
            `avatar_selected` varchar(250) NOT NULL default '',
            `avatar_login` varchar(250) NOT NULL default '',
            `name` varchar(250) NOT NULL default '',
            `email` varchar(250) NOT NULL default '',
            `moderate` varchar(250) NOT NULL default 'default',
            `token` varchar(250) NOT NULL default '',
            `to_all` tinyint(1) unsigned NOT NULL default '1',
            `to_admin` tinyint(1) unsigned NOT NULL default '1',
            `to_reply` tinyint(1) unsigned NOT NULL default '1',
            `to_approve` tinyint(1) unsigned NOT NULL default '1',
            `format` varchar(250) NOT NULL default 'html',
            `ip_address` varchar(250) NOT NULL default '',
            `date_modified` datetime NOT NULL,
            `date_added` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        /*****************************************************************************************************************/
    }

    public function createTableVersion($version)
    {
        /********************************************** CREATE TABLE 'version' *******************************************/
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . CMTX_DB_PREFIX . "version` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `version` varchar(10) NOT NULL default '',
            `type` varchar(250) NOT NULL default '',
            `date_added` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "version` SET `version` = '" . $this->db->escape($version) . "', `type` = 'Installation', `date_added` = NOW()");
        /*****************************************************************************************************************/
    }

    public function createTableViewers()
    {
        /********************************************** CREATE TABLE 'viewers' *******************************************/
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . CMTX_DB_PREFIX . "viewers` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `viewer` varchar(250) NOT NULL default '',
            `type` varchar(250) NOT NULL default '',
            `ip_address` varchar(250) NOT NULL default '',
            `page_id` int(10) unsigned NOT NULL default '0',
            `page_reference` varchar(250) NOT NULL default '',
            `page_url` varchar(1000) NOT NULL default '',
            `time_added` int(50) unsigned NOT NULL default '0',
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        /*****************************************************************************************************************/
    }

    public function createTableVoters()
    {
        /********************************************** CREATE TABLE 'voters' ********************************************/
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . CMTX_DB_PREFIX . "voters` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `comment_id` int(10) unsigned NOT NULL default '0',
            `ip_address` varchar(250) NOT NULL default '',
            `date_added` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        /*****************************************************************************************************************/
    }

    public function getBackendFolder()
    {
        $folders = array();

        foreach (glob('../*', GLOB_ONLYDIR) as $dir) {
            array_push($folders, basename($dir));
        }

        $backend_folder = 'backend';

        foreach ($folders as $folder) {
            if (!in_array($folder, array('3rdparty', 'frontend', 'install', 'system', 'upload'))) {
                $backend_folder = $folder;
            }
        }

        return $backend_folder;
    }

    private function getCommenticsFolder()
    {
        $parts = explode('/', $this->url->decode($this->url->getPageUrl()));

        $commentics_folder = $parts[sizeof($parts) - 3];

        return $commentics_folder;
    }

    private function prepareState($state)
    {
        //$state = utf8_encode($state);

        $state = $this->db->escape($state);

        return $state;
    }
}
