<?php
namespace Commentics;

class ManageUsersController extends Controller
{
    public function index()
    {
        $this->loadLanguage('manage/users');

        $this->loadModel('manage/users');

        $this->loadModel('common/pagination');

        if (!$this->checkParameters()) {
            $this->response->redirect('main/dashboard');
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                if (isset($this->request->post['single_delete'])) {
                    $result = $this->model_manage_users->singleDelete($this->request->post['single_delete']);

                    if ($result) {
                        $this->data['success'] = $this->data['lang_message_single_delete_success'];
                    } else {
                        $this->data['error'] = $this->data['lang_message_single_delete_invalid'];
                    }
                } else if (isset($this->request->post['bulk']) && isset($this->request->post['bulk_action'])) {
                    if ($this->request->post['bulk_action'] == 'delete') {
                        $result = $this->model_manage_users->bulkDelete($this->request->post['bulk']);

                        if ($result['success']) {
                            $this->data['success'] = sprintf($this->data['lang_message_bulk_delete_success'], $result['success']);
                        }

                        if ($result['failure']) {
                            $this->data['error'] = sprintf($this->data['lang_message_bulk_delete_invalid'], $result['failure']);
                        }
                    } else if ($this->request->post['bulk_action'] == 'approve_avatar') {
                        $result = $this->model_manage_users->bulkApproveAvatar($this->request->post['bulk']);

                        if ($result['success']) {
                            $this->data['success'] = sprintf($this->data['lang_message_bulk_approve_avatar_success'], $result['success']);
                        }

                        if ($result['failure']) {
                            $this->data['error'] = sprintf($this->data['lang_message_bulk_approve_avatar_invalid'], $result['failure']);
                        }
                    } else if ($this->request->post['bulk_action'] == 'disapprove_avatar') {
                        $result = $this->model_manage_users->bulkDisapproveAvatar($this->request->post['bulk']);

                        if ($result['success']) {
                            $this->data['success'] = sprintf($this->data['lang_message_bulk_disapprove_avatar_success'], $result['success']);
                        }

                        if ($result['failure']) {
                            $this->data['error'] = sprintf($this->data['lang_message_bulk_disapprove_avatar_invalid'], $result['failure']);
                        }
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

        if (isset($this->request->get['filter_email'])) {
            $filter_email = $this->request->get['filter_email'];
        } else {
            $filter_email = '';
        }

        if (isset($this->request->get['filter_avatar_approved'])) {
            $filter_avatar_approved = $this->request->get['filter_avatar_approved'];
        } else {
            $filter_avatar_approved = '';
        }

        if (isset($this->request->get['filter_moderate'])) {
            $filter_moderate = $this->request->get['filter_moderate'];
        } else {
            $filter_moderate = '';
        }

        if (isset($this->request->get['filter_date'])) {
            $filter_date = $this->request->get['filter_date'];
        } else {
            $filter_date = '';
        }

        $page_cookie = $this->model_manage_users->getPageCookie();

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else if ($page_cookie['sort']) {
            $sort = $page_cookie['sort'];
        } else {
            $sort = 'u.date_added';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else if ($page_cookie['order']) {
            $order = $page_cookie['order'];
        } else {
            $order = 'desc';
        }

        if (isset($this->request->get['sort']) && isset($this->request->get['order'])) {
            $this->model_manage_users->setPageCookie($this->request->get['sort'], $this->request->get['order']);
        }

        $data = array(
            'filter_id'              => $filter_id,
            'filter_name'            => $filter_name,
            'filter_email'           => $filter_email,
            'filter_avatar_approved' => $filter_avatar_approved,
            'filter_moderate'        => $filter_moderate,
            'filter_date'            => $filter_date,
            'group_by'               => '',
            'sort'                   => $sort,
            'order'                  => $order,
            'start'                  => ($page - 1) * $this->setting->get('limit_results'),
            'limit'                  => $this->setting->get('limit_results')
        );

        $users = $this->model_manage_users->getUsers($data);

        $total = $this->model_manage_users->getUsers($data, true);

        $this->data['users'] = array();

        foreach ($users as $user) {
            if ($user['moderate'] == 'default') {
                $moderate = $this->data['lang_text_default'];
            } else if ($user['moderate'] == 'never') {
                $moderate = $this->data['lang_text_never'];
            } else {
                $moderate = $this->data['lang_text_always'];
            }

            $this->data['users'][] = array(
                'id'                => $user['id'],
                'avatar'            => ($this->setting->get('avatar_type')) ? $this->user->getAvatar($user['id'], true) : '',
                'name'              => $user['name'],
                'email'             => $user['email'],
                'comments'          => $user['comments'],
                'comments_url'      => $this->url->link('manage/comments', '&filter_user_id=' . $user['id']),
                'subscriptions'     => $user['subscriptions'],
                'subscriptions_url' => $this->url->link('manage/subscriptions', '&filter_user_id=' . $user['id']),
                'avatar_approved'   => ($user['avatar_pending_id'] == '0') ? $this->data['lang_text_yes'] : $this->data['lang_text_no'],
                'moderate'          => $moderate,
                'date_added'        => $this->variable->formatDate($user['date_added'], $this->data['lang_date_time_format'], $this->data),
                'action_view'       => $this->setting->get('commentics_url') . 'frontend/index.php?route=main/user&u-t=' . $user['token'],
                'action_edit'       => $this->url->link('edit/user', '&id=' . $user['id'])
            );
        }

        $sort_url = $this->model_manage_users->sortUrl();

        $this->data['sort_name'] = $this->url->link('manage/users', '&sort=u.name' . $sort_url);

        $this->data['sort_email'] = $this->url->link('manage/users', '&sort=u.email' . $sort_url);

        $this->data['sort_comments'] = $this->url->link('manage/users', '&sort=comments' . $sort_url);

        $this->data['sort_subscriptions'] = $this->url->link('manage/users', '&sort=subscriptions' . $sort_url);

        $this->data['sort_avatar_approved'] = $this->url->link('manage/users', '&sort=avatar_approved' . $sort_url);

        $this->data['sort_moderate'] = $this->url->link('manage/users', '&sort=u.moderate' . $sort_url);

        $this->data['sort_date'] = $this->url->link('manage/users', '&sort=u.date_added' . $sort_url);

        if ($users) {
            $pagination_url = $this->model_manage_users->paginateUrl();

            $url = $this->url->link('manage/users', $pagination_url . '&page=[page]');

            $pagination = $this->model_common_pagination->paginate($page, $total, $url, $this->data);

            $this->data['pagination_stats'] = $pagination['stats'];

            $this->data['pagination_links'] = $pagination['links'];
        } else {
            $this->data['pagination_stats'] = '';

            $this->data['pagination_links'] = '';
        }

        $this->data['filter_name'] = $filter_name;

        $this->data['filter_email'] = $filter_email;

        $this->data['filter_avatar_approved'] = $filter_avatar_approved;

        $this->data['filter_moderate'] = $filter_moderate;

        $this->data['filter_date'] = $filter_date;

        $this->data['sort'] = $sort;

        $this->data['order'] = $order;

        $this->data['button_view'] = $this->loadImage('button/view.png');

        $this->data['button_edit'] = $this->loadImage('button/edit.png');

        $this->data['button_delete'] = $this->loadImage('button/delete.png');

        $this->data['lang_description'] = sprintf($this->data['lang_description'], $this->url->link('manage/bans'), $this->url->link('manage/subscriptions'));

        $this->data['avatar_type'] = $this->setting->get('avatar_type');

        $this->components = array('common/header', 'common/footer');

        $this->loadView('manage/users');
    }

    private function checkParameters()
    {
        if (isset($this->request->get['page']) && (!$this->validation->isInt($this->request->get['page']) || $this->request->get['page'] < 1)) {
            return false;
        }

        if (isset($this->request->get['sort']) && !in_array($this->request->get['sort'], array('u.name', 'u.email', 'comments', 'subscriptions', 'avatar_approved', 'u.moderate', 'u.date_added'))) {
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

            if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_email'])) {
                $this->loadModel('manage/users');

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

                $data = array(
                    'filter_id'              => '',
                    'filter_name'            => $filter_name,
                    'filter_email'           => $filter_email,
                    'filter_avatar_approved' => '',
                    'filter_moderate'        => '',
                    'filter_date'            => '',
                    'group_by'               => $group_by,
                    'sort'                   => $sort,
                    'order'                  => 'asc',
                    'start'                  => 0,
                    'limit'                  => 10
                );

                $users = $this->model_manage_users->getUsers($data);

                foreach ($users as $user) {
                    $json[] = array(
                        'name'  => strip_tags($this->security->decode($user['name'])),
                        'email' => strip_tags($this->security->decode($user['email']))
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
