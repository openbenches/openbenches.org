<?php
namespace Commentics;

class MainChecklistController extends Controller
{
    public function index()
    {
        $this->loadLanguage('main/checklist');

        $this->loadModel('main/checklist');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_main_checklist->update();

                $this->response->redirect('main/dashboard');
            }
        }

        $this->components = array('common/header', 'common/footer');

        $this->loadView('main/checklist');
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
