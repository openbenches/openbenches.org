<?php
namespace Commentics;

class EditAdminModel extends Model
{
    public function update($data, $id)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "admins` SET `username` = '" . $this->db->escape($data['username']) . "', `email` = '" . $this->db->escape($data['email']) . "', `date_modified` = NOW() WHERE `id` = '" . (int) $id . "'");

        if ($id == $this->session->data['cmtx_admin_id']) { // if the admin is changing their own username
            $this->session->data['cmtx_username'] = $data['username']; // update the session
        }

        // if the super admin is editing another account
        if ($this->session->data['cmtx_is_super'] && $id != $this->session->data['cmtx_admin_id']) {
            if (isset($data['is_super'])) {
                $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "admins` SET `is_super` = '0', `date_modified` = NOW() WHERE `is_super` = '1'");

                $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "admins` SET `is_enabled` = '1', `is_super` = '1', `restrict_pages` = '0', `viewable_pages` = '', `modifiable_pages` = '', `date_modified` = NOW() WHERE `id` = '" . (int) $id . "'");
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

                $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "admins` SET `is_enabled` = '" . (isset($data['is_enabled']) ? 1 : 0) . "', `is_super` = '0', `restrict_pages` = '" . (isset($data['restrict_pages']) ? 1 : 0) . "', `viewable_pages` = '" . $this->db->escape($data['viewable_pages']) . "', `modifiable_pages` = '" . $this->db->escape($data['modifiable_pages']) . "', `date_modified` = NOW() WHERE `id` = '" . (int) $id . "'");
            }
        }

        if (isset($data['password_1']) && $data['password_1']) {
            $password = password_hash($data['password_1'], PASSWORD_DEFAULT);

            $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "admins` SET `password` = '" . $this->db->escape($password) . "' WHERE `id` = '" . (int) $id . "'");
        }
    }
}
