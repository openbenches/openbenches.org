<?php
namespace Commentics;

class MainInstall2Controller extends Controller
{
    public function index()
    {
        if (!$this->db->isConnected() || $this->db->isInstalled()) {
            $this->response->redirect('start');
        }

        $this->loadLanguage('main/install_2');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $this->loadModel('main/install_2');

            $this->model_main_install_2->install();

            if ($this->db->getQueryError()) {
                $this->data['error'] = $this->db->getQueryError();
            } else {
                $this->data['lang_info_backend'] = sprintf($this->data['lang_info_backend'], '../' . $this->model_main_install_2->getBackendFolder());
            }
        } else {
            $this->response->redirect('start');
        }

        $this->data['page'] = '6';

        $this->components = array('common/header', 'common/footer');

        $this->loadView('main/install_2');
    }
}
