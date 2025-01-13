<?php
namespace Commentics;

class MainMenuController extends Controller
{
    public function index()
    {
        if (!$this->db->isConnected()) {
            $this->response->redirect('start');
        }

        $this->loadLanguage('main/menu');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->request->post['action'] == 'install') {
                $this->response->redirect('install_1');
            } else {
                $this->response->redirect('upgrade_1');
            }
        }

        $this->data['page'] = '4';

        $this->db->query("SET @@global.sql_mode = ''");

        $this->components = array('common/header', 'common/footer');

        $this->loadView('main/menu');
    }
}
