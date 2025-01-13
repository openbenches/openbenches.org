<?php
namespace Commentics;

class LoginLoginModel extends Model
{
    public function validateAccount($username, $password)
    {
        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "admins` WHERE `username` = '" . $this->db->escape($username) . "' AND `is_enabled` = '0'"))) {
            return '1'; // Disabled
        }

        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "admins` WHERE `username` = '" . $this->db->escape($username) . "' AND `login_attempts` >= 10"))) {
            return '2'; // Locked
        }

        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "admins` WHERE `username` = '" . $this->db->escape($username) . "'"))) {
            $query = $this->db->query("SELECT `password` FROM `" . CMTX_DB_PREFIX . "admins` WHERE `username` = '" . $this->db->escape($username) . "'");

            $result = $this->db->row($query);

            $hash = $result['password'];

            if (password_verify($password, $hash)) {
                if (password_needs_rehash($hash, PASSWORD_DEFAULT)) {
                    $hash = password_hash($password, PASSWORD_DEFAULT);

                    $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "admins` SET `password` = '" . $this->db->escape($hash) . "' WHERE `username` = '" . $this->db->escape($username) . "'");
                }

                return '3'; // Okay
            }
        }

        return '4'; // Wrong
    }

    public function addAttempt($username)
    {
        $ip_address = $this->user->getIpAddress();

        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "attempts` WHERE `type` = 'admin' AND `ip_address` = '" . $this->db->escape($ip_address) . "'"))) {
            $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "attempts` SET `amount` = `amount` + 1, `date_added` = NOW() WHERE `type` = 'admin' AND `ip_address` = '" . $this->db->escape($ip_address) . "'");
        } else {
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "attempts` SET `type` = 'admin', `ip_address` = '" . $this->db->escape($ip_address) . "', `amount` = '1', `date_added` = NOW()");
        }

        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "admins` WHERE `username` = '" . $this->db->escape($username) . "'"))) {
            $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "admins` SET `login_attempts` = `login_attempts` + 1 WHERE `username` = '" . $this->db->escape($username) . "'");
        }
    }

    public function hasMaxAttempts()
    {
        $ip_address = $this->user->getIpAddress();

        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "attempts` WHERE `type` = 'admin' AND `ip_address` = '" . $this->db->escape($ip_address) . "' AND `amount` >= 3"))) {
            $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "attempts` WHERE `type` = 'admin' AND `ip_address` = '" . $this->db->escape($ip_address) . "' AND `amount` >= 3");

            $result = $this->db->row($query);

            $time = strtotime($result['date_added']);

            $difference = time() - $time;

            if ($difference < 60 * 30) { // seconds * minutes
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function login($admin_id)
    {
        $ip_address = $this->user->getIpAddress();

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "admins` SET `last_login` = NOW() WHERE `id` = '" . (int) $admin_id . "'");
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "admins` SET `resets` = '0' WHERE `id` = '" . (int) $admin_id . "'");
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "admins` SET `login_attempts` = '0' WHERE `id` = '" . (int) $admin_id . "'");
        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "attempts` WHERE `type` = 'admin' AND `ip_address` = '" . $this->db->escape($ip_address) . "'");
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "logins` SET `date_modified` = NOW() ORDER BY `date_modified` ASC LIMIT 1");
    }

    public function isAccountStillValid()
    {
        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "admins` WHERE `id` = '" . (int) $this->session->data['cmtx_admin_id'] . "' AND `is_enabled` = '1'"))) {
            return true;
        } else {
            return false;
        }
    }
}
