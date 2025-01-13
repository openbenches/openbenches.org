<?php
namespace Commentics;

class PartOnlineModel extends Model
{
    public function getNumOnline($page_id)
    {
        $query = $this->db->query(" SELECT COUNT(*) AS `count`
                                    FROM `" . CMTX_DB_PREFIX . "viewers`
                                    WHERE `page_id` = '" . (int) $page_id . "'
                                    AND `type` = 'Person'
                                 ");

        $result = $this->db->row($query);

        $online = $result['count'];

        return $online;
    }
}
