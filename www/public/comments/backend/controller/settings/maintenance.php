<?php
namespace Commentics;

class SettingsMaintenanceController extends Controller
{
    public function index()
    {
        $this->loadLanguage('settings/maintenance');

        $this->loadModel('settings/maintenance');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_settings_maintenance->update($this->request->post);
            }
        }

        if (isset($this->request->post['maintenance_mode'])) {
            $this->data['maintenance_mode'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['maintenance_mode'])) {
            $this->data['maintenance_mode'] = false;
        } else {
            $this->data['maintenance_mode'] = $this->setting->get('maintenance_mode');
        }

        if (isset($this->request->post['maintenance_message'])) {
            $this->data['maintenance_message'] = $this->request->post['maintenance_message'];
        } else {
            $this->data['maintenance_message'] = $this->setting->get('maintenance_message');
        }

        if (isset($this->error['maintenance_message'])) {
            $this->data['error_maintenance_message'] = $this->error['maintenance_message'];
        } else {
            $this->data['error_maintenance_message'] = '';
        }

        $this->components = array('common/header', 'common/footer');

        $this->loadView('settings/maintenance');
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        if (!isset($this->request->post['maintenance_message']) || $this->validation->length($this->request->post['maintenance_message']) < 1 || $this->validation->length($this->request->post['maintenance_message']) > 250) {
            $this->error['maintenance_message'] = sprintf($this->data['lang_error_length'], 1, 250);
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
