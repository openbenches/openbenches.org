<?php
namespace Commentics;

class MainUserModel extends Model
{
    /* Get the user's subscription */
    public function getSubscription($user_token, $subscription_token)
    {
        $query = $this->db->query("SELECT *
                                   FROM `" . CMTX_DB_PREFIX . "subscriptions` `s`
                                   RIGHT JOIN `" . CMTX_DB_PREFIX . "users` `u` ON `s`.`user_id` = `u`.`id`
                                   WHERE `s`.`token` = '" . $this->db->escape($subscription_token) . "'
                                   AND `u`.`token` = '" . $this->db->escape($user_token) . "'");

        $result = $this->db->row($query);

        return $result;
    }

    /* Get the user's subscriptions */
    public function getSubscriptions($user_token)
    {
        $query = $this->db->query("SELECT `s`.*, `p`.`reference`, `p`.`url`
                                   FROM `" . CMTX_DB_PREFIX . "subscriptions` `s`
                                   RIGHT JOIN `" . CMTX_DB_PREFIX . "users` `u` ON `s`.`user_id` = `u`.`id`
                                   RIGHT JOIN `" . CMTX_DB_PREFIX . "pages` `p` ON `s`.`page_id` = `p`.`id`
                                   WHERE `u`.`token` = '" . $this->db->escape($user_token) . "'
                                   AND `s`.`is_confirmed` = '1'
                                   ORDER BY `s`.`date_added` DESC");

        $results = $this->db->rows($query);

        return $results;
    }

    /* Confirm the user's subscription */
    public function confirmSubscription($subscription_token)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "subscriptions` SET `is_confirmed` = '1' WHERE `token` = '" . $this->db->escape($subscription_token) . "'");
    }

