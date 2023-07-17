<?php
namespace Commentics;

class Comment
{
    private $cache;
    private $db;
    private $session;
    private $setting;
    private $parents = array();
    private $replies = array();

    public function __construct($registry)
    {
        $this->cache   = $registry->get('cache');
        $this->db      = $registry->get('db');
        $this->session = $registry->get('session');
        $this->setting = $registry->get('setting');
    }

    public function createComment($user_id, $page_id, $website, $town, $state_id, $country_id, $rating, $reply_to, $headline, $original_comment, $comment, $ip_address, $approve, $notes, $is_admin, $uploads, $extra_fields)
    {
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "comments` SET `user_id` = '" . (int) $user_id . "', `page_id` = '" . (int) $page_id . "', `website` = '" . $this->db->escape($website) . "', `town` = '" . $this->db->escape($town) . "', `state_id` = '" . (int) $state_id . "', `country_id` = '" . (int) $country_id . "', `rating` = '" . (int) $rating . "', `reply_to` = '" . (int) $reply_to . "', `headline` = '" . $this->db->escape($headline) . "', `original_comment` = '" . $this->db->escape($original_comment) . "', `comment` = '" . $this->db->escape($comment) . "', `reply` = '', `ip_address` = '" . $this->db->escape($ip_address) . "', `is_approved` = '" . ($approve ? 0 : 1) . "', `notes` = '" . $this->db->escape($notes) . "', `is_admin` = '" . (int) $is_admin . "', `is_sent` = '0', `sent_to` = '0', `likes` = '0', `dislikes` = '0', `reports` = '0', `is_sticky` = '0', `is_locked` = '0', `is_verified` = '0', `session_id` = '" . $this->db->escape($this->session->getId()) . "', `date_modified` = NOW(), `date_added` = NOW()");

        $comment_id = $this->db->insertId();

