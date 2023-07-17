<?php
namespace Commentics;

class ModuleRichSnippetsModel extends Model
{
    public function update($data)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (isset($data['rich_snippets_enabled']) ? 1 : 0) . "' WHERE `title` = 'rich_snippets_enabled'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['rich_snippets_type']) . "' WHERE `title` = 'rich_snippets_type'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['rich_snippets_other']) . "' WHERE `title` = 'rich_snippets_other'");

        $this->db->query("TRUNCATE TABLE `" . CMTX_DB_PREFIX . "rich_snippets_properties`");

        if (isset($data['rich_snippets_property'])) {
            foreach ($data['rich_snippets_property'] as $property) {
                $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "rich_snippets_properties` SET `name` = '" . $this->db->escape($property['name']) . "', `value` = '" . $this->db->escape($property['value']) . "'");
            }
        }
    }

    public function getRichSnippetsProperties()
    {
        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "rich_snippets_properties`");

        $result = $this->db->rows($query);

        return $result;
    }

    public function install()
    {
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'module', `title` = 'rich_snippets_enabled', `value` = '0'");

        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'module', `title` = 'rich_snippets_type', `value` = 'Brand'");

        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'module', `title` = 'rich_snippets_other', `value` = ''");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . CMTX_DB_PREFIX . "rich_snippets_properties` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `name` varchar(250) NOT NULL default '',
            `value` varchar(250) NOT NULL default '',
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
    }

    public function uninstall()
    {
        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'rich_snippets_enabled'");

        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'rich_snippets_type'");

        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'rich_snippets_other'");

        $this->db->query("DROP TABLE IF EXISTS `" . CMTX_DB_PREFIX . "rich_snippets_properties`");
    }
}
