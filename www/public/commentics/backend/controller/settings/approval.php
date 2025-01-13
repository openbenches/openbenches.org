<?php
namespace Commentics;

class SettingsApprovalController extends Controller
{
    public function index()
    {
        $this->loadLanguage('settings/approval');

        $this->loadModel('settings/approval');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_settings_approval->update($this->request->post);
            }
        }

        if (isset($this->request->post['approve_comments'])) {
            $this->data['approve_comments'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['approve_comments'])) {
            $this->data['approve_comments'] = false;
        } else {
            $this->data['approve_comments'] = $this->setting->get('approve_comments');
        }

        if (isset($this->request->post['approve_notifications'])) {
            $this->data['approve_notifications'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['approve_notifications'])) {
            $this->data['approve_notifications'] = false;
        } else {
            $this->data['approve_notifications'] = $this->setting->get('approve_notifications');
        }

        if (isset($this->request->post['trust_previous_users'])) {
            $this->data['trust_previous_users'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['trust_previous_users'])) {
            $this->data['trust_previous_users'] = false;
        } else {
            $this->data['trust_previous_users'] = $this->setting->get('trust_previous_users');
        }

        $this->components = array('common/header', 'common/footer');

        $this->loadView('settings/approval');
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
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
