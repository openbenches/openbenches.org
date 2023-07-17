<?php
namespace Commentics;

class SettingsSecurityModel extends Model
{
    public function update($data)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['check_referrer']) ? 1 : 0) . "' WHERE `title` = 'check_referrer'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['check_config']) ? 1 : 0) . "' WHERE `title` = 'check_config'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['check_honeypot']) ? 1 : 0) . "' WHERE `title` = 'check_honeypot'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['check_time']) ? 1 : 0) . "' WHERE `title` = 'check_time'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['check_ip_address']) ? 1 : 0) . "' WHERE `title` = 'check_ip_address'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['ssl_certificate']) ? 1 : 0) . "' WHERE `title` = 'ssl_certificate'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['ban_cookie_days'] . "' WHERE `title` = 'ban_cookie_days'");

        if (isset($data['delete_install'])) {
            remove_directory(CMTX_DIR_INSTALL);
        }
    }
}
