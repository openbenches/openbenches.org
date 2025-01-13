<?php
namespace Commentics;

class SettingsSystemController extends Controller
{
    public function index()
    {
        $this->loadLanguage('settings/system');

        $this->loadModel('settings/system');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_settings_system->update($this->request->post);

                $this->setting->set('purpose', $this->request->post['purpose']);
            }
        }

        if (isset($this->request->post['site_name'])) {
            $this->data['site_name'] = $this->request->post['site_name'];
        } else {
            $this->data['site_name'] = $this->setting->get('site_name');
        }

        if (isset($this->request->post['site_domain'])) {
            $this->data['site_domain'] = $this->request->post['site_domain'];
        } else {
            $this->data['site_domain'] = $this->setting->get('site_domain');
        }

        if (isset($this->request->post['site_url'])) {
            $this->data['site_url'] = $this->request->post['site_url'];
        } else {
            $this->data['site_url'] = $this->setting->get('site_url');
        }

        if (isset($this->request->post['time_zone'])) {
            $this->data['time_zone'] = $this->request->post['time_zone'];
        } else {
            $this->data['time_zone'] = $this->setting->get('time_zone');
        }

        if (isset($this->request->post['commentics_folder'])) {
            $this->data['commentics_folder'] = $this->request->post['commentics_folder'];
        } else {
            $this->data['commentics_folder'] = $this->setting->get('commentics_folder');
        }

        if (isset($this->request->post['commentics_url'])) {
            $this->data['commentics_url'] = $this->request->post['commentics_url'];
        } else {
            $this->data['commentics_url'] = $this->setting->get('commentics_url');
        }

        if (isset($this->request->post['backend_folder'])) {
            $this->data['backend_folder'] = $this->request->post['backend_folder'];
        } else {
            $this->data['backend_folder'] = $this->setting->get('backend_folder');
        }

        if (isset($this->request->post['purpose'])) {
            $this->data['purpose'] = $this->request->post['purpose'];
        } else {
            $this->data['purpose'] = $this->setting->get('purpose');
        }

        if (isset($this->request->post['use_wysiwyg'])) {
            $this->data['use_wysiwyg'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['use_wysiwyg'])) {
            $this->data['use_wysiwyg'] = false;
        } else {
            $this->data['use_wysiwyg'] = $this->setting->get('use_wysiwyg');
        }

        if (isset($this->request->post['display_parsing'])) {
            $this->data['display_parsing'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['display_parsing'])) {
            $this->data['display_parsing'] = false;
        } else {
            $this->data['display_parsing'] = $this->setting->get('display_parsing');
        }

        if (isset($this->request->post['empty_pages'])) {
            $this->data['empty_pages'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['empty_pages'])) {
            $this->data['empty_pages'] = false;
        } else {
            $this->data['empty_pages'] = $this->setting->get('empty_pages');
        }

        if (isset($this->request->post['limit_results'])) {
            $this->data['limit_results'] = $this->request->post['limit_results'];
        } else {
            $this->data['limit_results'] = $this->setting->get('limit_results');
        }

        if (isset($this->request->post['admin_cookie_days'])) {
            $this->data['admin_cookie_days'] = $this->request->post['admin_cookie_days'];
        } else {
            $this->data['admin_cookie_days'] = $this->setting->get('admin_cookie_days');
        }

        if (isset($this->error['site_name'])) {
            $this->data['error_site_name'] = $this->error['site_name'];
        } else {
            $this->data['error_site_name'] = '';
        }

        if (isset($this->error['site_domain'])) {
            $this->data['error_site_domain'] = $this->error['site_domain'];
        } else {
            $this->data['error_site_domain'] = '';
        }

        if (isset($this->error['site_url'])) {
            $this->data['error_site_url'] = $this->error['site_url'];
        } else {
            $this->data['error_site_url'] = '';
        }

        if (isset($this->error['time_zone'])) {
            $this->data['error_time_zone'] = $this->error['time_zone'];
        } else {
            $this->data['error_time_zone'] = '';
        }

        if (isset($this->error['commentics_folder'])) {
            $this->data['error_commentics_folder'] = $this->error['commentics_folder'];
        } else {
            $this->data['error_commentics_folder'] = '';
        }

        if (isset($this->error['commentics_url'])) {
            $this->data['error_commentics_url'] = $this->error['commentics_url'];
        } else {
            $this->data['error_commentics_url'] = '';
        }

        if (isset($this->error['backend_folder'])) {
            $this->data['error_backend_folder'] = $this->error['backend_folder'];
        } else {
            $this->data['error_backend_folder'] = '';
        }

        if (isset($this->error['purpose'])) {
            $this->data['error_purpose'] = $this->error['purpose'];
        } else {
            $this->data['error_purpose'] = '';
        }

        if (isset($this->error['limit_results'])) {
            $this->data['error_limit_results'] = $this->error['limit_results'];
        } else {
            $this->data['error_limit_results'] = '';
        }

        if (isset($this->error['admin_cookie_days'])) {
            $this->data['error_admin_cookie_days'] = $this->error['admin_cookie_days'];
        } else {
            $this->data['error_admin_cookie_days'] = '';
        }

        $this->data['lang_text_comments'] = $this->variable->fixCase($this->data['lang_type_comments']);
        $this->data['lang_text_reviews'] = $this->variable->fixCase($this->data['lang_type_reviews']);
        $this->data['lang_text_testimonials'] = $this->variable->fixCase($this->data['lang_type_testimonials']);

        $this->data['zones'] = $this->model_settings_system->get_time_zones();

        $this->components = array('common/header', 'common/footer');

        $this->loadView('settings/system');
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        $this->loadModel('settings/system');

        if (!isset($this->request->post['site_name']) || $this->validation->length($this->request->post['site_name']) < 1 || $this->validation->length($this->request->post['site_name']) > 250) {
            $this->error['site_name'] = sprintf($this->data['lang_error_length'], 1, 250);
        }

        if (!isset($this->request->post['site_domain']) || $this->validation->length($this->request->post['site_domain']) < 1 || $this->validation->length($this->request->post['site_domain']) > 250) {
            $this->error['site_domain'] = sprintf($this->data['lang_error_length'], 1, 250);
        }

        if (!isset($this->request->post['site_url']) || !$this->validation->isUrl($this->request->post['site_url'])) {
            $this->error['site_url'] = $this->data['lang_error_url'];
        }

        if (!isset($this->request->post['site_url']) || $this->validation->length($this->request->post['site_url']) < 1 || $this->validation->length($this->request->post['site_url']) > 250) {
            $this->error['site_url'] = sprintf($this->data['lang_error_length'], 1, 250);
        }

        if (!isset($this->request->post['time_zone']) || !in_array($this->request->post['time_zone'], $this->model_settings_system->get_time_zones())) {
            $this->error['time_zone'] = $this->data['lang_error_zone'];
        }

        if (!isset($this->request->post['commentics_folder']) || !$this->validation->isFolder($this->request->post['commentics_folder'])) {
            $this->error['commentics_folder'] = $this->data['lang_error_folder'];
        }

        if (!isset($this->request->post['commentics_folder']) || $this->validation->length($this->request->post['commentics_folder']) < 1 || $this->validation->length($this->request->post['commentics_folder']) > 250) {
            $this->error['commentics_folder'] = sprintf($this->data['lang_error_length'], 1, 250);
        }

        if (!isset($this->request->post['commentics_url']) || !$this->validation->isUrl($this->request->post['commentics_url'])) {
            $this->error['commentics_url'] = $this->data['lang_error_url'];
        }

        if (!isset($this->request->post['commentics_url']) || $this->validation->length($this->request->post['commentics_url']) < 1 || $this->validation->length($this->request->post['commentics_url']) > 250) {
            $this->error['commentics_url'] = sprintf($this->data['lang_error_length'], 1, 250);
        }

        if (!isset($this->request->post['backend_folder']) || !$this->validation->isFolder($this->request->post['backend_folder'])) {
            $this->error['backend_folder'] = $this->data['lang_error_folder'];
        }

        if (!isset($this->request->post['backend_folder']) || $this->validation->length($this->request->post['backend_folder']) < 1 || $this->validation->length($this->request->post['backend_folder']) > 250) {
            $this->error['backend_folder'] = sprintf($this->data['lang_error_length'], 1, 250);
        }

        if (!isset($this->request->post['purpose']) || !in_array($this->request->post['purpose'], array('comment', 'review', 'testimonial'))) {
            $this->error['purpose'] = $this->data['lang_error_purpose'];
        }

        if (!isset($this->request->post['limit_results']) || !$this->validation->isInt($this->request->post['limit_results']) || $this->request->post['limit_results'] < 5 || $this->request->post['limit_results'] > 1000) {
            $this->error['limit_results'] = sprintf($this->data['lang_error_range'], 5, 1000);
        }

        if (!isset($this->request->post['admin_cookie_days']) || !$this->validation->isInt($this->request->post['admin_cookie_days']) || $this->request->post['admin_cookie_days'] < 1 || $this->request->post['admin_cookie_days'] > 1000) {
            $this->error['admin_cookie_days'] = sprintf($this->data['lang_error_range'], 1, 1000);
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
