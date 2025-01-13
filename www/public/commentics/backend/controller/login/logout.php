<?php
namespace Commentics;

class LoginLogoutController extends Controller
{
    public function index()
    {
        $this->loadModel('login/logout');

        $this->model_login_logout->logout();
    }
}
