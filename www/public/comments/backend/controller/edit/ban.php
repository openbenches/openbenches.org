<?php
namespace Commentics;

class EditBanController extends Controller
{
    public function index()
    {
        $this->loadLanguage('edit/ban');

        $this->loadModel('edit/ban');

        if (!isset($this->request->get['id']) || !$this->model_edit_ban->banExists($this->request->get['id'])) {
            $this->response->redirect('main/dashboard');
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_edit_ban->update($this->request->post, $this->request->get['id']);

                $this->session->data['cmtx_success'] = $this->data['lang_message_success'];

                $this->response->redirect('manage/bans');
            }
        }

        $ban = $this->model_edit_ban->getBan($this->request->get['id']);

        if (isset($this->request->post['ip_address'])) {
            $this->data['ip_address'] = $this->request->post['ip_address'];
        } else {
            $this->data['ip_address'] = $ban['ip_address'];
        }

        if (isset($this->request->post['reason'])) {
            $this->data['reason'] = $this->request->post['reason'];
        } else {
            $this->data['reason'] = $ban['reason'];
        }

        $this->data['date_added'] = $this->variable->formatDate($ban['date_added'], $this->data['lang_date_time_format'], $this->data);

        if (isset($this->error['ip_address'])) {
            $this->data['error_ip_address'] = $this->error['ip_address'];
        } else {
            $this->data['error_ip_address'] = '';
        }

        if (isset($this->error['reason'])) {
            $this->data['error_reason'] = $this->error['reason'];
        } else {
            $this->data['error_reason'] = '';
        }

        $this->data['id'] = $this->request->get['id'];

        $this->data['link_back'] = $this->url->link('manage/bans');

        $this->components = array('common/header', 'common/footer');

        $this->loadView('edit/ban');
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        if (!isset($this->request->post['ip_address']) || $this->validation->length($this->request->post['ip_address']) < 1 || $this->validation->length($this->request->post['ip_address']) > 250) {
            $this->error['ip_address'] = sprintf($this->data['lang_error_length'], 1, 250);
        }

        if (!isset($this->request->post['reason']) || $this->validation->length($this->request->post['reason']) < 1 || $this->validation->length($this->request->post['reason']) > 250) {
            $this->error['reason'] = sprintf($this->data['lang_error_length'], 1, 250);
        }

        $this->loadModel('edit/ban');

        if (isset($this->request->post['ip_address']) && $this->model_edit_ban->isAlreadyBanned($this->request->post['ip_address'], $this->request->get['id'])) {
            $this->error['ip_address'] = $this->data['lang_error_already_banned'];
        }

        if ($this->error) {
            $this->data['error'] = $this->data['lang_message_error'];

            return false;
        } else {
            return true;
        }
    }
}
