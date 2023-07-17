<?php
namespace Commentics;

class MainChecklistModel extends Model
{
    public function update()
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '1' WHERE `title` = 'checklist_complete'");
    }
}
