<?php
namespace Commentics;

class ManageCountriesModel extends Model
{
    public function getCountries($data, $count = false)
    {
        $sql = "SELECT `c`.*, `g`.`name` FROM `" . CMTX_DB_PREFIX . "countries` `c`";

        $sql .= " LEFT JOIN `" . CMTX_DB_PREFIX . "geo` `g` ON `g`.`country_code` = `c`.`code`";

        $sql .= " WHERE 1 = 1";

        $sql .= " AND `g`.`language` = '" . $this->db->escape($this->setting->get('language_backend')) . "'";

        if ($data['filter_id']) {
            $sql .= " AND `c`.`id` = '" . (int) $data['filter_id'] . "'";
        }

        if ($data['filter_name']) {
            $sql .= " AND `g`.`name` LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if ($data['filter_code']) {
            $sql .= " AND `c`.`code` LIKE '%" . $this->db->escape($data['filter_code']) . "%'";
        }

        if ($data['filter_top'] != '') {
            $sql .= " AND `c`.`top` = '" . (int) $data['filter_top'] . "'";
        }

        if ($data['filter_enabled'] != '') {
            $sql .= " AND `c`.`enabled` = '" . (int) $data['filter_enabled'] . "'";
        }

        if ($data['filter_date']) {
            $sql .= " AND `c`.`date_added` LIKE '%" . $this->db->escape($data['filter_date']) . "%'";
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

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . $this->url->encode($this->security->decode($this->request->get['filter_name']));
        }

        if (isset($this->request->get['filter_code'])) {
            $url .= '&filter_code=' . $this->url->encode($this->security->decode($this->request->get['filter_code']));
        }

        if (isset($this->request->get['filter_top'])) {
            $url .= '&filter_top=' . $this->url->encode($this->security->decode($this->request->get['filter_top']));
        }

        if (isset($this->request->get['filter_enabled'])) {
            $url .= '&filter_enabled=' . $this->url->encode($this->security->decode($this->request->get['filter_enabled']));
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

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . $this->url->encode($this->security->decode($this->request->get['filter_name']));
        }

        if (isset($this->request->get['filter_code'])) {
            $url .= '&filter_code=' . $this->url->encode($this->security->decode($this->request->get['filter_code']));
        }

        if (isset($this->request->get['filter_top'])) {
            $url .= '&filter_top=' . $this->url->encode($this->security->decode($this->request->get['filter_top']));
        }

        if (isset($this->request->get['filter_enabled'])) {
            $url .= '&filter_enabled=' . $this->url->encode($this->security->decode($this->request->get['filter_enabled']));
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
        return $this->geo->deleteCountry($id);
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
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '0' WHERE `title` = 'notice_manage_countries'");
    }

    public function getPageCookie()
    {
        $sort = $order = '';

        if ($this->cookie->exists('Commentics-Manage-Countries')) {
            $content = $this->cookie->get('Commentics-Manage-Countries');

            $content = explode('|', $content);

            if (isset($content[0]) && in_array($content[0], array('g.name', 'c.code', 'c.top', 'c.enabled', 'c.date_added'))) {
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
        $this->cookie->set('Commentics-Manage-Countries', $sort . '|' . $order, 60 * 60 * 24 * $this->setting->get('admin_cookie_days') + time());
    }
}
