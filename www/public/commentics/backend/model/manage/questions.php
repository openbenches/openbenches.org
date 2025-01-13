<?php
namespace Commentics;

class ManageQuestionsModel extends Model
{
    public function getQuestions($data, $count = false)
    {
        $sql = "SELECT * FROM `" . CMTX_DB_PREFIX . "questions` `q`";

        $sql .= " WHERE 1 = 1";

        if ($data['filter_id']) {
            $sql .= " AND `q`.`id` = '" . (int) $data['filter_id'] . "'";
        }

        if ($data['filter_question']) {
            $sql .= " AND `q`.`question` LIKE '%" . $this->db->escape($data['filter_question']) . "%'";
        }

        if ($data['filter_answer']) {
            $sql .= " AND `q`.`answer` LIKE '%" . $this->db->escape($data['filter_answer']) . "%'";
        }

        if ($data['filter_language']) {
            $sql .= " AND `q`.`language` LIKE '%" . $this->db->escape($data['filter_language']) . "%'";
        }

        if ($data['filter_date']) {
            $sql .= " AND `q`.`date_added` LIKE '%" . $this->db->escape($data['filter_date']) . "%'";
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

        if (isset($this->request->get['filter_question'])) {
            $url .= '&filter_question=' . $this->url->encode($this->security->decode($this->request->get['filter_question']));
        }

        if (isset($this->request->get['filter_answer'])) {
            $url .= '&filter_answer=' . $this->url->encode($this->security->decode($this->request->get['filter_answer']));
        }

        if (isset($this->request->get['filter_language'])) {
            $url .= '&filter_language=' . $this->url->encode($this->security->decode($this->request->get['filter_language']));
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

        if (isset($this->request->get['filter_question'])) {
            $url .= '&filter_question=' . $this->url->encode($this->security->decode($this->request->get['filter_question']));
        }

        if (isset($this->request->get['filter_answer'])) {
            $url .= '&filter_answer=' . $this->url->encode($this->security->decode($this->request->get['filter_answer']));
        }

        if (isset($this->request->get['filter_language'])) {
            $url .= '&filter_language=' . $this->url->encode($this->security->decode($this->request->get['filter_language']));
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
        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "questions` WHERE `id` = '" . (int) $id . "'");

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
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '0' WHERE `title` = 'notice_manage_questions'");
    }

    public function getPageCookie()
    {
        $sort = $order = '';

        if ($this->cookie->exists('Commentics-Manage-Questions')) {
            $content = $this->cookie->get('Commentics-Manage-Questions');

            $content = explode('|', $content);

            if (isset($content[0]) && in_array($content[0], array('q.question', 'q.answer', 'q.language', 'q.date_added'))) {
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
        $this->cookie->set('Commentics-Manage-Questions', $sort . '|' . $order, 60 * 60 * 24 * $this->setting->get('admin_cookie_days') + time());
    }
}
