<?php
namespace Commentics;

class ManagePagesModel extends Model
{
    public function getPages($data, $count = false)
    {
        if ($count) {
            $sql = "SELECT COUNT(`p`.`id`)";
        } else {
            $sql = "SELECT `p`.*,";

            $sql .= " (SELECT COUNT(`id`) FROM `" . CMTX_DB_PREFIX . "subscriptions` `s` WHERE `s`.`page_id` = `p`.`id`) AS `subscriptions`,";

            $sql .= " (SELECT COUNT(`id`) FROM `" . CMTX_DB_PREFIX . "comments` `c` WHERE `c`.`page_id` = `p`.`id`) AS `comments`";
        }

        $sql .= " FROM `" . CMTX_DB_PREFIX . "pages` `p`";

        $sql .= " WHERE 1 = 1";

        if ($data['filter_id']) {
            $sql .= " AND `p`.`id` = '" . (int) $data['filter_id'] . "'";
        }

        if ($data['filter_identifier']) {
            $sql .= " AND `p`.`identifier` LIKE '%" . $this->db->escape($data['filter_identifier']) . "%'";
        }

        if ($data['filter_reference']) {
            $sql .= " AND `p`.`reference` LIKE '%" . $this->db->escape($data['filter_reference']) . "%'";
        }

        if ($data['filter_url']) {
            $sql .= " AND `p`.`url` LIKE '%" . $this->db->escape($data['filter_url']) . "%'";
        }

        if ($data['filter_moderate']) {
            $sql .= " AND `p`.`moderate` = '" . $this->db->escape($data['filter_moderate']) . "'";
        }

        if ($data['filter_is_form_enabled'] != '') {
            $sql .= " AND `p`.`is_form_enabled` = '" . (int) $data['filter_is_form_enabled'] . "'";
        }

        if ($data['filter_date']) {
            $sql .= " AND `p`.`date_added` LIKE '%" . $this->db->escape($data['filter_date']) . "%'";
        }

        if (!$this->setting->get('empty_pages')) {
            $sql .= " AND (SELECT COUNT(`id`) FROM `" . CMTX_DB_PREFIX . "comments` `c` WHERE `c`.`page_id` = `p`.`id`) > 0";
        }

        if ($data['group_by']) {
            $sql .= " GROUP BY " . $this->db->backticks($data['group_by']);
        } else {
            $sql .= " GROUP BY `p`.`id`";
        }

        if (!$count) {
            $sql .= " ORDER BY " . $this->db->backticks($data['sort']);

            if ($data['order'] == 'asc') {
                $sql .= " ASC";
            } else {
                $sql .= " DESC";
            }

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

        if (isset($this->request->get['filter_identifier'])) {
            $url .= '&filter_identifier=' . $this->url->encode($this->security->decode($this->request->get['filter_identifier']));
        }

        if (isset($this->request->get['filter_reference'])) {
            $url .= '&filter_reference=' . $this->url->encode($this->security->decode($this->request->get['filter_reference']));
        }

        if (isset($this->request->get['filter_url'])) {
            $url .= '&filter_url=' . $this->url->encode($this->security->decode($this->request->get['filter_url']));
        }

        if (isset($this->request->get['filter_moderate'])) {
            $url .= '&filter_moderate=' . $this->url->encode($this->security->decode($this->request->get['filter_moderate']));
        }

        if (isset($this->request->get['filter_is_form_enabled'])) {
            $url .= '&filter_is_form_enabled=' . $this->url->encode($this->security->decode($this->request->get['filter_is_form_enabled']));
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

        if (isset($this->request->get['filter_identifier'])) {
            $url .= '&filter_identifier=' . $this->url->encode($this->security->decode($this->request->get['filter_identifier']));
        }

        if (isset($this->request->get['filter_reference'])) {
            $url .= '&filter_reference=' . $this->url->encode($this->security->decode($this->request->get['filter_reference']));
        }

        if (isset($this->request->get['filter_url'])) {
            $url .= '&filter_url=' . $this->url->encode($this->security->decode($this->request->get['filter_url']));
        }

        if (isset($this->request->get['filter_moderate'])) {
            $url .= '&filter_moderate=' . $this->url->encode($this->security->decode($this->request->get['filter_moderate']));
        }

        if (isset($this->request->get['filter_is_form_enabled'])) {
            $url .= '&filter_is_form_enabled=' . $this->url->encode($this->security->decode($this->request->get['filter_is_form_enabled']));
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
        return $this->page->deletePage($id);
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
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '0' WHERE `title` = 'notice_manage_pages'");
    }

    public function discard()
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '0' WHERE `title` = 'warning_manage_pages'");
    }

    public function getPageCookie()
    {
        $sort = $order = '';

        if ($this->cookie->exists('Commentics-Manage-Pages')) {
            $content = $this->cookie->get('Commentics-Manage-Pages');

            $content = explode('|', $content);

            if (isset($content[0]) && in_array($content[0], array('p.identifier', 'p.reference', 'p.url', 'comments', 'subscriptions', 'p.moderate', 'p.is_form_enabled', 'p.date_added'))) {
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
        $this->cookie->set('Commentics-Manage-Pages', $sort . '|' . $order, 60 * 60 * 24 * $this->setting->get('admin_cookie_days') + time());
    }
}
