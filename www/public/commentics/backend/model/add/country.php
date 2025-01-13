<?php
namespace Commentics;

class AddCountryModel extends Model
{
    public function add($data)
    {
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "countries` SET `code` = '" . $this->db->escape($data['code']) . "', `top` = '" . (int) $data['top'] . "', `enabled` = '" . (int) $data['enabled'] . "', `date_modified` = NOW(), `date_added` = NOW()");

        foreach ($data['name'] as $key => $value) {
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = '" . $this->db->escape($value) . "', `country_code` = '" . $this->db->escape($data['code']) . "', `language` = '" . $this->db->escape($key) . "', `date_added` = NOW()");
        }
    }
}
