<?php
namespace Commentics;

class ReportViewersModel extends Model
{
    public function getViewers($data, $count = false)
    {
        $sql = "SELECT * FROM `" . CMTX_DB_PREFIX . "viewers` `v`";

        $sql .= " WHERE 1 = 1";

        if ($data['filter_id']) {
            $sql .= " AND `v`.`id` = '" . (int) $data['filter_id'] . "'";
        }

        if ($data['filter_type']) {
            $sql .= " AND `v`.`type` LIKE '%" . $this->db->escape($data['filter_type']) . "%'";
        }

        if ($data['filter_ip_address']) {
            $sql .= " AND `v`.`ip_address` LIKE '%" . $this->db->escape($data['filter_ip_address']) . "%'";
        }

        if ($data['filter_page_reference']) {
            $sql .= " AND `v`.`page_reference` LIKE '%" . $this->db->escape($data['filter_page_reference']) . "%'";
        }

        if ($data['filter_page_url']) {
            $sql .= " AND `v`.`page_url` LIKE '%" . $this->db->escape($data['filter_page_url']) . "%'";
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

        if (isset($this->request->get['filter_type'])) {
            $url .= '&filter_type=' . $this->url->encode($this->security->decode($this->request->get['filter_type']));
        }

        if (isset($this->request->get['filter_ip_address'])) {
            $url .= '&filter_ip_address=' . $this->url->encode($this->security->decode($this->request->get['filter_ip_address']));
        }

        if (isset($this->request->get['filter_page_reference'])) {
            $url .= '&filter_page_reference=' . $this->url->encode($this->security->decode($this->request->get['filter_page_reference']));
        }

        if (isset($this->request->get['filter_page_url'])) {
            $url .= '&filter_page_url=' . $this->url->encode($this->security->decode($this->request->get['filter_page_url']));
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

        if (isset($this->request->get['filter_type'])) {
            $url .= '&filter_type=' . $this->url->encode($this->security->decode($this->request->get['filter_type']));
        }

        if (isset($this->request->get['filter_ip_address'])) {
            $url .= '&filter_ip_address=' . $this->url->encode($this->security->decode($this->request->get['filter_ip_address']));
        }

        if (isset($this->request->get['filter_page_reference'])) {
            $url .= '&filter_page_reference=' . $this->url->encode($this->security->decode($this->request->get['filter_page_reference']));
        }

        if (isset($this->request->get['filter_page_url'])) {
            $url .= '&filter_page_url=' . $this->url->encode($this->security->decode($this->request->get['filter_page_url']));
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        return $url;
    }

    public function getPageCookie()
    {
        $sort = $order = '';

        if ($this->cookie->exists('Commentics-Report-Viewers')) {
            $content = $this->cookie->get('Commentics-Report-Viewers');

            $content = explode('|', $content);

            if (isset($content[0]) && in_array($content[0], array('v.viewer', 'v.type', 'v.ip_address', 'v.page_reference', 'v.page_url', 'v.time_added'))) {
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
        $this->cookie->set('Commentics-Report-Viewers', $sort . '|' . $order, 60 * 60 * 24 * $this->setting->get('admin_cookie_days') + time());
    }

    public function formatTime($timestamp)
    {
        $timestamp = time() - $timestamp;

        if ($timestamp >= 3600) {
            $time = gmdate('H\h i\m s\s', $timestamp);
        } else {
            $time = gmdate('i\m s\s', $timestamp);
        }

        return $time;
    }

    public function clearViewers()
    {
        $timeout = time() - $this->setting->get('viewers_timeout');

        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "viewers` WHERE `time_added` < '" . (int) $timeout . "'");
    }
}
