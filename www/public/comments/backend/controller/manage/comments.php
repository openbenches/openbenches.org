<?php
namespace Commentics;

class ManageCommentsController extends Controller
{
    public function index()
    {
        $this->loadLanguage('manage/comments');

        $this->loadModel('manage/comments');

        $this->loadModel('common/pagination');

        if (!$this->checkParameters()) {
            $this->response->redirect('main/dashboard');
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                if (isset($this->request->post['single_approve'])) {
                    $result = $this->model_manage_comments->singleApprove($this->request->post['single_approve']);

                    if ($result) {
                        $this->data['success'] = $this->data['lang_message_single_approve_success'];
                    } else {
                        $this->data['error'] = $this->data['lang_message_single_approve_invalid'];
                    }
                } else if (isset($this->request->post['single_send'])) {
                    $result = $this->model_manage_comments->singleSend($this->request->post['single_send']);

                    if ($result) {
                        $this->data['success'] = $this->data['lang_message_single_send_success'];
                    } else {
                        $this->data['error'] = $this->data['lang_message_single_send_invalid'];
                    }
                } else if (isset($this->request->post['single_delete'])) {
                    $result = $this->model_manage_comments->singleDelete($this->request->post['single_delete']);

                    if ($result) {
                        $this->data['success'] = $this->data['lang_message_single_delete_success'];
                    } else {
                        $this->data['error'] = $this->data['lang_message_single_delete_invalid'];
                    }
                } else if (isset($this->request->post['bulk']) && isset($this->request->post['bulk_action'])) {
                    if ($this->request->post['bulk_action'] == 'approve') {
                        $result = $this->model_manage_comments->bulkApprove($this->request->post['bulk']);

                        if ($result['success']) {
                            $this->data['success'] = sprintf($this->data['lang_message_bulk_approve_success'], $result['success']);
                        }

                        if ($result['failure']) {
                            $this->data['error'] = sprintf($this->data['lang_message_bulk_approve_invalid'], $result['failure']);
                        }
                    } else if ($this->request->post['bulk_action'] == 'send') {
                        $result = $this->model_manage_comments->bulkSend($this->request->post['bulk']);

                        if ($result['success']) {
                            $this->data['success'] = sprintf($this->data['lang_message_bulk_send_success'], $result['success']);
                        }

                        if ($result['failure']) {
                            $this->data['error'] = sprintf($this->data['lang_message_bulk_send_invalid'], $result['failure']);
                        }
                    } else if ($this->request->post['bulk_action'] == 'delete') {
                        $result = $this->model_manage_comments->bulkDelete($this->request->post['bulk']);

                        if ($result['success']) {
                            $this->data['success'] = sprintf($this->data['lang_message_bulk_delete_success'], $result['success']);
                        }

                        if ($result['failure']) {
                            $this->data['error'] = sprintf($this->data['lang_message_bulk_delete_invalid'], $result['failure']);
                        }
                    }
                }
            }
        } else if (isset($this->session->data['cmtx_success'])) {
            $this->data['success'] = $this->session->data['cmtx_success'];

            unset($this->session->data['cmtx_success']);
        }

