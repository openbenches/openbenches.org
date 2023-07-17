<?php
namespace Commentics;

class LoginLoginController extends Controller
{
    public function index()
    {
        if ($this->setting->get('ssl_certificate')) {
            if (!$this->url->isHttps()) {
                header('Location: https://' . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI']);

                die();
            }
        }

        $this->loadLanguage('login/login');

        $this->loadModel('login/login');

        $this->loadModel('common/administrator');

        if (!isset($this->session->data['cmtx_admin_id']) && isset($this->request->post['username']) && isset($this->request->post['password'])) {
            if ($this->model_login_login->hasMaxAttempts()) {
                $this->session->data['cmtx_login_message'] = array('type' => 'negative', 'text' => $this->data['lang_error_timeout']);

                $this->response->redirect('login/login');
            }

            $account = $this->model_login_login->validateAccount($this->request->post['username'], $this->request->post['password']);

            if ($account == '1') { // Disabled
                $this->session->data['cmtx_login_message'] = array('type' => 'negative', 'text' => $this->data['lang_error_disabled']);

                $this->response->redirect('login/login');
            } else if ($account == '2') { // Locked
                $this->session->data['cmtx_login_message'] = array('type' => 'negative', 'text' => $this->data['lang_error_locked']);

                $this->response->redirect('login/login');
            } else if ($account == '3') { // Okay
                $this->session->regenerate();

                $admin = $this->model_common_administrator->getAdminByUsername($this->request->post['username']);

                $admin_id = $admin['id'];

                $this->session->data['cmtx_admin_id']   = $admin_id;
                $this->session->data['cmtx_username']   = $this->request->post['username'];
                $this->session->data['cmtx_is_super']   = $admin['is_super'];
                $this->session->data['cmtx_csrf_key']   = $this->variable->random();
                $this->session->data['cmtx_user_agent'] = $this->user->getUserAgent();
                $this->session->data['cmtx_user_lang']  = $this->user->getAcceptLanguage();
                $this->session->data['cmtx_user_ip']    = $this->user->getIpAddress();

                $this->model_login_login->login($admin_id);

                $this->response->redirect('main/dashboard');
            } else { // Wrong
                if (!$this->setting->get('is_demo')) {
                    $this->model_login_login->addAttempt($this->request->post['username']);
                }

                $this->session->data['cmtx_login_message'] = array('type' => 'negative', 'text' => $this->data['lang_error_wrong']);

                $this->response->redirect('login/login');
            }
        } else if (isset($this->session->data['cmtx_admin_id'])) { // currently logged in, no action required.
            $admin = $this->model_common_administrator->getAdmin($this->session->data['cmtx_admin_id']);

            // verify account still exists and still enabled
            if (!$this->model_login_login->isAccountStillValid()) {
                $this->loadModel('login/logout');

                $this->model_login_logout->logout();
            }

            // verify user-agent is the same
            if ($this->session->data['cmtx_user_agent'] != $this->user->getUserAgent()) {
                $this->loadModel('login/logout');

                $this->model_login_logout->logout();
            }

            // verify user-language is the same
            if ($this->session->data['cmtx_user_lang'] != $this->user->getAcceptLanguage()) {
                $this->loadModel('login/logout');

                $this->model_login_logout->logout();
            }

            // verify ip-address is the same
            if ($this->setting->get('check_ip_address') && $this->session->data['cmtx_user_ip'] != $this->user->getIpAddress()) {
                $this->loadModel('login/logout');

                $this->model_login_logout->logout();
            }

            // update whether administrator is a super admin
            $this->session->data['cmtx_is_super'] = $admin['is_super'];

            if (isset($this->request->get['route']) && $this->request->get['route'] == 'login/login') {
                $this->response->redirect('main/dashboard');
            }
        } else {
            if (isset($this->session->data['cmtx_login_message'])) {
                $this->data['message'] = $this->session->data['cmtx_login_message'];

                unset($this->session->data['cmtx_login_message']);
            } else {
                $this->data['message'] = '';
            }

            $this->components = array('common/header', 'common/footer');

            $this->loadView('login/login');

            die();
        }
    }
}
