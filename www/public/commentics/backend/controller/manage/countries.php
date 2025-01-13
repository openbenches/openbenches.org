<?php
namespace Commentics;

class ManageCountriesController extends Controller
{
    public function index()
    {
        $this->loadLanguage('manage/countries');

        $this->loadModel('manage/countries');

        $this->loadModel('common/pagination');

        if (!$this->checkParameters()) {
            $this->response->redirect('main/dashboard');
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                if (isset($this->request->post['single_delete'])) {
                    $result = $this->model_manage_countries->singleDelete($this->request->post['single_delete']);

                    if ($result) {
                        $this->data['success'] = $this->data['lang_message_single_delete_success'];
                    } else {
                        $this->data['error'] = $this->data['lang_message_single_delete_invalid'];
                    }
                } else if (isset($this->request->post['bulk'])) {
                    $result = $this->model_manage_countries->bulkDelete($this->request->post['bulk']);

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

        if (isset($this->request->get['filter_code'])) {
            $filter_code = $this->request->get['filter_code'];
        } else {
            $filter_code = '';
        }

        if (isset($this->request->get['filter_top'])) {
            $filter_top = $this->request->get['filter_top'];
        } else {
            $filter_top = '';
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

        $page_cookie = $this->model_manage_countries->getPageCookie();

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else if ($page_cookie['sort']) {
            $sort = $page_cookie['sort'];
        } else {
            $sort = 'g.name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else if ($page_cookie['order']) {
            $order = $page_cookie['order'];
        } else {
            $order = 'asc';
        }

        if (isset($this->request->get['sort']) && isset($this->request->get['order'])) {
            $this->model_manage_countries->setPageCookie($this->request->get['sort'], $this->request->get['order']);
        }

        $data = array(
            'filter_id'      => $filter_id,
            'filter_name'    => $filter_name,
            'filter_code'    => $filter_code,
            'filter_top'     => $filter_top,
            'filter_enabled' => $filter_enabled,
            'filter_date'    => $filter_date,
            'group_by'       => '',
            'sort'           => $sort,
            'order'          => $order,
            'start'          => ($page - 1) * $this->setting->get('limit_results'),
            'limit'          => $this->setting->get('limit_results')
        );

        $countries = $this->model_manage_countries->getCountries($data);

        $total = $this->model_manage_countries->getCountries($data, true);

        $this->data['countries'] = array();

        foreach ($countries as $country) {
            $this->data['countries'][] = array(
                'id'         => $country['id'],
                'name'       => $country['name'],
                'code'       => $country['code'],
                'code_url'   => $this->url->link('manage/states', '&filter_country_code=' . $country['code']),
                'top'        => ($country['top']) ? $this->data['lang_text_yes'] : $this->data['lang_text_no'],
                'enabled'    => ($country['enabled']) ? $this->data['lang_text_yes'] : $this->data['lang_text_no'],
                'date_added' => $this->variable->formatDate($country['date_added'], $this->data['lang_date_time_format'], $this->data),
                'action'     => $this->url->link('edit/country', '&id=' . $country['id'])
            );
        }

        $sort_url = $this->model_manage_countries->sortUrl();

        $this->data['sort_name'] = $this->url->link('manage/countries', '&sort=g.name' . $sort_url);

        $this->data['sort_code'] = $this->url->link('manage/countries', '&sort=c.code' . $sort_url);

        $this->data['sort_top'] = $this->url->link('manage/countries', '&sort=c.top' . $sort_url);

        $this->data['sort_enabled'] = $this->url->link('manage/countries', '&sort=c.enabled' . $sort_url);

        $this->data['sort_date'] = $this->url->link('manage/countries', '&sort=c.date_added' . $sort_url);

        if ($countries) {
            $pagination_url = $this->model_manage_countries->paginateUrl();

            $url = $this->url->link('manage/countries', $pagination_url . '&page=[page]');

            $pagination = $this->model_common_pagination->paginate($page, $total, $url, $this->data);

            $this->data['pagination_stats'] = $pagination['stats'];

            $this->data['pagination_links'] = $pagination['links'];
        } else {
            $this->data['pagination_stats'] = '';

            $this->data['pagination_links'] = '';
        }

        $this->data['filter_name'] = $filter_name;

        $this->data['filter_code'] = $filter_code;

        $this->data['filter_top'] = $filter_top;

        $this->data['filter_enabled'] = $filter_enabled;

        $this->data['filter_date'] = $filter_date;

        $this->data['sort'] = $sort;

        $this->data['order'] = $order;

        $this->data['button_edit'] = $this->loadImage('button/edit.png');

        $this->data['button_delete'] = $this->loadImage('button/delete.png');

        $this->data['lang_description'] = sprintf($this->data['lang_description'], $this->url->link('add/country'));

        if ($this->setting->get('notice_manage_countries')) {
            $this->data['info'] = sprintf($this->data['lang_notice'], $this->url->link('tool/export_import'));
        }

        $this->components = array('common/header', 'common/footer');

        $this->loadView('manage/countries');
    }

    public function dismiss()
    {
        $this->loadModel('manage/countries');

        $this->model_manage_countries->dismiss();
    }

    private function checkParameters()
    {
        if (isset($this->request->get['page']) && (!$this->validation->isInt($this->request->get['page']) || $this->request->get['page'] < 1)) {
            return false;
        }

        if (isset($this->request->get['sort']) && !in_array($this->request->get['sort'], array('g.name', 'c.code', 'c.top', 'c.enabled', 'c.date_added'))) {
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

            if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_code'])) {
                $this->loadModel('manage/countries');

                if (isset($this->request->get['filter_name'])) {
                    $filter_name = $this->request->get['filter_name'];

                    $group_by = 'g.name';

                    $sort = 'g.name';
                } else {
                    $filter_name = '';
                }

                if (isset($this->request->get['filter_code'])) {
                    $filter_code = $this->request->get['filter_code'];

                    $group_by = 'c.code';

                    $sort = 'c.code';
                } else {
                    $filter_code = '';
                }

                $data = array(
                    'filter_id'      => '',
                    'filter_name'    => $filter_name,
                    'filter_code'    => $filter_code,
                    'filter_top'     => '',
                    'filter_enabled' => '',
                    'filter_date'    => '',
                    'group_by'       => $group_by,
                    'sort'           => $sort,
                    'order'          => 'asc',
                    'start'          => 0,
                    'limit'          => 10
                );

                $countries = $this->model_manage_countries->getCountries($data);

                foreach ($countries as $country) {
                    $json[] = array(
                        'name' => strip_tags($this->security->decode($country['name'])),
                        'code' => strip_tags($this->security->decode($country['code']))
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
