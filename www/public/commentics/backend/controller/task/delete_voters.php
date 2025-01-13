<?php
namespace Commentics;

class TaskDeleteVotersController extends Controller
{
    public function index()
    {
        $this->loadLanguage('task/delete_voters');

        $this->loadModel('task/delete_voters');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_task_delete_voters->update($this->request->post);
            }
        }

        if (isset($this->request->post['task_enabled_delete_voters'])) {
            $this->data['task_enabled_delete_voters'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['task_enabled_delete_voters'])) {
            $this->data['task_enabled_delete_voters'] = false;
        } else {
            $this->data['task_enabled_delete_voters'] = $this->setting->get('task_enabled_delete_voters');
        }

        if (isset($this->request->post['days_to_delete_voters'])) {
            $this->data['days_to_delete_voters'] = $this->request->post['days_to_delete_voters'];
        } else {
            $this->data['days_to_delete_voters'] = $this->setting->get('days_to_delete_voters');
        }

        if (isset($this->error['days_to_delete_voters'])) {
            $this->data['error_days_to_delete_voters'] = $this->error['days_to_delete_voters'];
        } else {
            $this->data['error_days_to_delete_voters'] = '';
        }

        $this->components = array('common/header', 'common/footer');

        $this->loadView('task/delete_voters');
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        if (!isset($this->request->post['days_to_delete_voters']) || !$this->validation->isInt($this->request->post['days_to_delete_voters']) || $this->request->post['days_to_delete_voters'] < 1 || $this->request->post['days_to_delete_voters'] > 1000) {
            $this->error['days_to_delete_voters'] = sprintf($this->data['lang_error_range'], 1, 1000);
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
