<?php
namespace Commentics;

class ReportErrorsController extends Controller
{
    public function index()
    {
        $this->loadLanguage('report/errors');

        $this->loadModel('report/errors');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_report_errors->resetErrors();
            }
        }

        if ($this->model_report_errors->isBig()) {
            $this->data['errors'] = '';

            $this->data['error'] = sprintf($this->data['lang_message_size'], CMTX_DIR_LOGS . 'errors.log');
        } else {
            $this->data['errors'] = $this->model_report_errors->getErrors();
        }

        $this->data['link_back'] = 'index.php?route=settings/error_reporting';

        $this->components = array('common/header', 'common/footer');

        $this->loadView('report/errors');
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        if (!is_writable(CMTX_DIR_LOGS . 'errors.log')) {
            $this->data['error'] = $this->data['lang_message_error'];

            return false;
        }

        $this->data['success'] = $this->data['lang_message_success'];

        return true;
    }
}
