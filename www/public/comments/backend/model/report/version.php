<?php
namespace Commentics;

class ReportVersionModel extends Model
{
    public function getVersions($data, $count = false)
    {
        $sql = "SELECT * FROM `" . CMTX_DB_PREFIX . "version` `v`";

        $sql .= " WHERE 1 = 1";

        if ($data['filter_id']) {
            $sql .= " AND `v`.`id` = '" . (int) $data['filter_id'] . "'";
        }

        if ($data['filter_version']) {
            $sql .= " AND `v`.`version` LIKE '%" . $this->db->escape($data['filter_version']) . "%'";
        }

        if ($data['filter_type']) {
            $sql .= " AND `v`.`type` = '" . $this->db->escape($data['filter_type']) . "'";
        }

        if ($data['filter_date']) {
            $sql .= " AND `v`.`date_added` LIKE '%" . $this->db->escape($data['filter_date']) . "%'";
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

        if (isset($this->request->get['filter_version'])) {
            $url .= '&filter_version=' . $this->url->encode($this->security->decode($this->request->get['filter_version']));
        }

        if (isset($this->request->get['filter_type'])) {
            $url .= '&filter_type=' . $this->url->encode($this->security->decode($this->request->get['filter_type']));
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

        if (isset($this->request->get['filter_version'])) {
            $url .= '&filter_version=' . $this->url->encode($this->security->decode($this->request->get['filter_version']));
        }

        if (isset($this->request->get['filter_type'])) {
            $url .= '&filter_type=' . $this->url->encode($this->security->decode($this->request->get['filter_type']));
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

    public function getPageCookie()
    {
        $sort = $order = '';

        if ($this->cookie->exists('Commentics-Report-Version')) {
            $content = $this->cookie->get('Commentics-Report-Version');

            $content = explode('|', $content);

            if (isset($content[0]) && in_array($content[0], array('v.version', 'v.type', 'v.date_added'))) {
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
        $this->cookie->set('Commentics-Report-Version', $sort . '|' . $order, 60 * 60 * 24 * $this->setting->get('admin_cookie_days') + time());
    }

    public function getCurrent()
    {
        $query = $this->db->query("SELECT `version` FROM `" . CMTX_DB_PREFIX . "version` ORDER BY `date_added` DESC LIMIT 1");

        $result = $this->db->row($query);

        return $result['version'];
    }
}
