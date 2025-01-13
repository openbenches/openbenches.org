<?php
namespace Commentics;

class ToolDatabaseBackupController extends Controller
{
    public function index()
    {
        $this->loadLanguage('tool/database_backup');

        $this->loadModel('tool/database_backup');

        $this->loadModel('common/pagination');

        if (!$this->checkParameters()) {
            $this->response->redirect('main/dashboard');
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                if (isset($this->request->post['create'])) {
                    $this->model_tool_database_backup->create($this->request->post['description']);

                    $this->data['success'] = $this->data['lang_message_create'];
                } else if (isset($this->request->post['single_delete'])) {
                    $result = $this->model_tool_database_backup->singleDelete($this->request->post['single_delete']);

                    if ($result) {
                        $this->data['success'] = $this->data['lang_message_single_delete_success'];
                    } else {
                        $this->data['error'] = $this->data['lang_message_single_delete_invalid'];
                    }
                } else if (isset($this->request->post['bulk'])) {
                    $result = $this->model_tool_database_backup->bulkDelete($this->request->post['bulk']);

                    if ($result['success']) {
                        $this->data['success'] = sprintf($this->data['lang_message_bulk_delete_success'], $result['success']);
                    }

                    if ($result['failure']) {
                        $this->data['error'] = sprintf($this->data['lang_message_bulk_delete_invalid'], $result['failure']);
                    }
                }
            }
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

        if (isset($this->request->get['filter_description'])) {
            $filter_description = $this->request->get['filter_description'];
        } else {
            $filter_description = '';
        }

        if (isset($this->request->get['filter_filename'])) {
            $filter_filename = $this->request->get['filter_filename'];
        } else {
            $filter_filename = '';
        }

        if (isset($this->request->get['filter_date'])) {
            $filter_date = $this->request->get['filter_date'];
        } else {
            $filter_date = '';
        }

        $page_cookie = $this->model_tool_database_backup->getPageCookie();

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
            $this->model_tool_database_backup->setPageCookie($this->request->get['sort'], $this->request->get['order']);
        }

        $this->model_tool_database_backup->deleteOrphans();

        $data = array(
            'filter_id'          => $filter_id,
            'filter_description' => $filter_description,
            'filter_filename'    => $filter_filename,
            'filter_date'        => $filter_date,
            'group_by'           => '',
            'sort'               => $sort,
            'order'              => $order,
            'start'              => ($page - 1) * $this->setting->get('limit_results'),
            'limit'              => $this->setting->get('limit_results')
        );

        $this->data['backups'] = array();

        $backups = $this->model_tool_database_backup->getBackups($data);

        $total = $this->model_tool_database_backup->getBackups($data, true);

        foreach ($backups as $backup) {
            $this->data['backups'][] = array(
                'id'          => substr($backup['filename'], 0, 50),
                'description' => $backup['description'],
                'url'         => CMTX_HTTP_BACKUPS . $backup['filename'],
                'filename'    => $backup['filename'],
                'size'        => $this->model_tool_database_backup->formatSize($backup['size']),
                'dated'       => $this->variable->formatDate($backup['date_added'], $this->data['lang_date_time_format'], $this->data)
            );
        }

        $sort_url = $this->model_tool_database_backup->sortUrl();

        $this->data['sort_description'] = $this->url->link('tool/database_backup', '&sort=b.description' . $sort_url);

        $this->data['sort_filename'] = $this->url->link('tool/database_backup', '&sort=b.filename' . $sort_url);

        $this->data['sort_date'] = $this->url->link('tool/database_backup', '&sort=b.date_added' . $sort_url);

        if ($backups) {
            $pagination_url = $this->model_tool_database_backup->paginateUrl();

            $url = $this->url->link('tool/database_backup', $pagination_url . '&page=[page]');

            $pagination = $this->model_common_pagination->paginate($page, $total, $url, $this->data);

            $this->data['pagination_stats'] = $pagination['stats'];

            $this->data['pagination_links'] = $pagination['links'];
        } else {
            $this->data['pagination_stats'] = '';

            $this->data['pagination_links'] = '';
        }

        $this->data['filter_description'] = $filter_description;

        $this->data['filter_filename'] = $filter_filename;

        $this->data['filter_date'] = $filter_date;

        $this->data['sort'] = $sort;

        $this->data['order'] = $order;

        $this->data['button_delete'] = $this->loadImage('button/delete.png');

        $this->components = array('common/header', 'common/footer');

        $this->loadView('tool/database_backup');
    }

    private function checkParameters()
    {
        if (isset($this->request->get['page']) && (!$this->validation->isInt($this->request->get['page']) || $this->request->get['page'] < 1)) {
            return false;
        }

        if (isset($this->request->get['sort']) && !in_array($this->request->get['sort'], array('b.description', 'b.filename', 'b.date_added'))) {
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

            if (isset($this->request->get['filter_description']) || isset($this->request->get['filter_filename'])) {
                $this->loadModel('tool/database_backup');

                if (isset($this->request->get['filter_description'])) {
                    $filter_description = $this->request->get['filter_description'];

                    $group_by = 'b.description';

                    $sort = 'b.description';
                } else {
                    $filter_description = '';
                }

                if (isset($this->request->get['filter_filename'])) {
                    $filter_filename = $this->request->get['filter_filename'];

                    $group_by = 'b.filename';

                    $sort = 'b.filename';
                } else {
                    $filter_filename = '';
                }

                $data = array(
                    'filter_id'          => '',
                    'filter_description' => $filter_description,
                    'filter_filename'    => $filter_filename,
                    'filter_date'        => '',
                    'group_by'           => $group_by,
                    'sort'               => $sort,
                    'order'              => 'asc',
                    'start'              => 0,
                    'limit'              => 10
                );

                $backups = $this->model_tool_database_backup->getBackups($data);

                foreach ($backups as $backup) {
                    $json[] = array(
                        'description' => strip_tags($this->security->decode($backup['description'])),
                        'filename'    => strip_tags($this->security->decode($backup['filename']))
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

        if (!is_writable(CMTX_DIR_BACKUPS)) {
            $this->data['error'] = $this->data['lang_message_permission'];

            return false;
        }

        if (isset($this->request->post['create'])) {
            if (!isset($this->request->post['description']) || $this->validation->length($this->request->post['description']) < 1 || $this->validation->length($this->request->post['description']) > 100) {
                $this->data['error'] = sprintf($this->data['lang_error_description'], 1, 100);

                return false;
            }
        }

        return true;
    }
}
