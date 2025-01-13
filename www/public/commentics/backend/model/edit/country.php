<?php
namespace Commentics;

class EditCountryModel extends Model
{
    public function getName($code)
    {
        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "geo` WHERE `country_code` = '" . $this->db->escape($code) . "'");

        $results = $this->db->rows($query);

        $names = array();

        foreach ($results as $result) {
            $names[$result['language']] = $result['name'];
        }

        return $names;
    }

    public function update($data, $id)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "countries` SET `code` = '" . $this->db->escape($data['code']) . "', `top` = '" . (int) $data['top'] . "', `enabled` = '" . (int) $data['enabled'] . "', `date_modified` = NOW() WHERE `id` = '" . (int) $id . "'");

        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "geo` WHERE `country_code` = '" . $this->db->escape($data['code']) . "'");

        foreach ($data['name'] as $key => $value) {
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "geo` SET `name` = '" . $this->db->escape($value) . "', `country_code` = '" . $this->db->escape($data['code']) . "', `language` = '" . $this->db->escape($key) . "', `date_added` = NOW()");
        }
    }
}
