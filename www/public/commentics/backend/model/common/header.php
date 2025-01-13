<?php
namespace Commentics;

class CommonHeaderModel extends Model
{
    public function backendFolderExists()
    {
        if (file_exists('../backend/')) {
            return true;
        } else {
            return false;
        }
    }

    public function installFolderExists()
    {
        if (file_exists('../install/')) {
            return true;
        } else {
            return false;
        }
    }

    public function isConfigWritable()
    {
        if (is_writable('../config.php')) {
            return true;
        } else {
            return false;
        }
    }

    public function isPageViewable($restrict_pages, $viewable_pages)
    {
        if (!$restrict_pages || ($restrict_pages && in_array($this->request->get['route'], $viewable_pages)) || $this->request->get['route'] == 'main/dashboard' || ($this->variable->stristr($this->request->get['route'], 'module/') && in_array('module/', $viewable_pages))) {
            return true;
        } else {
            return false;
        }
    }

    public function hasRestriction()
    {
        $query = $this->db->query("SELECT `restrict_pages` FROM `" . CMTX_DB_PREFIX . "admins` WHERE `id` = '" . (int) $this->session->data['cmtx_admin_id'] . "'");

        $result = $this->db->row($query);

        return $result['restrict_pages'];
    }

    public function getViewablePages()
    {
        $query = $this->db->query("SELECT `viewable_pages` FROM `" . CMTX_DB_PREFIX . "admins` WHERE `id` = '" . (int) $this->session->data['cmtx_admin_id'] . "'");

        $result = $this->db->row($query);

        $result = explode(',', $result['viewable_pages']);

        return $result;
    }

    public function addView()
    {
        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "access`")) >= 100) {
            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "access` ORDER BY `date_added` ASC LIMIT 1");
        }

        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "access` SET `username` = '" . $this->db->escape($this->session->data['cmtx_username']) . "', `ip_address` = '" . $this->db->escape($this->user->getIpAddress()) . "', `page` = '" . $this->db->escape($this->request->get['route']) . "', `date_added` = NOW()");
    }
}
