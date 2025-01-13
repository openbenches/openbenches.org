<?php
namespace Commentics;

class MainUpgrade1Controller extends Controller
{
    public function index()
    {
        if (!$this->db->isConnected()) {
            $this->response->redirect('start');
        }

        $this->loadLanguage('main/upgrade_1');

        $this->loadModel('main/upgrade_1');

        if ($this->db->isInstalled()) {
            $installed_version = $this->model_main_upgrade_1->getInstalledVersion();

            if ($installed_version == CMTX_VERSION) {
                $this->data['error'] = $this->data['lang_error_latest'];
            } else if (version_compare($installed_version, CMTX_VERSION, '>')) {
                $this->data['error'] = $this->data['lang_error_older'];
            } else if (version_compare($installed_version, 3.0, '<')) {
                $this->data['error'] = $this->data['lang_error_oldest'];
            } else {
                $this->data['installed_version'] = $installed_version;

                $this->data['latest_version'] = CMTX_VERSION;
            }
        } else {
            $this->data['error'] = $this->data['lang_error_no_tables'];
        }

        $this->data['page'] = '5';

        $this->components = array('common/header', 'common/footer');

        $this->loadView('main/upgrade_1');
    }
}
