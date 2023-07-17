<?php
namespace Commentics;

class MainUpgrade1Model extends Model
{
    public function getInstalledVersion()
    {
        $query = $this->db->query("SELECT `version` FROM `" . CMTX_DB_PREFIX . "version` ORDER BY `date_added` DESC LIMIT 1");

        $result = $this->db->row($query);

        return $result['version'];
    }
}
