<?php
namespace Commentics;

class ManageStatesController extends Controller
{
    public function index()
    {
        $this->loadLanguage('manage/states');

        $this->loadModel('manage/states');

        $this->loadModel('common/pagination');

        if (!$this->checkParameters()) {
            $this->response->redirect('main/dashboard');
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                if (isset($this->request->post['single_delete'])) {
                    $result = $this->model_manage_states->singleDelete($this->request->post['single_delete']);

                    if ($result) {
                        $this->data['success'] = $this->data['lang_message_single_delete_success'];
                    } else {
                        $this->data['error'] = $this->data['lang_message_single_delete_invalid'];
                    }
                } else if (isset($this->request->post['bulk'])) {
                    $result = $this->model_manage_states->bulkDelete($this->request->post['bulk']);

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

        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = '';
        }

        if (isset($this->request->get['filter_country_code'])) {
            $filter_country_code = $this->request->get['filter_country_code'];
        } else {
            $filter_country_code = '';
        }

        if (isset($this->request->get['filter_enabled'])) {
            $filter_enabled = $this->request->get['filter_enabled'];
        } else {
            $filter_enabled = '';
        }

        if (isset($this->request->get['filter_date'])) {
            $filter_date = $this->request->get['filter_date'];
        } else {
            $filter_date = '';
        }

        $page_cookie = $this->model_manage_states->getPageCookie();

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else if ($page_cookie['sort']) {
            $sort = $page_cookie['sort'];
        } else {
            $sort = 'country_name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else if ($page_cookie['order']) {
            $order = $page_cookie['order'];
        } else {
            $order = 'asc';
        }

        if (isset($this->request->get['sort']) && isset($this->request->get['order'])) {
            $this->model_manage_states->setPageCookie($this->request->get['sort'], $this->request->get['order']);
        }

        $data = array(
            'filter_id'           => $filter_id,
            'filter_name'         => $filter_name,
            'filter_country_code' => $filter_country_code,
            'filter_enabled'      => $filter_enabled,
            'filter_date'         => $filter_date,
            'group_by'            => '',
            'sort'                => $sort,
            'order'               => $order,
            'start'               => ($page - 1) * $this->setting->get('limit_results'),
            'limit'               => $this->setting->get('limit_results')
        );

        $states = $this->model_manage_states->getStates($data);

        $total = $this->model_manage_states->getStates($data, true);

        $this->data['states'] = array();

        foreach ($states as $state) {
            $this->data['states'][] = array(
                'id'           => $state['id'],
                'name'         => $state['name'],
                'country_name' => $state['country_name'],
                'country_code' => $state['country_code'],
                'enabled'      => ($state['enabled']) ? $this->data['lang_text_yes'] : $this->data['lang_text_no'],
                'date_added'   => $this->variable->formatDate($state['date_added'], $this->data['lang_date_time_format'], $this->data),
                'action'       => $this->url->link('edit/state', '&id=' . $state['id'])
            );
        }

        $sort_url = $this->model_manage_states->sortUrl();

        $this->data['sort_name'] = $this->url->link('manage/states', '&sort=s.name' . $sort_url);

        $this->data['sort_country'] = $this->url->link('manage/states', '&sort=country_name' . $sort_url);

        $this->data['sort_enabled'] = $this->url->link('manage/states', '&sort=s.enabled' . $sort_url);

        $this->data['sort_date'] = $this->url->link('manage/states', '&sort=s.date_added' . $sort_url);

        if ($states) {
            $pagination_url = $this->model_manage_states->paginateUrl();

            $url = $this->url->link('manage/states', $pagination_url . '&page=[page]');

            $pagination = $this->model_common_pagination->paginate($page, $total, $url, $this->data);

            $this->data['pagination_stats'] = $pagination['stats'];

            $this->data['pagination_links'] = $pagination['links'];
        } else {
            $this->data['pagination_stats'] = '';

            $this->data['pagination_links'] = '';
        }

        $this->data['filter_name'] = $filter_name;

        $this->data['filter_country_code'] = $filter_country_code;

        $this->data['filter_enabled'] = $filter_enabled;

        $this->data['filter_date'] = $filter_date;

        $this->data['sort'] = $sort;

        $this->data['order'] = $order;

        $this->data['button_edit'] = $this->loadImage('button/edit.png');

        $this->data['button_delete'] = $this->loadImage('button/delete.png');

        $this->data['lang_description'] = sprintf($this->data['lang_description'], $this->url->link('add/state'));

        $this->data['countries'] = $this->geo->getCountries(true);

        $this->components = array('common/header', 'common/footer');

        $this->loadView('manage/states');
    }

    private function checkParameters()
    {
        if (isset($this->request->get['page']) && (!$this->validation->isInt($this->request->get['page']) || $this->request->get['page'] < 1)) {
            return false;
        }

        if (isset($this->request->get['sort']) && !in_array($this->request->get['sort'], array('s.name', 'country_name', 's.enabled', 's.date_added'))) {
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

            if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_country_code'])) {
                $this->loadModel('manage/states');

                if (isset($this->request->get['filter_name'])) {
                    $filter_name = $this->request->get['filter_name'];

                    $group_by = 's.name';

                    $sort = 's.name';
                } else {
                    $filter_name = '';
                }

                if (isset($this->request->get['filter_country_code'])) {
                    $filter_country_code = $this->request->get['filter_country_code'];

                    $group_by = 's.country_code';

                    $sort = 's.country_code';
                } else {
                    $filter_country_code = '';
                }

                $data = array(
                    'filter_id'           => '',
                    'filter_name'         => $filter_name,
                    'filter_country_code' => $filter_country_code,
                    'filter_enabled'      => '',
                    'filter_date'         => '',
                    'group_by'            => $group_by,
                    'sort'                => $sort,
                    'order'               => 'asc',
                    'start'               => 0,
                    'limit'               => 10
                );

                $states = $this->model_manage_states->getStates($data);

                foreach ($states as $state) {
                    $json[] = array(
                        'name'         => strip_tags($this->security->decode($state['name'])),
                        'country_code' => strip_tags($this->security->decode($state['country_code']))
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
