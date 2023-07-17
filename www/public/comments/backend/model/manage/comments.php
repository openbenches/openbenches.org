<?php
namespace Commentics;

class ManageCommentsModel extends Model
{
    public function getComments($data, $count = false)
    {
        $sql = "SELECT `c`.*, `u`.`id` AS `user_id`, `u`.`name` AS `name`, `p`.`id` AS `page_id`, `p`.`reference` AS `page_reference`, `p`.`url` AS `page_url` FROM `" . CMTX_DB_PREFIX . "comments` `c`";

        $sql .= " LEFT JOIN `" . CMTX_DB_PREFIX . "users` `u` ON `c`.`user_id` = `u`.`id`";

        $sql .= " LEFT JOIN `" . CMTX_DB_PREFIX . "pages` `p` ON `c`.`page_id` = `p`.`id`";

        $sql .= " WHERE 1 = 1";

        if ($data['filter_id']) {
            $sql .= " AND `c`.`id` = '" . (int) $data['filter_id'] . "'";
        }

        if ($data['filter_user_id']) {
            $sql .= " AND `c`.`user_id` = '" . (int) $data['filter_user_id'] . "'";
        }

        if ($data['filter_page_id']) {
            $sql .= " AND `c`.`page_id` = '" . (int) $data['filter_page_id'] . "'";
        }

        if ($data['filter_name']) {
            $sql .= " AND `u`.`name` LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if ($data['filter_comment']) {
            $sql .= " AND `c`.`comment` LIKE '%" . $this->db->escape($data['filter_comment']) . "%'";
        }

        if ($data['filter_rating']) {
            $sql .= " AND `c`.`rating` = '" . (int) $data['filter_rating'] . "'";
        }

        if ($data['filter_page']) {
            $sql .= " AND `p`.`reference` LIKE '%" . $this->db->escape($data['filter_page']) . "%'";
        }

        if ($data['filter_approved'] != '') {
            $sql .= " AND `c`.`is_approved` = '" . (int) $data['filter_approved'] . "'";
        }

        if ($data['filter_sent'] != '') {
            $sql .= " AND `c`.`is_sent` = '" . (int) $data['filter_sent'] . "'";
        }

        if ($data['filter_flagged'] != '') {
            if ($data['filter_flagged'] == '1') {
                $sql .= " AND `c`.`reports` >= '" . (int) $this->setting->get('flag_min_per_comment') . "'";
            } else {
                $sql .= " AND `c`.`reports` < '" . (int) $this->setting->get('flag_min_per_comment') . "'";
            }
        }

        if ($data['filter_ip_address']) {
            $sql .= " AND `c`.`ip_address` LIKE '%" . $this->db->escape($data['filter_ip_address']) . "%'";
        }

        if ($data['filter_date']) {
            $sql .= " AND `c`.`date_added` LIKE '%" . $this->db->escape($data['filter_date']) . "%'";
        }

        if ($data['group_by']) {
            $sql .= " GROUP BY " . $this->db->backticks($data['group_by']);
        } else {
            $sql .= " GROUP BY `c`.`id`";
        }

        if ($data['sort'] == 'c.flagged') {
            $data['sort'] = 'c.reports';
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

        if (isset($this->request->get['filter_comment'])) {
            $url .= '&filter_comment=' . $this->url->encode($this->security->decode($this->request->get['filter_comment']));
        }

        if (isset($this->request->get['filter_rating'])) {
            $url .= '&filter_rating=' . $this->url->encode($this->security->decode($this->request->get['filter_rating']));
        }

        if (isset($this->request->get['filter_page'])) {
            $url .= '&filter_page=' . $this->url->encode($this->security->decode($this->request->get['filter_page']));
        }

        if (isset($this->request->get['filter_approved'])) {
            $url .= '&filter_approved=' . $this->url->encode($this->security->decode($this->request->get['filter_approved']));
        }

        if (isset($this->request->get['filter_sent'])) {
            $url .= '&filter_sent=' . $this->url->encode($this->security->decode($this->request->get['filter_sent']));
        }

        if (isset($this->request->get['filter_flagged'])) {
            $url .= '&filter_flagged=' . $this->url->encode($this->security->decode($this->request->get['filter_flagged']));
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

        if (isset($this->request->get['filter_comment'])) {
            $url .= '&filter_comment=' . $this->url->encode($this->security->decode($this->request->get['filter_comment']));
        }

        if (isset($this->request->get['filter_rating'])) {
            $url .= '&filter_rating=' . $this->url->encode($this->security->decode($this->request->get['filter_rating']));
        }

        if (isset($this->request->get['filter_page'])) {
            $url .= '&filter_page=' . $this->url->encode($this->security->decode($this->request->get['filter_page']));
        }

        if (isset($this->request->get['filter_approved'])) {
            $url .= '&filter_approved=' . $this->url->encode($this->security->decode($this->request->get['filter_approved']));
        }

        if (isset($this->request->get['filter_sent'])) {
            $url .= '&filter_sent=' . $this->url->encode($this->security->decode($this->request->get['filter_sent']));
        }

        if (isset($this->request->get['filter_flagged'])) {
            $url .= '&filter_flagged=' . $this->url->encode($this->security->decode($this->request->get['filter_flagged']));
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

    public function singleApprove($id)
    {
        if ($this->db->numRows($this->db->query("SELECT `id` FROM `" . CMTX_DB_PREFIX . "comments` WHERE `is_approved` = '0' AND `id` = '" . (int) $id . "'"))) {
            $site_id = $this->site->getSiteIdByCommentId($id);

            if ($site_id) {
                $this->page->setSiteId($site_id); // ensure relevant site's email sender details are used
            }

            /* Notify the user that their comment is approved */
            $this->notify->approvalNotification($id);

            /* Notify other users about the new comment only if granular notification control is disabled */
            if (!$this->setting->get('approve_notifications')) {
                $this->singleSend($id);
            }

            $this->comment->approveComment($id);

            $this->comment->deleteCache($id);

            return true;
        } else {
            return false;
        }
    }

    public function bulkApprove($ids)
    {
        $success = $failure = 0;

        foreach ($ids as $id) {
            if ($this->singleApprove($id)) {
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

    public function singleSend($id)
    {
        if ($this->db->numRows($this->db->query("SELECT `id` FROM `" . CMTX_DB_PREFIX . "comments` WHERE `is_sent` = '0' AND `id` = '" . (int) $id . "'"))) {
            $site_id = $this->site->getSiteIdByCommentId($id);

            if ($site_id) {
                $this->page->setSiteId($site_id); // ensure relevant site's email sender details are used
            }

            $this->notify->subscriberNotification($id);

            return true;
        } else {
            return false;
        }
    }

    public function bulkSend($ids)
    {
        $success = $failure = 0;

        foreach ($ids as $id) {
            if ($this->singleSend($id)) {
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

    public function singleDelete($id)
    {
        return $this->comment->deleteComment($id);
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
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '0' WHERE `title` = 'notice_manage_comments'");
    }

    public function getPageCookie()
    {
        $sort = $order = '';

        if ($this->cookie->exists('Commentics-Manage-Comments')) {
            $content = $this->cookie->get('Commentics-Manage-Comments');

            $content = explode('|', $content);

            if (isset($content[0]) && in_array($content[0], array('u.name', 'c.comment', 'c.rating', 'p.reference', 'c.is_approved', 'c.is_sent', 'c.reports', 'c.flagged', 'c.ip_address', 'c.date_added'))) {
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
        $this->cookie->set('Commentics-Manage-Comments', $sort . '|' . $order, 60 * 60 * 24 * $this->setting->get('admin_cookie_days') + time());
    }

    public function shortenComment($comment)
    {
        $comment = str_replace(array('<br>', '<p></p>'), ' ', $comment);

        $comment = str_replace('</p>', '</p> ', $comment);

        $comment = trim($comment);

        $comment = strip_tags($comment);

        $comment = $this->security->decode($comment);

        if ($this->validation->length($comment) > 50) {
            $comment = $this->variable->substr($comment, 0, 50) . ' ...';
        }

        $comment = $this->security->encode($comment);

        return $comment;
    }
}
