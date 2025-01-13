<?php
namespace Commentics;

class EditBanModel extends Model
{
    public function banExists($id)
    {
        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "bans` WHERE `id` = '" . (int) $id . "' AND `unban` = '0'"))) {
            return true;
        } else {
            return false;
        }
    }

    public function isAlreadyBanned($ip_address, $id)
    {
        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "bans` WHERE `ip_address` = '" . $this->db->escape($ip_address) . "' AND `id` != '" . (int) $id . "' AND `unban` = '0'"))) {
            return true;
        } else {
            return false;
        }
    }

    public function getBan($id)
    {
        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "bans` WHERE `id` = '" . (int) $id . "' AND `unban` = '0'");

        $result = $this->db->row($query);

        return $result;
    }

    public function update($data, $id)
    {
        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "bans` WHERE `ip_address` = '" . $this->db->escape($data['ip_address']) . "' AND `id` != '" . (int) $id . "'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "bans` SET `ip_address` = '" . $this->db->escape($data['ip_address']) . "', `reason` = '" . $this->db->escape($data['reason']) . "', `date_modified` = NOW() WHERE `id` = '" . (int) $id . "' AND `unban` = '0'");
    }
}
