<?php
namespace Commentics;

class ModuleMergeUsersModel extends Model
{
    public function merge($data)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "comments` SET `user_id` = '" . (int) $data['user_id_to'] . "' WHERE `user_id` = '" . (int) $data['user_id_from'] . "'");

        $this->avoidDuplicateSubscriptions($data);

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "subscriptions` SET `user_id` = '" . (int) $data['user_id_to'] . "' WHERE `user_id` = '" . (int) $data['user_id_from'] . "'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "uploads` SET `user_id` = '" . (int) $data['user_id_to'] . "' WHERE `user_id` = '" . (int) $data['user_id_from'] . "'");

        $this->user->deleteUser($data['user_id_from']);
    }

    private function avoidDuplicateSubscriptions($data)
    {
        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "subscriptions` WHERE `user_id` = '" . (int) $data['user_id_to'] . "'");

        $subscriptions = $this->db->rows($query);

        foreach ($subscriptions as $subscription) {
            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "subscriptions` WHERE `user_id` = '" . (int) $data['user_id_from'] . "' AND `page_id` = '" . (int) $subscription['page_id'] . "'");
        }
    }
}
