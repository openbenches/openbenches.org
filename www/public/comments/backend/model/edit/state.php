<?php
namespace Commentics;

class EditStateModel extends Model
{
    public function update($data, $id)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "states` SET `name` = '" . $this->db->escape($data['name']) . "', `country_code` = '" . $this->db->escape($data['country_code']) . "', `enabled` = '" . (int) $data['enabled'] . "', `date_modified` = NOW() WHERE `id` = '" . (int) $id . "'");
    }
}
