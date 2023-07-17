<?php
namespace Commentics;

class EditSpamModel extends Model
{
    public function remove($data, $id)
    {
        $comment = $this->comment->getComment($id);

        if ($data['delete'] == 'delete_this') {
            $this->user->deleteUser($comment['user_id']);
        } else {
            $query = $this->db->query("SELECT `user_id` FROM `" . CMTX_DB_PREFIX . "comments` WHERE `ip_address` = '" . $this->db->escape($comment['ip_address']) . "'");

            $results = $this->db->rows($query);

            foreach ($results as $result) {
                $this->user->deleteUser($result['user_id']);
            }
        }

        if ($data['ban'] == 'ban') {
            $this->loadModel('add/ban');

            $ban_data = array(
                'ip_address' => $comment['ip_address'],
                'reason' => $this->loadWord('edit/spam', 'lang_text_reason')
            );

            $this->model_add_ban->add($ban_data);
        }

        if (isset($data['add_name'])) {
            $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "data` SET `text` = CASE WHEN `text` = '' THEN '" . $this->db->escape($comment['name']) . "' ELSE CONCAT_WS('\r\n', `text`, '" . $this->db->escape($comment['name']) . "') END WHERE `type` = 'banned_names'");
        }

        if (isset($data['add_email'])) {
            $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "data` SET `text` = CASE WHEN `text` = '' THEN '" . $this->db->escape($comment['email']) . "' ELSE CONCAT_WS('\r\n', `text`, '" . $this->db->escape($comment['email']) . "') END WHERE `type` = 'banned_emails'");
        }

        if (isset($data['add_website'])) {
            $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "data` SET `text` = CASE WHEN `text` = '' THEN '" . $this->db->escape($comment['website']) . "' ELSE CONCAT_WS('\r\n', `text`, '" . $this->db->escape($comment['website']) . "') END WHERE `type` = 'banned_websites'");
        }
    }

    public function dismiss()
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '0' WHERE `title` = 'notice_edit_spam'");
    }
}
