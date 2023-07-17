<?php
namespace Commentics;

class ToolTextFinderController extends Controller
{
    public function index()
    {
        $this->loadLanguage('tool/text_finder');

        $this->loadModel('tool/text_finder');

        $this->data['search'] = false;

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->data['search'] = true;

                $this->data['results'] = $this->model_tool_text_finder->search($this->request->post);
            }
        }

        if (isset($this->request->post['location'])) {
            $this->data['location'] = $this->request->post['location'];
        } else {
            $this->data['location'] = 'frontend';
        }

        if (isset($this->request->post['case'])) {
            $this->data['case'] = $this->request->post['case'];
        } else {
            $this->data['case'] = 'sensitive';
        }

        if (isset($this->request->post['text'])) {
            $this->data['text'] = $this->request->post['text'];
        } else {
            $this->data['text'] = '';
        }

        if (isset($this->error['location'])) {
            $this->data['error_location'] = $this->error['location'];
        } else {
            $this->data['error_location'] = '';
        }

        if (isset($this->error['case'])) {
            $this->data['error_case'] = $this->error['case'];
        } else {
            $this->data['error_case'] = '';
        }

        if (isset($this->error['text'])) {
            $this->data['error_text'] = $this->error['text'];
        } else {
            $this->data['error_text'] = '';
        }

        $this->data['lang_subheading'] = sprintf($this->data['lang_subheading'], $this->data['text']);

        $this->components = array('common/header', 'common/footer');

        $this->loadView('tool/text_finder');
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        if (!isset($this->request->post['location']) || !in_array($this->request->post['location'], array('backend', 'frontend'))) {
            $this->error['location'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['case']) || !in_array($this->request->post['case'], array('sensitive', 'insensitive'))) {
            $this->error['case'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['text']) || $this->validation->length($this->request->post['text']) < 1 || $this->validation->length($this->request->post['text']) > 250) {
            $this->error['text'] = sprintf($this->data['lang_error_length'], 1, 250);
        }

        if ($this->error) {
            $this->data['error'] = $this->data['lang_message_error'];

            return false;
        } else {
            return true;
        }
    }
}
