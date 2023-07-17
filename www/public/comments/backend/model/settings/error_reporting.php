<?php
namespace Commentics;

class SettingsErrorReportingModel extends Model
{
    public function update($data)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['error_reporting_frontend']) ? 1 : 0) . "' WHERE `title` = 'error_reporting_frontend'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['error_reporting_backend']) ? 1 : 0) . "' WHERE `title` = 'error_reporting_backend'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['error_reporting_method']) . "' WHERE `title` = 'error_reporting_method'");
    }
}
