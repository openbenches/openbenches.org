<?php
namespace Commentics;

class TaskDeleteBansController extends Controller
{
    public function index()
    {
        $this->loadLanguage('task/delete_bans');

        $this->loadModel('task/delete_bans');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_task_delete_bans->update($this->request->post);
            }
        }

        if (isset($this->request->post['task_enabled_delete_bans'])) {
            $this->data['task_enabled_delete_bans'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['task_enabled_delete_bans'])) {
            $this->data['task_enabled_delete_bans'] = false;
        } else {
            $this->data['task_enabled_delete_bans'] = $this->setting->get('task_enabled_delete_bans');
        }

        if (isset($this->request->post['days_to_delete_bans'])) {
            $this->data['days_to_delete_bans'] = $this->request->post['days_to_delete_bans'];
        } else {
            $this->data['days_to_delete_bans'] = $this->setting->get('days_to_delete_bans');
        }

        if (isset($this->error['days_to_delete_bans'])) {
            $this->data['error_days_to_delete_bans'] = $this->error['days_to_delete_bans'];
        } else {
            $this->data['error_days_to_delete_bans'] = '';
        }

        $this->components = array('common/header', 'common/footer');

        $this->loadView('task/delete_bans');
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        if (!isset($this->request->post['days_to_delete_bans']) || !$this->validation->isInt($this->request->post['days_to_delete_bans']) || $this->request->post['days_to_delete_bans'] < 1 || $this->request->post['days_to_delete_bans'] > 1000) {
            $this->error['days_to_delete_bans'] = sprintf($this->data['lang_error_range'], 1, 1000);
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
