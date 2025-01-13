<?php
namespace Commentics;

class ManageAdminsController extends Controller
{
    public function index()
    {
        $this->loadLanguage('manage/admins');

        $this->loadModel('manage/admins');

        $this->loadModel('common/pagination');

        if (!$this->checkParameters()) {
            $this->response->redirect('main/dashboard');
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                if (isset($this->request->post['single_delete'])) {
                    $result = $this->model_manage_admins->singleDelete($this->request->post['single_delete']);

                    if ($result) {
                        $this->data['success'] = $this->data['lang_message_single_delete_success'];
                    } else {
                        $this->data['error'] = $this->data['lang_message_single_delete_invalid'];
                    }
                } else if (isset($this->request->post['bulk'])) {
                    $result = $this->model_manage_admins->bulkDelete($this->request->post['bulk']);

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

        if (isset($this->request->get['filter_username'])) {
            $filter_username = $this->request->get['filter_username'];
        } else {
            $filter_username = '';
        }

        if (isset($this->request->get['filter_email'])) {
            $filter_email = $this->request->get['filter_email'];
        } else {
            $filter_email = '';
        }

        if (isset($this->request->get['filter_enabled'])) {
            $filter_enabled = $this->request->get['filter_enabled'];
        } else {
            $filter_enabled = '';
        }

        if (isset($this->request->get['filter_super'])) {
            $filter_super = $this->request->get['filter_super'];
        } else {
            $filter_super = '';
        }

        if (isset($this->request->get['filter_last_login'])) {
            $filter_last_login = $this->request->get['filter_last_login'];
        } else {
            $filter_last_login = '';
        }

        if (isset($this->request->get['filter_date'])) {
            $filter_date = $this->request->get['filter_date'];
        } else {
            $filter_date = '';
        }

        $page_cookie = $this->model_manage_admins->getPageCookie();

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
            $this->model_manage_admins->setPageCookie($this->request->get['sort'], $this->request->get['order']);
        }

        $data = array(
            'filter_id'         => $filter_id,
            'filter_username'   => $filter_username,
            'filter_email'      => $filter_email,
            'filter_enabled'    => $filter_enabled,
            'filter_super'      => $filter_super,
            'filter_last_login' => $filter_last_login,
            'filter_date'       => $filter_date,
            'group_by'          => '',
            'sort'              => $sort,
            'order'             => $order,
            'start'             => ($page - 1) * $this->setting->get('limit_results'),
            'limit'             => $this->setting->get('limit_results')
        );

        $admins = $this->model_manage_admins->getAdmins($data);

        $total = $this->model_manage_admins->getAdmins($data, true);

        $this->data['admins'] = array();

        foreach ($admins as $admin) {
            $this->data['admins'][] = array(
                'id'         => $admin['id'],
                'username'   => $admin['username'],
                'email'      => $admin['email'],
                'enabled'    => ($admin['is_enabled']) ? $this->data['lang_text_yes'] : $this->data['lang_text_no'],
                'super'      => ($admin['is_super']) ? $this->data['lang_text_yes'] : $this->data['lang_text_no'],
                'last_login' => $this->variable->formatDate($admin['last_login'], $this->data['lang_date_time_format'], $this->data),
                'date_added' => $this->variable->formatDate($admin['date_added'], $this->data['lang_date_time_format'], $this->data),
                'action'     => $this->url->link('edit/admin', '&id=' . $admin['id'])
            );
        }

        $sort_url = $this->model_manage_admins->sortUrl();

        $this->data['sort_username'] = $this->url->link('manage/admins', '&sort=a.username' . $sort_url);

        $this->data['sort_email'] = $this->url->link('manage/admins', '&sort=a.email' . $sort_url);

        $this->data['sort_enabled'] = $this->url->link('manage/admins', '&sort=a.is_enabled' . $sort_url);

        $this->data['sort_super'] = $this->url->link('manage/admins', '&sort=a.is_super' . $sort_url);

        $this->data['sort_last_login'] = $this->url->link('manage/admins', '&sort=a.last_login' . $sort_url);

        $this->data['sort_date'] = $this->url->link('manage/admins', '&sort=a.date_added' . $sort_url);

        if ($admins) {
            $pagination_url = $this->model_manage_admins->paginateUrl();

            $url = $this->url->link('manage/admins', $pagination_url . '&page=[page]');

            $pagination = $this->model_common_pagination->paginate($page, $total, $url, $this->data);

            $this->data['pagination_stats'] = $pagination['stats'];

            $this->data['pagination_links'] = $pagination['links'];
        } else {
            $this->data['pagination_stats'] = '';

            $this->data['pagination_links'] = '';
        }

        $this->data['filter_username'] = $filter_username;

        $this->data['filter_email'] = $filter_email;

        $this->data['filter_enabled'] = $filter_enabled;

        $this->data['filter_super'] = $filter_super;

        $this->data['filter_last_login'] = $filter_last_login;

        $this->data['filter_date'] = $filter_date;

        $this->data['sort'] = $sort;

        $this->data['order'] = $order;

        $this->data['button_edit'] = $this->loadImage('button/edit.png');

        $this->data['button_delete'] = $this->loadImage('button/delete.png');

        if ($this->setting->get('notice_manage_admins')) {
            $this->data['info'] = $this->data['lang_notice'];
        }

        $this->data['lang_description'] = sprintf($this->data['lang_description'], $this->url->link('add/admin'));

        $this->components = array('common/header', 'common/footer');

        $this->loadView('manage/admins');
    }

    public function dismiss()
    {
        $this->loadModel('manage/admins');

        $this->model_manage_admins->dismiss();
    }

    private function checkParameters()
    {
        if (isset($this->request->get['page']) && (!$this->validation->isInt($this->request->get['page']) || $this->request->get['page'] < 1)) {
            return false;
        }

        if (isset($this->request->get['sort']) && !in_array($this->request->get['sort'], array('a.username', 'a.email', 'a.is_enabled', 'a.is_super', 'a.last_login', 'a.date_added'))) {
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

            if (isset($this->request->get['filter_username']) || isset($this->request->get['filter_email'])) {
                $this->loadModel('manage/admins');

                if (isset($this->request->get['filter_username'])) {
                    $filter_username = $this->request->get['filter_username'];

                    $group_by = 'a.username';

                    $sort = 'a.username';
                } else {
                    $filter_username = '';
                }

                if (isset($this->request->get['filter_email'])) {
                    $filter_email = $this->request->get['filter_email'];

                    $group_by = 'a.email';

                    $sort = 'a.email';
                } else {
                    $filter_email = '';
                }

                $data = array(
                    'filter_id'         => '',
                    'filter_username'   => $filter_username,
                    'filter_email'      => $filter_email,
                    'filter_enabled'    => '',
                    'filter_super'      => '',
                    'filter_last_login' => '',
                    'filter_date'       => '',
                    'group_by'          => $group_by,
                    'sort'              => $sort,
                    'order'             => 'asc',
                    'start'             => 0,
                    'limit'             => 10
                );

                $admins = $this->model_manage_admins->getAdmins($data);

                foreach ($admins as $admin) {
                    $json[] = array(
                        'username' => strip_tags($this->security->decode($admin['username'])),
                        'email' => strip_tags($this->security->decode($admin['email']))
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
