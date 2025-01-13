<?php
namespace Commentics;

class EditSubscriptionModel extends Model
{
    public function subscriptionExists($id)
    {
        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "subscriptions` WHERE `id` = '" . (int) $id . "'"))) {
            return true;
        } else {
            return false;
        }
    }

    public function getSubscription($id)
    {
        $query = $this->db->query(" SELECT `p`.*, `u`.*, `s`.*
                                    FROM `" . CMTX_DB_PREFIX . "subscriptions` `s`
                                    RIGHT JOIN `" . CMTX_DB_PREFIX . "pages` `p` ON `s`.`page_id` = `p`.`id`
                                    RIGHT JOIN `" . CMTX_DB_PREFIX . "users` `u` ON `s`.`user_id` = `u`.`id`
                                    WHERE `s`.`id` = '" . (int) $id . "'");

        if ($this->db->numRows($query)) {
            $subscription = $this->db->row($query);

            return array(
                'id'             => $subscription['id'],
                'user_id'        => $subscription['user_id'],
                'page_id'        => $subscription['page_id'],
                'name'           => $subscription['name'],
                'email'          => $subscription['email'],
                'page_reference' => $subscription['reference'],
                'is_confirmed'   => $subscription['is_confirmed'],
                'ip_address'     => $subscription['ip_address'],
                'date_added'     => $subscription['date_added']
            );
        } else {
            return false;
        }
    }

    public function update($data, $id)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "subscriptions` SET `is_confirmed` = '" . (int) $data['is_confirmed'] . "', `date_modified` = NOW() WHERE `id` = '" . (int) $id . "'");
    }
}
