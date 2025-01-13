<?php
namespace Commentics;

class TaskDeleteCommentsModel extends Model
{
    public function update($data)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['task_enabled_delete_comments']) ? 1 : 0) . "' WHERE `title` = 'task_enabled_delete_comments'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['days_to_delete_comments'] . "' WHERE `title` = 'days_to_delete_comments'");
    }
}
