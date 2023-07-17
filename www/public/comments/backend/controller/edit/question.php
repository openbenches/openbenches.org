<?php
namespace Commentics;

class EditQuestionController extends Controller
{
    public function index()
    {
        $this->loadLanguage('edit/question');

        $this->loadModel('edit/question');

        $this->loadModel('common/language');

        if (!isset($this->request->get['id']) || !$this->model_edit_question->questionExists($this->request->get['id'])) {
            $this->response->redirect('main/dashboard');
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_edit_question->update($this->request->post, $this->request->get['id']);

                $this->session->data['cmtx_success'] = $this->data['lang_message_success'];

                $this->response->redirect('manage/questions');
            }
        }

        $question = $this->model_edit_question->getQuestion($this->request->get['id']);

        if (isset($this->request->post['question'])) {
            $this->data['question'] = $this->request->post['question'];
        } else {
            $this->data['question'] = $question['question'];
        }

        if (isset($this->request->post['answer'])) {
            $this->data['answer'] = $this->request->post['answer'];
        } else {
            $this->data['answer'] = $question['answer'];
        }

        if (isset($this->request->post['language'])) {
            $this->data['language'] = $this->request->post['language'];
        } else {
            $this->data['language'] = $question['language'];
        }

        $this->data['date_added'] = $this->variable->formatDate($question['date_added'], $this->data['lang_date_time_format'], $this->data);

        if (isset($this->error['question'])) {
            $this->data['error_question'] = $this->error['question'];
        } else {
            $this->data['error_question'] = '';
        }

        if (isset($this->error['answer'])) {
            $this->data['error_answer'] = $this->error['answer'];
        } else {
            $this->data['error_answer'] = '';
        }

        if (isset($this->error['language'])) {
            $this->data['error_language'] = $this->error['language'];
        } else {
            $this->data['error_language'] = '';
        }

        $this->data['id'] = $this->request->get['id'];

        $this->data['languages'] = $this->model_common_language->getFrontendLanguages();

        $this->data['link_back'] = $this->url->link('manage/questions');

        if ($this->setting->get('notice_edit_question')) {
            $this->data['info'] = sprintf($this->data['lang_notice'], $this->url->link('tool/export_import'));
        }

        $this->components = array('common/header', 'common/footer');

        $this->loadView('edit/question');
    }

    public function dismiss()
    {
        $this->loadModel('edit/question');

        $this->model_edit_question->dismiss();
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        if (!isset($this->request->post['question']) || $this->validation->length($this->request->post['question']) < 1 || $this->validation->length($this->request->post['question']) > 250) {
            $this->error['question'] = sprintf($this->data['lang_error_length'], 1, 250);
        }

        if (!isset($this->request->post['answer']) || $this->validation->length($this->request->post['answer']) < 1 || $this->validation->length($this->request->post['answer']) > 250) {
            $this->error['answer'] = sprintf($this->data['lang_error_length'], 1, 250);
        }

        $this->loadModel('common/language');

        if (!isset($this->request->post['language']) || !in_array($this->request->post['language'], $this->model_common_language->getFrontendLanguages())) {
            $this->error['language'] = $this->data['lang_error_selection'];
        }

        if ($this->error) {
            $this->data['error'] = $this->data['lang_message_error'];

            return false;
        } else {
            return true;
        }
    }
}
