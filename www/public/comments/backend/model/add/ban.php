<?php
namespace Commentics;

class AddBanModel extends Model
{
    public function add($data)
    {
        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "bans` WHERE `ip_address` = '" . $this->db->escape($data['ip_address']) . "'");

        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "bans` SET `ip_address` = '" . $this->db->escape($data['ip_address']) . "', `reason` = '" . $this->db->escape($data['reason']) . "', `unban` = '0', `date_modified` = NOW(), `date_added` = NOW()");
    }

    public function isAlreadyBanned($ip_address)
    {
        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "bans` WHERE `ip_address` = '" . $this->db->escape($ip_address) . "' AND `unban` = '0'"))) {
            return true;
        } else {
            return false;
        }
    }
}
