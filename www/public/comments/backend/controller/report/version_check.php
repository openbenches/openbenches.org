<?php
namespace Commentics;

class ReportVersionCheckController extends Controller
{
    public function index()
    {
        $this->loadLanguage('report/version_check');

        $this->loadModel('report/version_check');

        $this->model_report_version_check->clearLog();

        $this->home->getLatestVersion(true);

        $this->data['log'] = $this->model_report_version_check->getLog();

        $this->data['link_back'] = 'index.php?route=main/dashboard';

        $this->data['lang_description'] = sprintf($this->data['lang_description'], 'https://commentics.com/forum/');

        $this->components = array('common/header', 'common/footer');

        $this->loadView('report/version_check');
    }

    public function download()
    {
        $this->loadLanguage('report/version_check');

        $this->loadModel('report/version_check');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_report_version_check->downloadLog();
            }
        }
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        return true;
    }
}
