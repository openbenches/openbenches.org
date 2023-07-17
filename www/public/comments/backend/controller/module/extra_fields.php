<?php
namespace Commentics;

class ModuleExtraFieldsController extends Controller
{
    public function index()
    {
        $this->loadLanguage('module/extra_fields');

        $this->loadModel('module/extra_fields');

        $this->loadModel('common/pagination');

        if (!$this->setting->has('extra_fields_enabled')) {
            $this->response->redirect('extension/modules');
        }

        if (!$this->checkParameters()) {
            $this->response->redirect('main/dashboard');
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                if (isset($this->request->post['single_delete'])) {
                    $result = $this->model_module_extra_fields->singleDelete($this->request->post['single_delete']);

                    if ($result) {
                        $this->data['success'] = $this->data['lang_message_single_delete_success'];
                    } else {
                        $this->data['error'] = $this->data['lang_message_single_delete_invalid'];
                    }
                } else if (isset($this->request->post['bulk'])) {
                    $result = $this->model_module_extra_fields->bulkDelete($this->request->post['bulk']);

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

        $page_cookie = $this->model_module_extra_fields->getPageCookie();

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else if ($page_cookie['sort']) {
            $sort = $page_cookie['sort'];
        } else {
            $sort = 'f.name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else if ($page_cookie['order']) {
            $order = $page_cookie['order'];
        } else {
            $order = 'asc';
        }

        if (isset($this->request->get['sort']) && isset($this->request->get['order'])) {
            $this->model_module_extra_fields->setPageCookie($this->request->get['sort'], $this->request->get['order']);
        }

        $data = array(
            'group_by' => '',
            'sort'     => $sort,
            'order'    => $order,
            'start'    => ($page - 1) * $this->setting->get('limit_results'),
            'limit'    => $this->setting->get('limit_results')
        );

        $fields = $this->model_module_extra_fields->getFields($data);

        $total = $this->model_module_extra_fields->getFields($data, true);

        $this->data['fields'] = array();

        foreach ($fields as $field) {
            if ($field['type'] == 'select') {
                $type = $this->data['lang_text_select'];
            } else if ($field['type'] == 'text') {
                $type = $this->data['lang_text_text'];
            } else {
                $type = $this->data['lang_text_textarea'];
            }

            $this->data['fields'][] = array(
                'id'       => $field['id'],
                'name'     => $field['name'],
                'type'     => $type,
                'required' => ($field['is_required']) ? $this->data['lang_text_yes'] : $this->data['lang_text_no'],
                'enabled'  => ($field['is_enabled']) ? $this->data['lang_text_yes'] : $this->data['lang_text_no'],
                'action'   => $this->url->link('module/extra_fields/edit', '&id=' . $field['id'])
            );
        }

        $sort_url = $this->model_module_extra_fields->sortUrl();

        $this->data['sort_name'] = $this->url->link('module/extra_fields', '&sort=f.name' . $sort_url);

        $this->data['sort_type'] = $this->url->link('module/extra_fields', '&sort=f.type' . $sort_url);

        $this->data['sort_required'] = $this->url->link('module/extra_fields', '&sort=f.is_required' . $sort_url);

        $this->data['sort_enabled'] = $this->url->link('module/extra_fields', '&sort=f.is_enabled' . $sort_url);

        if ($fields) {
            $pagination_url = $this->model_module_extra_fields->paginateUrl();

            $url = $this->url->link('module/extra_fields', $pagination_url . '&page=[page]');

            $pagination = $this->model_common_pagination->paginate($page, $total, $url, $this->data);

            $this->data['pagination_stats'] = $pagination['stats'];

            $this->data['pagination_links'] = $pagination['links'];
        } else {
            $this->data['pagination_stats'] = '';

            $this->data['pagination_links'] = '';
        }

        $this->data['sort'] = $sort;

        $this->data['order'] = $order;

        $this->data['button_edit'] = $this->loadImage('button/edit.png');

        $this->data['button_delete'] = $this->loadImage('button/delete.png');

        $this->data['lang_description'] = sprintf($this->data['lang_description'], $this->url->link('module/extra_fields/add'));

        $this->data['page'] = 'list';

        if ($this->setting->get('notice_extra_fields')) {
            $this->data['info'] = sprintf($this->data['lang_notice'], $this->url->link('settings/layout_form'));
        }

        $this->data['link_back'] = $this->url->link('extension/modules');

        $this->components = array('common/header', 'common/footer');

        $this->loadView('module/extra_fields');
    }

    public function add()
    {
        $this->loadLanguage('module/extra_fields');

        $this->loadModel('module/extra_fields');

        if (!$this->setting->has('extra_fields_enabled')) {
            $this->response->redirect('extension/modules');
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validateForm()) {
                $this->model_module_extra_fields->add($this->request->post);

                $this->session->data['cmtx_success'] = $this->data['lang_message_add'];

                $this->response->redirect('module/extra_fields');
            }
        }

        if (isset($this->request->post['name'])) {
            $this->data['name'] = $this->request->post['name'];
        } else {
            $this->data['name'] = '';
        }

        if (isset($this->request->post['type'])) {
            $this->data['type'] = $this->request->post['type'];
        } else {
            $this->data['type'] = 'select';
        }

        if (isset($this->request->post['is_required'])) {
            $this->data['is_required'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['is_required'])) {
            $this->data['is_required'] = false;
        } else {
            $this->data['is_required'] = false;
        }

        if (isset($this->request->post['values'])) {
            $this->data['values'] = $this->request->post['values'];
        } else {
            $this->data['values'] = '';
        }

        if (isset($this->request->post['default'])) {
            $this->data['default'] = $this->request->post['default'];
        } else {
            $this->data['default'] = '';
        }

        if (isset($this->request->post['minimum'])) {
            $this->data['minimum'] = $this->request->post['minimum'];
        } else {
            $this->data['minimum'] = '0';
        }

        if (isset($this->request->post['maximum'])) {
            $this->data['maximum'] = $this->request->post['maximum'];
        } else {
            $this->data['maximum'] = '9999';
        }

        if (isset($this->request->post['validation'])) {
            $this->data['validation'] = $this->request->post['validation'];
        } else {
            $this->data['validation'] = '';
        }

        if (isset($this->request->post['display'])) {
            $this->data['display'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['display'])) {
            $this->data['display'] = false;
        } else {
            $this->data['display'] = false;
        }

        if (isset($this->request->post['sort'])) {
            $this->data['sort'] = $this->request->post['sort'];
        } else {
            $this->data['sort'] = '0';
        }

        if (isset($this->request->post['is_enabled'])) {
            $this->data['is_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['is_enabled'])) {
            $this->data['is_enabled'] = false;
        } else {
            $this->data['is_enabled'] = true;
        }

        if (isset($this->error['name'])) {
            $this->data['error_name'] = $this->error['name'];
        } else {
            $this->data['error_name'] = '';
        }

        if (isset($this->error['type'])) {
            $this->data['error_type'] = $this->error['type'];
        } else {
            $this->data['error_type'] = '';
        }

        if (isset($this->error['values'])) {
            $this->data['error_values'] = $this->error['values'];
        } else {
            $this->data['error_values'] = '';
        }

        if (isset($this->error['default'])) {
            $this->data['error_default'] = $this->error['default'];
        } else {
            $this->data['error_default'] = '';
        }

        if (isset($this->error['minimum'])) {
            $this->data['error_minimum'] = $this->error['minimum'];
        } else {
            $this->data['error_minimum'] = '';
        }

        if (isset($this->error['maximum'])) {
            $this->data['error_maximum'] = $this->error['maximum'];
        } else {
            $this->data['error_maximum'] = '';
        }

        if (isset($this->error['validation'])) {
            $this->data['error_validation'] = $this->error['validation'];
        } else {
            $this->data['error_validation'] = '';
        }

        if (isset($this->error['sort'])) {
            $this->data['error_sort'] = $this->error['sort'];
        } else {
            $this->data['error_sort'] = '';
        }

        $this->data['link_back'] = $this->url->link('module/extra_fields');

        $this->data['page'] = 'add';

        $this->data['lang_heading'] = $this->data['lang_heading_add'];

        $this->data['lang_description'] = $this->data['lang_description_add'];

        $this->data['action'] = $this->url->link('module/extra_fields/add');

        $this->components = array('common/header', 'common/footer');

        $this->loadView('module/extra_fields');
    }

    public function edit()
    {
        $this->loadLanguage('module/extra_fields');

        $this->loadModel('module/extra_fields');

        if (!$this->setting->has('extra_fields_enabled')) {
            $this->response->redirect('extension/modules');
        }

        if (!isset($this->request->get['id']) || !$this->model_module_extra_fields->fieldExists($this->request->get['id'])) {
            $this->response->redirect('main/dashboard');
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validateForm()) {
                $this->model_module_extra_fields->update($this->request->post, $this->request->get['id']);

                $this->session->data['cmtx_success'] = $this->data['lang_message_edit'];

                $this->response->redirect('module/extra_fields');
            }
        }

        $field = $this->model_module_extra_fields->getField($this->request->get['id']);

        if (isset($this->request->post['name'])) {
            $this->data['name'] = $this->request->post['name'];
        } else {
            $this->data['name'] = $field['name'];
        }

        if (isset($this->request->post['type'])) {
            $this->data['type'] = $this->request->post['type'];
        } else {
            $this->data['type'] = $field['type'];
        }

        if (isset($this->request->post['is_required'])) {
            $this->data['is_required'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['is_required'])) {
            $this->data['is_required'] = false;
        } else {
            $this->data['is_required'] = $field['is_required'];
        }

        if (isset($this->request->post['values'])) {
            $this->data['values'] = $this->request->post['values'];
        } else {
            $this->data['values'] = $field['values'];
        }

        if (isset($this->request->post['default'])) {
            $this->data['default'] = $this->request->post['default'];
        } else {
            $this->data['default'] = $field['default'];
        }

        if (isset($this->request->post['minimum'])) {
            $this->data['minimum'] = $this->request->post['minimum'];
        } else {
            $this->data['minimum'] = $field['minimum'];
        }

        if (isset($this->request->post['maximum'])) {
            $this->data['maximum'] = $this->request->post['maximum'];
        } else {
            $this->data['maximum'] = $field['maximum'];
        }

        if (isset($this->request->post['validation'])) {
            $this->data['validation'] = $this->request->post['validation'];
        } else {
            $this->data['validation'] = $field['validation'];
        }

        if (isset($this->request->post['display'])) {
            $this->data['display'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['display'])) {
            $this->data['display'] = false;
        } else {
            $this->data['display'] = $field['display'];
        }

        if (isset($this->request->post['sort'])) {
            $this->data['sort'] = $this->request->post['sort'];
        } else {
            $this->data['sort'] = $field['sort'];
        }

        if (isset($this->request->post['is_enabled'])) {
            $this->data['is_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['is_enabled'])) {
            $this->data['is_enabled'] = false;
        } else {
            $this->data['is_enabled'] = $field['is_enabled'];
        }

        if (isset($this->error['name'])) {
            $this->data['error_name'] = $this->error['name'];
        } else {
            $this->data['error_name'] = '';
        }

        if (isset($this->error['type'])) {
            $this->data['error_type'] = $this->error['type'];
        } else {
            $this->data['error_type'] = '';
        }

        if (isset($this->error['values'])) {
            $this->data['error_values'] = $this->error['values'];
        } else {
            $this->data['error_values'] = '';
        }

        if (isset($this->error['default'])) {
            $this->data['error_default'] = $this->error['default'];
        } else {
            $this->data['error_default'] = '';
        }

        if (isset($this->error['minimum'])) {
            $this->data['error_minimum'] = $this->error['minimum'];
        } else {
            $this->data['error_minimum'] = '';
        }

        if (isset($this->error['maximum'])) {
            $this->data['error_maximum'] = $this->error['maximum'];
        } else {
            $this->data['error_maximum'] = '';
        }

        if (isset($this->error['validation'])) {
            $this->data['error_validation'] = $this->error['validation'];
        } else {
            $this->data['error_validation'] = '';
        }

        if (isset($this->error['sort'])) {
            $this->data['error_sort'] = $this->error['sort'];
        } else {
            $this->data['error_sort'] = '';
        }

        $this->data['id'] = $this->request->get['id'];

        $this->data['link_back'] = $this->url->link('module/extra_fields');

        $this->data['page'] = 'edit';

        $this->data['lang_heading'] = $this->data['lang_heading_edit'];

        $this->data['lang_description'] = $this->data['lang_description_edit'];

        $this->data['action'] = $this->url->link('module/extra_fields/edit', '&id=' . $field['id']);

        $this->components = array('common/header', 'common/footer');

        $this->loadView('module/extra_fields');
    }

    public function install()
    {
        $this->loadModel('module/extra_fields');

        $this->model_module_extra_fields->install();
    }

    public function uninstall()
    {
        $this->loadModel('module/extra_fields');

        $this->model_module_extra_fields->uninstall();
    }

    public function dismiss()
    {
        $this->loadModel('module/extra_fields');

        $this->model_module_extra_fields->dismiss();
    }

    private function checkParameters()
    {
        if (isset($this->request->get['page']) && (!$this->validation->isInt($this->request->get['page']) || $this->request->get['page'] < 1)) {
            return false;
        }

        if (isset($this->request->get['sort']) && !in_array($this->request->get['sort'], array('f.name', 'f.type', 'f.is_required', 'f.is_enabled'))) {
            return false;
        }

        if (isset($this->request->get['order']) && !in_array($this->request->get['order'], array('asc', 'desc'))) {
            return false;
        }

        return true;
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

    private function validateForm()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        if (!isset($this->request->post['name']) || $this->validation->length($this->request->post['name']) < 1 || $this->validation->length($this->request->post['name']) > 250) {
            $this->error['name'] = sprintf($this->data['lang_error_length'], 1, 250);
        }

        if (!isset($this->request->post['type']) || !in_array($this->request->post['type'], array('select', 'text', 'textarea'))) {
            $this->error['type'] = $this->data['lang_error_selection'];
        }

        if (!empty($this->request->post['type']) && $this->request->post['type'] == 'select') {
            if (!isset($this->request->post['values']) || $this->validation->length($this->request->post['values']) < 1 || $this->validation->length($this->request->post['values']) > 9999) {
                $this->error['values'] = sprintf($this->data['lang_error_length'], 1, 9999);
            }
        }

        if (!empty($this->request->post['type']) && in_array($this->request->post['type'], array('text', 'textarea'))) {
            if (!isset($this->request->post['default']) || $this->validation->length($this->request->post['default']) > 250) {
                $this->error['default'] = sprintf($this->data['lang_error_length'], 0, 250);
            }

            if (!isset($this->request->post['minimum']) || !$this->validation->isInt($this->request->post['minimum']) || $this->request->post['minimum'] < 0 || $this->request->post['minimum'] > 9999) {
                $this->error['minimum'] = sprintf($this->data['lang_error_range'], 0, 9999);
            }

            if (!isset($this->request->post['maximum']) || !$this->validation->isInt($this->request->post['maximum']) || $this->request->post['maximum'] < 1 || $this->request->post['maximum'] > 9999) {
                $this->error['maximum'] = sprintf($this->data['lang_error_range'], 1, 9999);
            }

            if (!isset($this->request->post['validation']) || $this->validation->length($this->request->post['validation']) > 250) {
                $this->error['validation'] = sprintf($this->data['lang_error_length'], 0, 250);
            }
        }

        if (!isset($this->request->post['sort']) || !$this->validation->isInt($this->request->post['sort']) || $this->request->post['sort'] < 0 || $this->request->post['sort'] > 9999) {
            $this->error['sort'] = sprintf($this->data['lang_error_range'], 0, 9999);
        }

        if ($this->error) {
            $this->data['error'] = $this->data['lang_message_error'];

            return false;
        } else {
            return true;
        }
    }
}
