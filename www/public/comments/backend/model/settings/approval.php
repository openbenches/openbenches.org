<?php
namespace Commentics;

class SettingsApprovalModel extends Model
{
    public function update($data)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['approve_comments']) ? 1 : 0) . "' WHERE `title` = 'approve_comments'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['approve_notifications']) ? 1 : 0) . "' WHERE `title` = 'approve_notifications'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['trust_previous_users']) ? 1 : 0) . "' WHERE `title` = 'trust_previous_users'");
    }
}
