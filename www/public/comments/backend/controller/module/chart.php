<?php
namespace Commentics;

class ModuleChartController extends Controller
{
    public function index()
    {
        if (!$this->setting->has('chart_enabled')) {
            $this->response->redirect('extension/modules');
        }

        $this->loadLanguage('module/chart');

        $this->loadModel('module/chart');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_module_chart->update($this->request->post);
            }
        }

        if (isset($this->request->post['chart_enabled'])) {
            $this->data['chart_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['chart_enabled'])) {
            $this->data['chart_enabled'] = false;
        } else {
            $this->data['chart_enabled'] = $this->setting->get('chart_enabled');
        }

        $this->data['link_back'] = $this->url->link('extension/modules');

        $this->components = array('common/header', 'common/footer');

        $this->loadView('module/chart');
    }

    public function install()
    {
        $this->loadModel('module/chart');

        $this->model_module_chart->install();
    }

    public function uninstall()
    {
        $this->loadModel('module/chart');

        $this->model_module_chart->uninstall();
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
