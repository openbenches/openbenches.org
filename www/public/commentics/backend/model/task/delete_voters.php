<?php
namespace Commentics;

class TaskDeleteVotersModel extends Model
{
    public function update($data)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['task_enabled_delete_voters']) ? 1 : 0) . "' WHERE `title` = 'task_enabled_delete_voters'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['days_to_delete_voters'] . "' WHERE `title` = 'days_to_delete_voters'");
    }
}
