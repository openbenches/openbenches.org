<?php
namespace Commentics;

class SettingsAdministratorController extends Controller
{
    public function index()
    {
        $this->loadLanguage('settings/administrator');

        $this->loadModel('settings/administrator');

        $this->loadModel('common/administrator');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_settings_administrator->update($this->request->post, $this->session->data['cmtx_admin_id']);
            }
        }

        $admin = $this->model_common_administrator->getAdmin($this->session->data['cmtx_admin_id']);

        if (isset($this->request->post['username'])) {
            $this->data['username'] = $this->request->post['username'];
        } else {
            $this->data['username'] = $admin['username'];
        }

        if (isset($this->request->post['email'])) {
            $this->data['email'] = $this->request->post['email'];
        } else {
            $this->data['email'] = $admin['email'];
        }

        if (isset($this->request->post['receive_email_ban'])) {
            $this->data['receive_email_ban'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['receive_email_ban'])) {
            $this->data['receive_email_ban'] = false;
        } else {
            $this->data['receive_email_ban'] = $admin['receive_email_ban'];
        }

        if (isset($this->request->post['receive_email_comment_approve'])) {
            $this->data['receive_email_comment_approve'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['receive_email_comment_approve'])) {
            $this->data['receive_email_comment_approve'] = false;
        } else {
            $this->data['receive_email_comment_approve'] = $admin['receive_email_comment_approve'];
        }

        if (isset($this->request->post['receive_email_comment_success'])) {
            $this->data['receive_email_comment_success'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['receive_email_comment_success'])) {
            $this->data['receive_email_comment_success'] = false;
        } else {
            $this->data['receive_email_comment_success'] = $admin['receive_email_comment_success'];
        }

        if (isset($this->request->post['receive_email_flag'])) {
            $this->data['receive_email_flag'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['receive_email_flag'])) {
            $this->data['receive_email_flag'] = false;
        } else {
            $this->data['receive_email_flag'] = $admin['receive_email_flag'];
        }

        if (isset($this->request->post['receive_email_edit'])) {
            $this->data['receive_email_edit'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['receive_email_edit'])) {
            $this->data['receive_email_edit'] = false;
        } else {
            $this->data['receive_email_edit'] = $admin['receive_email_edit'];
        }

        if (isset($this->request->post['receive_email_delete'])) {
            $this->data['receive_email_delete'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['receive_email_delete'])) {
            $this->data['receive_email_delete'] = false;
        } else {
            $this->data['receive_email_delete'] = $admin['receive_email_delete'];
        }

        if (isset($this->request->post['format'])) {
            $this->data['format'] = $this->request->post['format'];
        } else {
            $this->data['format'] = $admin['format'];
        }

        if (isset($this->error['username'])) {
            $this->data['error_username'] = $this->error['username'];
        } else {
            $this->data['error_username'] = '';
        }

        if (isset($this->error['password'])) {
            $this->data['error_password'] = $this->error['password'];
        } else {
            $this->data['error_password'] = '';
        }

        if (isset($this->error['email'])) {
            $this->data['error_email'] = $this->error['email'];
        } else {
            $this->data['error_email'] = '';
        }

        if (isset($this->error['format'])) {
            $this->data['error_format'] = $this->error['format'];
        } else {
            $this->data['error_format'] = '';
        }

        $this->components = array('common/header', 'common/footer');

        $this->loadView('settings/administrator');
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        $this->loadModel('common/administrator');

        if (!isset($this->request->post['username']) || $this->model_common_administrator->usernameExists($this->request->post['username'], $this->session->data['cmtx_admin_id'])) {
            $this->error['username'] = $this->data['lang_error_username_exists'];
        }

        if (!isset($this->request->post['username']) || $this->validation->length($this->request->post['username']) < 1 || $this->validation->length($this->request->post['username']) > 250) {
            $this->error['username'] = sprintf($this->data['lang_error_length'], 1, 250);
        }

        if (isset($this->request->post['password_1']) && $this->request->post['password_1'] && (!isset($this->request->post['password_2']) || $this->request->post['password_1'] != $this->request->post['password_2'])) {
            $this->error['password'] = $this->data['lang_error_password_mismatch'];
        }

        if (!isset($this->request->post['email']) || !$this->validation->isEmail($this->request->post['email'])) {
            $this->error['email'] = $this->data['lang_error_email_invalid'];
        }

        if (!isset($this->request->post['email']) || $this->model_common_administrator->emailExists($this->request->post['email'], $this->session->data['cmtx_admin_id'])) {
            $this->error['email'] = $this->data['lang_error_email_exists'];
        }

        if (!isset($this->request->post['email']) || $this->validation->length($this->request->post['email']) < 1 || $this->validation->length($this->request->post['email']) > 250) {
            $this->error['email'] = sprintf($this->data['lang_error_length'], 1, 250);
        }

        if (!isset($this->request->post['format']) || !in_array($this->request->post['format'], array('html', 'text'))) {
            $this->error['format'] = $this->data['lang_error_selection'];
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
