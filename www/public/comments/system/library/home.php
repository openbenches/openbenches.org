<?php
namespace Commentics;

class Home
{
    private $db;
    private $email;
    private $log;
    private $setting;
    private $user;

    public function __construct($registry)
    {
        $this->db      = $registry->get('db');
        $this->email   = $registry->get('email');
        $this->log     = $registry->get('log');
        $this->setting = $registry->get('setting');
        $this->user    = $registry->get('user');
    }

    public function getLatestVersion($debug = false)
    {
        $url = 'https://commentics.com/version.txt';

        startRequest:

        ini_set('user_agent', 'Commentics');

        $latest_version = '';

        if (extension_loaded('curl')) {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Commentics');
            curl_setopt($ch, CURLOPT_URL, $url);

            if ($debug) {
                curl_setopt($ch, CURLOPT_VERBOSE, true);
                $verbose = fopen('php://temp', 'w+');
                curl_setopt($ch, CURLOPT_STDERR, $verbose);
            }

            $latest_version = curl_exec($ch);

            if ($debug) {
                if (curl_errno($ch)) {
                    $curl_errno = curl_errno($ch);
                    $curl_error = curl_error($ch);
                }

                $curl_info = curl_getinfo($ch);
            }

            curl_close($ch);
        } else if ((bool) ini_get('allow_url_fopen')) {
            $latest_version = @file_get_contents($url);

            if ($debug) {
                $fopen_error = error_get_last();

                if (isset($http_response_header)) {
                    $fopen_http_response_header = $http_response_header;
                }
            }
        }

        if ($debug) {
            $this->log->setFilename('version_check');

            $this->log->write('Calling: ' . $url);

            $this->log->write('Outbound IP: ' . file_get_contents('http://ipecho.net/plain'));

            if (extension_loaded('curl')) {
                $this->log->write('Connection Method: cURL');

                $this->log->write('cURL Version:');

                $this->log->write(curl_version());
            } else if ((bool) ini_get('allow_url_fopen')) {
                $this->log->write('Connection Method: url_fopen');
            }

            if (isset($fopen_error)) {
                $this->log->write('Error: ' . trim($fopen_error['message']));
            }

            if (isset($fopen_http_response_header)) {
                $this->log->write('HTTP Response Header:');
                $this->log->write($fopen_http_response_header);
            }

            if (isset($curl_errno)) {
                $this->log->write('cURL Errno: ' . $curl_errno);
                $this->log->write('cURL Error: ' . $curl_error);
            }

            if (isset($curl_info)) {
                $this->log->write('cURL Info:');
                $this->log->write($curl_info);
            }

            $this->log->write('Type:');
            $this->log->write(gettype($latest_version));

            if (gettype($latest_version) != 'string') {
                $this->log->write('Export:');
                $this->log->write(var_export($latest_version, true));
            }

            $this->log->write('Response:');
            $this->log->write($latest_version);

            if (isset($verbose)) {
                rewind($verbose);
                $verbose = stream_get_contents($verbose);

                $this->log->write('Verbose:');
                $this->log->write($verbose);
            }

            if ($url != 'https://www.example.com') {
                $url = 'https://www.example.com';

                unset($curl_errno, $curl_error, $fopen_http_response_header);

                $this->log->write('Comparing with example.com');

                goto startRequest;
            }
        }

        return $latest_version;
    }

    public function getVersions()
    {
        $url = 'https://commentics.com/versions.php';

        ini_set('user_agent', 'Commentics');

        $versions = '';

        if (extension_loaded('curl')) {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Commentics');
            curl_setopt($ch, CURLOPT_URL, $url);

            $versions = curl_exec($ch);

            curl_close($ch);
        } else if ((bool) ini_get('allow_url_fopen')) {
            $versions = file_get_contents($url);
        }

        return $versions;
    }

    public function getNews()
    {
        $url = 'https://commentics.com/news.txt';

        ini_set('user_agent', 'Commentics');

        $news = '';

        if (extension_loaded('curl')) {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Commentics');
            curl_setopt($ch, CURLOPT_URL, $url);

            $news = curl_exec($ch);

            curl_close($ch);
        } else if ((bool) ini_get('allow_url_fopen')) {
            $news = file_get_contents($url);
        }

        return $news;
    }

