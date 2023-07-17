<?php
namespace Commentics;

class PartAverageRatingModel extends Model
{
    public function getAverageRating($page_id)
    {
        $result = $this->cache->get('getaveragerating_pageid' . $page_id);

        if ($result !== false) {
            return $result;
        }

        $query = $this->db->query(" SELECT AVG(`rating`) AS `average`, COUNT(*) AS `count`
                                    FROM (
                                    SELECT `rating` FROM `" . CMTX_DB_PREFIX . "comments` WHERE `is_approved` = '1' AND `rating` != '0' AND `page_id` = '" . (int) $page_id . "'
                                    UNION ALL
                                    SELECT `rating` FROM `" . CMTX_DB_PREFIX . "ratings` WHERE `page_id` = '" . (int) $page_id . "'
                                    )
                                    AS `average`
                                 ");

        $result = $this->db->row($query);

        if (is_null($result['average'])) {
            $result['average'] = 0;
        }

        $average = round($result['average'], 0, PHP_ROUND_HALF_UP);

        $total = $result['count'];

        $result = array(
            'average' => $average,
            'total'   => $total
        );

        $this->cache->set('getaveragerating_pageid' . $page_id, $result);

        return $result;
    }

    public function hasAlreadyRatedPage($page_id, $ip_address)
    {
        if ($this->db->numRows($this->db->query("SELECT `id` FROM `" . CMTX_DB_PREFIX . "comments` WHERE `page_id` = '" . (int) $page_id . "' AND `ip_address` = '" . $this->db->escape($ip_address) . "' AND `rating` != '0'"))) {
            return true;
        }

        if ($this->db->numRows($this->db->query("SELECT `id` FROM `" . CMTX_DB_PREFIX . "ratings` WHERE `page_id` = '" . (int) $page_id . "' AND `ip_address` = '" . $this->db->escape($ip_address) . "'"))) {
            return true;
        }

        return false;
    }

    public function addRating($page_id, $rating, $ip_address)
    {
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "ratings` SET `page_id` = '" . (int) $page_id . "', `rating` = '" . (int) $rating . "', `ip_address` = '" . $this->db->escape($ip_address) . "', `date_added` = NOW()");
    }
}
