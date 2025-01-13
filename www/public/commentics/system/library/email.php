<?php
namespace Commentics;

class Email
{
    private $db;
    private $log;
    private $setting;

    public function __construct($registry)
    {
        $this->db      = $registry->get('db');
        $this->log     = $registry->get('log');
        $this->setting = $registry->get('setting');
    }

    public function get($type)
    {
        /* The new version email is sent from the frontend but uses the backend language */
        if ($type == 'new_version') {
            $language = $this->setting->get('language_backend');
        } else {
            $language = $this->setting->get('language');
        }

        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "emails` WHERE `type` = '" . $this->db->escape($type) . "' AND `language` = '" . $this->db->escape($language) . "'"))) {
            $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "emails` WHERE `type` = '" . $this->db->escape($type) . "' AND `language` = '" . $this->db->escape($language) . "'");

            return $this->db->row($query);
        } else if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "emails` WHERE `type` = '" . $this->db->escape($type) . "' AND `language` = 'english'"))) {
            $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "emails` WHERE `type` = '" . $this->db->escape($type) . "' AND `language` = 'english'");

            return $this->db->row($query);
        } else {
            die('<b>Error</b>: Could not load email ' . strtolower($type) . '!');
        }
    }

    public function getSignatureText($site_id = '')
    {
        $query = $this->db->query("SELECT `text` FROM `" . CMTX_DB_PREFIX . "data` WHERE `type` = 'signature_text'");

        $result = $this->db->row($query);

        $signature = $result['text'];

        $signature = $this->convertSignatureKeywords($signature, $site_id);

        return $signature;
    }

    public function getSignatureHtml($site_id = '')
    {
        $query = $this->db->query("SELECT `text` FROM `" . CMTX_DB_PREFIX . "data` WHERE `type` = 'signature_html'");

        $result = $this->db->row($query);

        $signature = $result['text'];

        $signature = $this->convertSignatureKeywords($signature, $site_id);

        return $signature;
    }

    private function convertSignatureKeywords($signature, $site_id)
    {
        $site_name = $this->setting->get('site_name');
        $site_domain = $this->setting->get('site_domain');
        $site_url = $this->setting->get('site_url');

        if ($site_id) {
            $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "sites` WHERE `id` = '" . (int) $site_id . "'");

            $result = $this->db->row($query);

            if ($result) {
                $site_name = $result['name'];

                $site_domain = $result['domain'];

                $site_url = $result['url'];
            }
        }

        $signature = str_ireplace('[site name]', $site_name, $signature);
        $signature = str_ireplace('[site domain]', $site_domain, $signature);
        $signature = str_ireplace('[site url]', $site_url, $signature);

        return $signature;
    }

    public function getAdminLink()
    {
        return $this->setting->get('commentics_url') . $this->setting->get('backend_folder') . '/';
    }

    public function send($to_email, $to_name, $subject, $text, $html, $format, $site_id = '', $attachments = array())
    {
        if (!$to_email) { // sanity check
            return;
        }

        $this->log->setFilename('errors');

        $from_name = $this->setting->get('from_name');
        $from_email = $this->setting->get('from_email');
        $reply_email = $this->setting->get('reply_email');

        if ($site_id) {
            $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "sites` WHERE `id` = '" . (int) $site_id . "'");

            $result = $this->db->row($query);

            if ($result) {
                if ($result['from_name']) {
                    $from_name = $result['from_name'];
                }

                if ($result['from_email']) {
                    $from_email = $result['from_email'];
                }

                if ($result['reply_email']) {
                    $reply_email = $result['reply_email'];
                }
            }
        }

        $boundary = md5(uniqid());

        $headers  = 'MIME-Version: 1.0' . PHP_EOL;
        $headers .= 'Message-ID: <' . md5(time() . mt_rand()) . '@' . $this->setting->get('site_domain') . '>' . PHP_EOL;
        $headers .= 'From: ' . $from_name . ' <' . $from_email . '>' . PHP_EOL;

        if ($this->setting->get('transport_method') == 'smtp') {
            $headers .= 'To: ' . ($to_name ? $to_name . ' <' . $to_email . '>' : $to_email) . PHP_EOL;
            $headers .= 'Subject: ' . $subject . PHP_EOL;
        }

        $headers .= 'Reply-To: ' . $from_name . ' <' . $reply_email . '>' . PHP_EOL;
        $headers .= 'Return-Path: ' . $from_email . PHP_EOL;
        $headers .= 'Date: ' . date('D, d M Y H:i:s O') . PHP_EOL;
        $headers .= 'Content-Type: multipart/mixed; boundary="' . $boundary . '"' . PHP_EOL . PHP_EOL;

        $body = '--' . $boundary . PHP_EOL;

        if ($format == 'text') {
            $body .= 'Content-Type: text/plain; charset="utf-8"' . PHP_EOL;
            $body .= 'Content-Transfer-Encoding: 8bit' . PHP_EOL . PHP_EOL;
            $body .= $text . PHP_EOL;
        } else {
            $body .= 'Content-Type: multipart/alternative; boundary="' . $boundary . '_alt"' . PHP_EOL . PHP_EOL;
            $body .= '--' . $boundary . '_alt' . PHP_EOL;
            $body .= 'Content-Type: text/plain; charset="utf-8"' . PHP_EOL;
            $body .= 'Content-Transfer-Encoding: 8bit' . PHP_EOL . PHP_EOL;
            $body .= $text . PHP_EOL;
            $body .= '--' . $boundary . '_alt' . PHP_EOL;
            $body .= 'Content-Type: text/html; charset="utf-8"' . PHP_EOL;
            $body .= 'Content-Transfer-Encoding: 8bit' . PHP_EOL . PHP_EOL;
            $body .= $html . PHP_EOL;
            $body .= '--' . $boundary . '_alt--' . PHP_EOL;
        }

        foreach ($attachments as $attachment) {
            if (file_exists($attachment)) {
                $handle = fopen($attachment, 'r');

                $content = fread($handle, filesize($attachment));

                fclose($handle);

                $body .= '--' . $boundary . PHP_EOL;
                $body .= 'Content-Type: application/octet-stream; name="' . basename($attachment) . '"' . PHP_EOL;
                $body .= 'Content-Transfer-Encoding: base64' . PHP_EOL;
                $body .= 'Content-Disposition: attachment; filename="' . basename($attachment) . '"' . PHP_EOL;
                $body .= 'Content-ID: <' . urlencode(basename($attachment)) . '>' . PHP_EOL;
                $body .= 'X-Attachment-Id: ' . urlencode(basename($attachment)) . PHP_EOL . PHP_EOL;
                $body .= chunk_split(base64_encode($content));
            }
        }

        $body .= '--' . $boundary . '--' . PHP_EOL;

        if ($this->setting->get('transport_method') == 'php') {
            if ($to_name) {
                $to_email = $to_name . ' <' . $to_email . '>';
            }

            ini_set('sendmail_from', $from_email);

            if (!mail($to_email, $subject, $body, $headers)) {
                $this->log->write('Error sending email to ' . $to_email);
            }
        } else { // SMTP
            $handle = @fsockopen($this->setting->get('smtp_host'), $this->setting->get('smtp_port'), $errno, $errstr, $this->setting->get('smtp_timeout'));

            if (!$handle) {
                $this->log->write('Email Error: ' . $errstr . ' (' . $errno . ')');
                return;
            } else {
                if (substr(PHP_OS, 0, 3) != 'WIN') {
                    socket_set_timeout($handle, $this->setting->get('smtp_timeout'), 0);
                }

                while ($line = fgets($handle, 515)) {
                    if (substr($line, 3, 1) == ' ') {
                        break;
                    }
                }

                fputs($handle, 'EHLO ' . getenv('SERVER_NAME') . "\r\n");

                $reply = '';

                while ($line = fgets($handle, 515)) {
                    $reply .= $line;

                    // some SMTP servers respond with 220 code before responding with 250, hence, we need to ignore 220 response string.
                    if (substr($reply, 0, 3) == 220 && substr($line, 3, 1) == ' ') {
                        $reply = '';
                        continue;
                    } else if (substr($line, 3, 1) == ' ') {
                        break;
                    }
                }

                if (substr($reply, 0, 3) != 250) {
                    $this->log->write('Email Error: EHLO not accepted from server');
                    return;
                }

                if ($this->setting->get('smtp_encrypt') == 'TLS') {
                    fputs($handle, 'STARTTLS' . "\r\n");

                    $reply = '';

                    while ($line = fgets($handle, 515)) {
                        $reply .= $line;

                        if (substr($line, 3, 1) == ' ') {
                            break;
                        }
                    }

                    if (substr($reply, 0, 3) != 220) {
                        $this->log->write('Email Error: STARTTLS not accepted from server');
                        return;
                    }

                    stream_socket_enable_crypto($handle, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
                }

                if ($this->setting->get('smtp_username') && $this->setting->get('smtp_password')) {
                    fputs($handle, 'EHLO ' . getenv('SERVER_NAME') . "\r\n");

                    $reply = '';

                    while ($line = fgets($handle, 515)) {
                        $reply .= $line;

                        if (substr($line, 3, 1) == ' ') {
                            break;
                        }
                    }

                    if (substr($reply, 0, 3) != 250) {
                        $this->log->write('Email Error: EHLO not accepted from server');
                        return;
                    }

                    fputs($handle, 'AUTH LOGIN' . "\r\n");

                    $reply = '';

                    while ($line = fgets($handle, 515)) {
                        $reply .= $line;

                        if (substr($line, 3, 1) == ' ') {
                            break;
                        }
                    }

                    if (substr($reply, 0, 3) != 334) {
                        $this->log->write('Email Error: AUTH LOGIN not accepted from server');
                        return;
                    }

                    fputs($handle, base64_encode($this->setting->get('smtp_username')) . "\r\n");

                    $reply = '';

                    while ($line = fgets($handle, 515)) {
                        $reply .= $line;

                        if (substr($line, 3, 1) == ' ') {
                            break;
                        }
                    }

                    if (substr($reply, 0, 3) != 334) {
                        $this->log->write('Email Error: Username not accepted from server');
                        return;
                    }

                    fputs($handle, base64_encode($this->setting->get('smtp_password')) . "\r\n");

                    $reply = '';

                    while ($line = fgets($handle, 515)) {
                        $reply .= $line;

                        if (substr($line, 3, 1) == ' ') {
                            break;
                        }
                    }

                    if (substr($reply, 0, 3) != 235) {
                        $this->log->write('Email Error: Password not accepted from server');
                        return;
                    }
                } else {
                    fputs($handle, 'HELO ' . getenv('SERVER_NAME') . "\r\n");

                    $reply = '';

                    while ($line = fgets($handle, 515)) {
                        $reply .= $line;

                        if (substr($line, 3, 1) == ' ') {
                            break;
                        }
                    }

                    if (substr($reply, 0, 3) != 250) {
                        $this->log->write('Email Error: HELO not accepted from server');
                        return;
                    }
                }

                fputs($handle, 'MAIL FROM: <' . $from_email . '>' . "\r\n");

                $reply = '';

                while ($line = fgets($handle, 515)) {
                    $reply .= $line;

                    if (substr($line, 3, 1) == ' ') {
                        break;
                    }
                }

                if (substr($reply, 0, 3) != 250) {
                    $this->log->write('Email Error: MAIL FROM not accepted from server');
                    $this->log->write('MAIL FROM: ' . $from_email);
                    return;
                }

                fputs($handle, 'RCPT TO: <' . $to_email . '>' . "\r\n");

                $reply = '';

                while ($line = fgets($handle, 515)) {
                    $reply .= $line;

                    if (substr($line, 3, 1) == ' ') {
                        break;
                    }
                }

                if ((substr($reply, 0, 3) != 250) && (substr($reply, 0, 3) != 251)) {
                    $this->log->write('Email Error: RCPT TO not accepted from server');
                    $this->log->write('RCPT TO: ' . $to_email);
                    return;
                }

                fputs($handle, 'DATA' . "\r\n");

                $reply = '';

                while ($line = fgets($handle, 515)) {
                    $reply .= $line;

                    if (substr($line, 3, 1) == ' ') {
                        break;
                    }
                }

                if (substr($reply, 0, 3) != 354) {
                    $this->log->write('Email Error: DATA not accepted from server');
                    return;
                }

                // According to RFC 821 we should not send more than 1,000 characters (including the CRLF)
                $body = str_replace("\r\n", "\n", $headers . $body);
                $body = str_replace("\r", "\n", $body);

                $lines = explode("\n", $body);

                foreach ($lines as $line) {
                    $results = str_split($line, 998);

                    foreach ($results as $result) {
                        if (substr(PHP_OS, 0, 3) != 'WIN') {
                            fputs($handle, $result . "\r\n");
                        } else {
                            fputs($handle, str_replace("\n", "\r\n", $result) . "\r\n");
                        }
                    }
                }

                fputs($handle, '.' . "\r\n");

                $reply = '';

                while ($line = fgets($handle, 515)) {
                    $reply .= $line;

                    if (substr($line, 3, 1) == ' ') {
                        break;
                    }
                }

                if (substr($reply, 0, 3) != 250) {
                    $this->log->write('Email Error: DATA not accepted from server');
                    return;
                }

                fputs($handle, 'QUIT' . "\r\n");

                $reply = '';

                while ($line = fgets($handle, 515)) {
                    $reply .= $line;

                    if (substr($line, 3, 1) == ' ') {
                        break;
                    }
                }

                if (substr($reply, 0, 3) != 221) {
                    $this->log->write('Email Error: QUIT not accepted from server');
                    return;
                }

                fclose($handle);
            }
        }
    }

    public function changePurpose($from, $to)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "emails` SET `subject` = REPLACE(`subject`,'Commentics','C-o-m-m-e-n-t-i-c-s'), `text` = REPLACE(`text`,'Commentics','C-o-m-m-e-n-t-i-c-s'), `html` = REPLACE(`html`,'Commentics','C-o-m-m-e-n-t-i-c-s') WHERE `language` = 'english'");
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "emails` SET `subject` = REPLACE(`subject`,'" . $this->db->escape($from) . "','" . $this->db->escape($to) . "'), `text` = REPLACE(`text`,'" . $this->db->escape($from) . "','" . $this->db->escape($to) . "'), `html` = REPLACE(`html`,'" . $this->db->escape($from) . "','" . $this->db->escape($to) . "') WHERE `language` = 'english'");
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "emails` SET `subject` = REPLACE(`subject`,'" . $this->db->escape(ucwords($from)) . "','" . $this->db->escape(ucwords($to)) . "'), `text` = REPLACE(`text`,'" . $this->db->escape(ucwords($from)) . "','" . $this->db->escape(ucwords($to)) . "'), `html` = REPLACE(`html`,'" . $this->db->escape(ucwords($from)) . "','" . $this->db->escape(ucwords($to)) . "') WHERE `language` = 'english'");
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "emails` SET `subject` = REPLACE(`subject`,'C-o-m-m-e-n-t-i-c-s','Commentics'), `text` = REPLACE(`text`,'C-o-m-m-e-n-t-i-c-s','Commentics'), `html` = REPLACE(`html`,'C-o-m-m-e-n-t-i-c-s','Commentics') WHERE `language` = 'english'");
    }
}
