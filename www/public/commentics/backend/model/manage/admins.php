<?php
namespace Commentics;

class ManageAdminsModel extends Model
{
    public function getAdmins($data, $count = false)
    {
        $sql = "SELECT * FROM `" . CMTX_DB_PREFIX . "admins` `a`";

        $sql .= " WHERE 1 = 1";

        if ($data['filter_id']) {
            $sql .= " AND `a`.`id` = '" . (int) $data['filter_id'] . "'";
        }

        if ($data['filter_username']) {
            $sql .= " AND `a`.`username` LIKE '%" . $this->db->escape($data['filter_username']) . "%'";
        }

        if ($data['filter_email']) {
            $sql .= " AND `a`.`email` LIKE '%" . $this->db->escape($data['filter_email']) . "%'";
        }

        if ($data['filter_enabled'] != '') {
            $sql .= " AND `a`.`is_enabled` = '" . (int) $data['filter_enabled'] . "'";
        }

        if ($data['filter_super'] != '') {
            $sql .= " AND `a`.`is_super` = '" . (int) $data['filter_super'] . "'";
        }

        if ($data['filter_last_login']) {
            $sql .= " AND `a`.`last_login` LIKE '%" . $this->db->escape($data['filter_last_login']) . "%'";
        }

        if ($data['filter_date']) {
            $sql .= " AND `a`.`date_added` LIKE '%" . $this->db->escape($data['filter_date']) . "%'";
        }

        if ($data['group_by']) {
            $sql .= " GROUP BY " . $this->db->backticks($data['group_by']);
        }

        $sql .= " ORDER BY " . $this->db->backticks($data['sort']);

        if ($data['order'] == 'asc') {
            $sql .= " ASC";
        } else {
            $sql .= " DESC";
        }

        if (!$count) {
            $sql .= " LIMIT " . (int) $data['start'] . ", " . (int) $data['limit'];
        }

        $query = $this->db->query($sql);

        if ($count) {
            return $this->db->numRows($query);
        } else {
            return $this->db->rows($query);
        }
    }

    public function sortUrl()
    {
        $url = '';

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        if (isset($this->request->get['filter_username'])) {
            $url .= '&filter_username=' . $this->url->encode($this->security->decode($this->request->get['filter_username']));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . $this->url->encode($this->security->decode($this->request->get['filter_email']));
        }

        if (isset($this->request->get['filter_enabled'])) {
            $url .= '&filter_enabled=' . $this->url->encode($this->security->decode($this->request->get['filter_enabled']));
        }

        if (isset($this->request->get['filter_super'])) {
            $url .= '&filter_super=' . $this->url->encode($this->security->decode($this->request->get['filter_super']));
        }

        if (isset($this->request->get['filter_last_login'])) {
            $url .= '&filter_last_login=' . $this->url->encode($this->security->decode($this->request->get['filter_last_login']));
        }

        if (isset($this->request->get['filter_date'])) {
            $url .= '&filter_date=' . $this->url->encode($this->security->decode($this->request->get['filter_date']));
        }

        if (isset($this->request->get['order'])) {
            if ($this->request->get['order'] == 'desc') {
                $url .= '&order=asc';
            } else {
                $url .= '&order=desc';
            }
        } else {
            $url .= '&order=asc';
        }

        return $url;
    }

    public function paginateUrl()
    {
        $url = '';

        if (isset($this->request->get['filter_username'])) {
            $url .= '&filter_username=' . $this->url->encode($this->security->decode($this->request->get['filter_username']));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . $this->url->encode($this->security->decode($this->request->get['filter_email']));
        }

        if (isset($this->request->get['filter_enabled'])) {
            $url .= '&filter_enabled=' . $this->url->encode($this->security->decode($this->request->get['filter_enabled']));
        }

        if (isset($this->request->get['filter_super'])) {
            $url .= '&filter_super=' . $this->url->encode($this->security->decode($this->request->get['filter_super']));
        }

        if (isset($this->request->get['filter_last_login'])) {
            $url .= '&filter_last_login=' . $this->url->encode($this->security->decode($this->request->get['filter_last_login']));
        }

        if (isset($this->request->get['filter_date'])) {
            $url .= '&filter_date=' . $this->url->encode($this->security->decode($this->request->get['filter_date']));
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        return $url;
    }

    public function singleDelete($id)
    {
        /* Don't let the super admin delete their own account */
        if ($this->session->data['cmtx_is_super'] && $id == $this->session->data['cmtx_admin_id']) {
            return false;
        }

        /* Don't let a regular admin delete another account */
        if (!$this->session->data['cmtx_is_super'] && $id != $this->session->data['cmtx_admin_id']) {
            return false;
        }

        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "admins` WHERE `id` = '" . (int) $id . "'");

        /* If admin deleted their own account */
        if ($id == $this->session->data['cmtx_admin_id']) {
            $this->response->redirect('login/login');
        }

        if ($this->db->affectedRows()) {
            return true;
        } else {
            return false;
        }
    }

    public function bulkDelete($ids)
    {
        $success = $failure = 0;

        foreach ($ids as $id) {
            if ($this->singleDelete($id)) {
                $success++;
            } else {
                $failure++;
            }
        }

        return array(
            'success' => $success,
            'failure' => $failure
        );
    }

    public function dismiss()
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '0' WHERE `title` = 'notice_manage_admins'");
    }

    public function getPageCookie()
    {
        $sort = $order = '';

        if ($this->cookie->exists('Commentics-Manage-Admins')) {
            $content = $this->cookie->get('Commentics-Manage-Admins');

            $content = explode('|', $content);

            if (isset($content[0]) && in_array($content[0], array('a.username', 'a.email', 'a.is_enabled', 'a.is_super', 'a.last_login', 'a.date_added'))) {
                $sort = $content[0];
            }

            if (isset($content[1]) && in_array($content[1], array('asc', 'desc'))) {
                $order = $content[1];
            }
        }

        $page_cookie = array('sort' => $sort, 'order' => $order);

        return $page_cookie;
    }

    public function setPageCookie($sort, $order)
    {
        $this->cookie->set('Commentics-Manage-Admins', $sort . '|' . $order, 60 * 60 * 24 * $this->setting->get('admin_cookie_days') + time());
    }
}
