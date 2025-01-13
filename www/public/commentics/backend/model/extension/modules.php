<?php
namespace Commentics;

class ExtensionModulesModel extends Model
{
    public function getInstalled()
    {
        $query = $this->db->query("SELECT `module` FROM `" . CMTX_DB_PREFIX . "modules`");

        $results = $this->db->rows($query);

        $installed = array();

        foreach ($results as $result) {
            $installed[] = $result['module'];
        }

        return $installed;
    }

    public function getFiles()
    {
        $files = glob(CMTX_DIR_CONTROLLER . 'module/*.php');

        return $files;
    }

    public function install($data)
    {
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "modules` SET `module` = '" . $this->db->escape($data['module']) . "', `date_added` = NOW()");
    }

    public function uninstall($data)
    {
        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "modules` WHERE `module` = '" . $this->db->escape($data['module']) . "'");
    }
}
