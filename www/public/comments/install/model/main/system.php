<?php
namespace Commentics;

class MainSystemModel extends Model
{
    public function check()
    {
        $check = array();

        $check['continue'] = true;

        if (version_compare(PHP_VERSION, '5.3.7', '>=')) {
            $check['php_version'] = true;
        } else {
            $check['php_version'] = false;

            $check['continue'] = false;
        }

        if (version_compare($this->db->getServerInfo(), '5.5.3', '>=')) {
            $check['mysql_version'] = true;
        } else {
            $check['mysql_version'] = false;

            $check['continue'] = false;
        }

        if ($this->session->getId() != '' && isset($this->session->data['cmtx_session_test']) && $this->session->data['cmtx_session_test']) {
            $check['php_session'] = true;
        } else {
            $check['php_session'] = false;

            $check['continue'] = false;
        }

        if (extension_loaded('ctype')) {
            $check['ctype_loaded'] = true;
        } else {
            $check['ctype_loaded'] = false;

            $check['continue'] = false;
        }

        if (extension_loaded('filter')) {
            $check['filter_loaded'] = true;
        } else {
            $check['filter_loaded'] = false;

            $check['continue'] = false;
        }

        if (extension_loaded('json')) {
            $check['json_loaded'] = true;
        } else {
            $check['json_loaded'] = false;

            $check['continue'] = false;
        }

        if (extension_loaded('curl')) {
            $check['curl_loaded'] = true;
        } else {
            $check['curl_loaded'] = false;
        }

        if (extension_loaded('mbstring')) {
            $check['mbstring_loaded'] = true;
        } else {
            $check['mbstring_loaded'] = false;
        }

        if (extension_loaded('gd')) {
            $check['gd_loaded'] = true;
        } else {
            $check['gd_loaded'] = false;
        }

        if (extension_loaded('dom')) {
            $check['dom_loaded'] = true;
        } else {
            $check['dom_loaded'] = false;
        }

        if (extension_loaded('libxml')) {
            $check['libxml_loaded'] = true;
        } else {
            $check['libxml_loaded'] = false;
        }

        if (extension_loaded('openssl')) {
            $check['openssl_loaded'] = true;
        } else {
            $check['openssl_loaded'] = false;
        }

        if (extension_loaded('zip')) {
            $check['zip_loaded'] = true;
        } else {
            $check['zip_loaded'] = false;
        }

        if ((bool) ini_get('allow_url_fopen')) {
            $check['fopen_enabled'] = true;
        } else {
            $check['fopen_enabled'] = false;
        }

        if (function_exists('imagettftext') && is_callable('imagettftext')) {
            $check['freetype_enabled'] = true;
        } else {
            $check['freetype_enabled'] = false;
        }

        return $check;
    }
}
