<?php
namespace Commentics;

class ReportAccessController extends Controller
{
    public function index()
    {
        $this->loadLanguage('report/access');

        $this->loadModel('report/access');

        $this->loadModel('common/pagination');

        if (!$this->checkParameters()) {
            $this->response->redirect('main/dashboard');
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

        if (isset($this->request->get['filter_username'])) {
            $filter_username = $this->request->get['filter_username'];
        } else {
            $filter_username = '';
        }

        if (isset($this->request->get['filter_ip_address'])) {
            $filter_ip_address = $this->request->get['filter_ip_address'];
        } else {
            $filter_ip_address = '';
        }

        if (isset($this->request->get['filter_page'])) {
            $filter_page = $this->request->get['filter_page'];
        } else {
            $filter_page = '';
        }

        if (isset($this->request->get['filter_date'])) {
            $filter_date = $this->request->get['filter_date'];
        } else {
            $filter_date = '';
        }

        $page_cookie = $this->model_report_access->getPageCookie();

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else if ($page_cookie['sort']) {
            $sort = $page_cookie['sort'];
        } else {
            $sort = 'a.date_added';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else if ($page_cookie['order']) {
            $order = $page_cookie['order'];
        } else {
            $order = 'desc';
        }

        if (isset($this->request->get['sort']) && isset($this->request->get['order'])) {
            $this->model_report_access->setPageCookie($this->request->get['sort'], $this->request->get['order']);
        }

        $data = array(
            'filter_id'         => $filter_id,
            'filter_username'   => $filter_username,
            'filter_ip_address' => $filter_ip_address,
            'filter_page'       => $filter_page,
            'filter_date'       => $filter_date,
            'group_by'          => '',
            'sort'              => $sort,
            'order'             => $order,
            'start'             => ($page - 1) * $this->setting->get('limit_results'),
            'limit'             => $this->setting->get('limit_results')
        );

        $views = $this->model_report_access->getViews($data);

        $total = $this->model_report_access->getViews($data, true);

        $this->data['views'] = array();

        foreach ($views as $view) {
            $this->data['views'][] = array(
                'username'   => $view['username'],
                'ip_address' => ($this->setting->get('is_demo') ? '(Hidden in Demo)' : $view['ip_address']),
                'page'       => $view['page'],
                'date_added' => $this->variable->formatDate($view['date_added'], $this->data['lang_date_time_format'], $this->data)
            );
        }

        $sort_url = $this->model_report_access->sortUrl();

        $this->data['sort_username'] = $this->url->link('report/access', '&sort=a.username' . $sort_url);

        $this->data['sort_ip_address'] = $this->url->link('report/access', '&sort=a.ip_address' . $sort_url);

        $this->data['sort_page'] = $this->url->link('report/access', '&sort=a.page' . $sort_url);

        $this->data['sort_date'] = $this->url->link('report/access', '&sort=a.date_added' . $sort_url);

        if ($views) {
            $pagination_url = $this->model_report_access->paginateUrl();

            $url = $this->url->link('report/access', $pagination_url . '&page=[page]');

            $pagination = $this->model_common_pagination->paginate($page, $total, $url, $this->data);

            $this->data['pagination_stats'] = $pagination['stats'];

            $this->data['pagination_links'] = $pagination['links'];
        } else {
            $this->data['pagination_stats'] = '';

            $this->data['pagination_links'] = '';
        }

        $this->data['filter_username'] = $filter_username;

        $this->data['filter_ip_address'] = $filter_ip_address;

        $this->data['filter_page'] = $filter_page;

        $this->data['filter_date'] = $filter_date;

        $this->data['sort'] = $sort;

        $this->data['order'] = $order;

        $this->components = array('common/header', 'common/footer');

        $this->loadView('report/access');
    }

    private function checkParameters()
    {
        if (isset($this->request->get['page']) && (!$this->validation->isInt($this->request->get['page']) || $this->request->get['page'] < 1)) {
            return false;
        }

        if (isset($this->request->get['sort']) && !in_array($this->request->get['sort'], array('a.username', 'a.ip_address', 'a.page', 'a.date_added'))) {
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

            if (isset($this->request->get['filter_username']) || isset($this->request->get['filter_ip_address']) || isset($this->request->get['filter_page'])) {
                $this->loadModel('report/access');

                if (isset($this->request->get['filter_username'])) {
                    $filter_username = $this->request->get['filter_username'];

                    $group_by = 'a.username';

                    $sort = 'a.username';
                } else {
                    $filter_username = '';
                }

                if (isset($this->request->get['filter_ip_address'])) {
                    $filter_ip_address = $this->request->get['filter_ip_address'];

                    $group_by = 'a.ip_address';

                    $sort = 'a.ip_address';
                } else {
                    $filter_ip_address = '';
                }

                if (isset($this->request->get['filter_page'])) {
                    $filter_page = $this->request->get['filter_page'];

                    $group_by = 'a.page';

                    $sort = 'a.page';
                } else {
                    $filter_page = '';
                }

                $data = array(
                    'filter_id'         => '',
                    'filter_username'   => $filter_username,
                    'filter_ip_address' => $filter_ip_address,
                    'filter_page'       => $filter_page,
                    'filter_date'       => '',
                    'group_by'          => $group_by,
                    'sort'              => $sort,
                    'order'             => 'asc',
                    'start'             => 0,
                    'limit'             => 10
                );

                $views = $this->model_report_access->getViews($data);

                foreach ($views as $view) {
                    $json[] = array(
                        'username'   => strip_tags($this->security->decode($view['username'])),
                        'ip_address' => strip_tags($this->security->decode($view['ip_address'])),
                        'page'       => strip_tags($this->security->decode($view['page']))
                    );
                }
            }

            echo json_encode($json);
        }
    }
}
