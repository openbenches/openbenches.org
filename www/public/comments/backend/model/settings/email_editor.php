<?php
namespace Commentics;

class SettingsEmailEditorModel extends Model
{
    public function getEmail($type)
    {
        $email = array();

        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "emails` WHERE `type` = '" . $this->db->escape($type) . "'");

        $results = $this->db->rows($query);

        foreach ($results as $result) {
            $email[$result['language']] = array(
                'subject' => $result['subject'],
                'text'    => $result['text'],
                'html'    => $result['html']
            );
        }

        return $email;
    }

    public function update($data, $type)
    {
        $this->db->query("DELETE FROM `" . CMTX_DB_PREFIX . "emails` WHERE `type` = '" . $this->db->escape($type) . "'");

        foreach ($data['field'] as $key => $value) {
            $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "emails` SET `type` = '" . $this->db->escape($type) . "', `subject` = '" . $this->db->escape($value['subject']) . "', `text` = '" . $this->db->escape($value['text']) . "', `html` = '" . $this->db->escape($value['html']) . "', `language` = '" . $this->db->escape($key) . "', `date_modified` = NOW()");
        }
    }

    public function dismiss()
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '0' WHERE `title` = 'notice_settings_email_editor'");
    }
}
