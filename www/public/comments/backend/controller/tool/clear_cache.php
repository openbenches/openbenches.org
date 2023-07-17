<?php
namespace Commentics;

class ToolClearCacheController extends Controller
{
    public function index()
    {
        $this->loadLanguage('tool/clear_cache');

        $this->loadModel('tool/clear_cache');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_tool_clear_cache->clear($this->request->post);
            }
        }

        if (isset($this->request->post['database'])) {
            $this->data['database'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['database'])) {
            $this->data['database'] = false;
        } else {
            $this->data['database'] = true;
        }

        if (isset($this->request->post['modification'])) {
            $this->data['modification'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['modification'])) {
            $this->data['modification'] = false;
        } else {
            $this->data['modification'] = true;
        }

        if (isset($this->request->post['template'])) {
            $this->data['template'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['template'])) {
            $this->data['template'] = false;
        } else {
            $this->data['template'] = true;
        }

        $this->components = array('common/header', 'common/footer');

        $this->loadView('tool/clear_cache');
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
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
