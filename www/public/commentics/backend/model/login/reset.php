<?php
namespace Commentics;

class LoginResetModel extends Model
{
    public function sendReset($username, $password, $to_email, $format)
    {
        $email = $this->email->get('password_reset');

        $subject = $this->security->decode($email['subject']);

        $text = str_ireplace('[username]', $username, $email['text']);
        $text = str_ireplace('[password]', $password, $text);
        $text = str_ireplace('[admin link]', $this->email->getAdminLink(), $text);
        $text = str_ireplace('[signature]', $this->email->getSignatureText(), $text);
        $text = $this->security->decode($text);

        $html = str_ireplace('[username]', $username, $email['html']);
        $html = str_ireplace('[password]', $password, $html);
        $html = str_ireplace('[admin link]', $this->email->getAdminLink(), $html);
        $html = str_ireplace('[signature]', $this->email->getSignatureHtml(), $html);
        $html = $this->security->decode($html);

        $this->email->send($to_email, null, $subject, $text, $html, $format);
    }

    public function updatePassword($password, $email)
    {
        $password = password_hash($password, PASSWORD_DEFAULT);

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "admins` SET `password` = '" . $this->db->escape($password) . "' WHERE `email` = '" . $this->db->escape($email) . "'");
    }

    public function updateReset($email)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "admins` SET `resets` = `resets` + 1 WHERE `email` = '" . $this->db->escape($email) . "'");
    }
}
