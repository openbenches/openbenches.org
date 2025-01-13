<?php
namespace Commentics;

class EditPageModel extends Model
{
    public function identifierExists($identifier, $id = 0)
    {
        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "pages` WHERE `identifier` = '" . $this->db->escape($identifier) . "' AND `id` != '" . (int) $id . "'"))) {
            return true;
        } else {
            return false;
        }
    }

    public function update($data, $id)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "pages` SET `identifier` = '" . $this->db->escape($data['identifier']) . "', `reference` = '" . $this->db->escape($data['reference']) . "', `url` = '" . $this->db->escape($data['url']) . "', `moderate` = '" . $this->db->escape($data['moderate']) . "', `is_form_enabled` = '" . (isset($data['is_form_enabled']) ? 1 : 0) . "', `date_modified` = NOW() WHERE `id` = '" . (int) $id . "'");
    }
}
