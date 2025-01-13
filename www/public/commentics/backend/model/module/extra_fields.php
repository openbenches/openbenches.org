<?php
namespace Commentics;

class ModuleExtraFieldsModel extends Model
{
    public function getFields($data, $count = false)
    {
        $sql = "SELECT * FROM `" . CMTX_DB_PREFIX . "fields` `f`";

        $sql .= " WHERE 1 = 1";

        if ($data['group_by']) {
            $sql .= " GROUP BY " . $this->db->backticks($data['group_by']);
        }

        $sql .= " ORDER BY " . $this->db->backticks($data['sort']);

        if ($data['order'] == 'asc') {
            $sql .= " ASC";
        } else {
            $sql .= " DESC";
        }

        if (!$count) {
            $sql .= " LIMIT " . (int) $data['start'] . ", " . (int) $data['limit'];
        }

        $query = $this->db->query($sql);

        if ($count) {
            return $this->db->numRows($query);
        } else {
            return $this->db->rows($query);
        }
    }

    public function sortUrl()
    {
        $url = '';

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        if (isset($this->request->get['order'])) {
            if ($this->request->get['order'] == 'desc') {
                $url .= '&order=asc';
            } else {
                $url .= '&order=desc';
            }
        } else {
            $url .= '&order=asc';
        }

        return $url;
    }

    public function paginateUrl()
    {
        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        return $url;
    }

    public function singleDelete($id)
    {
        $order_fields = $this->setting->get('order_fields');

        $order_fields = str_replace('field_' . $id . ',', '', $order_fields);
        $order_fields = str_replace(',field_' . $id, '', $order_fields);

        $order_fields = rtrim($order_fields, ',');

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($order_fields) . "' WHERE `title` = 'order_fields'");

