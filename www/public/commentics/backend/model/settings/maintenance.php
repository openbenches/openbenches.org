<?php
namespace Commentics;

class SettingsMaintenanceModel extends Model
{
    public function update($data)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['maintenance_mode']) ? 1 : 0) . "' WHERE `title` = 'maintenance_mode'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['maintenance_message']) . "' WHERE `title` = 'maintenance_message'");
    }
}
