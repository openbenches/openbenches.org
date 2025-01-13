<?php
namespace Commentics;

class ModuleRichSnippetsController extends Controller
{
    public function index()
    {
        if (!$this->setting->has('rich_snippets_enabled')) {
            $this->response->redirect('extension/modules');
        }

        $this->loadLanguage('module/rich_snippets');

        $this->loadModel('module/rich_snippets');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_module_rich_snippets->update($this->request->post);
            }
        }

        if (isset($this->request->post['rich_snippets_enabled'])) {
            $this->data['rich_snippets_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['rich_snippets_enabled'])) {
            $this->data['rich_snippets_enabled'] = false;
        } else {
            $this->data['rich_snippets_enabled'] = $this->setting->get('rich_snippets_enabled');
        }

        if (isset($this->request->post['rich_snippets_type'])) {
            $this->data['rich_snippets_type'] = $this->request->post['rich_snippets_type'];
        } else {
            $this->data['rich_snippets_type'] = $this->setting->get('rich_snippets_type');
        }

        if (isset($this->request->post['rich_snippets_other'])) {
            $this->data['rich_snippets_other'] = $this->request->post['rich_snippets_other'];
        } else {
            $this->data['rich_snippets_other'] = $this->setting->get('rich_snippets_other');
        }

        if (isset($this->request->post['rich_snippets_property'])) {
            $this->data['rich_snippets_properties'] = $this->request->post['rich_snippets_property'];
        } else {
            $this->data['rich_snippets_properties'] = $this->model_module_rich_snippets->getRichSnippetsProperties();
        }

        if (isset($this->error['rich_snippets_type'])) {
            $this->data['error_rich_snippets_type'] = $this->error['rich_snippets_type'];
        } else {
            $this->data['error_rich_snippets_type'] = '';
        }

        if (isset($this->error['rich_snippets_other'])) {
            $this->data['error_rich_snippets_other'] = $this->error['rich_snippets_other'];
        } else {
            $this->data['error_rich_snippets_other'] = '';
        }

        $this->data['lang_description'] = sprintf($this->data['lang_description'], $this->loadImage('misc/example.png'));

        $this->data['link_back'] = $this->url->link('extension/modules');

        $this->components = array('common/header', 'common/footer');

        $this->loadView('module/rich_snippets');
    }

    public function install()
    {
        $this->loadModel('module/rich_snippets');

        $this->model_module_rich_snippets->install();
    }

    public function uninstall()
    {
        $this->loadModel('module/rich_snippets');

        $this->model_module_rich_snippets->uninstall();
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        if (!isset($this->request->post['rich_snippets_type']) || !in_array($this->request->post['rich_snippets_type'], array('Brand', 'CreativeWork', 'Event', 'Offer', 'Organization', 'Place', 'Product', 'Service', 'other'))) {
            $this->error['rich_snippets_type'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['rich_snippets_other']) || $this->validation->length($this->request->post['rich_snippets_other']) > 100) {
            $this->error['rich_snippets_other'] = sprintf($this->data['lang_error_length'], 0, 100);
        }

        if (isset($this->request->post['rich_snippets_type']) && $this->request->post['rich_snippets_type'] == 'other' && isset($this->request->post['rich_snippets_other']) && !$this->request->post['rich_snippets_other']) {
            $this->error['rich_snippets_other'] = sprintf($this->data['lang_error_length'], 1, 100);
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
