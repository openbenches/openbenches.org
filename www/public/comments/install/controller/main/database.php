<?php
namespace Commentics;

class MainDatabaseController extends Controller
{
    public function index()
    {
        if (defined('CMTX_DB_HOSTNAME')) {
            $this->response->redirect('start');
        }

        $this->loadLanguage('main/database');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $this->db->connect($this->request->post['hostname'], $this->security->decode($this->request->post['username']), $this->security->decode($this->request->post['password']), $this->request->post['database'], $this->request->post['port'], $this->request->post['prefix'], $this->request->post['driver']);

            if ($this->db->isConnected()) {
                $this->loadModel('main/database');

                $this->model_main_database->writeConfig();

                $this->response->redirect('system');
            } else {
                $this->data['error'] = $this->db->getConnectError() . ($this->db->getConnectErrno() ? ' (' . $this->db->getConnectErrno() . ')' : '');
            }
        } else {
            $this->data['error'] = '';
        }

        if (isset($this->request->post['database'])) {
            $this->data['database'] = $this->request->post['database'];
        } else {
            $this->data['database'] = '';
        }

        if (isset($this->request->post['username'])) {
            $this->data['username'] = $this->request->post['username'];
        } else {
            $this->data['username'] = '';
        }

        if (isset($this->request->post['password'])) {
            $this->data['password'] = $this->request->post['password'];
        } else {
            $this->data['password'] = '';
        }

        if (isset($this->request->post['hostname'])) {
            $this->data['hostname'] = $this->request->post['hostname'];
        } else {
            $this->data['hostname'] = 'localhost';
        }

        if (isset($this->request->post['port'])) {
            $this->data['port'] = $this->request->post['port'];
        } else {
            $this->data['port'] = '';
        }

        if (isset($this->request->post['prefix'])) {
            $this->data['prefix'] = $this->request->post['prefix'];
        } else {
            $this->data['prefix'] = '';
        }

        if (isset($this->request->post['driver'])) {
            $this->data['driver'] = $this->request->post['driver'];
        } else {
            $this->data['driver'] = 'mysqli';
        }

        if (is_writable('../')) {
            $this->data['writable'] = true;
        } else {
            $this->data['writable'] = false;
        }

        $this->data['lang_error_permission'] = sprintf($this->data['lang_error_permission'], '"/' . basename(realpath('../')) . '/"');

        $this->data['page'] = '2';

        $this->components = array('common/header', 'common/footer');

        $this->loadView('main/database');
    }
}
