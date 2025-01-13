<?php
namespace Commentics;

class ManagePagesController extends Controller
{
    public function index()
    {
        $this->loadLanguage('manage/pages');

        $this->loadModel('manage/pages');

        $this->loadModel('common/pagination');

        if (!$this->checkParameters()) {
            $this->response->redirect('main/dashboard');
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                if (isset($this->request->post['single_delete'])) {
                    $result = $this->model_manage_pages->singleDelete($this->request->post['single_delete']);

                    if ($result) {
                        $this->data['success'] = $this->data['lang_message_single_delete_success'];
                    } else {
                        $this->data['error'] = $this->data['lang_message_single_delete_invalid'];
                    }
                } else if (isset($this->request->post['bulk'])) {
                    $result = $this->model_manage_pages->bulkDelete($this->request->post['bulk']);

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

        if (isset($this->request->get['filter_identifier'])) {
            $filter_identifier = $this->request->get['filter_identifier'];
        } else {
            $filter_identifier = '';
        }

        if (isset($this->request->get['filter_reference'])) {
            $filter_reference = $this->request->get['filter_reference'];
        } else {
            $filter_reference = '';
        }

        if (isset($this->request->get['filter_url'])) {
            $filter_url = $this->request->get['filter_url'];
        } else {
            $filter_url = '';
        }

        if (isset($this->request->get['filter_moderate'])) {
            $filter_moderate = $this->request->get['filter_moderate'];
        } else {
            $filter_moderate = '';
        }

        if (isset($this->request->get['filter_is_form_enabled'])) {
            $filter_is_form_enabled = $this->request->get['filter_is_form_enabled'];
        } else {
            $filter_is_form_enabled = '';
        }

        if (isset($this->request->get['filter_date'])) {
            $filter_date = $this->request->get['filter_date'];
        } else {
            $filter_date = '';
        }

        $page_cookie = $this->model_manage_pages->getPageCookie();

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else if ($page_cookie['sort']) {
            $sort = $page_cookie['sort'];
        } else {
            $sort = 'p.date_added';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else if ($page_cookie['order']) {
            $order = $page_cookie['order'];
        } else {
            $order = 'desc';
        }

        if (isset($this->request->get['sort']) && isset($this->request->get['order'])) {
            $this->model_manage_pages->setPageCookie($this->request->get['sort'], $this->request->get['order']);
        }

        $data = array(
            'filter_id'              => $filter_id,
            'filter_identifier'      => $filter_identifier,
            'filter_reference'       => $filter_reference,
            'filter_url'             => $filter_url,
            'filter_moderate'        => $filter_moderate,
            'filter_is_form_enabled' => $filter_is_form_enabled,
            'filter_date'            => $filter_date,
            'group_by'               => '',
            'sort'                   => $sort,
            'order'                  => $order,
            'start'                  => ($page - 1) * $this->setting->get('limit_results'),
            'limit'                  => $this->setting->get('limit_results')
        );

        $pages = $this->model_manage_pages->getPages($data);

        $total = $this->model_manage_pages->getPages($data, true);

        $this->data['pages'] = array();

        foreach ($pages as $page) {
            if ($page['moderate'] == 'default') {
                $moderate = $this->data['lang_text_default'];
            } else if ($page['moderate'] == 'never') {
                $moderate = $this->data['lang_text_never'];
            } else {
                $moderate = $this->data['lang_text_always'];
            }

            $this->data['pages'][] = array(
                'id'                => $page['id'],
                'identifier'        => $page['identifier'],
                'reference'         => $page['reference'],
                'url'               => $page['url'],
                'comments'          => $page['comments'],
                'comments_url'      => $this->url->link('manage/comments', '&filter_page_id=' . $page['id']),
                'subscriptions'     => $page['subscriptions'],
                'subscriptions_url' => $this->url->link('manage/subscriptions', '&filter_page_id=' . $page['id']),
                'moderate'          => $moderate,
                'is_form_enabled'   => ($page['is_form_enabled']) ? $this->data['lang_text_yes'] : $this->data['lang_text_no'],
                'date_added'        => $this->variable->formatDate($page['date_added'], $this->data['lang_date_time_format'], $this->data),
                'action_view'       => $page['url'],
                'action_edit'       => $this->url->link('edit/page', '&id=' . $page['id'])
            );
        }

        $sort_url = $this->model_manage_pages->sortUrl();

        $this->data['sort_identifier'] = $this->url->link('manage/pages', '&sort=p.identifier' . $sort_url);

        $this->data['sort_reference'] = $this->url->link('manage/pages', '&sort=p.reference' . $sort_url);

        $this->data['sort_url'] = $this->url->link('manage/pages', '&sort=p.url' . $sort_url);

        $this->data['sort_comments'] = $this->url->link('manage/pages', '&sort=comments' . $sort_url);

        $this->data['sort_subscriptions'] = $this->url->link('manage/pages', '&sort=subscriptions' . $sort_url);

        $this->data['sort_moderate'] = $this->url->link('manage/pages', '&sort=p.moderate' . $sort_url);

        $this->data['sort_is_form_enabled'] = $this->url->link('manage/pages', '&sort=p.is_form_enabled' . $sort_url);

        $this->data['sort_date'] = $this->url->link('manage/pages', '&sort=p.date_added' . $sort_url);

        if ($pages) {
            $pagination_url = $this->model_manage_pages->paginateUrl();

            $url = $this->url->link('manage/pages', $pagination_url . '&page=[page]');

            (isset($this->request->get['page'])) ? $page = $this->request->get['page'] : $page = 1;

            $pagination = $this->model_common_pagination->paginate($page, $total, $url, $this->data);

            $this->data['pagination_stats'] = $pagination['stats'];

            $this->data['pagination_links'] = $pagination['links'];
        } else {
            $this->data['pagination_stats'] = '';

            $this->data['pagination_links'] = '';
        }

        $this->data['filter_identifier'] = $filter_identifier;

        $this->data['filter_reference'] = $filter_reference;

        $this->data['filter_url'] = $filter_url;

        $this->data['filter_moderate'] = $filter_moderate;

        $this->data['filter_is_form_enabled'] = $filter_is_form_enabled;

        $this->data['filter_date'] = $filter_date;

        $this->data['sort'] = $sort;

        $this->data['order'] = $order;

        $this->data['button_view'] = $this->loadImage('button/view.png');

        $this->data['button_edit'] = $this->loadImage('button/edit.png');

        $this->data['button_delete'] = $this->loadImage('button/delete.png');

        if ($this->setting->get('notice_manage_pages')) {
            $this->data['info'] = sprintf($this->data['lang_notice'], 'https://commentics.com/integration');
        }

        if ($this->setting->get('warning_manage_pages') && !$this->setting->get('empty_pages')) {
            $this->data['warning'] = sprintf($this->data['lang_warning'], $this->url->link('settings/system'));
        }

        $this->components = array('common/header', 'common/footer');

        $this->loadView('manage/pages');
    }

    public function dismiss()
    {
        $this->loadModel('manage/pages');

        $this->model_manage_pages->dismiss();
    }

    public function discard()
    {
        $this->loadModel('manage/pages');

        $this->model_manage_pages->discard();
    }

    private function checkParameters()
    {
        if (isset($this->request->get['page']) && (!$this->validation->isInt($this->request->get['page']) || $this->request->get['page'] < 1)) {
            return false;
        }

        if (isset($this->request->get['sort']) && !in_array($this->request->get['sort'], array('p.identifier', 'p.reference', 'p.url', 'comments', 'subscriptions', 'p.moderate', 'p.is_form_enabled', 'p.date_added'))) {
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

            if (isset($this->request->get['filter_identifier']) || isset($this->request->get['filter_reference']) || isset($this->request->get['filter_url'])) {
                $this->loadModel('manage/pages');

                if (isset($this->request->get['filter_identifier'])) {
                    $filter_identifier = $this->request->get['filter_identifier'];

                    $group_by = 'p.identifier';

                    $sort = 'p.identifier';
                } else {
                    $filter_identifier = '';
                }

                if (isset($this->request->get['filter_reference'])) {
                    $filter_reference = $this->request->get['filter_reference'];

                    $group_by = 'p.reference';

                    $sort = 'p.reference';
                } else {
                    $filter_reference = '';
                }

                if (isset($this->request->get['filter_url'])) {
                    $filter_url = $this->request->get['filter_url'];

                    $group_by = 'p.url';

                    $sort = 'p.url';
                } else {
                    $filter_url = '';
                }

                $data = array(
                    'filter_id'              => '',
                    'filter_identifier'      => $filter_identifier,
                    'filter_reference'       => $filter_reference,
                    'filter_url'             => $filter_url,
                    'filter_moderate'        => '',
                    'filter_is_form_enabled' => '',
                    'filter_date'            => '',
                    'group_by'               => $group_by,
                    'sort'                   => $sort,
                    'order'                  => 'asc',
                    'start'                  => 0,
                    'limit'                  => 10
                );

                $pages = $this->model_manage_pages->getPages($data);

                foreach ($pages as $page) {
                    $json[] = array(
                        'identifier' => strip_tags($this->security->decode($page['identifier'])),
                        'reference'  => strip_tags($this->security->decode($page['reference'])),
                        'url'        => strip_tags($this->security->decode($page['url']))
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
