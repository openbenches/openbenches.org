<?php
namespace Commentics;

class SettingsSecurityController extends Controller
{
    public function index()
    {
        $this->loadLanguage('settings/security');

        $this->loadModel('settings/security');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_settings_security->update($this->request->post);
            }
        }

        if (isset($this->request->post['check_referrer'])) {
            $this->data['check_referrer'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['check_referrer'])) {
            $this->data['check_referrer'] = false;
        } else {
            $this->data['check_referrer'] = $this->setting->get('check_referrer');
        }

        if (isset($this->request->post['check_config'])) {
            $this->data['check_config'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['check_config'])) {
            $this->data['check_config'] = false;
        } else {
            $this->data['check_config'] = $this->setting->get('check_config');
        }

        if (isset($this->request->post['check_honeypot'])) {
            $this->data['check_honeypot'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['check_honeypot'])) {
            $this->data['check_honeypot'] = false;
        } else {
            $this->data['check_honeypot'] = $this->setting->get('check_honeypot');
        }

        if (isset($this->request->post['check_time'])) {
            $this->data['check_time'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['check_time'])) {
            $this->data['check_time'] = false;
        } else {
            $this->data['check_time'] = $this->setting->get('check_time');
        }

        if (isset($this->request->post['check_ip_address'])) {
            $this->data['check_ip_address'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['check_ip_address'])) {
            $this->data['check_ip_address'] = false;
        } else {
            $this->data['check_ip_address'] = $this->setting->get('check_ip_address');
        }

        if (isset($this->request->post['ssl_certificate'])) {
            $this->data['ssl_certificate'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['ssl_certificate'])) {
            $this->data['ssl_certificate'] = false;
        } else {
            $this->data['ssl_certificate'] = $this->setting->get('ssl_certificate');
        }

        if (isset($this->request->post['ban_cookie_days'])) {
            $this->data['ban_cookie_days'] = $this->request->post['ban_cookie_days'];
        } else {
            $this->data['ban_cookie_days'] = $this->setting->get('ban_cookie_days');
        }

        if (isset($this->error['check_referrer'])) {
            $this->data['error_check_referrer'] = $this->error['check_referrer'];
        } else {
            $this->data['error_check_referrer'] = '';
        }

        if (isset($this->error['ban_cookie_days'])) {
            $this->data['error_ban_cookie_days'] = $this->error['ban_cookie_days'];
        } else {
            $this->data['error_ban_cookie_days'] = '';
        }

        $this->components = array('common/header', 'common/footer');

        $this->loadView('settings/security');
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        if (isset($this->request->post['check_referrer'])) {
            $url = $this->url->decode($this->url->getPageUrl());

            $domain = $this->url->decode($this->setting->get('site_domain'));

            if (!$this->variable->stristr($url, $domain)) { // if URL does not contain domain
                $this->error['check_referrer'] = $this->data['lang_error_check_referrer'];
            }
        }

        if (!isset($this->request->post['ban_cookie_days']) || !$this->validation->isInt($this->request->post['ban_cookie_days']) || $this->request->post['ban_cookie_days'] < 1 || $this->request->post['ban_cookie_days'] > 1000) {
            $this->error['ban_cookie_days'] = sprintf($this->data['lang_error_range'], 1, 1000);
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
