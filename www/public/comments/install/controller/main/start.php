<?php
namespace Commentics;

class MainStartController extends Controller
{
    public function index()
    {
        $this->loadLanguage('main/start');

        if ($this->session->getId() != '') {
            $this->session->data['cmtx_session_test'] = true;
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if (file_exists('../config.php')) {
                $this->response->redirect('system');
            } else {
                $this->response->redirect('database');
            }
        }

        $this->data['page'] = '1';

        $this->components = array('common/header', 'common/footer');

        $this->loadView('main/start');
    }
}