        foreach ($uploads as $upload) {
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "uploads` SET `user_id` = '" . (int) $user_id . "', `comment_id` = '" . (int) $comment_id . "', `folder` = '" . $this->db->escape($upload['folder']) . "', `filename` = '" . $this->db->escape($upload['filename']) . "', `extension` = '" . $this->db->escape($upload['extension']) . "', `mime_type` = '" . $this->db->escape($upload['mime_type']) . "', `file_size` = '" . $this->db->escape($upload['file_size']) . "', `date_added` = NOW()");
        }

        foreach ($extra_fields as $key => $value) {
            $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "comments` SET `" . $this->db->escape($key) . "` = '" . $this->db->escape($value) . "' WHERE `id` = '" . (int) $comment_id . "'");
        }

        return $comment_id;
    }

    public function editComment($comment_id, $original_comment, $comment, $approve, $notes)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "comments` SET `original_comment` = '" . $this->db->escape($original_comment) . "', `comment` = '" . $this->db->escape($comment) . "', `is_approved` = '" . ($approve ? 0 : 1) . "', `notes` = '" . $this->db->escape($notes) . "', `number_edits` = `number_edits` + 1 WHERE `id` = '" . (int) $comment_id . "'");
    }

    public function commentExists($id)
    {
        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "comments` WHERE `id` = '" . (int) $id . "'"))) {
            return true;
        } else {
            return false;
        }
    }

    public function getComment($id)
    {
        $query = $this->db->query("SELECT `p`.*, `u`.*, `u`.`date_added` AS `date_added_user`, `c`.*, `states`.`name` AS `state_name`, `g`.`name` AS `country_name`
                                   FROM `" . CMTX_DB_PREFIX . "comments` `c`
                                   RIGHT JOIN `" . CMTX_DB_PREFIX . "pages` `p` ON `c`.`page_id` = `p`.`id`
                                   RIGHT JOIN `" . CMTX_DB_PREFIX . "users` `u` ON `c`.`user_id` = `u`.`id`
                                   LEFT JOIN `" . CMTX_DB_PREFIX . "states` `states` ON `c`.`state_id` = `states`.`id`
                                   LEFT JOIN `" . CMTX_DB_PREFIX . "countries` `countries` ON `c`.`country_id` = `countries`.`id`
                                   LEFT JOIN `" . CMTX_DB_PREFIX . "geo` `g` ON `g`.`country_code` = `countries`.`code`
                                   WHERE `c`.`id` = '" . (int) $id . "'
                                   AND (`g`.`language` IS NULL OR `g`.`language` = '" . $this->db->escape($this->setting->get('language')) . "')
                                   ");

        if ($this->db->numRows($query)) {
            $comment = $this->db->row($query);

            $uploads = $this->getUploads($id);

            $result = array(
                'id'               => $comment['id'],
                'user_id'          => $comment['user_id'],
                'page_id'          => $comment['page_id'],
                'name'             => $comment['name'],
                'email'            => $comment['email'],
                'page_reference'   => $comment['reference'],
                'page_url'         => $comment['url'],
                'website'          => $comment['website'],
                'town'             => $comment['town'],
                'state_id'         => $comment['state_id'],
                'state'            => $comment['state_name'],
                'country_id'       => $comment['country_id'],
                'country'          => $comment['country_name'],
                'rating'           => $comment['rating'],
                'reply_to'         => $comment['reply_to'],
                'headline'         => $comment['headline'],
                'original_comment' => $comment['original_comment'],
                'comment'          => $comment['comment'],
                'reply'            => $comment['reply'],
                'ip_address'       => $comment['ip_address'],
                'is_approved'      => $comment['is_approved'],
                'notes'            => $comment['notes'],
                'is_admin'         => $comment['is_admin'],
                'is_sent'          => $comment['is_sent'],
                'sent_to'          => $comment['sent_to'],
                'likes'            => $comment['likes'],
                'dislikes'         => $comment['dislikes'],
                'reports'          => $comment['reports'],
                'is_sticky'        => $comment['is_sticky'],
                'is_locked'        => $comment['is_locked'],
                'is_verified'      => $comment['is_verified'],
                'number_edits'     => $comment['number_edits'],
                'session_id'       => $comment['session_id'],
                'date_modified'    => $comment['date_modified'],
                'date_added'       => $comment['date_added'],
                'token'            => $comment['token'],
                'to_all'           => $comment['to_all'],
                'to_admin'         => $comment['to_admin'],
                'to_reply'         => $comment['to_reply'],
                'to_approve'       => $comment['to_approve'],
                'format'           => $comment['format'],
                'date_added_user'  => $comment['date_added_user'],
                'uploads'          => $uploads
            );

            $result = $this->addExtraFields($result, $comment);

            return $result;
        } else {
            return false;
        }
    }

    public function getPageIdByCommentId($id)
    {
        $query = $this->db->query("SELECT `page_id` FROM `" . CMTX_DB_PREFIX . "comments` WHERE `id` = '" . (int) $id . "'");

        $result = $this->db->row($query);

        $page_id = $result['page_id'];

        return $page_id;
    }

    public function getComments($sort = 'id', $order = 'ASC')
    {
        $query = $this->db->query("SELECT `id` FROM `" . CMTX_DB_PREFIX . "comments` ORDER BY `" . $sort . "` " . $order);

        $results = $this->db->rows($query);

        $comments = array();

        foreach ($results as $result) {
            $comments[$result['id']] = $this->getComment($result['id']);
        }

        return $comments;
    }

    public function deleteComment($id)
    {
        if ($this->setting->get('flood_control_delay_enabled') || $this->setting->get('flood_control_maximum_enabled')) {
            $this->moveDeleted($id);
        }

        $this->deleteReplies($id);

        $this->deleteUploads($id);

        $this->deleteCache($id);

        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "reporters` WHERE `comment_id` = '" . (int) $id . "'");

        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "voters` WHERE `comment_id` = '" . (int) $id . "'");

        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "comments` WHERE `id` = '" . (int) $id . "'");

        if ($this->db->affectedRows()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteCache($id)
    {
        if ($this->setting->get('cache_type')) {
            $page_id = $this->getPageIdByCommentId($id);

            /* Clear the cache for the number of comments on the page */
            $this->cache->delete('getcomments_pageid' . $page_id . '_*');

            /* If the comment is a reply, we need to clear the cache of the parent comments */
            $parent_ids = $this->getParents($id);

            foreach ($parent_ids as $parent_id) {
                $this->cache->delete('getcomment_commentid' . $parent_id . '_*');
            }

            /* Clear the cache of the comment */
            $this->cache->delete('getcomment_commentid' . $id . '_*');
        }
    }

    public function isApproved($id)
    {
        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "comments` WHERE `id` = '" . (int) $id . "' AND `is_approved` = '1'"))) {
            return true;
        } else {
            return false;
        }
    }

    public function approveComment($id)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "comments` SET `is_approved` = '1', `date_modified` = NOW() WHERE `id` = '" . (int) $id . "'");
    }

    public function unapproveComment($id)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "comments` SET `is_approved` = '0', `date_modified` = NOW() WHERE `id` = '" . (int) $id . "'");

        $replies = $this->getReplies($id);

        foreach ($replies as $id) {
            $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "comments` SET `is_approved` = '0', `date_modified` = NOW() WHERE `id` = '" . (int) $id . "'");
        }
    }

    public function getParents($id)
    {
        $query = $this->db->query("SELECT `reply_to` FROM `" . CMTX_DB_PREFIX . "comments` WHERE `id` = '" . (int) $id . "'");

        $result = $this->db->row($query);

        if ($result['reply_to']) {
            $this->parents[] = $result['reply_to'];

            $this->getParents($result['reply_to']);
        }

        return $this->parents;
    }

    public function getReplies($id)
    {
        $query = $this->db->query("SELECT `id` FROM `" . CMTX_DB_PREFIX . "comments` WHERE `reply_to` = '" . (int) $id . "'");

        $results = $this->db->rows($query);

        foreach ($results as $result) {
            $this->replies[] = $result['id'];

            $this->getReplies($result['id']);
        }

        return $this->replies;
    }

    public function getTopParent($id)
    {
        $query = $this->db->query("SELECT `reply_to` FROM `" . CMTX_DB_PREFIX . "comments` WHERE `id` = '" . (int) $id . "'");

        $result = $this->db->row($query);

        if ($result['reply_to']) {
            $this->getTopParent($result['reply_to']);
        } else {
            return $id;
        }
    }

    public function buildCommentUrl($id, $url)
    {
        if (strstr($url, '?') && strstr($url, '=')) {
            $url .= '&cmtx_perm=' . $id . '#cmtx_perm_' . $id;
        } else {
            $url .= '?cmtx_perm=' . $id . '#cmtx_perm_' . $id;
        }

        return $url;
    }

    public function deleteUpload($upload_id)
    {
        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "uploads` WHERE `id` = '" . (int) $upload_id . "'");

        $result = $this->db->row($query);

        if ($result) {
            $location = CMTX_DIR_UPLOAD . $result['folder'] . '/' . $result['filename'] . '.' . $result['extension'];

            if (file_exists($location)) {
                @unlink($location);
            }
        }

        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "uploads` WHERE `id` = '" . (int) $upload_id . "'");
    }

    private function moveDeleted($id)
    {
        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "comments` WHERE `id` = '" . (int) $id . "'");

        $result = $this->db->row($query);

        if ($result) {
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "deleted` SET `user_id` = '" . (int) $result['user_id'] . "', `comment_id` = '" . (int) $id . "', `page_id` = '" . (int) $result['page_id'] . "', `ip_address` = '" . $this->db->escape($result['ip_address']) . "', `date_added` = NOW()");
        }
    }

    private function deleteReplies($id)
    {
        $replies = $this->getReplies($id);

        foreach ($replies as $id) {
            $this->deleteUploads($id);

            if ($this->setting->get('flood_control_delay_enabled') || $this->setting->get('flood_control_maximum_enabled')) {
                $this->moveDeleted($id);
            }

            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "reporters` WHERE `comment_id` = '" . (int) $id . "'");

            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "voters` WHERE `comment_id` = '" . (int) $id . "'");

            $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "comments` WHERE `id` = '" . (int) $id . "'");
        }
    }

    private function getUploads($comment_id)
    {
        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "uploads` WHERE `comment_id` = '" . (int) $comment_id . "' ORDER BY `date_added` DESC");

        $results = $this->db->rows($query);

        return $results;
    }

    private function deleteUploads($comment_id)
    {
        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "uploads` WHERE `comment_id` = '" . (int) $comment_id . "'");

        $results = $this->db->rows($query);

        foreach ($results as $result) {
            $location = CMTX_DIR_UPLOAD . $result['folder'] . '/' . $result['filename'] . '.' . $result['extension'];

            if (file_exists($location)) {
                @unlink($location);
            }
        }

        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "uploads` WHERE `comment_id` = '" . (int) $comment_id . "'");
    }

    private function addExtraFields($result, $comment)
    {
        $extra_fields = array();

        if ($this->setting->has('extra_fields_enabled') && $this->setting->get('extra_fields_enabled')) {
            foreach ($comment as $key => $value) {
                if (strpos($key, 'field_') === 0) {
                    $result[$key] = $value;
                }
            }

            $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "fields` WHERE `is_enabled` = '1' AND `display` = '1' ORDER BY `sort` ASC");

            $fields = $this->db->rows($query);

            foreach ($fields as $field) {
                $query = $this->db->query("SELECT `" . $this->db->escape('field_' . $field['id']) . "` FROM `" . CMTX_DB_PREFIX . "comments` WHERE `id` = '" . (int) $comment['id'] . "'");

                $result2 = $this->db->row($query);

                if ($result2['field_' . $field['id']]) {
                    $extra_fields[$field['name']] = $result2['field_' . $field['id']];
                }
            }
        }

        $result['extra_fields'] = $extra_fields;

        return $result;
    }
}
