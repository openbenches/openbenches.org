<?php
namespace Commentics;

class MainSystemController extends Controller
{
    public function index()
    {
        if (!$this->db->isConnected()) {
            $this->response->redirect('start');
        }

        $this->loadLanguage('main/system');

        $this->loadModel('main/system');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $this->response->redirect('menu');
        }

        $this->data['check'] = $this->model_main_system->check();

        $this->data['page'] = '3';

        $this->components = array('common/header', 'common/footer');

        $this->loadView('main/system');
    }
}
