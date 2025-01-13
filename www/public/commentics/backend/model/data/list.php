<?php
namespace Commentics;

class DataListModel extends Model
{
    public function getList($type)
    {
        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "data` WHERE `type` = '" . $this->db->escape($type) . "'");

        $result = $this->db->row($query);

        return $result;
    }

    public function update($data, $username, $type)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "data` SET `text` = '" . $this->db->escape($data['text']) . "', `modified_by` = '" . $this->db->escape($username) . "', `date_modified` = NOW() WHERE `type` = '" . $this->db->escape($type) . "'");
    }
}
