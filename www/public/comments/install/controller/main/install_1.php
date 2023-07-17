<?php
namespace Commentics;

class MainInstall1Controller extends Controller
{
    public function index()
    {
        if (!$this->db->isConnected()) {
            $this->response->redirect('start');
        }

        $this->loadLanguage('main/install_1');

        $this->loadModel('main/install_1');

        if ($this->db->isInstalled()) {
            $this->data['installed'] = true;
        } else {
            $this->data['installed'] = false;
        }

        $this->data['time_zones'] = $this->model_main_install_1->getTimeZones();

        $this->data['page'] = '5';

        /* These are passed to common.js via the template */
        $this->data['cmtx_js_settings_install_1'] = array(
            'lang_error_password_length'   => $this->data['lang_error_password_length'],
            'lang_error_password_mismatch' => $this->data['lang_error_password_mismatch'],
            'lang_error_site_name'         => $this->data['lang_error_site_name']
        );

        $this->components = array('common/header', 'common/footer');

        $this->loadView('main/install_1');
    }
}
