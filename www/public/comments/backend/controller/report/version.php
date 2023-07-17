<?php
namespace Commentics;

class ReportVersionController extends Controller
{
    public function index()
    {
        $this->loadLanguage('report/version');

        $this->loadModel('report/version');

        $this->loadModel('common/pagination');

        if (!$this->checkParameters()) {
            $this->response->redirect('main/dashboard');
        }

        $this->data['lang_description'] = sprintf($this->data['lang_description'], $this->model_report_version->getCurrent());

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

        if (isset($this->request->get['filter_version'])) {
            $filter_version = $this->request->get['filter_version'];
        } else {
            $filter_version = '';
        }

        if (isset($this->request->get['filter_type'])) {
            $filter_type = $this->request->get['filter_type'];
        } else {
            $filter_type = '';
        }

        if (isset($this->request->get['filter_date'])) {
            $filter_date = $this->request->get['filter_date'];
        } else {
            $filter_date = '';
        }

        $page_cookie = $this->model_report_version->getPageCookie();

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else if ($page_cookie['sort']) {
            $sort = $page_cookie['sort'];
        } else {
            $sort = 'v.date_added';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else if ($page_cookie['order']) {
            $order = $page_cookie['order'];
        } else {
            $order = 'desc';
        }

        if (isset($this->request->get['sort']) && isset($this->request->get['order'])) {
            $this->model_report_version->setPageCookie($this->request->get['sort'], $this->request->get['order']);
        }

        $data = array(
            'filter_id'      => $filter_id,
            'filter_version' => $filter_version,
            'filter_type'    => $filter_type,
            'filter_date'    => $filter_date,
            'group_by'       => '',
            'sort'           => $sort,
            'order'          => $order,
            'start'          => ($page - 1) * $this->setting->get('limit_results'),
            'limit'          => $this->setting->get('limit_results')
        );

        $versions = $this->model_report_version->getVersions($data);

        $total = $this->model_report_version->getVersions($data, true);

        $this->data['versions'] = array();

        foreach ($versions as $version) {
            $this->data['versions'][] = array(
                'version'    => $version['version'],
                'type'       => ($version['type'] == 'Installation') ? $this->data['lang_text_installation'] : $this->data['lang_text_upgrade'],
                'date_added' => $this->variable->formatDate($version['date_added'], $this->data['lang_date_time_format'], $this->data)
            );
        }

        $sort_url = $this->model_report_version->sortUrl();

        $this->data['sort_version'] = $this->url->link('report/version', '&sort=v.version' . $sort_url);

        $this->data['sort_type'] = $this->url->link('report/version', '&sort=v.type' . $sort_url);

        $this->data['sort_date'] = $this->url->link('report/version', '&sort=v.date_added' . $sort_url);

        if ($versions) {
            $pagination_url = $this->model_report_version->paginateUrl();

            $url = $this->url->link('report/version', $pagination_url . '&page=[page]');

            $pagination = $this->model_common_pagination->paginate($page, $total, $url, $this->data);

            $this->data['pagination_stats'] = $pagination['stats'];

            $this->data['pagination_links'] = $pagination['links'];
        } else {
            $this->data['pagination_stats'] = '';

            $this->data['pagination_links'] = '';
        }

        $this->data['filter_version'] = $filter_version;

        $this->data['filter_type'] = $filter_type;

        $this->data['filter_date'] = $filter_date;

        $this->data['sort'] = $sort;

        $this->data['order'] = $order;

        $this->components = array('common/header', 'common/footer');

        $this->loadView('report/version');
    }

    private function checkParameters()
    {
        if (isset($this->request->get['page']) && (!$this->validation->isInt($this->request->get['page']) || $this->request->get['page'] < 1)) {
            return false;
        }

        if (isset($this->request->get['sort']) && !in_array($this->request->get['sort'], array('v.version', 'v.type', 'v.date_added'))) {
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

            if (isset($this->request->get['filter_version'])) {
                $this->loadModel('report/version');

                if (isset($this->request->get['filter_version'])) {
                    $filter_version = $this->request->get['filter_version'];

                    $group_by = 'v.version';

                    $sort = 'v.version';
                } else {
                    $filter_version = '';
                }

                $data = array(
                    'filter_id'      => '',
                    'filter_version' => $filter_version,
                    'filter_type'    => '',
                    'filter_date'    => '',
                    'group_by'       => $group_by,
                    'sort'           => $sort,
                    'order'          => 'asc',
                    'start'          => 0,
                    'limit'          => 10
                );

                $versions = $this->model_report_version->getVersions($data);

                foreach ($versions as $version) {
                    $json[] = array(
                        'version' => strip_tags($this->security->decode($version['version']))
                    );
                }
            }

            echo json_encode($json);
        }
    }
}
