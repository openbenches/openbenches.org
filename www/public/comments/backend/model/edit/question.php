<?php
namespace Commentics;

class EditQuestionModel extends Model
{
    public function questionExists($id)
    {
        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "questions` WHERE `id` = '" . (int) $id . "'"))) {
            return true;
        } else {
            return false;
        }
    }

    public function getQuestion($id)
    {
        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "questions` WHERE `id` = '" . (int) $id . "'");

        $result = $this->db->row($query);

        return $result;
    }

    public function update($data, $id)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "questions` SET `question` = '" . $this->db->escape($data['question']) . "', `answer` = '" . $this->db->escape($data['answer']) . "', `language` = '" . $this->db->escape($data['language']) . "', `date_modified` = NOW() WHERE `id` = '" . (int) $id . "'");
    }

    public function dismiss()
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '0' WHERE `title` = 'notice_edit_question'");
    }
}
