<?php
namespace Commentics;

class AddAdminModel extends Model
{
    public function add($data)
    {
        if (isset($data['is_super'])) {
            $data['is_enabled'] = 1;

            unset($data['restrict_pages']);

            $data['viewable_pages'] = '';

            $data['modifiable_pages'] = '';

            $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "admins` SET `is_super` = '0', `date_modified` = NOW() WHERE `is_super` = '1'");
        } else {
            if (isset($data['viewable_pages'])) {
                $data['viewable_pages'] = implode(',', $data['viewable_pages']);
            } else {
                $data['viewable_pages'] = '';
            }

            if (isset($data['modifiable_pages'])) {
                $data['modifiable_pages'] = implode(',', $data['modifiable_pages']);
            } else {
                $data['modifiable_pages'] = '';
            }

            $data['viewable_pages'] = str_ireplace('extension/modules', 'extension/modules,extension/modules/install,extension/modules/uninstall', $data['viewable_pages']);

            $data['modifiable_pages'] = str_ireplace('extension/modules', 'extension/modules,extension/modules/install,extension/modules/uninstall', $data['modifiable_pages']);
        }

        $password = password_hash($data['password_1'], PASSWORD_DEFAULT);

        $cookie_key = $this->variable->random();

        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "admins` SET `username` = '" . $this->db->escape($data['username']) . "', `password` = '" . $this->db->escape($password) . "', `email` = '" . $this->db->escape($data['email']) . "', `ip_address` = '', `cookie_key` = '" . $this->db->escape($cookie_key) . "', `receive_email_ban` = '1', `receive_email_comment_approve` = '1', `receive_email_comment_success` = '1', `receive_email_flag` = '1', `login_attempts` = '0', `resets` = '0', `last_login` = NOW(), `restrict_pages` = '" . (isset($data['restrict_pages']) ? 1 : 0) . "', `viewable_pages` = '" . $this->db->escape($data['viewable_pages']) . "', `modifiable_pages` = '" . $this->db->escape($data['modifiable_pages']) . "', `format` = 'html', `is_super` = '" . (isset($data['is_super']) ? 1 : 0) . "', `is_enabled` = '" . (isset($data['is_enabled']) ? 1 : 0) . "', `date_modified` = NOW(), `date_added` = NOW()");
    }
}
