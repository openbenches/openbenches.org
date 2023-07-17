<?php
namespace Commentics;

class EditSiteController extends Controller
{
    public function index()
    {
        $this->loadLanguage('edit/site');

        $this->loadModel('edit/site');

        if (!isset($this->request->get['id']) || !$this->site->siteExists($this->request->get['id'])) {
            $this->response->redirect('main/dashboard');
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_edit_site->update($this->request->post, $this->request->get['id']);

                $this->session->data['cmtx_success'] = $this->data['lang_message_success'];

                $this->response->redirect('manage/sites');
            }
        }

        $site = $this->site->getSite($this->request->get['id']);

        if (isset($this->request->post['name'])) {
            $this->data['name'] = $this->request->post['name'];
        } else {
            $this->data['name'] = $site['name'];
        }

        if (isset($this->request->post['domain'])) {
            $this->data['domain'] = $this->request->post['domain'];
        } else {
            $this->data['domain'] = $site['domain'];
        }

        if (isset($this->request->post['url'])) {
            $this->data['url'] = $this->request->post['url'];
        } else {
            $this->data['url'] = $site['url'];
        }

        if (isset($this->request->post['iframe_enabled'])) {
            $this->data['iframe_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['iframe_enabled'])) {
            $this->data['iframe_enabled'] = false;
        } else {
            $this->data['iframe_enabled'] = $site['iframe_enabled'];
        }

        if (isset($this->request->post['new_pages'])) {
            $this->data['new_pages'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['new_pages'])) {
            $this->data['new_pages'] = false;
        } else {
            $this->data['new_pages'] = $site['new_pages'];
        }

        if (isset($this->request->post['from_name'])) {
            $this->data['from_name'] = $this->request->post['from_name'];
        } else {
            $this->data['from_name'] = $site['from_name'];
        }

        if (isset($this->request->post['from_email'])) {
            $this->data['from_email'] = $this->request->post['from_email'];
        } else {
            $this->data['from_email'] = $site['from_email'];
        }

        if (isset($this->request->post['reply_email'])) {
            $this->data['reply_email'] = $this->request->post['reply_email'];
        } else {
            $this->data['reply_email'] = $site['reply_email'];
        }

        $this->data['date_added'] = $this->variable->formatDate($site['date_added'], $this->data['lang_date_time_format'], $this->data);

        if (isset($this->error['name'])) {
            $this->data['error_name'] = $this->error['name'];
        } else {
            $this->data['error_name'] = '';
        }

        if (isset($this->error['domain'])) {
            $this->data['error_domain'] = $this->error['domain'];
        } else {
            $this->data['error_domain'] = '';
        }

        if (isset($this->error['url'])) {
            $this->data['error_url'] = $this->error['url'];
        } else {
            $this->data['error_url'] = '';
        }

        if (isset($this->error['from_name'])) {
            $this->data['error_from_name'] = $this->error['from_name'];
        } else {
            $this->data['error_from_name'] = '';
        }

        if (isset($this->error['from_email'])) {
            $this->data['error_from_email'] = $this->error['from_email'];
        } else {
            $this->data['error_from_email'] = '';
        }

        if (isset($this->error['reply_email'])) {
            $this->data['error_reply_email'] = $this->error['reply_email'];
        } else {
            $this->data['error_reply_email'] = '';
        }

        $this->data['id'] = $this->request->get['id'];

        $this->data['link_back'] = $this->url->link('manage/sites');

        $this->components = array('common/header', 'common/footer');

        $this->loadView('edit/site');
    }

    private function validate()
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

        if (!isset($this->request->post['domain']) || $this->validation->length($this->request->post['domain']) < 1 || $this->validation->length($this->request->post['domain']) > 250) {
            $this->error['domain'] = sprintf($this->data['lang_error_length'], 1, 250);
        }

        if (!isset($this->request->post['url']) || !$this->validation->isUrl($this->request->post['url'])) {
            $this->error['url'] = $this->data['lang_error_url'];
        }

        if (!isset($this->request->post['url']) || $this->validation->length($this->request->post['url']) < 1 || $this->validation->length($this->request->post['url']) > 250) {
            $this->error['url'] = sprintf($this->data['lang_error_length'], 1, 250);
        }

        if (!isset($this->request->post['from_name']) || $this->validation->length($this->request->post['from_name']) > 250) {
            $this->error['from_name'] = sprintf($this->data['lang_error_length'], 0, 250);
        }

        if (isset($this->request->post['from_email']) && $this->request->post['from_email'] && !$this->validation->isEmail($this->request->post['from_email'])) {
            $this->error['from_email'] = $this->data['lang_error_email_invalid'];
        }

        if (!isset($this->request->post['from_email']) || $this->validation->length($this->request->post['from_email']) > 250) {
            $this->error['from_email'] = sprintf($this->data['lang_error_length'], 0, 250);
        }

        if (isset($this->request->post['reply_email']) && $this->request->post['reply_email'] && !$this->validation->isEmail($this->request->post['reply_email'])) {
            $this->error['reply_email'] = $this->data['lang_error_email_invalid'];
        }

        if (!isset($this->request->post['reply_email']) || $this->validation->length($this->request->post['reply_email']) > 250) {
            $this->error['reply_email'] = sprintf($this->data['lang_error_length'], 0, 250);
        }

        if ($this->error) {
            $this->data['error'] = $this->data['lang_message_error'];

            return false;
        } else {
            return true;
        }
    }
}
