<?php
namespace Commentics;

class EditAdminController extends Controller
{
    public function index()
    {
        $this->loadLanguage('edit/admin');

        $this->loadModel('edit/admin');

        $this->loadModel('common/administrator');

        if (!isset($this->request->get['id']) || !$this->model_common_administrator->adminExists($this->request->get['id'])) {
            $this->response->redirect('main/dashboard');
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_edit_admin->update($this->request->post, $this->request->get['id']);

                $this->session->data['cmtx_success'] = $this->data['lang_message_success'];

                $this->response->redirect('manage/admins');
            }
        }

        $admin = $this->model_common_administrator->getAdmin($this->request->get['id']);

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

        if (isset($this->request->post['is_super'])) {
            $this->data['is_super'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['is_super'])) {
            $this->data['is_super'] = false;
        } else {
            $this->data['is_super'] = $admin['is_super'];
        }

        if (isset($this->request->post['is_enabled'])) {
            $this->data['is_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['is_enabled'])) {
            $this->data['is_enabled'] = false;
        } else {
            $this->data['is_enabled'] = $admin['is_enabled'];
        }

        if (isset($this->request->post['restrict_pages'])) {
            $this->data['restrict_pages'] = $this->request->post['restrict_pages'];
        } else {
            $this->data['restrict_pages'] = $admin['restrict_pages'];
        }

        $this->data['last_login'] = $this->variable->formatDate($admin['last_login'], $this->data['lang_date_time_format'], $this->data);

        $this->data['date_added'] = $this->variable->formatDate($admin['date_added'], $this->data['lang_date_time_format'], $this->data);

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

        $this->data['restrictions'] = $this->model_common_administrator->getRestrictions($this->request->get['id']);

        $this->data['id'] = $this->request->get['id'];

        if ($this->session->data['cmtx_is_super'] && $this->request->get['id'] != $this->session->data['cmtx_admin_id']) {
            $this->data['show_extra'] = true;
        } else {
            $this->data['show_extra'] = false;
        }

        if (!$this->session->data['cmtx_is_super'] && $this->request->get['id'] != $this->session->data['cmtx_admin_id']) {
            $this->data['info'] = $this->data['lang_notice'];
        }

        $this->data['link_back'] = $this->url->link('manage/admins');

        $this->components = array('common/header', 'common/footer');

        $this->loadView('edit/admin');
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        if ($this->request->get['id'] != $this->session->data['cmtx_admin_id'] && !$this->session->data['cmtx_is_super']) {
            $this->data['error'] = $this->data['lang_notice'];

            return false;
        }

        $this->loadModel('common/administrator');

        if (!isset($this->request->post['username']) || $this->model_common_administrator->usernameExists($this->request->post['username'], $this->request->get['id'])) {
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

        if (!isset($this->request->post['email']) || $this->model_common_administrator->emailExists($this->request->post['email'], $this->request->get['id'])) {
            $this->error['email'] = $this->data['lang_error_email_exists'];
        }

        if (!isset($this->request->post['email']) || $this->validation->length($this->request->post['email']) < 1 || $this->validation->length($this->request->post['email']) > 250) {
            $this->error['email'] = sprintf($this->data['lang_error_length'], 1, 250);
        }

        if ($this->error) {
            $this->data['error'] = $this->data['lang_message_error'];

            return false;
        } else {
            return true;
        }
    }
}
