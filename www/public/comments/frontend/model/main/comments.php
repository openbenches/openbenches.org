<?php
namespace Commentics;

class MainCommentsModel extends Model
{
    /* Get the approved parent comments for the page */
    public function getComments($data, $count = false)
    {
        if (!$data['filter_comment_id'] && !$data['filter_comment']) { // don't cache permalink and searches
            if ($data['group_by'] == '' && $data['sort'] == 'date_added' && $data['order'] == 'desc' && $data['start'] == 0) { // only cache initial page
                $result = $this->cache->get('getcomments_pageid' . $data['filter_page_id'] . '_count' . (int) $count . '_replies' . (int) $data['count_replies']);

                if ($result !== false) { // check boolean as could be zero or an empty array if no comments
                    return $result;
                }
            }
        }

        $sql = "SELECT `id` FROM `" . CMTX_DB_PREFIX . "comments` WHERE";

        $sql .= " `page_id` = '" . (int) $data['filter_page_id'] . "'";

        $sql .= " AND `is_approved` = '1'";

        if ($data['filter_comment_id']) {
            $sql .= " AND `id` = '" . (int) $data['filter_comment_id'] . "'";
        } else if ($count && $data['count_replies'] && !$data['filter_comment']) {
            // if counting replies and not a search
        } else {
            $sql .= " AND `reply_to` = '0'";
        }

        if ($data['filter_comment']) {
            $sql .= " AND (";

            $implode = array();

            $search_words = explode(' ', trim(preg_replace('/\s\s+/', ' ', $data['filter_comment'])));

            foreach ($search_words as $search_word) {
                $implode[] = "`comment` LIKE '%" . $this->db->escape($search_word) . "%'";
            }

            if ($implode) {
                $sql .= " " . implode(" OR ", $implode) . "";
            }

            $sql .= ")";
        }

        if ($data['group_by']) {
            $sql .= " GROUP BY " . $this->db->backticks($data['group_by']);
        } else {
            $sql .= " GROUP BY `id`";
        }

        /* Critical rating selected. Adds "`rating` = 0" to stop zero ratings showing first. */
        if ($data['sort'] == 'rating' && $data['order'] == 'asc') {
            $sql .= " ORDER BY `is_sticky` DESC, `rating` = 0, `rating`";
        } else {
            $sql .= " ORDER BY `is_sticky` DESC, " . $this->db->backticks($data['sort']);
        }

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
            $result = $this->db->numRows($query);
        } else {
            $result = $this->db->rows($query);
        }

        if (!$data['filter_comment_id'] && !$data['filter_comment']) { // don't cache permalink and searches
            if ($data['group_by'] == '' && $data['sort'] == 'date_added' && $data['order'] == 'desc' && $data['start'] == 0) { // only cache initial page
                $this->cache->set('getcomments_pageid' . $data['filter_page_id'] . '_count' . (int) $count . '_replies' . (int) $data['count_replies'], $result);
            }
        }

