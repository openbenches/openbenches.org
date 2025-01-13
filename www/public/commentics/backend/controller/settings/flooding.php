<?php
namespace Commentics;

class SettingsFloodingController extends Controller
{
    public function index()
    {
        $this->loadLanguage('settings/flooding');

        $this->loadModel('settings/flooding');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_settings_flooding->update($this->request->post);
            }
        }

        if (isset($this->request->post['flood_control_delay_enabled'])) {
            $this->data['flood_control_delay_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['flood_control_delay_enabled'])) {
            $this->data['flood_control_delay_enabled'] = false;
        } else {
            $this->data['flood_control_delay_enabled'] = $this->setting->get('flood_control_delay_enabled');
        }

        if (isset($this->request->post['flood_control_delay_time'])) {
            $this->data['flood_control_delay_time'] = $this->request->post['flood_control_delay_time'];
        } else {
            $this->data['flood_control_delay_time'] = $this->setting->get('flood_control_delay_time');
        }

        if (isset($this->request->post['flood_control_delay_all_pages'])) {
            $this->data['flood_control_delay_all_pages'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['flood_control_delay_all_pages'])) {
            $this->data['flood_control_delay_all_pages'] = false;
        } else {
            $this->data['flood_control_delay_all_pages'] = $this->setting->get('flood_control_delay_all_pages');
        }

        if (isset($this->request->post['flood_control_maximum_enabled'])) {
            $this->data['flood_control_maximum_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['flood_control_maximum_enabled'])) {
            $this->data['flood_control_maximum_enabled'] = false;
        } else {
            $this->data['flood_control_maximum_enabled'] = $this->setting->get('flood_control_maximum_enabled');
        }

        if (isset($this->request->post['flood_control_maximum_amount'])) {
            $this->data['flood_control_maximum_amount'] = $this->request->post['flood_control_maximum_amount'];
        } else {
            $this->data['flood_control_maximum_amount'] = $this->setting->get('flood_control_maximum_amount');
        }

        if (isset($this->request->post['flood_control_maximum_period'])) {
            $this->data['flood_control_maximum_period'] = $this->request->post['flood_control_maximum_period'];
        } else {
            $this->data['flood_control_maximum_period'] = $this->setting->get('flood_control_maximum_period');
        }

        if (isset($this->request->post['flood_control_maximum_all_pages'])) {
            $this->data['flood_control_maximum_all_pages'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['flood_control_maximum_all_pages'])) {
            $this->data['flood_control_maximum_all_pages'] = false;
        } else {
            $this->data['flood_control_maximum_all_pages'] = $this->setting->get('flood_control_maximum_all_pages');
        }

        if (isset($this->error['flood_control_delay_time'])) {
            $this->data['error_flood_control_delay_time'] = $this->error['flood_control_delay_time'];
        } else {
            $this->data['error_flood_control_delay_time'] = '';
        }

        if (isset($this->error['flood_control_maximum_amount'])) {
            $this->data['error_flood_control_maximum_amount'] = $this->error['flood_control_maximum_amount'];
        } else {
            $this->data['error_flood_control_maximum_amount'] = '';
        }

        if (isset($this->error['flood_control_maximum_period'])) {
            $this->data['error_flood_control_maximum_period'] = $this->error['flood_control_maximum_period'];
        } else {
            $this->data['error_flood_control_maximum_period'] = '';
        }

        $this->components = array('common/header', 'common/footer');

        $this->loadView('settings/flooding');
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        if (!isset($this->request->post['flood_control_delay_time']) || !$this->validation->isInt($this->request->post['flood_control_delay_time']) || $this->request->post['flood_control_delay_time'] < 1 || $this->request->post['flood_control_delay_time'] > 1000) {
            $this->error['flood_control_delay_time'] = sprintf($this->data['lang_error_range'], 1, 1000);
        }

        if (!isset($this->request->post['flood_control_maximum_amount']) || !$this->validation->isInt($this->request->post['flood_control_maximum_amount']) || $this->request->post['flood_control_maximum_amount'] < 1 || $this->request->post['flood_control_maximum_amount'] > 1000) {
            $this->error['flood_control_maximum_amount'] = sprintf($this->data['lang_error_range'], 1, 1000);
        }

        if (!isset($this->request->post['flood_control_maximum_period']) || !$this->validation->isInt($this->request->post['flood_control_maximum_period']) || $this->request->post['flood_control_maximum_period'] < 1 || $this->request->post['flood_control_maximum_period'] > 1000) {
            $this->error['flood_control_maximum_period'] = sprintf($this->data['lang_error_range'], 1, 1000);
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