        if (isset($this->session->data['cmtx_error'])) {
            $this->data['error'] = $this->session->data['cmtx_error'];

            unset($this->session->data['cmtx_error']);
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

        if (isset($this->request->get['filter_comment'])) {
            $filter_comment = $this->request->get['filter_comment'];
        } else {
            $filter_comment = '';
        }

        if (isset($this->request->get['filter_rating'])) {
            $filter_rating = $this->request->get['filter_rating'];
        } else {
            $filter_rating = '';
        }

        if (isset($this->request->get['filter_page'])) {
            $filter_page = $this->request->get['filter_page'];
        } else {
            $filter_page = '';
        }

        if (isset($this->request->get['filter_approved'])) {
            $filter_approved = $this->request->get['filter_approved'];
        } else {
            $filter_approved = '';
        }

        if (isset($this->request->get['filter_sent'])) {
            $filter_sent = $this->request->get['filter_sent'];
        } else {
            $filter_sent = '';
        }

        if (isset($this->request->get['filter_flagged'])) {
            $filter_flagged = $this->request->get['filter_flagged'];
        } else {
            $filter_flagged = '';
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

        $page_cookie = $this->model_manage_comments->getPageCookie();

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else if ($page_cookie['sort']) {
            $sort = $page_cookie['sort'];
        } else {
            $sort = 'c.date_added';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else if ($page_cookie['order']) {
            $order = $page_cookie['order'];
        } else {
            $order = 'desc';
        }

        if (isset($this->request->get['sort']) && isset($this->request->get['order'])) {
            $this->model_manage_comments->setPageCookie($this->request->get['sort'], $this->request->get['order']);
        }

        $data = array(
            'filter_id'         => $filter_id,
            'filter_user_id'    => $filter_user_id,
            'filter_page_id'    => $filter_page_id,
            'filter_name'       => $filter_name,
            'filter_comment'    => $filter_comment,
            'filter_rating'     => $filter_rating,
            'filter_page'       => $filter_page,
            'filter_approved'   => $filter_approved,
            'filter_sent'       => $filter_sent,
            'filter_flagged'    => $filter_flagged,
            'filter_ip_address' => $filter_ip_address,
            'filter_date'       => $filter_date,
            'group_by'          => '',
            'sort'              => $sort,
            'order'             => $order,
            'start'             => ($page - 1) * $this->setting->get('limit_results'),
            'limit'             => $this->setting->get('limit_results')
        );

        $comments = $this->model_manage_comments->getComments($data);

        $total = $this->model_manage_comments->getComments($data, true);

        $this->data['comments'] = array();

        foreach ($comments as $comment) {
            $this->data['comments'][] = array(
                'id'          => $comment['id'],
                'name'        => $comment['name'],
                'name_url'    => $this->url->link('edit/user', '&id=' . $comment['user_id']),
                'comment'     => $this->model_manage_comments->shortenComment($comment['comment']),
                'rating'      => $comment['rating'],
                'page'        => $comment['page_reference'],
                'page_url'    => $this->url->link('edit/page', '&id=' . $comment['page_id']),
                'approved'    => ($comment['is_approved']) ? $this->data['lang_text_yes'] : $this->data['lang_text_no'],
                'sent'        => ($comment['is_sent']) ? $this->data['lang_text_yes'] : $this->data['lang_text_no'],
                'reports'     => $comment['reports'],
                'flagged'     => ($comment['reports'] >= $this->setting->get('flag_min_per_comment')) ? $this->data['lang_text_yes'] : $this->data['lang_text_no'],
                'ip_address'  => $comment['ip_address'],
                'date_added'  => $this->variable->formatDate($comment['date_added'], $this->data['lang_date_time_format'], $this->data),
                'action_view' => $this->comment->buildCommentUrl($comment['id'], $comment['page_url']),
                'action_edit' => $this->url->link('edit/comment', '&id=' . $comment['id']),
                'action_spam' => $this->url->link('edit/spam', '&id=' . $comment['id'])
            );
        }

        $sort_url = $this->model_manage_comments->sortUrl();

        $this->data['sort_name'] = $this->url->link('manage/comments', '&sort=u.name' . $sort_url);

        $this->data['sort_comment'] = $this->url->link('manage/comments', '&sort=c.comment' . $sort_url);

        $this->data['sort_rating'] = $this->url->link('manage/comments', '&sort=c.rating' . $sort_url);

        $this->data['sort_page'] = $this->url->link('manage/comments', '&sort=p.reference' . $sort_url);

        $this->data['sort_approved'] = $this->url->link('manage/comments', '&sort=c.is_approved' . $sort_url);

        $this->data['sort_sent'] = $this->url->link('manage/comments', '&sort=c.is_sent' . $sort_url);

        $this->data['sort_reports'] = $this->url->link('manage/comments', '&sort=c.reports' . $sort_url);

        $this->data['sort_flagged'] = $this->url->link('manage/comments', '&sort=c.flagged' . $sort_url);

        $this->data['sort_ip_address'] = $this->url->link('manage/comments', '&sort=c.ip_address' . $sort_url);

        $this->data['sort_date'] = $this->url->link('manage/comments', '&sort=c.date_added' . $sort_url);

        if ($comments) {
            $pagination_url = $this->model_manage_comments->paginateUrl();

            $url = $this->url->link('manage/comments', $pagination_url . '&page=[page]');

            $pagination = $this->model_common_pagination->paginate($page, $total, $url, $this->data);

            $this->data['pagination_stats'] = $pagination['stats'];

            $this->data['pagination_links'] = $pagination['links'];
        } else {
            $this->data['pagination_stats'] = '';

            $this->data['pagination_links'] = '';
        }

        $this->data['filter_name'] = $filter_name;

        $this->data['filter_comment'] = $filter_comment;

        $this->data['filter_rating'] = $filter_rating;

        $this->data['filter_page'] = $filter_page;

        $this->data['filter_approved'] = $filter_approved;

        $this->data['filter_sent'] = $filter_sent;

        $this->data['filter_flagged'] = $filter_flagged;

        $this->data['filter_ip_address'] = $filter_ip_address;

        $this->data['filter_date'] = $filter_date;

        $this->data['sort'] = $sort;

        $this->data['order'] = $order;

        $this->data['button_view'] = $this->loadImage('button/view.png');

        $this->data['button_approve'] = $this->loadImage('button/approve.png');

        $this->data['button_send'] = $this->loadImage('button/send.png');

        $this->data['button_edit'] = $this->loadImage('button/edit.png');

        $this->data['button_delete'] = $this->loadImage('button/delete.png');

        $this->data['button_spam'] = $this->loadImage('button/spam.png');

        if ($this->setting->get('notice_manage_comments')) {
            $this->data['info'] = $this->data['lang_notice'];
        }

        $this->data['enabled_rating'] = $this->setting->get('enabled_rating');

        $this->data['show_rating'] = $this->setting->get('show_rating');

        $this->data['approve_notifications'] = $this->setting->get('approve_notifications');

        $this->data['show_flag'] = $this->setting->get('show_flag');

        $this->components = array('common/header', 'common/footer');

        $this->loadView('manage/comments');
    }

    public function dismiss()
    {
        $this->loadModel('manage/comments');

        $this->model_manage_comments->dismiss();
    }

    private function checkParameters()
    {
        if (isset($this->request->get['page']) && (!$this->validation->isInt($this->request->get['page']) || $this->request->get['page'] < 1)) {
            return false;
        }

        if (isset($this->request->get['sort']) && !in_array($this->request->get['sort'], array('u.name', 'c.comment', 'c.rating', 'p.reference', 'c.is_approved', 'c.is_sent', 'c.reports', 'c.flagged', 'c.ip_address', 'c.date_added'))) {
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

            if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_comment']) || isset($this->request->get['filter_page']) || isset($this->request->get['filter_ip_address'])) {
                $this->loadModel('manage/comments');

                if (isset($this->request->get['filter_name'])) {
                    $filter_name = $this->request->get['filter_name'];

                    $group_by = 'u.name';

                    $sort = 'u.name';
                } else {
                    $filter_name = '';
                }

                if (isset($this->request->get['filter_comment'])) {
                    $filter_comment = $this->request->get['filter_comment'];

                    $group_by = 'c.comment';

                    $sort = 'c.comment';
                } else {
                    $filter_comment = '';
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

                    $group_by = 'c.ip_address';

                    $sort = 'c.ip_address';
                } else {
                    $filter_ip_address = '';
                }

                $data = array(
                    'filter_id'         => '',
                    'filter_user_id'    => '',
                    'filter_page_id'    => '',
                    'filter_name'       => $filter_name,
                    'filter_comment'    => $filter_comment,
                    'filter_rating'     => '',
                    'filter_page'       => $filter_page,
                    'filter_approved'   => '',
                    'filter_sent'       => '',
                    'filter_flagged'    => '',
                    'filter_ip_address' => $filter_ip_address,
                    'filter_date'       => '',
                    'group_by'          => $group_by,
                    'sort'              => $sort,
                    'order'             => 'asc',
                    'start'             => 0,
                    'limit'             => 10
                );

                $comments = $this->model_manage_comments->getComments($data);

                foreach ($comments as $comment) {
                    $json[] = array(
                        'name'       => strip_tags($this->security->decode($comment['name'])),
                        'comment'    => $this->variable->substr(strip_tags($this->security->decode($comment['comment'])), 0, 50),
                        'page'       => strip_tags($this->security->decode($comment['page_reference'])),
                        'ip_address' => strip_tags($this->security->decode($comment['ip_address']))
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
