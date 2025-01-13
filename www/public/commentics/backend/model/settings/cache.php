<?php
namespace Commentics;

class SettingsCacheModel extends Model
{
    public function update($data)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['cache_type']) . "' WHERE `title` = 'cache_type'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['cache_time'] . "' WHERE `title` = 'cache_time'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['cache_host']) . "' WHERE `title` = 'cache_host'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['cache_port']) . "' WHERE `title` = 'cache_port'");
    }
}
