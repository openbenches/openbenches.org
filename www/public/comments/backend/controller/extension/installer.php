<?php
namespace Commentics;

class ExtensionInstallerController extends Controller
{
    public function index()
    {
        $this->loadLanguage('extension/installer');

        $this->loadModel('extension/installer');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $error = $this->model_extension_installer->install();

                if ($error) {
                    $this->data['error'] = $error;
                } else {
                    $this->data['success'] = $this->data['lang_message_success'];
                }
            }
        }

        $this->components = array('common/header', 'common/footer');

        $this->loadView('extension/installer');
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        if (!extension_loaded('zip')) {
            $this->data['error'] = $this->data['lang_error_unable'];

            return false;
        }

        if (!is_writable(CMTX_DIR_UPLOAD)) {
            $this->data['error'] = $this->data['lang_error_permission'];

            return false;
        }

        if (!isset($this->request->files['file']['tmp_name']) || !$this->request->files['file']['tmp_name']) {
            $this->data['error'] = $this->data['lang_error_no_upload'];

            return false;
        }

        if (!in_array($this->request->files['file']['type'], array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip'))) {
            $this->data['error'] = $this->data['lang_error_not_zip'];

            return false;
        }

        $extension = explode('.', $this->request->files['file']['name']);

        if (strtolower($extension[1]) != 'zip') {
            $this->data['error'] = $this->data['lang_error_not_zip'];

            return false;
        }

        return true;
    }
}
