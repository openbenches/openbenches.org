<?php
namespace Commentics;

class ManageSitesController extends Controller
{
    public function index()
    {
        $this->loadLanguage('manage/sites');

        $this->loadModel('manage/sites');

        $this->loadModel('common/pagination');

        if (!$this->checkParameters()) {
            $this->response->redirect('main/dashboard');
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                if (isset($this->request->post['single_delete'])) {
                    $result = $this->model_manage_sites->singleDelete($this->request->post['single_delete']);

                    if ($result) {
                        $this->data['success'] = $this->data['lang_message_single_delete_success'];
                    } else {
                        $this->data['error'] = $this->data['lang_message_single_delete_invalid'];
                    }
                } else if (isset($this->request->post['bulk'])) {
                    $result = $this->model_manage_sites->bulkDelete($this->request->post['bulk']);

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

        if (isset($this->request->get['filter_domain'])) {
            $filter_domain = $this->request->get['filter_domain'];
        } else {
            $filter_domain = '';
        }

        if (isset($this->request->get['filter_date'])) {
            $filter_date = $this->request->get['filter_date'];
        } else {
            $filter_date = '';
        }

        $page_cookie = $this->model_manage_sites->getPageCookie();

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else if ($page_cookie['sort']) {
            $sort = $page_cookie['sort'];
        } else {
            $sort = 's.date_added';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else if ($page_cookie['order']) {
            $order = $page_cookie['order'];
        } else {
            $order = 'desc';
        }

        if (isset($this->request->get['sort']) && isset($this->request->get['order'])) {
            $this->model_manage_sites->setPageCookie($this->request->get['sort'], $this->request->get['order']);
        }

        $data = array(
            'filter_id'     => $filter_id,
            'filter_name'   => $filter_name,
            'filter_domain' => $filter_domain,
            'filter_date'   => $filter_date,
            'group_by'      => '',
            'sort'          => $sort,
            'order'         => $order,
            'start'         => ($page - 1) * $this->setting->get('limit_results'),
            'limit'         => $this->setting->get('limit_results')
        );

        $sites = $this->model_manage_sites->getSites($data);

        $total = $this->model_manage_sites->getSites($data, true);

        $this->data['sites'] = array();

        foreach ($sites as $site) {
            $this->data['sites'][] = array(
                'id'          => $site['id'],
                'name'        => $site['name'],
                'domain'      => $site['domain'],
                'url'         => $site['url'],
                'pages'       => $site['pages'],
                'comments'    => $site['comments'],
                'date_added'  => $this->variable->formatDate($site['date_added'], $this->data['lang_date_time_format'], $this->data),
                'action_view' => $site['url'],
                'action_edit' => $this->url->link('edit/site', '&id=' . $site['id'])
            );
        }

        $sort_url = $this->model_manage_sites->sortUrl();

        $this->data['sort_name'] = $this->url->link('manage/sites', '&sort=s.name' . $sort_url);

        $this->data['sort_domain'] = $this->url->link('manage/sites', '&sort=s.domain' . $sort_url);

        $this->data['sort_url'] = $this->url->link('manage/sites', '&sort=s.url' . $sort_url);

        $this->data['sort_pages'] = $this->url->link('manage/sites', '&sort=pages' . $sort_url);

        $this->data['sort_comments'] = $this->url->link('manage/sites', '&sort=comments' . $sort_url);

        $this->data['sort_date'] = $this->url->link('manage/sites', '&sort=s.date_added' . $sort_url);

        if ($sites) {
            $pagination_url = $this->model_manage_sites->paginateUrl();

            $url = $this->url->link('manage/sites', $pagination_url . '&page=[page]');

            (isset($this->request->get['page'])) ? $page = $this->request->get['page'] : $page = 1;

            $pagination = $this->model_common_pagination->paginate($page, $total, $url, $this->data);

            $this->data['pagination_stats'] = $pagination['stats'];

            $this->data['pagination_links'] = $pagination['links'];
        } else {
            $this->data['pagination_stats'] = '';

            $this->data['pagination_links'] = '';
        }

        $this->data['filter_name'] = $filter_name;

        $this->data['filter_domain'] = $filter_domain;

        $this->data['filter_date'] = $filter_date;

        $this->data['sort'] = $sort;

        $this->data['order'] = $order;

        $this->data['button_view'] = $this->loadImage('button/view.png');

        $this->data['button_edit'] = $this->loadImage('button/edit.png');

        $this->data['button_delete'] = $this->loadImage('button/delete.png');

        $this->data['lang_description'] = sprintf($this->data['lang_description'], $this->url->link('add/site'));

        $this->components = array('common/header', 'common/footer');

        $this->loadView('manage/sites');
    }

    private function checkParameters()
    {
        if (isset($this->request->get['page']) && (!$this->validation->isInt($this->request->get['page']) || $this->request->get['page'] < 1)) {
            return false;
        }

        if (isset($this->request->get['sort']) && !in_array($this->request->get['sort'], array('s.name', 's.domain', 's.url', 'pages', 'comments', 's.date_added'))) {
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

            if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_domain'])) {
                $this->loadModel('manage/sites');

                if (isset($this->request->get['filter_name'])) {
                    $filter_name = $this->request->get['filter_name'];

                    $group_by = 's.name';

                    $sort = 's.name';
                } else {
                    $filter_name = '';
                }

                if (isset($this->request->get['filter_domain'])) {
                    $filter_domain = $this->request->get['filter_domain'];

                    $group_by = 's.domain';

                    $sort = 's.domain';
                } else {
                    $filter_domain = '';
                }

                $data = array(
                    'filter_id'     => '',
                    'filter_name'   => $filter_name,
                    'filter_domain' => $filter_domain,
                    'filter_date'   => '',
                    'group_by'      => $group_by,
                    'sort'          => $sort,
                    'order'         => 'asc',
                    'start'         => 0,
                    'limit'         => 10
                );

                $sites = $this->model_manage_sites->getSites($data);

                foreach ($sites as $site) {
                    $json[] = array(
                        'name'   => strip_tags($this->security->decode($site['name'])),
                        'domain' => strip_tags($this->security->decode($site['domain']))
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
