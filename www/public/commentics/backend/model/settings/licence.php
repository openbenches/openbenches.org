<?php
namespace Commentics;

class SettingsLicenceModel extends Model
{
    public function update($data)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['licence']) . "' WHERE `title` = 'licence'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['forum_user']) . "' WHERE `title` = 'forum_user'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '0' WHERE `title` = 'enabled_powered_by'");
    }
}
