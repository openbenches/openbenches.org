<?php
namespace Commentics;

class ToolOptimizeTablesModel extends Model
{
    public function optimize()
    {
        $query = $this->db->query("SHOW TABLES");

        $tables = $this->db->rows($query);

        foreach ($tables as $table) {
            $table = array_shift($table);

            $this->db->query("OPTIMIZE TABLE `" . $this->db->escape($table) . "`");
        }

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = NOW() WHERE `title` = 'optimize_date'");
    }

    public function getOptimizeDate()
    {
        $query = $this->db->query("SELECT `value` FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'optimize_date'");

        $result = $this->db->row($query);

        return $result['value'];
    }
}
