<?php
namespace Commentics;

class ManageSubscriptionsController extends Controller
{
    public function index()
    {
        $this->loadLanguage('manage/subscriptions');

        $this->loadModel('manage/subscriptions');

        $this->loadModel('common/pagination');

        if (!$this->checkParameters()) {
            $this->response->redirect('main/dashboard');
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                if (isset($this->request->post['single_delete'])) {
                    $result = $this->model_manage_subscriptions->singleDelete($this->request->post['single_delete']);

                    if ($result) {
                        $this->data['success'] = $this->data['lang_message_single_delete_success'];
                    } else {
                        $this->data['error'] = $this->data['lang_message_single_delete_invalid'];
                    }
                } else if (isset($this->request->post['bulk'])) {
                    $result = $this->model_manage_subscriptions->bulkDelete($this->request->post['bulk']);

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

        if (isset($this->request->get['filter_user_id'])) {
            $filter_user_id = $this->request->get['filter_user_id'];
        } else {
            $filter_user_id = '';
        }

        if (isset($this->request->get['filter_page_id'])) {
            $filter_page_id = $this->request->get['filter_page_id'];
        } else {
            $filter_page_id = '';
        }

        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = '';
        }

        if (isset($this->request->get['filter_email'])) {
            $filter_email = $this->request->get['filter_email'];
        } else {
            $filter_email = '';
        }

        if (isset($this->request->get['filter_page'])) {
            $filter_page = $this->request->get['filter_page'];
        } else {
            $filter_page = '';
        }

        if (isset($this->request->get['filter_confirmed'])) {
            $filter_confirmed = $this->request->get['filter_confirmed'];
        } else {
            $filter_confirmed = '';
        }

        if (isset($this->request->get['filter_ip_address'])) {
            $filter_ip_address = $this->request->get['filter_ip_address'];
        } else {
            $filter_ip_address = '';
        }

        if (isset($this->request->get['filter_date'])) {
            $filter_date = $this->request->get['filter_date'];
        } else {
            $filter_date = '';
        }

        $page_cookie = $this->model_manage_subscriptions->getPageCookie();

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
            $this->model_manage_subscriptions->setPageCookie($this->request->get['sort'], $this->request->get['order']);
        }

        $data = array(
            'filter_id'         => $filter_id,
            'filter_user_id'    => $filter_user_id,
            'filter_page_id'    => $filter_page_id,
            'filter_name'       => $filter_name,
            'filter_email'      => $filter_email,
            'filter_page'       => $filter_page,
            'filter_confirmed'  => $filter_confirmed,
            'filter_ip_address' => $filter_ip_address,
            'filter_date'       => $filter_date,
            'group_by'          => '',
            'sort'              => $sort,
            'order'             => $order,
            'start'             => ($page - 1) * $this->setting->get('limit_results'),
            'limit'             => $this->setting->get('limit_results')
        );

        $subscriptions = $this->model_manage_subscriptions->getSubscriptions($data);

        $total = $this->model_manage_subscriptions->getSubscriptions($data, true);

        $this->data['subscriptions'] = array();

        foreach ($subscriptions as $subscription) {
            $this->data['subscriptions'][] = array(
                'id'          => $subscription['id'],
                'name'        => $subscription['name'],
                'email'       => $subscription['email'],
                'name_url'    => $this->url->link('edit/user', '&id=' . $subscription['user_id']),
                'page'        => $subscription['page_reference'],
                'page_url'    => $this->url->link('edit/page', '&id=' . $subscription['page_id']),
                'confirmed'   => ($subscription['is_confirmed']) ? $this->data['lang_text_yes'] : $this->data['lang_text_no'],
                'ip_address'  => $subscription['ip_address'],
                'date_added'  => $this->variable->formatDate($subscription['date_added'], $this->data['lang_date_time_format'], $this->data),
                'action_view' => $this->setting->get('commentics_url') . 'frontend/index.php?route=main/user&u-t=' . $subscription['user_token'] . '#subscriptions',
                'action_edit' => $this->url->link('edit/subscription', '&id=' . $subscription['id'])
            );
        }

        $sort_url = $this->model_manage_subscriptions->sortUrl();

        $this->data['sort_name'] = $this->url->link('manage/subscriptions', '&sort=u.name' . $sort_url);

        $this->data['sort_email'] = $this->url->link('manage/subscriptions', '&sort=u.email' . $sort_url);

        $this->data['sort_page'] = $this->url->link('manage/subscriptions', '&sort=p.reference' . $sort_url);

        $this->data['sort_confirmed'] = $this->url->link('manage/subscriptions', '&sort=s.is_confirmed' . $sort_url);

        $this->data['sort_ip_address'] = $this->url->link('manage/subscriptions', '&sort=s.ip_address' . $sort_url);

        $this->data['sort_date'] = $this->url->link('manage/subscriptions', '&sort=s.date_added' . $sort_url);

        if ($subscriptions) {
            $pagination_url = $this->model_manage_subscriptions->paginateUrl();

            $url = $this->url->link('manage/subscriptions', $pagination_url . '&page=[page]');

            $pagination = $this->model_common_pagination->paginate($page, $total, $url, $this->data);

            $this->data['pagination_stats'] = $pagination['stats'];

            $this->data['pagination_links'] = $pagination['links'];
        } else {
            $this->data['pagination_stats'] = '';

            $this->data['pagination_links'] = '';
        }

        $this->data['filter_name'] = $filter_name;

        $this->data['filter_email'] = $filter_email;

        $this->data['filter_page'] = $filter_page;

        $this->data['filter_confirmed'] = $filter_confirmed;

        $this->data['filter_ip_address'] = $filter_ip_address;

        $this->data['filter_date'] = $filter_date;

        $this->data['sort'] = $sort;

        $this->data['order'] = $order;

        $this->data['button_view'] = $this->loadImage('button/view.png');

        $this->data['button_edit'] = $this->loadImage('button/edit.png');

        $this->data['button_delete'] = $this->loadImage('button/delete.png');

        if ($this->setting->get('notice_manage_subscriptions')) {
            $this->data['info'] = $this->data['lang_notice'];
        }

        $this->components = array('common/header', 'common/footer');

        $this->loadView('manage/subscriptions');
    }

