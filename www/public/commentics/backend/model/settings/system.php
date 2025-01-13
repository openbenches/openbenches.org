<?php
namespace Commentics;

class SettingsSystemModel extends Model
{
    public function update($data)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['site_name']) . "' WHERE `title` = 'site_name'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['site_domain']) . "' WHERE `title` = 'site_domain'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['site_url']) . "' WHERE `title` = 'site_url'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['time_zone']) . "' WHERE `title` = 'time_zone'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['commentics_folder']) . "' WHERE `title` = 'commentics_folder'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['commentics_url']) . "' WHERE `title` = 'commentics_url'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['backend_folder']) . "' WHERE `title` = 'backend_folder'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['purpose']) . "' WHERE `title` = 'purpose'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['use_wysiwyg']) ? 1 : 0) . "' WHERE `title` = 'use_wysiwyg'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['display_parsing']) ? 1 : 0) . "' WHERE `title` = 'display_parsing'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['empty_pages']) ? 1 : 0) . "' WHERE `title` = 'empty_pages'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['limit_results'] . "' WHERE `title` = 'limit_results'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['admin_cookie_days'] . "' WHERE `title` = 'admin_cookie_days'");

        if ($this->setting->get('purpose') != $data['purpose']) {
            $this->email->changePurpose($this->setting->get('purpose'), $data['purpose']);
        }
    }

    public function get_time_zones()
    {
        $time_zones = \DateTimeZone::listIdentifiers();

        return $time_zones;
    }
}