    /* Delete the user's subscription */
    public function deleteSubscription($subscription_token)
    {
        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "subscriptions` WHERE `token` = '" . $this->db->escape($subscription_token) . "'");
    }

    /* Delete all of the user's subscriptions */
    public function deleteAllSubscriptions($user_id)
    {
        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "subscriptions` WHERE `user_id` = '" . (int) $user_id . "'");
    }

    /* Save the user's uploaded avatar */
    public function saveUploadedAvatar($user, $data)
    {
        $responses = $this->loadWord('main/user');

        if (isset($data['avatar'])) {
            $file = $data['avatar'];

            if (!is_writable(CMTX_DIR_UPLOAD)) {
                return $responses['lang_error_upload_writable'];
            }

            $folder = 'avatar/' . date('Y') . '/' . date('m');

            if (!is_dir(CMTX_DIR_UPLOAD . $folder)) {
                if (!mkdir(CMTX_DIR_UPLOAD . $folder, 0777, true)) {
                    return $responses['lang_error_folder_create'];
                }
            }

            if ($file['error']) {
                if ($file['error'] == '1') {
                    return $responses['lang_error_image_size'];
                } else {
                    return $responses['lang_error_image_error'];
                }
            }

            if ($file['size'] > ($this->setting->get('avatar_upload_max_size') * pow(1024, 2))) {
                return $responses['lang_error_image_size'];
            }

            $file_extension = $this->variable->strstr($file['name'], '.');

            $allowed_file_extensions = array(
                '.jpeg',
                '.jpg',
                '.png',
                '.gif'
            );

            if (!in_array($this->variable->strtolower($file_extension), $allowed_file_extensions)) {
                return $responses['lang_error_image_extension'];
            }

            if (@getimagesize($file['tmp_name']) == false) {
                return $responses['lang_error_image_data'];
            }

            if (extension_loaded('gd') && @imagecreatefromstring(file_get_contents($file['tmp_name']) == false)) {
                return $responses['lang_error_image_malformed'];
            }

            $mime_type = $file['type'];

            $allowed_mime_types = array(
                'image/jpeg',
                'image/png',
                'image/gif'
            );

            if (!in_array($mime_type, $allowed_mime_types)) {
                return $responses['lang_error_image_type'];
            }

            switch ($mime_type) {
                case 'image/jpeg':
                    $extension = 'jpg';
                    break;
                case 'image/png':
                    $extension = 'png';
                    break;
                case 'image/gif':
                    $extension = 'gif';
                    break;
                default:
                    $extension = 'jpg';
            }

            do {
                $filename = $this->variable->random();
            } while (file_exists(CMTX_DIR_UPLOAD . $folder . '/' . $filename . '.' . $extension));

            $location = CMTX_DIR_UPLOAD . $folder . '/' . $filename . '.' . $extension;

            if (move_uploaded_file($file['tmp_name'], $location)) {
                $file_size = filesize($location);

                $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "uploads` SET `user_id` = '" . (int) $user['id'] . "', `comment_id` = '0', `folder` = '" . $this->db->escape($folder) . "', `filename` = '" . $this->db->escape($filename) . "', `extension` = '" . $this->db->escape($extension) . "', `mime_type` = '" . $this->db->escape($mime_type) . "', `file_size` = '" . $this->db->escape($file_size) . "', `date_added` = NOW()");

                $upload_id = $this->db->insertId();

                $approve = false;

                if ($user['moderate'] == 'always') {
                    $approve = true;
                } else if ($user['moderate'] == 'never') {
                    $approve = false;
                } else if ($this->setting->get('avatar_upload_approve')) {
                    $approve = true;
                } else {
                    $approve = false;
                }

                if ($approve) {
                    $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "users` SET `avatar_pending_id` = '" . (int) $upload_id . "' WHERE `id` = '" . (int) $user['id'] . "'");
                } else {
                    $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "users` SET `avatar_id` = '" . (int) $upload_id . "', `avatar_pending_id` = '0' WHERE `id` = '" . (int) $user['id'] . "'");
                }

                return array(
                    'folder'    => $folder,
                    'filename'  => $filename,
                    'extension' => $extension,
                    'mime_type' => $mime_type,
                    'file_size' => $file_size,
                    'approve'   => $approve
                );
            } else {
                return $responses['lang_error_upload'];
            }
        } else {
            return $responses['lang_error_no_image'];
        }
    }

    public function numUploadedAvatars($user_id)
    {
        $query = $this->db->query("SELECT COUNT(*) AS `count`
                                   FROM `" . CMTX_DB_PREFIX . "uploads`
                                   WHERE `folder` LIKE 'avatar/%'
                                   AND `user_id` = '" . (int) $user_id . "'
                                   GROUP BY `user_id`");

        $result = $this->db->row($query);

        if ($result) {
            return $result['count'];
        } else {
            return 0;
        }
    }

    /* Save the user's selected avatar */
    public function saveSelectedAvatar($user, $data)
    {
        $responses = $this->loadWord('main/user');

        if (isset($data['avatar'])) {
            $path_info = pathinfo($data['avatar']);

            if (!$path_info) {
                return $responses['lang_error_path_info'];
            }

            $path_info['filename'] = basename($path_info['filename']);

            if (!$this->validation->isSelectableAvatar($path_info['filename'])) {
                return $responses['lang_error_image_filename'];
            }

            if (!in_array($path_info['extension'], array('jpg', 'png', 'gif'))) {
                return $responses['lang_error_image_type'];
            }

            $file = $path_info['filename'] . '.' . $path_info['extension'];

            if (!file_exists(CMTX_DIR_VIEW . $this->setting->get('theme') . '/image/avatar/' . $file) && !file_exists(CMTX_DIR_VIEW . 'default/image/avatar/' . $file)) {
                return $responses['lang_error_no_selected_img'];
            }

            $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "users` SET `avatar_selected` = '" . $this->db->escape($file) . "' WHERE `id` = '" . (int) $user['id'] . "'");

            return array(
                'filename'  => $path_info['filename'],
                'extension' => $path_info['extension']
            );
        } else {
            return $responses['lang_error_no_image'];
        }
    }

    /* Save the user's settings */
    public function save($data)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "users` SET `to_all` = '" . ((isset($data['to_all']) && $data['to_all']) ? 1 : 0) . "', `to_admin` = '" . ((isset($data['to_admin']) && $data['to_admin']) ? 1 : 0) . "', `to_reply` = '" . ((isset($data['to_reply']) && $data['to_reply']) ? 1 : 0) . "', `to_approve` = '" . ((isset($data['to_approve']) && $data['to_approve']) ? 1 : 0) . "', `format` = '" . ((isset($data['format']) && $data['format'] == 'html') ? 'html' : 'text') . "' WHERE `token` = '" . $this->db->escape($data['u-t']) . "'");
    }

    public function getSelectableAvatars()
    {
        $avatars = array();

        $list = glob(CMTX_DIR_VIEW . $this->setting->get('theme') . '/image/avatar/*');

        if (!$list) {
            $list = glob(CMTX_DIR_VIEW . 'default/image/avatar/*');
        }

        natsort($list);

        foreach ($list as $avatar) {
            $path_info = pathinfo($avatar);

            if ($path_info && $this->validation->isSelectableAvatar($path_info['filename']) && in_array($path_info['extension'], array('jpg', 'png', 'gif'))) {
                $avatars[] = $this->loadImage('avatar/' . basename($avatar));
            }
        }

        return $avatars;
    }

    /* Calculate the number of days between user added and now */
    public function numDaysSinceUserAdded($date_added)
    {
        $datetime1 = new \DateTime($date_added);

        $datetime2 = new \DateTime();

        $difference = $datetime1->diff($datetime2)->d;

        return $difference;
    }

    public function addAttempt()
    {
        $ip_address = $this->user->getIpAddress();

        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "attempts` WHERE `type` = 'user' AND `ip_address` = '" . $this->db->escape($ip_address) . "'"))) {
            $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "attempts` SET `amount` = `amount` + 1, `date_added` = NOW() WHERE `type` = 'user' AND `ip_address` = '" . $this->db->escape($ip_address) . "'");
        } else {
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "attempts` SET `type` = 'user', `ip_address` = '" . $this->db->escape($ip_address) . "', `amount` = '1', `date_added` = NOW()");
        }
    }

    public function hasMaxAttempts()
    {
        $ip_address = $this->user->getIpAddress();

        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "attempts` WHERE `type` = 'user' AND `ip_address` = '" . $this->db->escape($ip_address) . "' AND `amount` >= 3"))) {
            $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "attempts` WHERE `type` = 'user' AND `ip_address` = '" . $this->db->escape($ip_address) . "' AND `amount` >= 3");

            $result = $this->db->row($query);

            $time = strtotime($result['date_added']);

            $difference = time() - $time;

            if ($difference < 60 * 30) { // seconds * minutes
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function resetAttempts()
    {
        $ip_address = $this->user->getIpAddress();

        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "attempts` WHERE `type` = 'user' AND `ip_address` = '" . $this->db->escape($ip_address) . "'");
    }
}
