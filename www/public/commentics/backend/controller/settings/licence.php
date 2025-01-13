<?php
namespace Commentics;

class SettingsLicenceController extends Controller
{
    public function index()
    {
        $this->loadLanguage('settings/licence');

        $this->loadModel('settings/licence');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_settings_licence->update($this->request->post);
            }
        }

        if (isset($this->request->post['licence'])) {
            $this->data['licence'] = $this->request->post['licence'];
        } else {
            $this->data['licence'] = $this->setting->get('licence');
        }

        if (isset($this->request->post['forum_user'])) {
            $this->data['forum_user'] = $this->request->post['forum_user'];
        } else {
            $this->data['forum_user'] = $this->setting->get('forum_user');
        }

        if (isset($this->error['licence'])) {
            $this->data['error_licence'] = $this->error['licence'];
        } else {
            $this->data['error_licence'] = '';
        }

        if (isset($this->error['forum_user'])) {
            $this->data['error_forum_user'] = $this->error['forum_user'];
        } else {
            $this->data['error_forum_user'] = '';
        }

        $this->components = array('common/header', 'common/footer');

        $this->loadView('settings/licence');
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        $this->loadModel('settings/licence');

        if (isset($this->request->post['licence'])) {
            if ($this->request->post['licence']) {
                if (extension_loaded('curl') || (bool) ini_get('allow_url_fopen')) {
                    $check = $this->home->checkLicence($this->request->post['licence'], $this->request->post['forum_user']);

                    $check = json_decode($check, true);

                    if (isset($check['result'])) {
                        if ($check['result'] == 'invalid') {
                            $this->error['licence'] = $check['error'];
                        }
                    } else {
                        $this->error['licence'] = $this->data['lang_error_site_issue'];
                    }
                } else {
                    $this->error['licence'] = $this->data['lang_error_unable'];
                }
            }
        } else {
            $this->error['licence'] = sprintf($this->data['lang_error_length'], 0, 250);
        }

        if (!isset($this->request->post['forum_user']) || $this->validation->length($this->request->post['forum_user']) > 250) {
            $this->error['forum_user'] = sprintf($this->data['lang_error_length'], 0, 250);
        }

        if (empty($this->request->post['licence']) && isset($this->request->post['forum_user']) && $this->request->post['forum_user']) {
            $this->error['forum_user'] = $this->data['lang_error_no_licence'];
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
