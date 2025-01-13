<?php
namespace Commentics;

class TaskDeleteCommentsController extends Controller
{
    public function index()
    {
        $this->loadLanguage('task/delete_comments');

        $this->loadModel('task/delete_comments');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_task_delete_comments->update($this->request->post);
            }
        }

        if (isset($this->request->post['task_enabled_delete_comments'])) {
            $this->data['task_enabled_delete_comments'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['task_enabled_delete_comments'])) {
            $this->data['task_enabled_delete_comments'] = false;
        } else {
            $this->data['task_enabled_delete_comments'] = $this->setting->get('task_enabled_delete_comments');
        }

        if (isset($this->request->post['days_to_delete_comments'])) {
            $this->data['days_to_delete_comments'] = $this->request->post['days_to_delete_comments'];
        } else {
            $this->data['days_to_delete_comments'] = $this->setting->get('days_to_delete_comments');
        }

        if (isset($this->error['days_to_delete_comments'])) {
            $this->data['error_days_to_delete_comments'] = $this->error['days_to_delete_comments'];
        } else {
            $this->data['error_days_to_delete_comments'] = '';
        }

        $this->components = array('common/header', 'common/footer');

        $this->loadView('task/delete_comments');
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        if (!isset($this->request->post['days_to_delete_comments']) || !$this->validation->isInt($this->request->post['days_to_delete_comments']) || $this->request->post['days_to_delete_comments'] < 1 || $this->request->post['days_to_delete_comments'] > 1000) {
            $this->error['days_to_delete_comments'] = sprintf($this->data['lang_error_range'], 1, 1000);
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
