<?php
namespace Commentics;

class ToolOptimizeTablesController extends Controller
{
    public function index()
    {
        $this->loadLanguage('tool/optimize_tables');

        $this->loadModel('tool/optimize_tables');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_tool_optimize_tables->optimize();
            }
        }

        $this->data['lang_description'] = sprintf($this->data['lang_description'], $this->variable->formatDate($this->model_tool_optimize_tables->getOptimizeDate(), $this->data['lang_date_format'], $this->data));

        $this->components = array('common/header', 'common/footer');

        $this->loadView('tool/optimize_tables');
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
