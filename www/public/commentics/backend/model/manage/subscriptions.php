<?php
namespace Commentics;

class ManageSubscriptionsModel extends Model
{
    public function getSubscriptions($data, $count = false)
    {
        $sql = "SELECT `s`.*, `u`.`id` AS `user_id`, `u`.`token` AS `user_token`, `u`.`name` AS `name`,  `u`.`email` AS `email`, `p`.`id` AS `page_id`, `p`.`reference` AS `page_reference` FROM `" . CMTX_DB_PREFIX . "subscriptions` `s`";

        $sql .= " LEFT JOIN `" . CMTX_DB_PREFIX . "users` `u` ON `s`.`user_id` = `u`.`id`";

        $sql .= " LEFT JOIN `" . CMTX_DB_PREFIX . "pages` `p` ON `s`.`page_id` = `p`.`id`";

        $sql .= " WHERE 1 = 1";

        if ($data['filter_id']) {
            $sql .= " AND `s`.`id` = '" . (int) $data['filter_id'] . "'";
        }

        if ($data['filter_user_id']) {
            $sql .= " AND `s`.`user_id` = '" . (int) $data['filter_user_id'] . "'";
        }

        if ($data['filter_page_id']) {
            $sql .= " AND `s`.`page_id` = '" . (int) $data['filter_page_id'] . "'";
        }

        if ($data['filter_name']) {
            $sql .= " AND `u`.`name` LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if ($data['filter_email']) {
            $sql .= " AND `u`.`email` LIKE '%" . $this->db->escape($data['filter_email']) . "%'";
        }

        if ($data['filter_page']) {
            $sql .= " AND `p`.`reference` LIKE '%" . $this->db->escape($data['filter_page']) . "%'";
        }

        if ($data['filter_confirmed'] != '') {
            $sql .= " AND `s`.`is_confirmed` = '" . (int) $data['filter_confirmed'] . "'";
        }

        if ($data['filter_ip_address']) {
            $sql .= " AND `s`.`ip_address` LIKE '%" . $this->db->escape($data['filter_ip_address']) . "%'";
        }

        if ($data['filter_date']) {
            $sql .= " AND `s`.`date_added` LIKE '%" . $this->db->escape($data['filter_date']) . "%'";
        }

        if ($data['group_by']) {
            $sql .= " GROUP BY " . $this->db->backticks($data['group_by']);
        } else {
            $sql .= " GROUP BY `s`.`id`";
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

        if (isset($this->request->get['filter_user_id'])) {
            $url .= '&filter_user_id=' . $this->url->encode($this->security->decode($this->request->get['filter_user_id']));
        }

        if (isset($this->request->get['filter_page_id'])) {
            $url .= '&filter_page_id=' . $this->url->encode($this->security->decode($this->request->get['filter_page_id']));
        }

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . $this->url->encode($this->security->decode($this->request->get['filter_name']));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . $this->url->encode($this->security->decode($this->request->get['filter_email']));
        }

        if (isset($this->request->get['filter_page'])) {
            $url .= '&filter_page=' . $this->url->encode($this->security->decode($this->request->get['filter_page']));
        }

        if (isset($this->request->get['filter_confirmed'])) {
            $url .= '&filter_confirmed=' . $this->url->encode($this->security->decode($this->request->get['filter_confirmed']));
        }

        if (isset($this->request->get['filter_ip_address'])) {
            $url .= '&filter_ip_address=' . $this->url->encode($this->security->decode($this->request->get['filter_ip_address']));
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

        if (isset($this->request->get['filter_user_id'])) {
            $url .= '&filter_user_id=' . $this->url->encode($this->security->decode($this->request->get['filter_user_id']));
        }

        if (isset($this->request->get['filter_page_id'])) {
            $url .= '&filter_page_id=' . $this->url->encode($this->security->decode($this->request->get['filter_page_id']));
        }

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . $this->url->encode($this->security->decode($this->request->get['filter_name']));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . $this->url->encode($this->security->decode($this->request->get['filter_email']));
        }

        if (isset($this->request->get['filter_page'])) {
            $url .= '&filter_page=' . $this->url->encode($this->security->decode($this->request->get['filter_page']));
        }

        if (isset($this->request->get['filter_confirmed'])) {
            $url .= '&filter_confirmed=' . $this->url->encode($this->security->decode($this->request->get['filter_confirmed']));
        }

        if (isset($this->request->get['filter_ip_address'])) {
            $url .= '&filter_ip_address=' . $this->url->encode($this->security->decode($this->request->get['filter_ip_address']));
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
        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "subscriptions` WHERE `id` = '" . (int) $id . "'");

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
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '0' WHERE `title` = 'notice_manage_subscriptions'");
    }

    public function getPageCookie()
    {
        $sort = $order = '';

        if ($this->cookie->exists('Commentics-Manage-Subscriptions')) {
            $content = $this->cookie->get('Commentics-Manage-Subscriptions');

            $content = explode('|', $content);

            if (isset($content[0]) && in_array($content[0], array('u.name', 'u.email', 'p.reference', 's.is_confirmed', 's.ip_address', 's.date_added'))) {
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
        $this->cookie->set('Commentics-Manage-Subscriptions', $sort . '|' . $order, 60 * 60 * 24 * $this->setting->get('admin_cookie_days') + time());
    }
}
