<?php
namespace Commentics;

class ModuleChartModel extends Model
{
    public function update($data)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['chart_enabled']) ? 1 : 0) . "' WHERE `title` = 'chart_enabled'");
    }

    public function install()
    {
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'module', `title` = 'chart_enabled', `value` = '0'");
    }

    public function uninstall()
    {
        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'chart_enabled'");
    }
}
