<?php
namespace Commentics;

class ModuleAkismetModel extends Model
{
    public function update($data)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['akismet_enabled']) ? 1 : 0) . "' WHERE `title` = 'akismet_enabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['akismet_key']) . "' WHERE `title` = 'akismet_key'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['akismet_logging']) ? 1 : 0) . "' WHERE `title` = 'akismet_logging'");
    }

    public function install()
    {
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'module', `title` = 'akismet_enabled', `value` = '0'");

        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'module', `title` = 'akismet_key', `value` = ''");

        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'module', `title` = 'akismet_logging', `value` = '0'");
    }

    public function uninstall()
    {
        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'akismet_enabled'");

        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'akismet_key'");

        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'akismet_logging'");
    }
}
