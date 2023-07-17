<?php
namespace Commentics;

class TaskDeleteReportersController extends Controller
{
    public function index()
    {
        $this->loadLanguage('task/delete_reporters');

        $this->loadModel('task/delete_reporters');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_task_delete_reporters->update($this->request->post);
            }
        }

        if (isset($this->request->post['task_enabled_delete_reporters'])) {
            $this->data['task_enabled_delete_reporters'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['task_enabled_delete_reporters'])) {
            $this->data['task_enabled_delete_reporters'] = false;
        } else {
            $this->data['task_enabled_delete_reporters'] = $this->setting->get('task_enabled_delete_reporters');
        }

        if (isset($this->request->post['days_to_delete_reporters'])) {
            $this->data['days_to_delete_reporters'] = $this->request->post['days_to_delete_reporters'];
        } else {
            $this->data['days_to_delete_reporters'] = $this->setting->get('days_to_delete_reporters');
        }

        if (isset($this->error['days_to_delete_reporters'])) {
            $this->data['error_days_to_delete_reporters'] = $this->error['days_to_delete_reporters'];
        } else {
            $this->data['error_days_to_delete_reporters'] = '';
        }

        $this->components = array('common/header', 'common/footer');

        $this->loadView('task/delete_reporters');
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        if (!isset($this->request->post['days_to_delete_reporters']) || !$this->validation->isInt($this->request->post['days_to_delete_reporters']) || $this->request->post['days_to_delete_reporters'] < 1 || $this->request->post['days_to_delete_reporters'] > 1000) {
            $this->error['days_to_delete_reporters'] = sprintf($this->data['lang_error_range'], 1, 1000);
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
