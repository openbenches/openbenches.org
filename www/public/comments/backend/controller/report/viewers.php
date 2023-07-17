<?php
namespace Commentics;

class ReportViewersController extends Controller
{
    public function index()
    {
        $this->loadLanguage('report/viewers');

        $this->loadModel('report/viewers');

        $this->loadModel('common/pagination');

        if (!$this->setting->get('viewers_enabled')) {
            $this->data['warning'] = $this->data['lang_message_disabled'];
        }

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

        if (isset($this->request->get['filter_type'])) {
            $filter_type = $this->request->get['filter_type'];
        } else {
            $filter_type = '';
        }

        if (isset($this->request->get['filter_ip_address'])) {
            $filter_ip_address = $this->request->get['filter_ip_address'];
        } else {
            $filter_ip_address = '';
        }

        if (isset($this->request->get['filter_page_reference'])) {
            $filter_page_reference = $this->request->get['filter_page_reference'];
        } else {
            $filter_page_reference = '';
        }

        if (isset($this->request->get['filter_page_url'])) {
            $filter_page_url = $this->request->get['filter_page_url'];
        } else {
            $filter_page_url = '';
        }

        $page_cookie = $this->model_report_viewers->getPageCookie();

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else if ($page_cookie['sort']) {
            $sort = $page_cookie['sort'];
        } else {
            $sort = 'v.time_added';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else if ($page_cookie['order']) {
            $order = $page_cookie['order'];
        } else {
            $order = 'desc';
        }

        if (isset($this->request->get['sort']) && isset($this->request->get['order'])) {
            $this->model_report_viewers->setPageCookie($this->request->get['sort'], $this->request->get['order']);
        }

        $data = array(
            'filter_id'             => $filter_id,
            'filter_type'           => $filter_type,
            'filter_ip_address'     => $filter_ip_address,
            'filter_page_reference' => $filter_page_reference,
            'filter_page_url'       => $filter_page_url,
            'group_by'              => '',
            'sort'                  => $sort,
            'order'                 => $order,
            'start'                 => ($page - 1) * $this->setting->get('limit_results'),
            'limit'                 => $this->setting->get('limit_results')
        );

        $this->model_report_viewers->clearViewers();

        $viewers = $this->model_report_viewers->getViewers($data);

        $total = $this->model_report_viewers->getViewers($data, true);

        $this->data['viewers'] = array();

        foreach ($viewers as $viewer) {
            $this->data['viewers'][] = array(
                'viewer'         => $this->loadImage('viewer/' . $viewer['viewer']),
                'type'           => ($viewer['type'] == 'Person') ? $this->data['lang_text_person'] : $viewer['type'],
                'ip_address'     => $viewer['ip_address'],
                'page_reference' => $viewer['page_reference'],
                'page_url'       => $viewer['page_url'],
                'time'           => $this->model_report_viewers->formatTime($viewer['time_added'])
            );
        }

        $sort_url = $this->model_report_viewers->sortUrl();

        $this->data['sort_viewer'] = $this->url->link('report/viewers', '&sort=v.viewer' . $sort_url);

        $this->data['sort_type'] = $this->url->link('report/viewers', '&sort=v.type' . $sort_url);

        $this->data['sort_ip_address'] = $this->url->link('report/viewers', '&sort=v.ip_address' . $sort_url);

        $this->data['sort_page_reference'] = $this->url->link('report/viewers', '&sort=v.page_reference' . $sort_url);

        $this->data['sort_page_url'] = $this->url->link('report/viewers', '&sort=v.page_url' . $sort_url);

        $this->data['sort_time'] = $this->url->link('report/viewers', '&sort=v.time_added' . $sort_url);

        if ($viewers) {
            $pagination_url = $this->model_report_viewers->paginateUrl();

            $url = $this->url->link('report/viewers', $pagination_url . '&page=[page]');

            $pagination = $this->model_common_pagination->paginate($page, $total, $url, $this->data);

            $this->data['pagination_stats'] = $pagination['stats'];

            $this->data['pagination_links'] = $pagination['links'];
        } else {
            $this->data['pagination_stats'] = '';

            $this->data['pagination_links'] = '';
        }

        $this->data['filter_type'] = $filter_type;

        $this->data['filter_ip_address'] = $filter_ip_address;

        $this->data['filter_page_reference'] = $filter_page_reference;

        $this->data['filter_page_url'] = $filter_page_url;

        $this->data['sort'] = $sort;

        $this->data['order'] = $order;

        $this->components = array('common/header', 'common/footer');

        $this->loadView('report/viewers');
    }

    private function checkParameters()
    {
        if (isset($this->request->get['page']) && (!$this->validation->isInt($this->request->get['page']) || $this->request->get['page'] < 1)) {
            return false;
        }

        if (isset($this->request->get['sort']) && !in_array($this->request->get['sort'], array('v.viewer', 'v.type', 'v.ip_address', 'v.page_reference', 'v.page_url', 'v.time_added'))) {
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

            if (isset($this->request->get['filter_type']) || isset($this->request->get['filter_ip_address']) || isset($this->request->get['filter_page_reference']) || isset($this->request->get['filter_page_url'])) {
                $this->loadModel('report/viewers');

                if (isset($this->request->get['filter_type'])) {
                    $filter_type = $this->request->get['filter_type'];

                    $group_by = 'v.type';

                    $sort = 'v.type';
                } else {
                    $filter_type = '';
                }

                if (isset($this->request->get['filter_ip_address'])) {
                    $filter_ip_address = $this->request->get['filter_ip_address'];

                    $group_by = 'v.ip_address';

                    $sort = 'v.ip_address';
                } else {
                    $filter_ip_address = '';
                }

                if (isset($this->request->get['filter_page_reference'])) {
                    $filter_page_reference = $this->request->get['filter_page_reference'];

                    $group_by = 'v.page_reference';

                    $sort = 'v.page_reference';
                } else {
                    $filter_page_reference = '';
                }

                if (isset($this->request->get['filter_page_url'])) {
                    $filter_page_url = $this->request->get['filter_page_url'];

                    $group_by = 'v.page_url';

                    $sort = 'v.page_url';
                } else {
                    $filter_page_url = '';
                }

                $data = array(
                    'filter_id'             => '',
                    'filter_type'           => $filter_type,
                    'filter_ip_address'     => $filter_ip_address,
                    'filter_page_reference' => $filter_page_reference,
                    'filter_page_url'       => $filter_page_url,
                    'group_by'              => $group_by,
                    'sort'                  => $sort,
                    'order'                 => 'asc',
                    'start'                 => 0,
                    'limit'                 => 10
                );

                $viewers = $this->model_report_viewers->getViewers($data);

                foreach ($viewers as $viewer) {
                    $json[] = array(
                        'type'           => strip_tags($this->security->decode($viewer['type'])),
                        'ip_address'     => strip_tags($this->security->decode($viewer['ip_address'])),
                        'page_reference' => strip_tags($this->security->decode($viewer['page_reference'])),
                        'page_url'       => strip_tags($this->security->decode($viewer['page_url']))
                    );
                }
            }

            echo json_encode($json);
        }
    }
}
