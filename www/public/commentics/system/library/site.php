<?php
namespace Commentics;

class Site
{
    private $db;
    private $page;

    public function __construct($registry)
    {
        $this->db   = $registry->get('db');
        $this->page = $registry->get('page');
    }

    public function siteExists($id)
    {
        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "sites` WHERE `id` = '" . (int) $id . "'"))) {
            return true;
        } else {
            return false;
        }
    }

    public function getSite($id)
    {
        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "sites` WHERE `id` = '" . (int) $id . "'");

        $result = $this->db->row($query);

        return $result;
    }

    public function getSiteIdByCommentId($comment_id)
    {
        $query = $this->db->query("SELECT `s`.`id` AS `site_id`
                                   FROM `" . CMTX_DB_PREFIX . "sites` `s`
                                   LEFT JOIN `" . CMTX_DB_PREFIX . "pages` `p` ON `p`.`site_id` = `s`.`id`
                                   LEFT JOIN `" . CMTX_DB_PREFIX . "comments` `c` ON `c`.`page_id` = `p`.`id`
                                   WHERE `c`.`id` = '" . (int) $comment_id . "'
                                   ");

        if ($this->db->numRows($query)) {
            $result = $this->db->row($query);

            return $result['site_id'];
        } else {
            return false;
        }
    }

    public function deleteSite($id)
    {
        $query = $this->db->query("SELECT `id` FROM `" . CMTX_DB_PREFIX . "pages` WHERE `site_id` = '" . (int) $id . "'");

        $pages = $this->db->rows($query);

        foreach ($pages as $page) {
            $this->page->deletePage($page['id']);
        }

        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "sites` WHERE `id` = '" . (int) $id . "'");

        if ($this->db->affectedRows()) {
            return true;
        } else {
            return false;
        }
    }
}
