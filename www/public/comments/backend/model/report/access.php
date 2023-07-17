<?php
namespace Commentics;

class ReportAccessModel extends Model
{
    public function getViews($data, $count = false)
    {
        $sql = "SELECT * FROM `" . CMTX_DB_PREFIX . "access` `a`";

        $sql .= " WHERE 1 = 1";

        if ($data['filter_id']) {
            $sql .= " AND `a`.`id` = '" . (int) $data['filter_id'] . "'";
        }

        if ($data['filter_username']) {
            $sql .= " AND `a`.`username` LIKE '%" . $this->db->escape($data['filter_username']) . "%'";
        }

        if ($data['filter_ip_address']) {
            $sql .= " AND `a`.`ip_address` LIKE '%" . $this->db->escape($data['filter_ip_address']) . "%'";
        }

        if ($data['filter_page']) {
            $sql .= " AND `a`.`page` LIKE '%" . $this->db->escape($data['filter_page']) . "%'";
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

        if (isset($this->request->get['filter_ip_address'])) {
            $url .= '&filter_ip_address=' . $this->url->encode($this->security->decode($this->request->get['filter_ip_address']));
        }

        if (isset($this->request->get['filter_page'])) {
            $url .= '&filter_page=' . $this->url->encode($this->security->decode($this->request->get['filter_page']));
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

        if (isset($this->request->get['filter_ip_address'])) {
            $url .= '&filter_ip_address=' . $this->url->encode($this->security->decode($this->request->get['filter_ip_address']));
        }

        if (isset($this->request->get['filter_page'])) {
            $url .= '&filter_page=' . $this->url->encode($this->security->decode($this->request->get['filter_page']));
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

        if ($this->cookie->exists('Commentics-Report-Access')) {
            $content = $this->cookie->get('Commentics-Report-Access');

            $content = explode('|', $content);

            if (isset($content[0]) && in_array($content[0], array('a.username', 'a.ip_address', 'a.page', 'a.date_added'))) {
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
        $this->cookie->set('Commentics-Report-Access', $sort . '|' . $order, 60 * 60 * 24 * $this->setting->get('admin_cookie_days') + time());
    }
}
