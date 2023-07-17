<?php
namespace Commentics;

class PartRssModel extends Model
{
    public function getComments($page_id, $limit)
    {
        if ($limit) {
            $query = $this->db->query("SELECT `id` FROM `" . CMTX_DB_PREFIX . "comments` WHERE `is_approved` = '1' AND `page_id` = '" . (int) $page_id . "' ORDER BY `date_added` DESC LIMIT " . (int) $limit);
        } else {
            $query = $this->db->query("SELECT `id` FROM `" . CMTX_DB_PREFIX . "comments` WHERE `is_approved` = '1' AND `page_id` = '" . (int) $page_id . "' ORDER BY `date_added` DESC");
        }

        $results = $this->db->rows($query);

        $comments = array();

        foreach ($results as $result) {
            $comments[$result['id']] = $this->comment->getComment($result['id']);
        }

        return $comments;
    }
}
