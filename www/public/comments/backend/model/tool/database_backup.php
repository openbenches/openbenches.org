<?php
namespace Commentics;

class ToolDatabaseBackupModel extends Model
{
    public function create($description = '')
    {
        $filename = CMTX_DIR_BACKUPS . $this->variable->random(50) . '.sql';

        $query = $this->db->query("SHOW TABLES");

        $result = $this->db->rows($query);

        $tables = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($result));

        $tables = iterator_to_array($tables, false);

        $output = '';

        foreach ($tables as $table) {
            $output .= 'DROP TABLE IF EXISTS `' . $table . '`;' . "\n\n";

            $query = $this->db->query("SHOW CREATE TABLE `" . $table . "`");

            $result = $this->db->row($query);

            $output .= $result['Create Table'] . ';' . "\n\n";

            $query = $this->db->query("SELECT * FROM `" . $table . "`");

            $results = $this->db->rows($query);

            foreach ($results as $result) {
                $columns = '';

                foreach (array_keys($result) as $value) {
                    $columns .= '`' . $value . '`, ';
                }

                $values = '';

                foreach (array_values($result) as $value) {
                    $value = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $value);
                    $value = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $value);
                    $value = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $value);
                    $value = str_replace('\\', '\\\\', $value);
                    $value = str_replace('\'', '\\\'', $value);
                    $value = str_replace('\\\n', '\n', $value);
                    $value = str_replace('\\\r', '\r', $value);
                    $value = str_replace('\\\t', '\t', $value);

                    $values .= '\'' . $value . '\', ';
                }

                $output .= 'INSERT INTO `' . $table . '` (' . preg_replace('/, $/', '', $columns) . ') VALUES (' . preg_replace('/, $/', '', $values) . ');' . "\n";
            }

            if ($results) {
                $output .= "\n";
            }
        }

        $output = rtrim($output, "\n\n");

        $handle = fopen($filename, 'w+');

        fwrite($handle, $output);

        fclose($handle);

        $size = filesize($filename);

        $filename = basename($filename);

        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "backups` SET `description` = '" . $this->db->escape($description) . "', `filename` = '" . $this->db->escape($filename) . "', `size` = '" . (int) $size . "', `date_added` = NOW()");
    }

    public function getBackups($data, $count = false)
    {
        $sql = "SELECT * FROM `" . CMTX_DB_PREFIX . "backups` `b`";

        $sql .= " WHERE 1 = 1";

        if ($data['filter_id']) {
            $sql .= " AND `b`.`id` = '" . (int) $data['filter_id'] . "'";
        }

        if ($data['filter_description']) {
            $sql .= " AND `b`.`description` LIKE '%" . $this->db->escape($data['filter_description']) . "%'";
        }

        if ($data['filter_filename']) {
            $sql .= " AND `b`.`filename` LIKE '%" . $this->db->escape($data['filter_filename']) . "%'";
        }

        if ($data['filter_date']) {
            $sql .= " AND `b`.`date_added` LIKE '%" . $this->db->escape($data['filter_date']) . "%'";
        }

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

    public function deleteOrphans()
    {
        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "backups`");

        $backups = $this->db->rows($query);

        foreach ($backups as $backup) {
            $file = CMTX_DIR_BACKUPS . $backup['filename'];

            if (!file_exists($file)) {
                $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "backups` WHERE `id` = '" . (int) $backup['id'] . "'");
            }
        }
    }

    public function sortUrl()
    {
        $url = '';

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        if (isset($this->request->get['filter_description'])) {
            $url .= '&filter_description=' . $this->url->encode($this->security->decode($this->request->get['filter_description']));
        }

        if (isset($this->request->get['filter_filename'])) {
            $url .= '&filter_filename=' . $this->url->encode($this->security->decode($this->request->get['filter_filename']));
        }

        if (isset($this->request->get['filter_date'])) {
            $url .= '&filter_date=' . $this->url->encode($this->security->decode($this->request->get['filter_date']));
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

        if (isset($this->request->get['filter_description'])) {
            $url .= '&filter_description=' . $this->url->encode($this->security->decode($this->request->get['filter_description']));
        }

        if (isset($this->request->get['filter_filename'])) {
            $url .= '&filter_filename=' . $this->url->encode($this->security->decode($this->request->get['filter_filename']));
        }

        if (isset($this->request->get['filter_date'])) {
            $url .= '&filter_date=' . $this->url->encode($this->security->decode($this->request->get['filter_date']));
        }

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
        if ($this->validation->isAlnum($id) && $this->validation->length($id) == 50 && file_exists(CMTX_DIR_BACKUPS . $id . '.sql') && is_writable(CMTX_DIR_BACKUPS . $id . '.sql')) {
            if (@unlink(CMTX_DIR_BACKUPS . $id . '.sql')) {
                $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "backups` WHERE `filename` = '" . $this->db->escape($id . '.sql') . "'");

                return true;
            } else {
                return false;
            }
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

    public function formatSize($size)
    {
        $i = 0;

        $suffix = array(
            'B',
            'KB',
            'MB',
            'GB',
            'TB',
            'PB',
            'EB',
            'ZB',
            'YB'
        );

        while (($size / 1024) > 1) {
            $size = $size / 1024;

            $i++;
        }

        $size = round(substr($size, 0, strpos($size, '.') + 4), 2) . ' ' . $suffix[$i];

        return $size;
    }

    public function getPageCookie()
    {
        $sort = $order = '';

        if ($this->cookie->exists('Commentics-Tool-Database-Backup')) {
            $content = $this->cookie->get('Commentics-Tool-Database-Backup');

            $content = explode('|', $content);

            if (isset($content[0]) && in_array($content[0], array('b.description', 'b.filename', 'b.date_added'))) {
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
        $this->cookie->set('Commentics-Tool-Database-Backup', $sort . '|' . $order, 60 * 60 * 24 * $this->setting->get('admin_cookie_days') + time());
    }
}
