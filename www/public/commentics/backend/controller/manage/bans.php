<?php
namespace Commentics;

class ManageBansController extends Controller
{
    public function index()
    {
        $this->loadLanguage('manage/bans');

        $this->loadModel('manage/bans');

        $this->loadModel('common/pagination');

        if (!$this->checkParameters()) {
            $this->response->redirect('main/dashboard');
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                if (isset($this->request->post['single_delete'])) {
                    $result = $this->model_manage_bans->singleDelete($this->request->post['single_delete']);

                    if ($result) {
                        $this->data['success'] = $this->data['lang_message_single_delete_success'];
                    } else {
                        $this->data['error'] = $this->data['lang_message_single_delete_invalid'];
                    }
                } else if (isset($this->request->post['bulk'])) {
                    $result = $this->model_manage_bans->bulkDelete($this->request->post['bulk']);

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

        if (isset($this->request->get['filter_ip_address'])) {
            $filter_ip_address = $this->request->get['filter_ip_address'];
        } else {
            $filter_ip_address = '';
        }

        if (isset($this->request->get['filter_reason'])) {
            $filter_reason = $this->request->get['filter_reason'];
        } else {
            $filter_reason = '';
        }

        if (isset($this->request->get['filter_date'])) {
            $filter_date = $this->request->get['filter_date'];
        } else {
            $filter_date = '';
        }

        $page_cookie = $this->model_manage_bans->getPageCookie();

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else if ($page_cookie['sort']) {
            $sort = $page_cookie['sort'];
        } else {
            $sort = 'b.date_added';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else if ($page_cookie['order']) {
            $order = $page_cookie['order'];
        } else {
            $order = 'desc';
        }

        if (isset($this->request->get['sort']) && isset($this->request->get['order'])) {
            $this->model_manage_bans->setPageCookie($this->request->get['sort'], $this->request->get['order']);
        }

        $data = array(
            'filter_id'         => $filter_id,
            'filter_ip_address' => $filter_ip_address,
            'filter_reason'     => $filter_reason,
            'filter_date'       => $filter_date,
            'group_by'          => '',
            'sort'              => $sort,
            'order'             => $order,
            'start'             => ($page - 1) * $this->setting->get('limit_results'),
            'limit'             => $this->setting->get('limit_results')
        );

        $bans = $this->model_manage_bans->getBans($data);

        $total = $this->model_manage_bans->getBans($data, true);

        $this->data['bans'] = array();

        foreach ($bans as $ban) {
            $this->data['bans'][] = array(
                'id'         => $ban['id'],
                'ip_address' => $ban['ip_address'],
                'reason'     => $ban['reason'],
                'date_added' => $this->variable->formatDate($ban['date_added'], $this->data['lang_date_time_format'], $this->data),
                'action'     => $this->url->link('edit/ban', '&id=' . $ban['id'])
            );
        }

        $sort_url = $this->model_manage_bans->sortUrl();

        $this->data['sort_ip_address'] = $this->url->link('manage/bans', '&sort=b.ip_address' . $sort_url);

        $this->data['sort_reason'] = $this->url->link('manage/bans', '&sort=b.reason' . $sort_url);

        $this->data['sort_date'] = $this->url->link('manage/bans', '&sort=b.date_added' . $sort_url);

        if ($bans) {
            $pagination_url = $this->model_manage_bans->paginateUrl();

            $url = $this->url->link('manage/bans', $pagination_url . '&page=[page]');

            $pagination = $this->model_common_pagination->paginate($page, $total, $url, $this->data);

            $this->data['pagination_stats'] = $pagination['stats'];

            $this->data['pagination_links'] = $pagination['links'];
        } else {
            $this->data['pagination_stats'] = '';

            $this->data['pagination_links'] = '';
        }

        $this->data['filter_ip_address'] = $filter_ip_address;

        $this->data['filter_reason'] = $filter_reason;

        $this->data['filter_date'] = $filter_date;

        $this->data['sort'] = $sort;

        $this->data['order'] = $order;

        $this->data['button_edit'] = $this->loadImage('button/edit.png');

        $this->data['button_delete'] = $this->loadImage('button/delete.png');

        if ($this->setting->get('notice_manage_bans')) {
            $this->data['info'] = $this->data['lang_notice'];
        }

        $this->data['lang_description'] = sprintf($this->data['lang_description'], $this->url->link('add/ban'));

        $this->components = array('common/header', 'common/footer');

        $this->loadView('manage/bans');
    }

    public function dismiss()
    {
        $this->loadModel('manage/bans');

        $this->model_manage_bans->dismiss();
    }

    private function checkParameters()
    {
        if (isset($this->request->get['page']) && (!$this->validation->isInt($this->request->get['page']) || $this->request->get['page'] < 1)) {
            return false;
        }

        if (isset($this->request->get['sort']) && !in_array($this->request->get['sort'], array('b.ip_address', 'b.reason', 'b.date_added'))) {
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

            if (isset($this->request->get['filter_ip_address']) || isset($this->request->get['filter_reason'])) {
                $this->loadModel('manage/bans');

                if (isset($this->request->get['filter_ip_address'])) {
                    $filter_ip_address = $this->request->get['filter_ip_address'];

                    $group_by = 'b.ip_address';

                    $sort = 'b.ip_address';
                } else {
                    $filter_ip_address = '';
                }

                if (isset($this->request->get['filter_reason'])) {
                    $filter_reason = $this->request->get['filter_reason'];

                    $group_by = 'b.reason';

                    $sort = 'b.reason';
                } else {
                    $filter_reason = '';
                }

                $data = array(
                    'filter_id'         => '',
                    'filter_ip_address' => $filter_ip_address,
                    'filter_reason'     => $filter_reason,
                    'filter_date'       => '',
                    'group_by'          => $group_by,
                    'sort'              => $sort,
                    'order'             => 'asc',
                    'start'             => 0,
                    'limit'             => 10
                );

                $bans = $this->model_manage_bans->getBans($data);

                foreach ($bans as $ban) {
                    $json[] = array(
                        'ip_address' => strip_tags($this->security->decode($ban['ip_address'])),
                        'reason' => strip_tags($this->security->decode($ban['reason']))
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
