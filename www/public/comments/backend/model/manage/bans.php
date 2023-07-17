<?php
namespace Commentics;

class ManageBansModel extends Model
{
    public function getBans($data, $count = false)
    {
        $sql = "SELECT * FROM `" . CMTX_DB_PREFIX . "bans` `b`";

        $sql .= " WHERE 1 = 1";

        $sql .= " AND `unban` = '0'";

        if ($data['filter_id']) {
            $sql .= " AND `b`.`id` = '" . (int) $data['filter_id'] . "'";
        }

        if ($data['filter_ip_address']) {
            $sql .= " AND `b`.`ip_address` LIKE '%" . $this->db->escape($data['filter_ip_address']) . "%'";
        }

        if ($data['filter_reason']) {
            $sql .= " AND `b`.`reason` LIKE '%" . $this->db->escape($data['filter_reason']) . "%'";
        }

        if ($data['filter_date']) {
            $sql .= " AND `b`.`date_added` LIKE '%" . $this->db->escape($data['filter_date']) . "%'";
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

        if (isset($this->request->get['filter_ip_address'])) {
            $url .= '&filter_ip_address=' . $this->url->encode($this->security->decode($this->request->get['filter_ip_address']));
        }

        if (isset($this->request->get['filter_reason'])) {
            $url .= '&filter_reason=' . $this->url->encode($this->security->decode($this->request->get['filter_reason']));
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

        if (isset($this->request->get['filter_ip_address'])) {
            $url .= '&filter_ip_address=' . $this->url->encode($this->security->decode($this->request->get['filter_ip_address']));
        }

        if (isset($this->request->get['filter_reason'])) {
            $url .= '&filter_reason=' . $this->url->encode($this->security->decode($this->request->get['filter_reason']));
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
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "bans` SET `unban` = '1' WHERE `id` = '" . (int) $id . "'");

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
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '0' WHERE `title` = 'notice_manage_bans'");
    }

    public function getPageCookie()
    {
        $sort = $order = '';

        if ($this->cookie->exists('Commentics-Manage-Bans')) {
            $content = $this->cookie->get('Commentics-Manage-Bans');

            $content = explode('|', $content);

            if (isset($content[0]) && in_array($content[0], array('b.ip_address', 'b.reason', 'b.date_added'))) {
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
        $this->cookie->set('Commentics-Manage-Bans', $sort . '|' . $order, 60 * 60 * 24 * $this->setting->get('admin_cookie_days') + time());
    }
}