    public function getSponsors()
    {
        $url = 'https://commentics.com/sponsors.php';

        ini_set('user_agent', 'Commentics');

        $sponsors = '';

        if (extension_loaded('curl')) {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Commentics');
            curl_setopt($ch, CURLOPT_URL, $url);

            $sponsors = curl_exec($ch);

            curl_close($ch);
        } else if ((bool) ini_get('allow_url_fopen')) {
            $sponsors = file_get_contents($url);
        }

        return $sponsors;
    }

    /* Sends completely anonymous data to Commentics.com once a day */
    public function callHome()
    {
        $last_call = $this->setting->get('last_call');

        $date = date('Y-m-d');

        if ($last_call != $date) {
            $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($date) . "' WHERE `title` = 'last_call'");

            $url = 'https://commentics.com/call_home.php';

            $data = array(
                'site_id'          => $this->setting->get('site_id'),
                'version'          => CMTX_VERSION,
                'online'           => (in_array($this->user->getIpAddress(), array('127.0.0.1', '::1')) ? 0 : 1),
                'admins'           => $this->getNumAdminsTotal(),
                'bans'             => $this->getNumBansTotal(),
                'comments'         => $this->getNumCommentsTotal(),
                'pages'            => $this->getNumPagesTotal(),
                'sites'            => $this->getNumSitesTotal(),
                'subscriptions'    => $this->getNumSubscriptionsTotal(),
                'users'            => $this->getNumUsersTotal(),
                'akismet_enabled'  => ($this->setting->has('akismet_enabled') && $this->setting->get('akismet_enabled') ? 1 : 0),
                'chart_enabled'    => ($this->setting->has('chart_enabled') && $this->setting->get('chart_enabled') ? 1 : 0),
                'snippets_enabled' => ($this->setting->has('rich_snippets_enabled') && $this->setting->get('rich_snippets_enabled') ? 1 : 0),
                'default_theme'    => ($this->setting->get('theme_frontend') == 'default' ? 1 : 0),
                'powered'          => $this->setting->get('enabled_powered_by'),
                'os'               => php_uname('s'),
                'php'              => PHP_VERSION,
                'db'               => $this->db->getServerInfo()
            );

            $url .= '?' . http_build_query($data);

            ini_set('user_agent', 'Commentics');

            if (extension_loaded('curl')) {
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Commentics');
                curl_setopt($ch, CURLOPT_URL, $url);

                $result = curl_exec($ch);

                curl_close($ch);
            } else if ((bool) ini_get('allow_url_fopen')) {
                $result = file_get_contents($url);
            }
        }
    }

    public function checkLicence($licence, $forum_user)
    {
        $url = 'https://commentics.com/licence_check.php';

        $data = array(
            'site_id'    => $this->setting->get('site_id'),
            'domain'     => $this->setting->get('site_domain'),
            'forum_user' => $forum_user,
            'ip_address' => $this->user->getIpAddress(),
            'licence'    => $licence
        );

        $url .= '?' . http_build_query($data);

        ini_set('user_agent', 'Commentics');

        if (extension_loaded('curl')) {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Commentics');
            curl_setopt($ch, CURLOPT_URL, $url);

            $result = curl_exec($ch);

            curl_close($ch);
        } else if ((bool) ini_get('allow_url_fopen')) {
            $result = file_get_contents($url);
        }

        return $result;
    }

    private function getNumAdminsTotal()
    {
        return $this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "admins`"));
    }

    private function getNumBansTotal()
    {
        return $this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "bans` WHERE `unban` = '0'"));
    }

    private function getNumCommentsTotal()
    {
        return $this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "comments`"));
    }

    private function getNumPagesTotal()
    {
        return $this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "pages`"));
    }

    private function getNumSitesTotal()
    {
        return $this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "sites`"));
    }

    private function getNumSubscriptionsTotal()
    {
        return $this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "subscriptions`"));
    }

    private function getNumUsersTotal()
    {
        return $this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "users`"));
    }
}
