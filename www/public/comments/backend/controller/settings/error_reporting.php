<?php
namespace Commentics;

class SettingsErrorReportingController extends Controller
{
    public function index()
    {
        $this->loadLanguage('settings/error_reporting');

        $this->loadModel('settings/error_reporting');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_settings_error_reporting->update($this->request->post);
            }
        }

        if (isset($this->request->post['error_reporting_frontend'])) {
            $this->data['error_reporting_frontend'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['error_reporting_frontend'])) {
            $this->data['error_reporting_frontend'] = false;
        } else {
            $this->data['error_reporting_frontend'] = $this->setting->get('error_reporting_frontend');
        }

        if (isset($this->request->post['error_reporting_backend'])) {
            $this->data['error_reporting_backend'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['error_reporting_backend'])) {
            $this->data['error_reporting_backend'] = false;
        } else {
            $this->data['error_reporting_backend'] = $this->setting->get('error_reporting_backend');
        }

        if (isset($this->request->post['error_reporting_method'])) {
            $this->data['error_reporting_method'] = $this->request->post['error_reporting_method'];
        } else {
            $this->data['error_reporting_method'] = $this->setting->get('error_reporting_method');
        }

        if (isset($this->error['error_reporting_method'])) {
            $this->data['error_error_reporting_method'] = $this->error['error_reporting_method'];
        } else {
            $this->data['error_error_reporting_method'] = '';
        }

        $this->data['link_log'] = $this->url->link('report/errors');

        $this->components = array('common/header', 'common/footer');

        $this->loadView('settings/error_reporting');
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        if (!isset($this->request->post['error_reporting_method']) || !in_array($this->request->post['error_reporting_method'], array('log', 'screen'))) {
            $this->error['error_reporting_method'] = $this->data['lang_error_selection'];
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
