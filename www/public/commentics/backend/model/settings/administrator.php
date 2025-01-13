<?php
namespace Commentics;

class SettingsAdministratorModel extends Model
{
    public function update($data, $admin_id)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "admins` SET `username` = '" . $this->db->escape($data['username']) . "', `email` = '" . $this->db->escape($data['email']) . "', `format` = '" . $this->db->escape($data['format']) . "', `receive_email_ban` = '" . (isset($data['receive_email_ban']) ? 1 : 0) . "', `receive_email_comment_approve` = '" . (isset($data['receive_email_comment_approve']) ? 1 : 0) . "', `receive_email_comment_success` = '" . (isset($data['receive_email_comment_success']) ? 1 : 0) . "', `receive_email_flag` = '" . (isset($data['receive_email_flag']) ? 1 : 0) . "', `receive_email_edit` = '" . (isset($data['receive_email_edit']) ? 1 : 0) . "', `receive_email_delete` = '" . (isset($data['receive_email_delete']) ? 1 : 0) . "' WHERE `id` = '" . (int) $admin_id . "'");

        $this->session->data['cmtx_username'] = $data['username'];

        if (isset($data['password_1']) && $data['password_1']) {
            $password = password_hash($data['password_1'], PASSWORD_DEFAULT);

            $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "admins` SET `password` = '" . $this->db->escape($password) . "' WHERE `id` = '" . (int) $admin_id . "'");
        }
    }
}
