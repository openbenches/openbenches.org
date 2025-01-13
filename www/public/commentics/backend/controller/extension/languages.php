<?php
namespace Commentics;

class ExtensionLanguagesController extends Controller
{
    public function index()
    {
        $this->loadLanguage('extension/languages');

        $this->loadModel('extension/languages');

        $this->loadModel('common/language');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_extension_languages->update($this->request->post);
            }
        }

        if (isset($this->request->post['language_frontend'])) {
            $this->data['language_frontend'] = $this->request->post['language_frontend'];
        } else {
            $this->data['language_frontend'] = $this->setting->get('language_frontend');
        }

        if (isset($this->request->post['language_backend'])) {
            $this->data['language_backend'] = $this->request->post['language_backend'];
        } else {
            $this->data['language_backend'] = $this->setting->get('language_backend');
        }

        if (isset($this->request->post['rtl'])) {
            $this->data['rtl'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['rtl'])) {
            $this->data['rtl'] = false;
        } else {
            $this->data['rtl'] = $this->setting->get('rtl');
        }

        if (isset($this->error['language_frontend'])) {
            $this->data['error_language_frontend'] = $this->error['language_frontend'];
        } else {
            $this->data['error_language_frontend'] = '';
        }

        if (isset($this->error['language_backend'])) {
            $this->data['error_language_backend'] = $this->error['language_backend'];
        } else {
            $this->data['error_language_backend'] = '';
        }

        $this->data['frontend_languages'] = $this->model_common_language->getFrontendLanguages();

        $this->data['backend_languages'] = $this->model_common_language->getBackendLanguages();

        if ($this->data['language_frontend'] == 'english') {
            $this->data['info'] = sprintf($this->data['lang_notice'], 'https://commentics.com/getlanguages');
        } else {
            $this->data['info'] = sprintf($this->data['lang_submit'], 'https://commentics.com/addlanguages');

            $this->data['warning'] = sprintf($this->data['lang_others'], $this->url->link('settings/email_editor') . '&type=ban', $this->url->link('manage/countries'), $this->url->link('manage/questions'));
        }

        $this->components = array('common/header', 'common/footer');

        $this->loadView('extension/languages');
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        $this->loadModel('common/language');

        if (!isset($this->request->post['language_frontend']) || !in_array($this->request->post['language_frontend'], $this->model_common_language->getFrontendLanguages())) {
            $this->error['language_frontend'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['language_backend']) || !in_array($this->request->post['language_backend'], $this->model_common_language->getBackendLanguages())) {
            $this->error['language_backend'] = $this->data['lang_error_selection'];
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
