<?php
namespace Commentics;

class LoginResetController extends Controller
{
    public function index()
    {
        if ($this->setting->get('ssl_certificate')) {
            if (!$this->url->isHttps()) {
                header('Location: https://' . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI']);

                die();
            }
        }

        $this->loadLanguage('login/reset');

        $this->loadModel('login/reset');

        $this->loadModel('common/administrator');

        if (isset($this->session->data['cmtx_admin_id'])) {
            $this->response->redirect('main/dashboard');
        }

        if (isset($this->request->post['email'])) {
            if ($this->setting->get('is_demo')) {
                $this->session->data['cmtx_reset_message'] = array('type' => 'negative', 'text' => $this->data['lang_error_demo']);
            } else {
                $email = $this->request->post['email'];

                if ($this->model_common_administrator->emailExists($email)) {
                    $admin = $this->model_common_administrator->getAdminByEmail($email);

                    if ($admin['resets'] >= 5) {
                        $this->session->data['cmtx_reset_message'] = array('type' => 'negative', 'text' => $this->data['lang_error_limit']);
                    } else {
                        $password = $this->variable->random(10);

                        $this->model_login_reset->sendReset($admin['username'], $password, $email, $admin['format']);

                        $this->model_login_reset->updatePassword($password, $email);

                        $this->model_login_reset->updateReset($email);

                        $this->session->data['cmtx_reset_message'] = array('type' => 'positive', 'text' => $this->data['lang_success_sent']);
                    }
                } else {
                    $this->session->data['cmtx_reset_message'] = array('type' => 'negative', 'text' => $this->data['lang_error_none']);
                }
            }
        }

        if (isset($this->session->data['cmtx_reset_message'])) {
            $this->data['message'] = $this->session->data['cmtx_reset_message'];

            unset($this->session->data['cmtx_reset_message']);
        } else {
            $this->data['message'] = '';
        }

        $this->components = array('common/header', 'common/footer');

        $this->loadView('login/reset');

        die();
    }
}
