<?php
namespace Commentics;

class MainDashboardModel extends Model
{
    public function getCurrentVersion()
    {
        $query = $this->db->query("SELECT `version` FROM `" . CMTX_DB_PREFIX . "version` ORDER BY `date_added` DESC LIMIT 1");

        $result = $this->db->row($query);

        return $result['version'];
    }

    public function getLastLogin()
    {
        $query = $this->db->query("SELECT `date_modified` FROM `" . CMTX_DB_PREFIX . "logins` ORDER BY `date_modified` ASC LIMIT 1");

        $result = $this->db->row($query);

        return $result['date_modified'];
    }

    public function getDaysInstalled()
    {
        $query = $this->db->query("SELECT `date_added` FROM `" . CMTX_DB_PREFIX . "version` WHERE `type` = 'Installation' LIMIT 1");

        $result = $this->db->row($query);

        $date = strtotime($result['date_added']);

        $difference = time() - $date;

        $days = floor($difference / (60 * 60 * 24));

        return $days;
    }

    public function getNumCommentsApprove()
    {
        return $this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "comments` WHERE `is_approved` = '0'"));
    }

    public function getNumAvatarsApprove()
    {
        return $this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "users` WHERE `avatar_pending_id` > '0'"));
    }

    public function getNumCommentsFlagged()
    {
        return $this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "comments` WHERE `reports` >= '" . (int) $this->setting->get('flag_min_per_comment') . "'"));
    }

    public function getNumCommentsNew()
    {
        return $this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "comments` WHERE `date_added` LIKE '" . $this->db->escape(date('Y-m-d')) . "%'"));
    }

    public function getNumSubscriptionsNew()
    {
        return $this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "subscriptions` WHERE `date_added` LIKE '" . $this->db->escape(date('Y-m-d')) . "%'"));
    }

    public function getNumBansNew()
    {
        return $this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "bans` WHERE `unban` = '0' AND `date_added` LIKE '" . $this->db->escape(date('Y-m-d')) . "%'"));
    }

    public function getNumCommentsTotal()
    {
        return $this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "comments`"));
    }

    public function getNumSubscriptionsTotal()
    {
        return $this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "subscriptions`"));
    }

    public function getNumBansTotal()
    {
        return $this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "bans` WHERE `unban` = '0'"));
    }

    public function getTipOfTheDay()
    {
        $lang = $this->loadWord('main/dashboard');

        $tips = array();

        $counter = 0;

        foreach ($lang as $key => $value) {
            if ($this->variable->strpos($key, 'lang_tip_') === 0) {
                $tips[$counter] = $value;

                $counter++;
            }
        }

        $amount = count($tips);

        if ($amount) {
            $day = date('z');

            $position = (int) $day % $amount;

            $tip = $tips[$position];
        } else {
            $tip = $lang['lang_text_no_tips'];
        }

        return $tip;
    }

    public function getQuickLinks()
    {
        $lang = $this->loadWord('main/dashboard');

        $query = $this->db->query("SELECT `page`, COUNT(*) AS `frequency`
                                   FROM `" . CMTX_DB_PREFIX . "access`
                                   WHERE `page` NOT IN (
                                        'main/checklist',
                                        'main/dashboard',
                                        'extension/modules/install',
                                        'extension/modules/uninstall'
                                   )
                                   AND `page` NOT LIKE 'edit%'
                                   AND `page` NOT LIKE '%edit'
                                   GROUP BY `page`
                                   ORDER BY `frequency` DESC
                                   LIMIT 5");

        if ($this->db->numRows($query) == 5) {
            $results = $this->db->rows($query);

            foreach ($results as &$result) {
                if (array_key_exists('lang_' . $result['page'], $lang)) {
                    $result['text'] = $lang['lang_' . $result['page']];
                } else {
                    $result['text'] = $result['page'];
                }
            }

            return $results;
        } else {
            return array();
        }
    }

    public function getChartComments()
    {
        $chart_comments = array();

        $chart_comments['jan'] = $this->getCommentsByMonth('01');
        $chart_comments['feb'] = $this->getCommentsByMonth('02');
        $chart_comments['mar'] = $this->getCommentsByMonth('03');
        $chart_comments['apr'] = $this->getCommentsByMonth('04');
        $chart_comments['may'] = $this->getCommentsByMonth('05');
        $chart_comments['jun'] = $this->getCommentsByMonth('06');
        $chart_comments['jul'] = $this->getCommentsByMonth('07');
        $chart_comments['aug'] = $this->getCommentsByMonth('08');
        $chart_comments['sep'] = $this->getCommentsByMonth('09');
        $chart_comments['oct'] = $this->getCommentsByMonth('10');
        $chart_comments['nov'] = $this->getCommentsByMonth('11');
        $chart_comments['dec'] = $this->getCommentsByMonth('12');

        return $chart_comments;
    }

    public function getChartSubscriptions()
    {
        $chart_subscriptions = array();

        $chart_subscriptions['jan'] = $this->getSubscriptionsByMonth('01');
        $chart_subscriptions['feb'] = $this->getSubscriptionsByMonth('02');
        $chart_subscriptions['mar'] = $this->getSubscriptionsByMonth('03');
        $chart_subscriptions['apr'] = $this->getSubscriptionsByMonth('04');
        $chart_subscriptions['may'] = $this->getSubscriptionsByMonth('05');
        $chart_subscriptions['jun'] = $this->getSubscriptionsByMonth('06');
        $chart_subscriptions['jul'] = $this->getSubscriptionsByMonth('07');
        $chart_subscriptions['aug'] = $this->getSubscriptionsByMonth('08');
        $chart_subscriptions['sep'] = $this->getSubscriptionsByMonth('09');
        $chart_subscriptions['oct'] = $this->getSubscriptionsByMonth('10');
        $chart_subscriptions['nov'] = $this->getSubscriptionsByMonth('11');
        $chart_subscriptions['dec'] = $this->getSubscriptionsByMonth('12');

        return $chart_subscriptions;
    }

    public function getNotes()
    {
        $query = $this->db->query("SELECT `text` FROM `" . CMTX_DB_PREFIX . "data` WHERE `type` = 'admin_notes'");

        $result = $this->db->row($query);

        return $result['text'];
    }

    public function update($data, $username)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "data` SET `text` = '" . $this->db->escape($data['notes']) . "', `modified_by` = '" . $this->db->escape($username) . "', `date_modified` = NOW() WHERE `type` = 'admin_notes'");
    }

    public function enabledPoweredBy()
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '1' WHERE `title` = 'enabled_powered_by'");
    }

    public function hasErrors()
    {
        if (file_exists(CMTX_DIR_LOGS . 'errors.log') && filesize(CMTX_DIR_LOGS . 'errors.log')) {
            return true;
        } else {
            return false;
        }
    }

    public function disableCheckReferrer()
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '0' WHERE `title` = 'check_referrer'");
    }

    private function getCommentsByMonth($month)
    {
        return $this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "comments` WHERE `date_added` LIKE '" . $this->db->escape(date('Y') . '-' . $month . '-%') . "'"));
    }

    private function getSubscriptionsByMonth($month)
    {
        return $this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "subscriptions` WHERE `date_added` LIKE '" . $this->db->escape(date('Y') . '-' . $month . '-%') . "'"));
    }

    public function checkVersionIssue($current_version)
    {
        if (version_compare($current_version, CMTX_VERSION, '<')) {
            return true;
        } else {
            return false;
        }
    }

    public function checkSystemSettings()
    {
        $site_domain = str_ireplace('www.', '', parse_url($this->url->decode($this->url->getPageUrl()), PHP_URL_HOST));

        $site_url = 'http' . ($this->url->isHttps() ? 's' : '') . '://' . parse_url($this->url->decode($this->url->getPageUrl()), PHP_URL_HOST);

        $parts = explode('/', $this->url->decode($this->url->getPageUrl()));

        $commentics_folder = $parts[sizeof($parts) - 4];

        $backend_folder = $parts[sizeof($parts) - 3];

        $commentics_url = str_ireplace($backend_folder . '/index.php?route=main/dashboard', '', $this->url->decode($this->url->getPageUrl()));

        $system_settings = array();

        if ($site_domain != $this->setting->get('site_domain')) {
            $system_settings[] = $this->loadWord('main/dashboard', 'lang_text_site_domain');
        }

        if ($this->cleanSystemSettingUrl($site_url) != $this->cleanSystemSettingUrl($this->setting->get('site_url'))) {
            $system_settings[] = $this->loadWord('main/dashboard', 'lang_text_site_url');
        }

        if ($commentics_folder != $this->setting->get('commentics_folder')) {
            $system_settings[] = $this->loadWord('main/dashboard', 'lang_text_commentics_folder');
        }

        if ($this->cleanSystemSettingUrl($commentics_url) != $this->cleanSystemSettingUrl($this->setting->get('commentics_url'))) {
            $system_settings[] = $this->loadWord('main/dashboard', 'lang_text_commentics_url');
        }

        if ($backend_folder != $this->setting->get('backend_folder')) {
            $system_settings[] = $this->loadWord('main/dashboard', 'lang_text_backend_folder');
        }

        return $system_settings;
    }

    private function cleanSystemSettingUrl($url)
    {
        $parts = array('https://', 'http://', 'www.');

        $url = str_ireplace($parts, '', $url);

        return $url;
    }

    public function stopSystemDetect()
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '0' WHERE `title` = 'system_detect'");
    }

    public function stopVersionDetect()
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '0' WHERE `title` = 'version_detect'");
    }
}
