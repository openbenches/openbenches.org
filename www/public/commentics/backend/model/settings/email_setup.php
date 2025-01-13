<?php
namespace Commentics;

class SettingsEmailSetupModel extends Model
{
    public function update($data, $username, $admin_id)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['transport_method']) . "' WHERE `title` = 'transport_method'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['smtp_host']) . "' WHERE `title` = 'smtp_host'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['smtp_port'] . "' WHERE `title` = 'smtp_port'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['smtp_encrypt']) . "' WHERE `title` = 'smtp_encrypt'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $data['smtp_timeout'] . "' WHERE `title` = 'smtp_timeout'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['smtp_username']) . "' WHERE `title` = 'smtp_username'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['smtp_password']) . "' WHERE `title` = 'smtp_password'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['from_name']) . "' WHERE `title` = 'from_name'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['from_email']) . "' WHERE `title` = 'from_email'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . $this->db->escape($data['reply_email']) . "' WHERE `title` = 'reply_email'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "data` SET `text` = '" . $this->db->escape($data['signature_text']) . "', `modified_by` = '" . $this->db->escape($username) . "', `date_modified` = NOW() WHERE `type` = 'signature_text'");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "data` SET `text` = '" . $this->db->escape($data['signature_html']) . "', `modified_by` = '" . $this->db->escape($username) . "', `date_modified` = NOW() WHERE `type` = 'signature_html'");

        if (isset($data['send'])) {
            $this->setting->refresh();

            $this->send($admin_id);
        }
    }

    public function getSignatureText()
    {
        $query = $this->db->query("SELECT `text` FROM `" . CMTX_DB_PREFIX . "data` WHERE `type` = 'signature_text'");

        $result = $this->db->row($query);

        return $result['text'];
    }

    public function getSignatureHtml()
    {
        $query = $this->db->query("SELECT `text` FROM `" . CMTX_DB_PREFIX . "data` WHERE `type` = 'signature_html'");

        $result = $this->db->row($query);

        return $result['text'];
    }

    private function send($admin_id)
    {
        $this->loadModel('common/administrator');

        $administrator = $this->model_common_administrator->getAdmin($admin_id);

        $to_email = $administrator['email'];

        $format = $administrator['format'];

        $email = $this->email->get('setup_test');

        $subject = $this->security->decode($email['subject']);

        $text = str_ireplace('[username]', $this->session->data['cmtx_username'], $email['text']);
        $text = str_ireplace('[admin link]', $this->email->getAdminLink(), $text);
        $text = str_ireplace('[signature]', $this->email->getSignatureText(), $text);
        $text = $this->security->decode($text);

        $html = str_ireplace('[username]', $this->session->data['cmtx_username'], $email['html']);
        $html = str_ireplace('[admin link]', $this->email->getAdminLink(), $html);
        $html = str_ireplace('[signature]', $this->email->getSignatureHtml(), $html);
        $html = $this->security->decode($html);

        $this->email->send($to_email, null, $subject, $text, $html, $format);
    }
}
