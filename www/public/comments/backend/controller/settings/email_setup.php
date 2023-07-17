<?php
namespace Commentics;

class SettingsEmailSetupController extends Controller
{
    public function index()
    {
        $this->loadLanguage('settings/email_setup');

        $this->loadModel('settings/email_setup');

        $this->loadModel('common/administrator');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_settings_email_setup->update($this->request->post, $this->session->data['cmtx_username'], $this->session->data['cmtx_admin_id']);
            }
        }

        $admin = $this->model_common_administrator->getAdmin($this->session->data['cmtx_admin_id']);

        $this->data['lang_description_test'] = sprintf($this->data['lang_description_test'], $admin['email']);

        if (isset($this->request->post['transport_method'])) {
            $this->data['transport_method'] = $this->request->post['transport_method'];
        } else {
            $this->data['transport_method'] = $this->setting->get('transport_method');
        }

        if (isset($this->request->post['smtp_host'])) {
            $this->data['smtp_host'] = $this->request->post['smtp_host'];
        } else {
            $this->data['smtp_host'] = $this->setting->get('smtp_host');
        }

        if (isset($this->request->post['smtp_port'])) {
            $this->data['smtp_port'] = $this->request->post['smtp_port'];
        } else {
            $this->data['smtp_port'] = $this->setting->get('smtp_port');
        }

        if (isset($this->request->post['smtp_encrypt'])) {
            $this->data['smtp_encrypt'] = $this->request->post['smtp_encrypt'];
        } else {
            $this->data['smtp_encrypt'] = $this->setting->get('smtp_encrypt');
        }

        if (isset($this->request->post['smtp_timeout'])) {
            $this->data['smtp_timeout'] = $this->request->post['smtp_timeout'];
        } else {
            $this->data['smtp_timeout'] = $this->setting->get('smtp_timeout');
        }

        if (isset($this->request->post['smtp_username'])) {
            $this->data['smtp_username'] = $this->request->post['smtp_username'];
        } else {
            $this->data['smtp_username'] = $this->setting->get('smtp_username');
        }

        if (isset($this->request->post['smtp_password'])) {
            $this->data['smtp_password'] = $this->request->post['smtp_password'];
        } else {
            $this->data['smtp_password'] = $this->setting->get('smtp_password');
        }

        if (isset($this->request->post['from_name'])) {
            $this->data['from_name'] = $this->request->post['from_name'];
        } else {
            $this->data['from_name'] = $this->setting->get('from_name');
        }

        if (isset($this->request->post['from_email'])) {
            $this->data['from_email'] = $this->request->post['from_email'];
        } else {
            $this->data['from_email'] = $this->setting->get('from_email');
        }

        if (isset($this->request->post['reply_email'])) {
            $this->data['reply_email'] = $this->request->post['reply_email'];
        } else {
            $this->data['reply_email'] = $this->setting->get('reply_email');
        }

        if (isset($this->request->post['signature_text'])) {
            $this->data['signature_text'] = $this->request->post['signature_text'];
        } else {
            $this->data['signature_text'] = $this->model_settings_email_setup->getSignatureText();
        }

        if (isset($this->request->post['signature_html'])) {
            $this->data['signature_html'] = $this->request->post['signature_html'];
        } else {
            $this->data['signature_html'] = $this->model_settings_email_setup->getSignatureHtml();
        }

        if (isset($this->error['transport_method'])) {
            $this->data['error_transport_method'] = $this->error['transport_method'];
        } else {
            $this->data['error_transport_method'] = '';
        }

        if (isset($this->error['smtp_host'])) {
            $this->data['error_smtp_host'] = $this->error['smtp_host'];
        } else {
            $this->data['error_smtp_host'] = '';
        }

        if (isset($this->error['smtp_port'])) {
            $this->data['error_smtp_port'] = $this->error['smtp_port'];
        } else {
            $this->data['error_smtp_port'] = '';
        }

        if (isset($this->error['smtp_encrypt'])) {
            $this->data['error_smtp_encrypt'] = $this->error['smtp_encrypt'];
        } else {
            $this->data['error_smtp_encrypt'] = '';
        }

        if (isset($this->error['smtp_timeout'])) {
            $this->data['error_smtp_timeout'] = $this->error['smtp_timeout'];
        } else {
            $this->data['error_smtp_timeout'] = '';
        }

        if (isset($this->error['smtp_username'])) {
            $this->data['error_smtp_username'] = $this->error['smtp_username'];
        } else {
            $this->data['error_smtp_username'] = '';
        }

        if (isset($this->error['smtp_password'])) {
            $this->data['error_smtp_password'] = $this->error['smtp_password'];
        } else {
            $this->data['error_smtp_password'] = '';
        }

        if (isset($this->error['sendmail_path'])) {
            $this->data['error_sendmail_path'] = $this->error['sendmail_path'];
        } else {
            $this->data['error_sendmail_path'] = '';
        }

        if (isset($this->error['from_name'])) {
            $this->data['error_from_name'] = $this->error['from_name'];
        } else {
            $this->data['error_from_name'] = '';
        }

        if (isset($this->error['from_email'])) {
            $this->data['error_from_email'] = $this->error['from_email'];
        } else {
            $this->data['error_from_email'] = '';
        }

        if (isset($this->error['reply_email'])) {
            $this->data['error_reply_email'] = $this->error['reply_email'];
        } else {
            $this->data['error_reply_email'] = '';
        }

        if (isset($this->error['signature_text'])) {
            $this->data['error_signature_text'] = $this->error['signature_text'];
        } else {
            $this->data['error_signature_text'] = '';
        }

        if (isset($this->error['signature_html'])) {
            $this->data['error_signature_html'] = $this->error['signature_html'];
        } else {
            $this->data['error_signature_html'] = '';
        }

        $this->components = array('common/header', 'common/footer');

        $this->loadView('settings/email_setup');
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        if (!isset($this->request->post['transport_method']) || !in_array($this->request->post['transport_method'], array('php', 'smtp'))) {
            $this->error['transport_method'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['smtp_host']) || $this->validation->length($this->request->post['smtp_host']) < 1 || $this->validation->length($this->request->post['smtp_host']) > 250) {
            $this->error['smtp_host'] = sprintf($this->data['lang_error_length'], 1, 250);
        }

        if (!isset($this->request->post['smtp_port']) || !$this->validation->isInt($this->request->post['smtp_port']) || $this->request->post['smtp_port'] < 1 || $this->request->post['smtp_port'] > 1000) {
            $this->error['smtp_port'] = sprintf($this->data['lang_error_range'], 1, 1000);
        }

        if (!isset($this->request->post['smtp_encrypt']) || !in_array($this->request->post['smtp_encrypt'], array('SSL', 'TLS'))) {
            $this->error['smtp_encrypt'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['smtp_timeout']) || !$this->validation->isInt($this->request->post['smtp_timeout']) || $this->request->post['smtp_timeout'] < 1 || $this->request->post['smtp_timeout'] > 60) {
            $this->error['smtp_timeout'] = sprintf($this->data['lang_error_range'], 1, 60);
        }

        if (!isset($this->request->post['smtp_username']) || $this->validation->length($this->request->post['smtp_username']) > 250) {
            $this->error['smtp_username'] = sprintf($this->data['lang_error_length'], 0, 250);
        }

        if (!isset($this->request->post['smtp_password']) || $this->validation->length($this->request->post['smtp_password']) > 250) {
            $this->error['smtp_password'] = sprintf($this->data['lang_error_length'], 0, 250);
        }

        if (!isset($this->request->post['from_name']) || $this->validation->length($this->request->post['from_name']) < 1 || $this->validation->length($this->request->post['from_name']) > 250) {
            $this->error['from_name'] = sprintf($this->data['lang_error_length'], 1, 250);
        }

        if (isset($this->request->post['from_email']) && !empty($this->request->post['from_email']) && !$this->validation->isEmail($this->request->post['from_email'])) {
            $this->error['from_email'] = $this->data['lang_error_email_invalid'];
        }

        if (!isset($this->request->post['from_email']) || $this->validation->length($this->request->post['from_email']) < 1 || $this->validation->length($this->request->post['from_email']) > 250) {
            $this->error['from_email'] = sprintf($this->data['lang_error_length'], 1, 250);
        }

        if (isset($this->request->post['reply_email']) && !empty($this->request->post['reply_email']) && !$this->validation->isEmail($this->request->post['reply_email'])) {
            $this->error['reply_email'] = $this->data['lang_error_email_invalid'];
        }

        if (!isset($this->request->post['reply_email']) || $this->validation->length($this->request->post['reply_email']) < 1 || $this->validation->length($this->request->post['reply_email']) > 250) {
            $this->error['reply_email'] = sprintf($this->data['lang_error_length'], 1, 250);
        }

        if (!isset($this->request->post['signature_text']) || $this->validation->length($this->request->post['signature_text']) > 1000) {
            $this->error['signature_text'] = sprintf($this->data['lang_error_length'], 0, 1000);
        }

        if (!isset($this->request->post['signature_html']) || $this->validation->length($this->request->post['signature_html']) > 1000) {
            $this->error['signature_html'] = sprintf($this->data['lang_error_length'], 0, 1000);
        }

        if ($this->error) {
            $this->data['error'] = $this->data['lang_message_error'];

            return false;
        } else {
            $this->data['success'] = $this->data['lang_message_success'];

            return true;
        }
    }
}
