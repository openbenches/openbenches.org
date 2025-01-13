<?php
namespace Commentics;

class ModuleMergeUsersController extends Controller
{
    public function index()
    {
        $this->loadLanguage('module/merge_users');

        $this->loadModel('module/merge_users');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_module_merge_users->merge($this->request->post);
            }
        }

        if (isset($this->request->post['user_id_from'])) {
            $this->data['user_id_from'] = $this->request->post['user_id_from'];
        } else {
            $this->data['user_id_from'] = 0;
        }

        if (isset($this->request->post['user_id_to'])) {
            $this->data['user_id_to'] = $this->request->post['user_id_to'];
        } else {
            $this->data['user_id_to'] = 0;
        }

        if (isset($this->error['user_id_from'])) {
            $this->data['error_user_id_from'] = $this->error['user_id_from'];
        } else {
            $this->data['error_user_id_from'] = '';
        }

        if (isset($this->error['user_id_to'])) {
            $this->data['error_user_id_to'] = $this->error['user_id_to'];
        } else {
            $this->data['error_user_id_to'] = '';
        }

        $users = $this->user->getUsers('name');

        foreach ($users as &$user) {
            $info = $user['name'];

            if ($user['email']) {
                $info .= ' (' . $user['email'] . ')';
            }

            $user['info'] = $info;
        }

        $this->data['users'] = $users;

        $this->data['link_back'] = $this->url->link('extension/modules');

        $this->components = array('common/header', 'common/footer');

        $this->loadView('module/merge_users');
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        if (empty($this->request->post['user_id_from'])) {
            $this->error['user_id_from'] = $this->data['lang_error_selection'];
        }

        if (empty($this->request->post['user_id_to'])) {
            $this->error['user_id_to'] = $this->data['lang_error_selection'];
        }

        if (!$this->error) {
            if ($this->request->post['user_id_from'] == $this->request->post['user_id_to']) {
                $this->error['user_id_from'] = $this->data['lang_error_same'];

                $this->error['user_id_to'] = $this->data['lang_error_same'];
            }
        }

        if (!$this->error) {
            if (!$this->user->userExists($this->request->post['user_id_from'])) {
                $this->error['user_id_from'] = $this->data['lang_error_exists'];
            }

            if (!$this->user->userExists($this->request->post['user_id_to'])) {
                $this->error['user_id_to'] = $this->data['lang_error_exists'];
            }
        }

        if ($this->error) {
            $this->data['error'] = $this->data['lang_message_error'];

            return false;
        } else {
            $this->data['success'] = $this->data['lang_message_merge'];

            return true;
        }
    }
}
