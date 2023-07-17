<?php
namespace Commentics;

class ModuleAkismetController extends Controller
{
    public function index()
    {
        if (!$this->setting->has('akismet_enabled')) {
            $this->response->redirect('extension/modules');
        }

        $this->loadLanguage('module/akismet');

        $this->loadModel('module/akismet');

        if ((!function_exists('fsockopen') || !is_callable('fsockopen')) && $this->request->server['REQUEST_METHOD'] != 'POST') {
            $this->data['warning'] = $this->data['lang_message_unable'];
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_module_akismet->update($this->request->post);
            }
        }

        if (isset($this->request->post['akismet_enabled'])) {
            $this->data['akismet_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['akismet_enabled'])) {
            $this->data['akismet_enabled'] = false;
        } else {
            $this->data['akismet_enabled'] = $this->setting->get('akismet_enabled');
        }

        if (isset($this->request->post['akismet_key'])) {
            $this->data['akismet_key'] = $this->request->post['akismet_key'];
        } else {
            $this->data['akismet_key'] = $this->setting->get('akismet_key');
        }

        if (isset($this->request->post['akismet_logging'])) {
            $this->data['akismet_logging'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['akismet_logging'])) {
            $this->data['akismet_logging'] = false;
        } else {
            $this->data['akismet_logging'] = $this->setting->get('akismet_logging');
        }

        if (isset($this->error['akismet_key'])) {
            $this->data['error_akismet_key'] = $this->error['akismet_key'];
        } else {
            $this->data['error_akismet_key'] = '';
        }

        $this->data['link_back'] = $this->url->link('extension/modules');

        $this->components = array('common/header', 'common/footer');

        $this->loadView('module/akismet');
    }

    public function install()
    {
        $this->loadModel('module/akismet');

        $this->model_module_akismet->install();
    }

    public function uninstall()
    {
        $this->loadModel('module/akismet');

        $this->model_module_akismet->uninstall();
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        if (!extension_loaded('curl')) {
            $this->data['error'] = $this->data['lang_message_unable'];

            return false;
        }

        if (!isset($this->request->post['akismet_key']) || $this->validation->length($this->request->post['akismet_key']) < 1 || $this->validation->length($this->request->post['akismet_key']) > 250) {
            $this->error['akismet_key'] = sprintf($this->data['lang_error_length'], 1, 250);
        }

        if ($this->error) {
            $this->data['error'] = $this->data['lang_message_error'];

            return false;
        } else {
            $this->data['success'] = $this->data['lang_message_success'];

            return true;
        }
    }
}
