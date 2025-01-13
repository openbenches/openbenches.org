<?php
namespace Commentics;

class ModuleLanguageEditorController extends Controller
{
    public function index()
    {
        $this->loadLanguage('module/language_editor');

        $this->loadModel('module/language_editor');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_module_language_editor->update($this->request->post);
            }
        }

        $this->data['results'] = $this->model_module_language_editor->getText();

        $this->data['link_back'] = $this->url->link('extension/modules');

        $this->components = array('common/header', 'common/footer');

        $this->loadView('module/language_editor');
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        $language_file = CMTX_DIR_ROOT . 'frontend/view/' . $this->setting->get('theme_frontend') . '/language/' . $this->setting->get('language_frontend') . '/custom.php';

        if (file_exists($language_file) && !is_writable($language_file)) {
            $this->data['error'] = $this->data['lang_message_error'];

            return false;
        }

        $this->data['success'] = $this->data['lang_message_success'];

        return true;
    }
}
