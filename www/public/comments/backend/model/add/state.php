<?php
namespace Commentics;

class AddStateModel extends Model
{
    public function add($data)
    {
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->db->escape($data['name']) . "', `country_code` = '" . $this->db->escape($data['country_code']) . "', `enabled` = '" . (int) $data['enabled'] . "', `date_modified` = NOW(), `date_added` = NOW()");
    }
}
