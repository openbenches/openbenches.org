<?php
namespace Commentics;

class TaskDeleteBansModel extends Model
{
    public function update($data)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['task_enabled_delete_bans']) ? 1 : 0) . "' WHERE `title` = 'task_enabled_delete_bans'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['days_to_delete_bans'] . "' WHERE `title` = 'days_to_delete_bans'");
    }
}
