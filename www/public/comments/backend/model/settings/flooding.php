<?php
namespace Commentics;

class SettingsFloodingModel extends Model
{
    public function update($data)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['flood_control_delay_enabled']) ? 1 : 0) . "' WHERE `title` = 'flood_control_delay_enabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['flood_control_delay_time'] . "' WHERE `title` = 'flood_control_delay_time'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['flood_control_delay_all_pages']) ? 1 : 0) . "' WHERE `title` = 'flood_control_delay_all_pages'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['flood_control_maximum_enabled']) ? 1 : 0) . "' WHERE `title` = 'flood_control_maximum_enabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['flood_control_maximum_amount'] . "' WHERE `title` = 'flood_control_maximum_amount'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['flood_control_maximum_period'] . "' WHERE `title` = 'flood_control_maximum_period'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['flood_control_maximum_all_pages']) ? 1 : 0) . "' WHERE `title` = 'flood_control_maximum_all_pages'");
    }
}
