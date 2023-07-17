<?php
namespace Commentics;

class CommonAdministratorModel extends Model
{
    public function adminExists($id)
    {
        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "admins` WHERE `id` = '" . (int) $id . "'"))) {
            return true;
        } else {
            return false;
        }
    }

    public function getAdmin($id)
    {
        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "admins` WHERE `id` = '" . (int) $id . "'");

        $result = $this->db->row($query);

        return $result;
    }

    public function getAdmins()
    {
        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "admins`");

        $results = $this->db->rows($query);

        return $results;
    }

    public function usernameExists($username, $id = 0)
    {
        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "admins` WHERE `username` = '" . $this->db->escape($username) . "' AND `id` != '" . (int) $id . "'"))) {
            return true;
        } else {
            return false;
        }
    }

    public function getAdminByUsername($username)
    {
        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "admins` WHERE `username` = '" . $this->db->escape($username) . "'");

        $result = $this->db->row($query);

        return $result;
    }

    public function emailExists($email, $id = 0)
    {
        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "admins` WHERE `email` = '" . $this->db->escape($email) . "' AND `id` != '" . (int) $id . "'"))) {
            return true;
        } else {
            return false;
        }
    }

    public function getAdminByEmail($email)
    {
        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "admins` WHERE `email` = '" . $this->db->escape($email) . "'");

        $result = $this->db->row($query);

        return $result;
    }

    public function getRestrictions($id = '')
    {
        if ($id) {
            $admin = $this->getAdmin($id);
        } else {
            $admin = '';
        }

        if (isset($this->request->post['viewable_pages'])) {
            $viewable_pages = implode(',', $this->request->post['viewable_pages']);
        } else if ($admin) {
            $viewable_pages = $admin['viewable_pages'];
        } else {
            $viewable_pages = '';
        }

        if (isset($this->request->post['modifiable_pages'])) {
            $modifiable_pages = implode(',', $this->request->post['modifiable_pages']);
        } else if ($admin) {
            $modifiable_pages = $admin['modifiable_pages'];
        } else {
            $modifiable_pages = '';
        }

        $viewable_pages = explode(',', $viewable_pages);

        $modifiable_pages = explode(',', $modifiable_pages);

        $restrictions = array();

        $restrictions[] = $this->getRestriction('manage', 0, true, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('manage/admins', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('add/admin', 40, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('edit/admin', 40, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('manage/bans', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('add/ban', 40, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('edit/ban', 40, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('manage/comments', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('edit/comment', 40, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('edit/spam', 40, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('manage/countries', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('add/country', 40, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('edit/country', 40, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('manage/pages', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('edit/page', 40, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('manage/questions', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('add/question', 40, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('edit/question', 40, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('manage/sites', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('add/site', 40, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('edit/site', 40, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('manage/states', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('add/state', 40, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('edit/state', 40, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('manage/subscriptions', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('edit/subscription', 40, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('manage/users', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('edit/user', 40, false, $viewable_pages, $modifiable_pages);

        $restrictions[] = $this->getRestriction('extensions', 0, true, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('extension/installer', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('extension/languages', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('extension/modules', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('module/', 40, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('extension/themes', 20, false, $viewable_pages, $modifiable_pages);

        $restrictions[] = $this->getRestriction('settings', 0, true, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('settings/administrator', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('settings/approval', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('settings/cache', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('settings/email', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('settings/email_editor', 40, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('settings/email_setup', 40, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('settings/error_reporting', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('settings/flooding', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('settings/layout', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('settings/layout_comments', 40, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('settings/layout_form', 40, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('settings/licence', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('settings/maintenance', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('settings/processor', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('data/list', 40, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('settings/security', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('settings/system', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('settings/viewers', 20, false, $viewable_pages, $modifiable_pages);

        $restrictions[] = $this->getRestriction('tasks', 0, true, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('task/delete_bans', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('task/delete_comments', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('task/delete_reporters', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('task/delete_subscriptions', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('task/delete_voters', 20, false, $viewable_pages, $modifiable_pages);

        $restrictions[] = $this->getRestriction('reports', 0, true, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('report/access', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('report/errors', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('report/permissions', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('report/phpinfo', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('report/version', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('report/version_check', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('report/viewers', 20, false, $viewable_pages, $modifiable_pages);

        $restrictions[] = $this->getRestriction('tools', 0, true, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('tool/clear_cache', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('tool/database_backup', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('tool/export_import', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('tool/optimize_tables', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('tool/text_finder', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('tool/upgrade', 20, false, $viewable_pages, $modifiable_pages);

        $restrictions[] = $this->getRestriction('help', 0, true, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('help/faq', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('help/forum', 20, false, $viewable_pages, $modifiable_pages);
        $restrictions[] = $this->getRestriction('help/private', 20, false, $viewable_pages, $modifiable_pages);

        return $restrictions;
    }

    private function getRestriction($page, $indent, $is_top, $viewable_pages, $modifiable_pages)
    {
        $lang = $this->loadWord('common/administrator');

        if (in_array($page, $viewable_pages)) {
            $is_viewable = true;
        } else {
            $is_viewable = false;
        }

        if (in_array($page, $modifiable_pages)) {
            $is_modifiable = true;
        } else {
            $is_modifiable = false;
        }

        if (array_key_exists('lang_' . $page, $lang)) {
            $title = $lang['lang_' . $page];
        } else {
            $title = 'Undefined';
        }

        $restriction = array(
            'title'         => $title,
            'page'          => $page,
            'indent'        => $indent,
            'is_top'        => $is_top,
            'is_viewable'   => $is_viewable,
            'is_modifiable' => $is_modifiable
        );

        return $restriction;
    }
}
