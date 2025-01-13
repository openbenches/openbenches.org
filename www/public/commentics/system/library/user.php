<?php
namespace Commentics;

class User
{
    private $comment;
    private $cookie;
    private $db;
    private $email;
    private $page;
    private $request;
    private $security;
    private $session;
    private $setting;
    private $url;
    private $validation;
    private $variable;
    private $is_admin = false;
    private $login = array();

    public function __construct($registry)
    {
        $this->comment    = $registry->get('comment');
        $this->cookie     = $registry->get('cookie');
        $this->db         = $registry->get('db');
        $this->email      = $registry->get('email');
        $this->page       = $registry->get('page');
        $this->request    = $registry->get('request');
        $this->security   = $registry->get('security');
        $this->session    = $registry->get('session');
        $this->setting    = $registry->get('setting');
        $this->url        = $registry->get('url');
        $this->validation = $registry->get('validation');
        $this->variable   = $registry->get('variable');

        if (defined('CMTX_FRONTEND')) {
            $this->login['name']    = (defined('CMTX_NAME')) ? $this->security->encode(CMTX_NAME) : '';
            $this->login['email']   = (defined('CMTX_EMAIL')) ? $this->security->encode(CMTX_EMAIL) : '';
            $this->login['website'] = (defined('CMTX_WEBSITE')) ? $this->security->encode(CMTX_WEBSITE) : '';
            $this->login['town']    = (defined('CMTX_TOWN')) ? $this->security->encode(CMTX_TOWN) : '';
            $this->login['country'] = (defined('CMTX_COUNTRY')) ? $this->security->encode(CMTX_COUNTRY) : '';
            $this->login['state']   = (defined('CMTX_STATE')) ? $this->security->encode(CMTX_STATE) : '';
            $this->login['avatar']  = (defined('CMTX_AVATAR')) ? $this->security->encode(CMTX_AVATAR) : '';

            $this->is_admin = $this->isAdministrator();

            /* Update avatar provided by login information */
            if ($this->setting->get('avatar_type') == 'login' && $this->login['avatar'] && $this->validation->isUrl($this->login['avatar']) && $this->login['name'] && $this->login['email']) {
                $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "users` SET `avatar_login` = '" . $this->db->escape($this->login['avatar']) . "' WHERE `name` = '" . $this->db->escape($this->login['name']) . "' AND `email` = '" . $this->db->escape($this->login['email']) . "'");
            }
        }
    }

    public function createUser($name, $email, $token, $ip_address)
    {
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "users` SET `name` = '" . $this->db->escape($name) . "', `email` = '" . $this->db->escape($email) . "', `moderate` = 'default', `token` = '" . $this->db->escape($token) . "', `to_all` = '" . ($this->setting->get('notify_type') == 'all' ? 1 : 0) . "', `to_admin` = '1', `to_reply` = '1', `to_approve` = '1', `format` = '" . $this->db->escape($this->setting->get('notify_format')) . "', `ip_address` = '" . $this->db->escape($ip_address) . "', `date_modified` = NOW(), `date_added` = NOW()");

        return $this->db->insertId();
    }

    public function createToken()
    {
        do {
            $token = $this->variable->random(50);

            $user_token_exists = $this->db->query("SELECT `token` FROM `" . CMTX_DB_PREFIX . "users` WHERE `token` = '" . $this->db->escape($token) . "'");

            $subscription_token_exists = $this->db->query("SELECT `token` FROM `" . CMTX_DB_PREFIX . "subscriptions` WHERE `token` = '" . $this->db->escape($token) . "'");
        } while ($this->db->numRows($user_token_exists) > 0 || $this->db->numRows($subscription_token_exists) > 0);

        return $token;
    }

    public function userExists($id)
    {
        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "users` WHERE `id` = '" . (int) $id . "'"))) {
            return true;
        } else {
            return false;
        }
    }

    public function getUserByCommentId($comment_id)
    {
        $query = $this->db->query("SELECT `user_id` FROM `" . CMTX_DB_PREFIX . "comments` WHERE `id` = '" . (int) $comment_id . "' LIMIT 1");

        $result = $this->db->row($query);

        if ($result) {
            return $this->getUser($result['user_id']);
        } else {
            return false;
        }
    }

    public function getUserByNameAndEmail($name, $email)
    {
        $query = $this->db->query("SELECT `id` FROM `" . CMTX_DB_PREFIX . "users` WHERE `name` = '" . $this->db->escape($name) . "' AND `email` = '" . $this->db->escape($email) . "'");

        $result = $this->db->row($query);

        if ($result) {
            return $this->getUser($result['id']);
        } else {
            return false;
        }
    }

    public function getUserByNameAndNoEmail($name)
    {
        $query = $this->db->query("SELECT `id` FROM `" . CMTX_DB_PREFIX . "users` WHERE `name` = '" . $this->db->escape($name) . "' AND `email` = ''");

        $result = $this->db->row($query);

        if ($result) {
            return $this->getUser($result['id']);
        } else {
            return false;
        }
    }

    public function getUserByToken($token)
    {
        $query = $this->db->query("SELECT `id` FROM `" . CMTX_DB_PREFIX . "users` WHERE `token` = '" . $this->db->escape($token) . "'");

        $result = $this->db->row($query);

        if ($result) {
            return $this->getUser($result['id']);
        } else {
            return false;
        }
    }

    public function userExistsByName($name)
    {
        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "users` WHERE `name` = '" . $this->db->escape($name) . "'"))) {
            return true;
        } else {
            return false;
        }
    }

    public function userExistsByEmail($email)
    {
        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "users` WHERE `email` = '" . $this->db->escape($email) . "'"))) {
            return true;
        } else {
            return false;
        }
    }

    public function getUser($id)
    {
        $query = $this->db->query("SELECT `u`.*,
                                   (SELECT COUNT(`id`) FROM `" . CMTX_DB_PREFIX . "subscriptions` `s` WHERE `s`.`user_id` = `u`.`id`) AS `subscriptions`,
                                   (SELECT COUNT(`id`) FROM `" . CMTX_DB_PREFIX . "comments` `c` WHERE `c`.`user_id` = `u`.`id`) AS `comments`
                                   FROM `" . CMTX_DB_PREFIX . "users` `u`
                                   WHERE `u`.`id` = '" . (int) $id . "'");

        if ($this->db->numRows($query)) {
            $user = $this->db->row($query);

            return array(
                'id'                 => $user['id'],
                'avatar_id'          => $user['avatar_id'],
                'avatar_pending_id'  => $user['avatar_pending_id'],
                'avatar_selected'    => $user['avatar_selected'],
                'avatar_login'       => $user['avatar_login'],
                'name'               => $user['name'],
                'email'              => $user['email'],
                'moderate'           => $user['moderate'],
                'token'              => $user['token'],
                'to_all'             => $user['to_all'],
                'to_admin'           => $user['to_admin'],
                'to_reply'           => $user['to_reply'],
                'to_approve'         => $user['to_approve'],
                'format'             => $user['format'],
                'ip_address'         => $user['ip_address'],
                'comments'           => $user['comments'],
                'subscriptions'      => $user['subscriptions'],
                'date_modified'      => $user['date_modified'],
                'date_added'         => $user['date_added']
            );
        } else {
            return false;
        }
    }

    public function getUsers($sort = 'id', $order = 'ASC')
    {
        $query = $this->db->query("SELECT `id` FROM `" . CMTX_DB_PREFIX . "users` ORDER BY `" . $sort . "` " . $order);

        $results = $this->db->rows($query);

        $users = array();

        foreach ($results as $result) {
            $users[$result['id']] = $this->getUser($result['id']);
        }

        return $users;
    }

    public function getNumApprovedComments($id)
    {
        return $this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "comments` WHERE `user_id` = '" . (int) $id . "' AND `is_approved` = '1'"));
    }

    public function getNumLikedComments($id)
    {
        $query = $this->db->query("SELECT SUM(`likes`) AS `likes` FROM `" . CMTX_DB_PREFIX . "comments` WHERE `user_id` = '" . (int) $id . "' AND `is_approved` = '1'");

        $result = $this->db->row($query);

        return $result['likes'];
    }

    public function getNumDislikedComments($id)
    {
        $query = $this->db->query("SELECT SUM(`dislikes`) AS `dislikes` FROM `" . CMTX_DB_PREFIX . "comments` WHERE `user_id` = '" . (int) $id . "' AND `is_approved` = '1'");

        $result = $this->db->row($query);

        return $result['dislikes'];
    }

    public function deleteUser($id)
    {
        $query = $this->db->query("SELECT `id` FROM `" . CMTX_DB_PREFIX . "comments` WHERE `user_id` = '" . (int) $id . "'");

        $results = $this->db->rows($query);

        foreach ($results as $result) {
            $this->comment->deleteComment($result['id']);
        }

        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "subscriptions` WHERE `user_id` = '" . (int) $id . "'");

        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "users` WHERE `id` = '" . (int) $id . "'");

        if ($this->db->affectedRows()) {
            return true;
        } else {
            return false;
        }
    }

    public function ownComment($comment)
    {
        if (!$comment['session_id'] || $comment['session_id'] != $this->session->getId()) {
            return false;
        } else if ($comment['ip_address'] != $this->getIpAddress()) {
            return false;
        }

        return true;
    }

    /* Checks if the user is banned */
    public function isBanned($ip_address)
    {
        /* First check if the user's ban should be revoked */
        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "bans` WHERE `ip_address` = '" . $this->db->escape($ip_address) . "' AND `unban` = '1'"))) {
            $this->unban($ip_address);

            return false;
        }

        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "bans` WHERE '" . $this->db->escape($ip_address) . "' LIKE REPLACE(`ip_address`, '*', '%')"))) {
            return true;
        }

        if ($this->cookie->exists('Commentics-Ban')) {
            return true;
        }

        return false;
    }

    /* Bans the user */
    public function ban($reason, $ip_address = '')
    {
        if (!$ip_address) {
            $ip_address = $this->getIpAddress();
        }

        /* Insert into bans table */
        if ($ip_address) {
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "bans` SET `ip_address` = '" . $this->db->escape($ip_address) . "', `reason` = '" . $this->db->escape($reason) . "', `unban` = '0', `date_added` = NOW()");
        }

        /* Set ban cookie */
        $this->cookie->set('Commentics-Ban', 'Ban', 60 * 60 * 24 * $this->setting->get('ban_cookie_days') + time());

        /* Get administrators who have requested a notification of bans */
        $query = $this->db->query("SELECT `username`, `email`, `format`
                                   FROM `" . CMTX_DB_PREFIX . "admins` `a`
                                   WHERE `a`.`receive_email_ban` = '1'
                                   AND `a`.`is_enabled` = '1'");

        $admins = $this->db->rows($query);

        if ($admins) {
            $email = $this->email->get('ban');

            foreach ($admins as $admin) {
                $subject = $this->security->decode(str_ireplace('[username]', $admin['username'], $email['subject']));

                $text = str_ireplace('[username]', $admin['username'], $email['text']);
                $text = str_ireplace('[ip address]', $ip_address, $text);
                $text = str_ireplace('[reason]', $reason, $text);
                $text = str_ireplace('[admin link]', $this->email->getAdminLink(), $text);
                $text = str_ireplace('[signature]', $this->email->getSignatureText($this->page->getSiteId()), $text);
                $text = $this->security->decode($text);

                $html = str_ireplace('[username]', $admin['username'], $email['html']);
                $html = str_ireplace('[ip address]', $ip_address, $html);
                $html = str_ireplace('[reason]', $reason, $html);
                $html = str_ireplace('[admin link]', $this->email->getAdminLink(), $html);
                $html = str_ireplace('[signature]', $this->email->getSignatureHtml($this->page->getSiteId()), $html);
                $html = $this->security->decode($html);

                $this->email->send($admin['email'], null, $subject, $text, $html, $admin['format'], $this->page->getSiteId());
            }
        }
    }

    /* Unbans the user */
    public function unban($ip_address)
    {
        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "bans` WHERE `ip_address` = '" . $this->db->escape($ip_address) . "'");

        if ($this->cookie->exists('Commentics-Ban')) {
            $this->cookie->delete('Commentics-Ban');
        }
    }

    /* Checks if the user is the administrator */
    private function isAdministrator()
    {
        if (isset($this->session->data['cmtx_admin_id'])) {
            return true;
        } else {
            return false;
        }
    }

    public function getIpAddress()
    {
        if (isset($this->request->server['HTTP_X_FORWARDED_FOR']) && $this->validation->length($this->request->server['HTTP_X_FORWARDED_FOR']) < 250) {
            return $this->request->server['HTTP_X_FORWARDED_FOR'];
        } else if (isset($this->request->server['HTTP_CLIENT_IP']) && $this->validation->length($this->request->server['HTTP_CLIENT_IP']) < 250) {
            return $this->request->server['HTTP_CLIENT_IP'];
        } else if (isset($this->request->server['REMOTE_ADDR']) && $this->validation->length($this->request->server['REMOTE_ADDR']) < 250) {
            return $this->request->server['REMOTE_ADDR'];
        } else {
            return '';
        }
    }

    public function getUserAgent()
    {
        if (isset($this->request->server['HTTP_USER_AGENT']) && $this->validation->length($this->request->server['HTTP_USER_AGENT']) < 250) {
            return $this->request->server['HTTP_USER_AGENT'];
        } else {
            return '';
        }
    }

    public function getAcceptLanguage()
    {
        if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE']) && $this->validation->length($this->request->server['HTTP_ACCEPT_LANGUAGE']) < 250) {
            return $this->request->server['HTTP_ACCEPT_LANGUAGE'];
        } else {
            return '';
        }
    }

    public function isAdmin()
    {
        return $this->is_admin;
    }

    public function getLogin($index)
    {
        return $this->login[$index];
    }

    private function getUploadedAvatar($avatar_id)
    {
        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "uploads` WHERE `id` = '" . (int) $avatar_id . "'");

        $results = $this->db->row($query);

        return $results;
    }

    public function getAvatar($id, $show_pending = false)
    {
        $avatar = '';

        if ($id) {
            $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "users` WHERE `id` = '" . (int) $id . "'");

            $user = $this->db->row($query);

            if ($user) {
                if ($this->setting->get('avatar_type') == 'gravatar') {
                    $avatar = 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user['email']))) . '?d=' . ($this->setting->get('gravatar_default') == 'custom' ? $this->url->encode($this->setting->get('gravatar_custom')) : $this->setting->get('gravatar_default')) . '&amp;r=' . $this->setting->get('gravatar_audience') . '&amp;s=' . $this->setting->get('gravatar_size');
                }

                if ($this->setting->get('avatar_type') == 'login') {
                    if ($user['avatar_login']) {
                        $avatar = $user['avatar_login'];
                    }
                }

                if ($this->setting->get('avatar_type') == 'selection') {
                    if ($user['avatar_selected']) {
                        if (file_exists(CMTX_DIR_ROOT . 'frontend/view/' . $this->setting->get('theme') . '/image/avatar/' . $user['avatar_selected'])) {
                            $avatar = $this->url->getCommenticsUrl() . 'frontend/view/' . $this->setting->get('theme') . '/image/avatar/' . $user['avatar_selected'];
                        } else if (file_exists(CMTX_DIR_ROOT . 'frontend/view/default/image/avatar/' . $user['avatar_selected'])) {
                            $avatar = $this->url->getCommenticsUrl() . 'frontend/view/default/image/avatar/' . $user['avatar_selected'];
                        }
                    }
                }

                if ($this->setting->get('avatar_type') == 'upload') {
                    $avatar_approved = $this->getUploadedAvatar($user['avatar_id']);

                    $avatar_pending = $this->getUploadedAvatar($user['avatar_pending_id']);

                    if ($show_pending && $avatar_pending) {
                        if (file_exists(CMTX_DIR_UPLOAD . $avatar_pending['folder'] . '/' . $avatar_pending['filename'] . '.' . $avatar_pending['extension'])) {
                            $avatar = $this->url->getCommenticsUrl() . 'upload/' . $avatar_pending['folder'] . '/' . $avatar_pending['filename'] . '.' . $avatar_pending['extension'];
                        }
                    } else if ($avatar_approved) {
                        if (file_exists(CMTX_DIR_UPLOAD . $avatar_approved['folder'] . '/' . $avatar_approved['filename'] . '.' . $avatar_approved['extension'])) {
                            $avatar = $this->url->getCommenticsUrl() . 'upload/' . $avatar_approved['folder'] . '/' . $avatar_approved['filename'] . '.' . $avatar_approved['extension'];
                        }
                    }
                }
            }
        }

        if (!$avatar) {
            if (file_exists(CMTX_DIR_ROOT . 'frontend/view/' . $this->setting->get('theme') . '/image/misc/avatar.png')) {
                $avatar = $this->url->getCommenticsUrl() . 'frontend/view/' . $this->setting->get('theme') . '/image/misc/avatar.png';
            } else if (file_exists(CMTX_DIR_ROOT . 'frontend/view/default/image/misc/avatar.png')) {
                $avatar = $this->url->getCommenticsUrl() . 'frontend/view/default/image/misc/avatar.png';
            }
        }

        return $avatar;
    }

    public function saveAvatarLogin($id, $avatar_login)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "users` SET `avatar_login` = '" . $this->db->escape($avatar_login) . "' WHERE `id` = '" . (int) $id . "'");
    }
}