    public function dismiss()
    {
        $this->loadModel('manage/subscriptions');

        $this->model_manage_subscriptions->dismiss();
    }

    private function checkParameters()
    {
        if (isset($this->request->get['page']) && (!$this->validation->isInt($this->request->get['page']) || $this->request->get['page'] < 1)) {
            return false;
        }

        if (isset($this->request->get['sort']) && !in_array($this->request->get['sort'], array('u.name', 'u.email', 'p.reference', 's.is_confirmed', 's.ip_address', 's.date_added'))) {
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

            if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_email']) || isset($this->request->get['filter_page']) || isset($this->request->get['filter_ip_address'])) {
                $this->loadModel('manage/subscriptions');

                if (isset($this->request->get['filter_name'])) {
                    $filter_name = $this->request->get['filter_name'];

                    $group_by = 'u.name';

                    $sort = 'u.name';
                } else {
                    $filter_name = '';
                }

                if (isset($this->request->get['filter_email'])) {
                    $filter_email = $this->request->get['filter_email'];

                    $group_by = 'u.email';

                    $sort = 'u.email';
                } else {
                    $filter_email = '';
                }

                if (isset($this->request->get['filter_page'])) {
                    $filter_page = $this->request->get['filter_page'];

                    $group_by = 'p.reference';

                    $sort = 'p.reference';
                } else {
                    $filter_page = '';
                }

                if (isset($this->request->get['filter_ip_address'])) {
                    $filter_ip_address = $this->request->get['filter_ip_address'];

                    $group_by = 's.ip_address';

                    $sort = 's.ip_address';
                } else {
                    $filter_ip_address = '';
                }

                $data = array(
                    'filter_id'         => '',
                    'filter_user_id'    => '',
                    'filter_page_id'    => '',
                    'filter_name'       => $filter_name,
                    'filter_email'      => $filter_email,
                    'filter_page'       => $filter_page,
                    'filter_confirmed'  => '',
                    'filter_ip_address' => $filter_ip_address,
                    'filter_date'       => '',
                    'group_by'          => $group_by,
                    'sort'              => $sort,
                    'order'             => 'asc',
                    'start'             => 0,
                    'limit'             => 10
                );

                $subscriptions = $this->model_manage_subscriptions->getSubscriptions($data);

                foreach ($subscriptions as $subscription) {
                    $json[] = array(
                        'name'       => strip_tags($this->security->decode($subscription['name'])),
                        'email'      => strip_tags($this->security->decode($subscription['email'])),
                        'page'       => strip_tags($this->security->decode($subscription['page_reference'])),
                        'ip_address' => strip_tags($this->security->decode($subscription['ip_address']))
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