        return $result;
    }

    /* Calculate the difference in days between the current date and the comment date */
    public function calculateDayDifference($date_added)
    {
        $today = date('Y-m-d');

        $yesterday = date('Y-m-d', strtotime('-1 day'));

        $date_added = date('Y-m-d', strtotime($date_added));

        if ($date_added == $today) {
            return 0;
        } else if ($date_added == $yesterday) {
            return 1;
        } else {
            return 2;
        }
    }

    /* Checks if the comment was submitted by a particular IP address */
    public function isCommentByIpAddress($comment_id, $ip_address)
    {
        if ($this->db->numRows($this->db->query("SELECT `ip_address` FROM `" . CMTX_DB_PREFIX . "comments` WHERE `id` = '" . (int) $comment_id . "' AND `ip_address` = '" . $this->db->escape($ip_address) . "'"))) {
            return true;
        } else {
            return false;
        }
    }

    /* Checks if the user has already voted this comment */
    public function hasAlreadyVotedComment($comment_id, $ip_address)
    {
        if ($this->db->numRows($this->db->query("SELECT `ip_address` FROM `" . CMTX_DB_PREFIX . "voters` WHERE `comment_id` = '" . (int) $comment_id . "' AND `ip_address` = '" . $this->db->escape($ip_address) . "'"))) {
            return true;
        } else {
            return false;
        }
    }

    /* Add a vote for this comment */
    public function addVote($comment_id, $type, $ip_address)
    {
        if ($type == 'like') {
            $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "comments` SET `likes` = `likes` + 1 WHERE `id` = '" . (int) $comment_id . "'");
        } else {
            $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "comments` SET `dislikes` = `dislikes` + 1 WHERE `id` = '" . (int) $comment_id . "'");
        }

        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "voters` SET `comment_id` = '" . (int) $comment_id . "', `ip_address` = '" . $this->db->escape($ip_address) . "', `date_added` = NOW()");
    }

    /* Checks if the user has already reported this comment */
    public function hasAlreadyReportedComment($comment_id, $ip_address)
    {
        if ($this->db->numRows($this->db->query("SELECT `ip_address` FROM `" . CMTX_DB_PREFIX . "reporters` WHERE `comment_id` = '" . (int) $comment_id . "' AND `ip_address` = '" . $this->db->escape($ip_address) . "'"))) {
            return true;
        } else {
            return false;
        }
    }

    /* Counts how many reports the user has submitted */
    public function countReportsByIpAddress($ip_address)
    {
        return $this->db->numRows($this->db->query("SELECT `ip_address` FROM `" . CMTX_DB_PREFIX . "reporters` WHERE `ip_address` = '" . $this->db->escape($ip_address) . "'"));
    }

    /* Add a report for this comment */
    public function addReport($comment_id, $ip_address)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "comments` SET `reports` = `reports` + 1 WHERE `id` = '" . (int) $comment_id . "'");

        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "reporters` SET `comment_id` = '" . (int) $comment_id . "', `ip_address` = '" . $this->db->escape($ip_address) . "', `date_added` = NOW()");
    }

    /* Gets the user with the most comments for all pages */
    public function getTopPoster()
    {
        $result = $this->cache->get('gettopposter');

        if ($result !== false) {
            return $result;
        }

        $query = $this->db->query("SELECT `user_id`, COUNT(`user_id`) AS `count` FROM `" . CMTX_DB_PREFIX . "comments` WHERE `is_approved` = '1' GROUP BY `user_id` ORDER BY `count` DESC LIMIT 1");

        $result = $this->db->row($query);

        $this->cache->set('gettopposter', $result['user_id']);

        return $result['user_id'];
    }

    /* Gets the user with the most likes for all pages */
    public function getMostLikes()
    {
        $result = $this->cache->get('getmostlikes');

        if ($result !== false) {
            return $result;
        }

        $query = $this->db->query("SELECT `user_id`, SUM(`likes`) AS `total` FROM `" . CMTX_DB_PREFIX . "comments` WHERE `is_approved` = '1' GROUP BY `user_id` ORDER BY `total` DESC LIMIT 1");

        $result = $this->db->row($query);

        if ($result['total']) {
            $this->cache->set('getmostlikes', $result['user_id']);

            return $result['user_id'];
        } else {
            $this->cache->set('getmostlikes', 0);

            return 0;
        }
    }

    /* Gets the user with the first comment for this page */
    public function getFirstPoster($page_id)
    {
        $result = $this->cache->get('getfirstposter_pageid' . $page_id);

        if ($result !== false) {
            return $result;
        }

        $query = $this->db->query("SELECT `user_id` FROM `" . CMTX_DB_PREFIX . "comments` WHERE `is_approved` = '1' AND `page_id` = '" . (int) $page_id . "' ORDER BY `date_added` ASC LIMIT 1");

        $result = $this->db->row($query);

        $this->cache->set('getfirstposter_pageid' . $page_id, $result['user_id']);

        return $result['user_id'];
    }

    /* Gets the replies to the comment */
    public function getReplies($id)
    {
        $query = $this->db->query("SELECT `id` FROM `" . CMTX_DB_PREFIX . "comments` WHERE `reply_to` = '" . (int) $id . "' AND `is_approved` = '1' ORDER BY `date_added` ASC");

        return $this->db->rows($query);
    }

    /* Convert the textual smilies into spans */
    public function convertSmilies($comment)
    {
        $smiley = $this->loadWord('main/form');

        if ($this->setting->get('enabled_smilies_smile')) {
            $comment = str_ireplace($smiley['lang_tag_smiley_smile'], '<span title="' . $smiley['lang_title_smiley_smile'] . '" class="cmtx_smiley cmtx_smiley_smile"></span>', $comment);
        }

        if ($this->setting->get('enabled_smilies_sad')) {
            $comment = str_ireplace($smiley['lang_tag_smiley_sad'], '<span title="' . $smiley['lang_title_smiley_sad'] . '" class="cmtx_smiley cmtx_smiley_sad"></span>', $comment);
        }

        if ($this->setting->get('enabled_smilies_huh')) {
            $comment = str_ireplace($smiley['lang_tag_smiley_huh'], '<span title="' . $smiley['lang_title_smiley_huh'] . '" class="cmtx_smiley cmtx_smiley_huh"></span>', $comment);
        }

        if ($this->setting->get('enabled_smilies_laugh')) {
            $comment = str_ireplace($smiley['lang_tag_smiley_laugh'], '<span title="' . $smiley['lang_title_smiley_laugh'] . '" class="cmtx_smiley cmtx_smiley_laugh"></span>', $comment);
        }

        if ($this->setting->get('enabled_smilies_mad')) {
            $comment = str_ireplace($smiley['lang_tag_smiley_mad'], '<span title="' . $smiley['lang_title_smiley_mad'] . '" class="cmtx_smiley cmtx_smiley_mad"></span>', $comment);
        }

        if ($this->setting->get('enabled_smilies_tongue')) {
            $comment = str_ireplace($smiley['lang_tag_smiley_tongue'], '<span title="' . $smiley['lang_title_smiley_tongue'] . '" class="cmtx_smiley cmtx_smiley_tongue"></span>', $comment);
        }

        if ($this->setting->get('enabled_smilies_cry')) {
            $comment = str_ireplace($smiley['lang_tag_smiley_cry'], '<span title="' . $smiley['lang_title_smiley_cry'] . '" class="cmtx_smiley cmtx_smiley_cry"></span>', $comment);
        }

        if ($this->setting->get('enabled_smilies_grin')) {
            $comment = str_ireplace($smiley['lang_tag_smiley_grin'], '<span title="' . $smiley['lang_title_smiley_grin'] . '" class="cmtx_smiley cmtx_smiley_grin"></span>', $comment);
        }

        if ($this->setting->get('enabled_smilies_wink')) {
            $comment = str_ireplace($smiley['lang_tag_smiley_wink'], '<span title="' . $smiley['lang_title_smiley_wink'] . '" class="cmtx_smiley cmtx_smiley_wink"></span>', $comment);
        }

        if ($this->setting->get('enabled_smilies_scared')) {
            $comment = str_ireplace($smiley['lang_tag_smiley_scared'], '<span title="' . $smiley['lang_title_smiley_scared'] . '" class="cmtx_smiley cmtx_smiley_scared"></span>', $comment);
        }

        if ($this->setting->get('enabled_smilies_cool')) {
            $comment = str_ireplace($smiley['lang_tag_smiley_cool'], '<span title="' . $smiley['lang_title_smiley_cool'] . '" class="cmtx_smiley cmtx_smiley_cool"></span>', $comment);
        }

        if ($this->setting->get('enabled_smilies_sleep')) {
            $comment = str_ireplace($smiley['lang_tag_smiley_sleep'], '<span title="' . $smiley['lang_title_smiley_sleep'] . '" class="cmtx_smiley cmtx_smiley_sleep"></span>', $comment);
        }

        if ($this->setting->get('enabled_smilies_blush')) {
            $comment = str_ireplace($smiley['lang_tag_smiley_blush'], '<span title="' . $smiley['lang_title_smiley_blush'] . '" class="cmtx_smiley cmtx_smiley_blush"></span>', $comment);
        }

        if ($this->setting->get('enabled_smilies_confused')) {
            $comment = str_ireplace($smiley['lang_tag_smiley_confused'], '<span title="' . $smiley['lang_title_smiley_confused'] . '" class="cmtx_smiley cmtx_smiley_confused"></span>', $comment);
        }

        if ($this->setting->get('enabled_smilies_shocked')) {
            $comment = str_ireplace($smiley['lang_tag_smiley_shocked'], '<span title="' . $smiley['lang_title_smiley_shocked'] . '" class="cmtx_smiley cmtx_smiley_shocked"></span>', $comment);
        }

        return $comment;
    }

    /* Purify the comment. Ensures properly balanced tags and neutralizes attacks. */
    public function purifyComment($comment)
    {
        if (!function_exists('htmLawed')) {
            require_once CMTX_DIR_3RDPARTY . 'htmlawed/htmlawed.php';
        }

        $comment = htmLawed($comment);

        return $comment;
    }
}
