<?php
namespace Commentics;

class LoginLogoutModel extends Model
{
    public function logout()
    {
        $this->session->regenerate();

        $this->session->end();

        $this->response->redirect('login/login');
    }
}
