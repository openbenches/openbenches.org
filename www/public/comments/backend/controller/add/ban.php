<?php
namespace Commentics;

class AddBanController extends Controller
{
    public function index()
    {
        $this->loadLanguage('add/ban');

        $this->loadModel('add/ban');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_add_ban->add($this->request->post);

                $this->session->data['cmtx_success'] = $this->data['lang_message_success'];

                $this->response->redirect('manage/bans');
            }
        }

        if (isset($this->request->post['ip_address'])) {
            $this->data['ip_address'] = $this->request->post['ip_address'];
        } else {
            $this->data['ip_address'] = '';
        }

        if (isset($this->request->post['reason'])) {
            $this->data['reason'] = $this->request->post['reason'];
        } else {
            $this->data['reason'] = '';
        }

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

        $this->data['link_back'] = $this->url->link('manage/bans');

        $this->components = array('common/header', 'common/footer');

        $this->loadView('add/ban');
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

        $this->loadModel('add/ban');

        if (isset($this->request->post['ip_address']) && $this->model_add_ban->isAlreadyBanned($this->request->post['ip_address'])) {
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
