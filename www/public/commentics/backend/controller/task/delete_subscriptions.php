<?php
namespace Commentics;

class TaskDeleteSubscriptionsController extends Controller
{
    public function index()
    {
        $this->loadLanguage('task/delete_subscriptions');

        $this->loadModel('task/delete_subscriptions');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_task_delete_subscriptions->update($this->request->post);
            }
        }

        if (isset($this->request->post['task_enabled_delete_subscriptions'])) {
            $this->data['task_enabled_delete_subscriptions'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['task_enabled_delete_subscriptions'])) {
            $this->data['task_enabled_delete_subscriptions'] = false;
        } else {
            $this->data['task_enabled_delete_subscriptions'] = $this->setting->get('task_enabled_delete_subscriptions');
        }

        if (isset($this->request->post['days_to_delete_subscriptions'])) {
            $this->data['days_to_delete_subscriptions'] = $this->request->post['days_to_delete_subscriptions'];
        } else {
            $this->data['days_to_delete_subscriptions'] = $this->setting->get('days_to_delete_subscriptions');
        }

        if (isset($this->error['days_to_delete_subscriptions'])) {
            $this->data['error_days_to_delete_subscriptions'] = $this->error['days_to_delete_subscriptions'];
        } else {
            $this->data['error_days_to_delete_subscriptions'] = '';
        }

        $this->components = array('common/header', 'common/footer');

        $this->loadView('task/delete_subscriptions');
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        if (!isset($this->request->post['days_to_delete_subscriptions']) || !$this->validation->isInt($this->request->post['days_to_delete_subscriptions']) || $this->request->post['days_to_delete_subscriptions'] < 1 || $this->request->post['days_to_delete_subscriptions'] > 1000) {
            $this->error['days_to_delete_subscriptions'] = sprintf($this->data['lang_error_range'], 1, 1000);
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
