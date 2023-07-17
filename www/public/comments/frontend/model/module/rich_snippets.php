<?php
namespace Commentics;

class ModuleRichSnippetsModel extends Model
{
    public function getRichSnippetsProperties()
    {
        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "rich_snippets_properties`");

        $result = $this->db->rows($query);

        return $result;
    }
}