        $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "comments` DROP `field_" . (int) $id . "`");

        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "fields` WHERE `id` = '" . (int) $id . "'");

        if ($this->db->affectedRows()) {
            return true;
        } else {
            return false;
        }
    }

    public function bulkDelete($ids)
    {
        $success = $failure = 0;

        foreach ($ids as $id) {
            if ($this->singleDelete($id)) {
                $success++;
            } else {
                $failure++;
            }
        }

        return array(
            'success' => $success,
            'failure' => $failure
        );
    }

    public function dismiss()
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '0' WHERE `title` = 'notice_extra_fields'");
    }

    public function getPageCookie()
    {
        $sort = $order = '';

        if ($this->cookie->exists('Commentics-Extra-Fields')) {
            $content = $this->cookie->get('Commentics-Extra-Fields');

            $content = explode('|', $content);

            if (isset($content[0]) && in_array($content[0], array('f.name', 'f.type', 'f.is_required', 'f.is_enabled'))) {
                $sort = $content[0];
            }

            if (isset($content[1]) && in_array($content[1], array('asc', 'desc'))) {
                $order = $content[1];
            }
        }

        $page_cookie = array('sort' => $sort, 'order' => $order);

        return $page_cookie;
    }

    public function setPageCookie($sort, $order)
    {
        $this->cookie->set('Commentics-Extra-Fields', $sort . '|' . $order, 60 * 60 * 24 * $this->setting->get('admin_cookie_days') + time());
    }

    public function fieldExists($id)
    {
        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "fields` WHERE `id` = '" . (int) $id . "'"))) {
            return true;
        } else {
            return false;
        }
    }

    public function getField($id)
    {
        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "fields` WHERE `id` = '" . (int) $id . "'");

        $result = $this->db->row($query);

        return $result;
    }

    public function getFieldValue($field_id, $comment_id)
    {
        $query = $this->db->query("SELECT `" . $this->db->escape('field_' . $field_id) . "` FROM `" . CMTX_DB_PREFIX . "comments` WHERE `id` = '" . (int) $comment_id . "'");

        $result = $this->db->row($query);

        return $result['field_' . $field_id];
    }

    public function add($data)
    {
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "fields` SET `name` = '" . $this->db->escape($data['name']) . "', `type` = '" . $this->db->escape($data['type']) . "', `is_required` = '" . (isset($data['is_required']) ? 1 : 0) . "', `values` = '" . $this->db->escape($data['values']) . "', `default` = '" . $this->db->escape($data['default']) . "', `minimum` = '" . (int) $data['minimum'] . "', `maximum` = '" . (int) $data['maximum'] . "', `validation` = '" . $this->db->escape($data['validation']) . "', `display` = '" . (isset($data['display']) ? 1 : 0) . "', `sort` = '" . (int) $data['sort'] . "', `is_enabled` = '" . (isset($data['is_enabled']) ? 1 : 0) . "', `date_modified` = NOW(), `date_added` = NOW()");

        $field_id = $this->db->insertId();

        $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "comments` ADD `field_" . (int) $field_id . "` text AFTER `date_modified`");

        $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "comments` MODIFY `date_modified` datetime NOT NULL AFTER `field_" . (int) $field_id . "`");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($this->setting->get('order_fields') . ',field_' . $field_id) . "' WHERE `title` = 'order_fields'");
    }

    public function update($data, $id)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "fields` SET `name` = '" . $this->db->escape($data['name']) . "', `type` = '" . $this->db->escape($data['type']) . "', `is_required` = '" . (isset($data['is_required']) ? 1 : 0) . "', `values` = '" . $this->db->escape($data['values']) . "', `default` = '" . $this->db->escape($data['default']) . "', `minimum` = '" . (int) $data['minimum'] . "', `maximum` = '" . (int) $data['maximum'] . "', `validation` = '" . $this->db->escape($data['validation']) . "', `display` = '" . (isset($data['display']) ? 1 : 0) . "', `sort` = '" . (int) $data['sort'] . "', `is_enabled` = '" . (isset($data['is_enabled']) ? 1 : 0) . "', `date_modified` = NOW() WHERE `id` = '" . (int) $id . "'");
    }

    public function install()
    {
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "settings` SET `category` = 'module', `title` = 'extra_fields_enabled', `value` = '1'");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . CMTX_DB_PREFIX . "fields` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `name` varchar(250) NOT NULL default '',
            `type` varchar(250) NOT NULL default '',
            `is_required` tinyint(1) unsigned NOT NULL default '0',
            `values` text NOT NULL,
            `default` varchar(250) NOT NULL default '',
            `minimum` int(10) NOT NULL default '0',
            `maximum` int(10) NOT NULL default '250',
            `validation` varchar(250) NOT NULL default '',
            `display` tinyint(1) unsigned NOT NULL default '0',
            `sort` int(10) NOT NULL default '0',
            `is_enabled` tinyint(1) unsigned NOT NULL default '1',
            `date_modified` datetime NOT NULL,
            `date_added` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
    }

    public function uninstall()
    {
        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "settings` WHERE `title` = 'extra_fields_enabled'");

        $query = $this->db->query("SHOW COLUMNS FROM `" . CMTX_DB_PREFIX . "comments` LIKE 'field_%'");

        $results = $this->db->rows($query);

        foreach ($results as $result) {
            $this->db->query("ALTER TABLE `" . CMTX_DB_PREFIX . "comments` DROP `" . $this->db->escape($result['Field']) . "`");
        }

        $this->db->query("DROP TABLE IF EXISTS `" . CMTX_DB_PREFIX . "fields`");

        $order_fields = $this->setting->get('order_fields');

        preg_match_all('/field_[0-9]+,?/', $this->setting->get('order_fields'), $fields);

        foreach ($fields[0] as $field) {
            $order_fields = str_replace($field, '', $order_fields);
        }

        $order_fields = rtrim($order_fields, ',');

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($order_fields) . "' WHERE `title` = 'order_fields'");
    }
}
