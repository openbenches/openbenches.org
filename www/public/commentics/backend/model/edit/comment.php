<?php
namespace Commentics;

class EditCommentModel extends Model
{
    public function update($data, $id)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "comments` SET `page_id` = '" . (int) $data['page_id'] . "', `website` = '" . $this->db->escape($data['website']) . "', `town` = '" . $this->db->escape($data['town']) . "', `state_id` = '" . (int) $data['state_id'] . "', `country_id` = '" . (int) $data['country_id'] . "', `rating` = '" . (int) $data['rating'] . "', `reply_to` = '" . (int) $data['reply_to'] . "', `headline` = '" . $this->db->escape($data['headline']) . "', `comment` = '" . $this->db->escape($this->security->decode($data['comment'])) . "', `reply` = '" . $this->db->escape($this->security->decode($data['reply'])) . "', `notes` = '" . $this->db->escape($data['notes']) . "', `reports` = " . (isset($data['verify']) ? '0' : '`reports`') . ", `is_sticky` = '" . (isset($data['is_sticky']) && $data['is_sticky'] ? 1 : 0) . "', `is_locked` = '" . (int) $data['is_locked'] . "', `is_admin` = '" . (int) $data['is_admin'] . "', `is_verified` = " . (isset($data['verify']) ? '1' : '`is_verified`') . ", `date_modified` = NOW() WHERE `id` = '" . (int) $id . "'");

        unset($this->request->files['files']); // summernote

        if ($this->request->files) {
            $lang = $this->loadWord('edit/comment');

            foreach ($this->request->files as $file) {
                if (!is_writable(CMTX_DIR_UPLOAD)) {
                    $this->session->data['cmtx_error'] = $lang['lang_error_upload_message'] . ' ' . $lang['lang_error_upload_writable'];
                    continue;
                }

                $folder = 'comment/' . date('Y') . '/' . date('m');

                if (!is_dir(CMTX_DIR_UPLOAD . $folder)) {
                    if (!mkdir(CMTX_DIR_UPLOAD . $folder, 0777, true)) {
                        $this->session->data['cmtx_error'] = $lang['lang_error_upload_message'] . ' ' . $lang['lang_error_folder_create'];
                        continue;
                    }
                }

                if ($file['error']) {
                    if ($file['error'] == '1') {
                        $this->session->data['cmtx_error'] = $lang['lang_error_upload_message'] . ' ' . $lang['lang_error_image_code_1'];
                    } else {
                        $this->session->data['cmtx_error'] = $lang['lang_error_upload_message'] . ' ' . sprintf($lang['lang_error_image_code'], $file['error']);
                    }
                    continue;
                }

                $file_extension = $this->variable->strstr($file['name'], '.');

                $allowed_file_extensions = array(
                    '.jpeg',
                    '.jpg',
                    '.png',
                    '.gif'
                );

                if (!in_array($this->variable->strtolower($file_extension), $allowed_file_extensions)) {
                    $this->session->data['cmtx_error'] = $lang['lang_error_upload_message'] . ' ' . $lang['lang_error_image_extension'];
                    continue;
                }

                if (@getimagesize($file['tmp_name']) == false) {
                    $this->session->data['cmtx_error'] = $lang['lang_error_upload_message'] . ' ' . $lang['lang_error_image_getimagesize'];
                    continue;
                }

                if (extension_loaded('gd') && @imagecreatefromstring(file_get_contents($file['tmp_name']) == false)) {
                    $this->session->data['cmtx_error'] = $lang['lang_error_upload_message'] . ' ' . $lang['lang_error_image_imagecreatefromstring'];
                    continue;
                }

                $mime_type = $file['type'];

                $allowed_mime_types = array(
                    'image/jpeg',
                    'image/png',
                    'image/gif'
                );

                if (!in_array($mime_type, $allowed_mime_types)) {
                    $this->session->data['cmtx_error'] = $lang['lang_error_upload_message'] . ' ' . $lang['lang_error_image_type'];
                    continue;
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

                    $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "uploads` SET `user_id` = '" . (int) $data['user_id'] . "', `comment_id` = '" . (int) $id . "', `folder` = '" . $this->db->escape($folder) . "', `filename` = '" . $this->db->escape($filename) . "', `extension` = '" . $this->db->escape($extension) . "', `mime_type` = '" . $this->db->escape($mime_type) . "', `file_size` = '" . $this->db->escape($file_size) . "', `date_added` = NOW()");
                } else {
                    $this->session->data['cmtx_error'] = $lang['lang_error_upload_message'] . ' ' . $lang['lang_error_image_move_uploaded_file'];
                }
            }
        }

        if (isset($data['upload_remove'])) {
            foreach ($data['upload_remove'] as $upload_id) {
                $this->comment->deleteUpload($upload_id);
            }
        }

        if ($data['is_approved'] == 1 && !$this->comment->isApproved($id)) {
            $this->comment->approveComment($id);
        } else if ($data['is_approved'] == 0 && $this->comment->isApproved($id)) {
            $this->comment->unapproveComment($id);
        }

        if (isset($data['send'])) {
            $this->notify->subscriberNotification($id);
        }

        $this->comment->deleteCache($id);
    }

    public function getStates($id)
    {
        $query = $this->db->query("SELECT `code` FROM `" . CMTX_DB_PREFIX . "countries` WHERE `id` = '" . (int) $id . "'");

        $result = $this->db->row($query);

        $code = $result['code'];

        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "states` WHERE `country_code` = '" . $this->db->escape($code) . "' ORDER BY `name` ASC");

        $results = $this->db->rows($query);

        return $results;
    }

    public function getReplies($id, $page_id)
    {
        $query = $this->db->query("SELECT `id` FROM `" . CMTX_DB_PREFIX . "comments` WHERE `id` != '" . (int) $id . "' AND `page_id` = '" . (int) $page_id . "'");

        $results = $this->db->rows($query);

        $comments = array();

        foreach ($results as $result) {
            $comments[$result['id']] = $this->comment->getComment($result['id']);
        }

        return $comments;
    }

    public function getPages()
    {
        $query = $this->db->query("SELECT `id`, `reference` FROM `" . CMTX_DB_PREFIX . "pages`");

        $results = $this->db->rows($query);

        return $results;
    }

    public function dismiss()
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '0' WHERE `title` = 'notice_edit_comment'");
    }
}
