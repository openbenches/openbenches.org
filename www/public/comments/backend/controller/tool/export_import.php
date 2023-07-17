<?php
namespace Commentics;

class ToolExportImportController extends Controller
{
    public function index()
    {
        $this->loadLanguage('tool/export_import');

        $this->loadModel('tool/export_import');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                if (isset($this->request->post['import'])) {
                    $this->model_tool_export_import->import();

                    $this->data['success'] = $this->data['lang_message_import'];
                } else {
                    $this->model_tool_export_import->export($this->request->post['type']);
                }
            }
        }

        if (isset($this->request->post['type'])) {
            $this->data['type'] = $this->request->post['type'];
        } else {
            $this->data['type'] = 'countries';
        }

        if (isset($this->error['type'])) {
            $this->data['error_type'] = $this->error['type'];
        } else {
            $this->data['error_type'] = '';
        }

        $this->components = array('common/header', 'common/footer');

        $this->loadView('tool/export_import');
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        if (isset($this->request->post['import'])) {
            if (!isset($this->request->files['file']['tmp_name']) || !$this->request->files['file']['tmp_name']) {
                $this->data['error'] = $this->data['lang_error_no_upload'];

                return false;
            }

            $extension = explode('.', $this->request->files['file']['name']);

            if (strtolower($extension[1]) != 'csv') {
                $this->data['error'] = $this->data['lang_error_not_csv'];

                return false;
            }

            $csv_file = $this->request->files['file']['tmp_name'];

            $csv_data = $this->request->getCsvData($csv_file);

            if (!in_array($csv_data[0][0], array('country code', 'type', 'id'))) {
                $this->data['error'] = $this->data['lang_error_bad_data'];

                return false;
            }
        } else {
            if (!isset($this->request->post['type']) || !in_array($this->request->post['type'], array('countries', 'emails', 'questions'))) {
                $this->error['type'] = $this->data['lang_error_selection'];
            }
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
