<?php
namespace Commentics;

class ManageQuestionsController extends Controller
{
    public function index()
    {
        $this->loadLanguage('manage/questions');

        $this->loadModel('manage/questions');

        $this->loadModel('common/language');

        $this->loadModel('common/pagination');

        if (!$this->checkParameters()) {
            $this->response->redirect('main/dashboard');
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                if (isset($this->request->post['single_delete'])) {
                    $result = $this->model_manage_questions->singleDelete($this->request->post['single_delete']);

                    if ($result) {
                        $this->data['success'] = $this->data['lang_message_single_delete_success'];
                    } else {
                        $this->data['error'] = $this->data['lang_message_single_delete_invalid'];
                    }
                } else if (isset($this->request->post['bulk'])) {
                    $result = $this->model_manage_questions->bulkDelete($this->request->post['bulk']);

                    if ($result['success']) {
                        $this->data['success'] = sprintf($this->data['lang_message_bulk_delete_success'], $result['success']);
                    }

                    if ($result['failure']) {
                        $this->data['error'] = sprintf($this->data['lang_message_bulk_delete_invalid'], $result['failure']);
                    }
                }
            }
        } else if (isset($this->session->data['cmtx_success'])) {
            $this->data['success'] = $this->session->data['cmtx_success'];

            unset($this->session->data['cmtx_success']);
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['filter_id'])) {
            $filter_id = $this->request->get['filter_id'];
        } else {
            $filter_id = '';
        }

        if (isset($this->request->get['filter_question'])) {
            $filter_question = $this->request->get['filter_question'];
        } else {
            $filter_question = '';
        }

        if (isset($this->request->get['filter_answer'])) {
            $filter_answer = $this->request->get['filter_answer'];
        } else {
            $filter_answer = '';
        }

        if (isset($this->request->get['filter_language'])) {
            $filter_language = $this->request->get['filter_language'];
        } else {
            $filter_language = '';
        }

        if (isset($this->request->get['filter_date'])) {
            $filter_date = $this->request->get['filter_date'];
        } else {
            $filter_date = '';
        }

        $page_cookie = $this->model_manage_questions->getPageCookie();

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else if ($page_cookie['sort']) {
            $sort = $page_cookie['sort'];
        } else {
            $sort = 'q.date_added';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else if ($page_cookie['order']) {
            $order = $page_cookie['order'];
        } else {
            $order = 'desc';
        }

        if (isset($this->request->get['sort']) && isset($this->request->get['order'])) {
            $this->model_manage_questions->setPageCookie($this->request->get['sort'], $this->request->get['order']);
        }

        $data = array(
            'filter_id'       => $filter_id,
            'filter_question' => $filter_question,
            'filter_answer'   => $filter_answer,
            'filter_language' => $filter_language,
            'filter_date'     => $filter_date,
            'group_by'        => '',
            'sort'            => $sort,
            'order'           => $order,
            'start'           => ($page - 1) * $this->setting->get('limit_results'),
            'limit'           => $this->setting->get('limit_results')
        );

        $questions = $this->model_manage_questions->getQuestions($data);

        $total = $this->model_manage_questions->getQuestions($data, true);

        $this->data['questions'] = array();

        foreach ($questions as $question) {
            $this->data['questions'][] = array(
                'id'         => $question['id'],
                'question'   => $question['question'],
                'answer'     => $question['answer'],
                'language'   => $this->model_common_language->getFriendlyLanguageName($question['language']),
                'date_added' => $this->variable->formatDate($question['date_added'], $this->data['lang_date_time_format'], $this->data),
                'action'     => $this->url->link('edit/question', '&id=' . $question['id'])
            );
        }

        $sort_url = $this->model_manage_questions->sortUrl();

        $this->data['sort_question'] = $this->url->link('manage/questions', '&sort=q.question' . $sort_url);

        $this->data['sort_answer'] = $this->url->link('manage/questions', '&sort=q.answer' . $sort_url);

        $this->data['sort_language'] = $this->url->link('manage/questions', '&sort=q.language' . $sort_url);

        $this->data['sort_date'] = $this->url->link('manage/questions', '&sort=q.date_added' . $sort_url);

        if ($questions) {
            $pagination_url = $this->model_manage_questions->paginateUrl();

            $url = $this->url->link('manage/questions', $pagination_url . '&page=[page]');

            $pagination = $this->model_common_pagination->paginate($page, $total, $url, $this->data);

            $this->data['pagination_stats'] = $pagination['stats'];

            $this->data['pagination_links'] = $pagination['links'];
        } else {
            $this->data['pagination_stats'] = '';

            $this->data['pagination_links'] = '';
        }

        $this->data['filter_question'] = $filter_question;

        $this->data['filter_answer'] = $filter_answer;

        $this->data['filter_language'] = $filter_language;

        $this->data['filter_date'] = $filter_date;

        $this->data['sort'] = $sort;

        $this->data['order'] = $order;

        $this->data['button_edit'] = $this->loadImage('button/edit.png');

        $this->data['button_delete'] = $this->loadImage('button/delete.png');

        if ($this->setting->get('notice_manage_questions')) {
            $this->data['info'] = sprintf($this->data['lang_notice'], $this->url->link('tool/export_import'));
        }

        $this->data['lang_description'] = sprintf($this->data['lang_description'], $this->url->link('add/question'));

        $this->components = array('common/header', 'common/footer');

        $this->loadView('manage/questions');
    }

    public function dismiss()
    {
        $this->loadModel('manage/questions');

        $this->model_manage_questions->dismiss();
    }

    private function checkParameters()
    {
        if (isset($this->request->get['page']) && (!$this->validation->isInt($this->request->get['page']) || $this->request->get['page'] < 1)) {
            return false;
        }

        if (isset($this->request->get['sort']) && !in_array($this->request->get['sort'], array('q.question', 'q.answer', 'q.language', 'q.date_added'))) {
            return false;
        }

        if (isset($this->request->get['order']) && !in_array($this->request->get['order'], array('asc', 'desc'))) {
            return false;
        }

        return true;
    }

    public function autocomplete()
    {
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');

            $json = array();

            if (isset($this->request->get['filter_question']) || isset($this->request->get['filter_answer']) || isset($this->request->get['filter_language'])) {
                $this->loadModel('manage/questions');

                if (isset($this->request->get['filter_question'])) {
                    $filter_question = $this->request->get['filter_question'];

                    $group_by = 'q.question';

                    $sort = 'q.question';
                } else {
                    $filter_question = '';
                }

                if (isset($this->request->get['filter_answer'])) {
                    $filter_answer = $this->request->get['filter_answer'];

                    $group_by = 'q.answer';

                    $sort = 'q.answer';
                } else {
                    $filter_answer = '';
                }

                if (isset($this->request->get['filter_language'])) {
                    $filter_language = $this->request->get['filter_language'];

                    $group_by = 'q.language';

                    $sort = 'q.language';
                } else {
                    $filter_language = '';
                }

                $data = array(
                    'filter_id'       => '',
                    'filter_question' => $filter_question,
                    'filter_answer'   => $filter_answer,
                    'filter_language' => $filter_language,
                    'filter_date'     => '',
                    'group_by'        => $group_by,
                    'sort'            => $sort,
                    'order'           => 'asc',
                    'start'           => 0,
                    'limit'           => 10
                );

                $questions = $this->model_manage_questions->getQuestions($data);

                foreach ($questions as $question) {
                    $json[] = array(
                        'question' => strip_tags($this->security->decode($question['question'])),
                        'answer'   => strip_tags($this->security->decode($question['answer'])),
                        'language' => strip_tags($this->security->decode($question['language']))
                    );
                }
            }

            echo json_encode($json);
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

        if ($this->error) {
            $this->data['error'] = $this->data['lang_message_error'];

            return false;
        } else {
            return true;
        }
    }
}
