<?php
namespace Commentics;

class EditSpamController extends Controller
{
    public function index()
    {
        $this->loadLanguage('edit/spam');

        $this->loadModel('edit/spam');

        if (!isset($this->request->get['id']) || !$this->comment->commentExists($this->request->get['id'])) {
            $this->response->redirect('main/dashboard');
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_edit_spam->remove($this->request->post, $this->request->get['id']);

                $this->session->data['cmtx_success'] = $this->data['lang_message_success'];

                $this->response->redirect('manage/comments');
            }
        }

        $comment = $this->comment->getComment($this->request->get['id']);

        if (isset($this->request->post['delete'])) {
            $this->data['delete'] = $this->request->post['delete'];
        } else {
            $this->data['delete'] = 'delete_this';
        }

        if (isset($this->request->post['ban'])) {
            $this->data['ban'] = $this->request->post['ban'];
        } else {
            $this->data['ban'] = 'ban';
        }

        if (isset($this->request->post['add_name'])) {
            $this->data['add_name'] = true;
        } else {
            $this->data['add_name'] = false;
        }

        if (isset($this->request->post['add_email'])) {
            $this->data['add_email'] = true;
        } else {
            $this->data['add_email'] = false;
        }

        if (isset($this->request->post['add_website'])) {
            $this->data['add_website'] = true;
        } else {
            $this->data['add_website'] = false;
        }

        if (isset($this->error['delete'])) {
            $this->data['error_delete'] = $this->error['delete'];
        } else {
            $this->data['error_delete'] = '';
        }

        if (isset($this->error['ban'])) {
            $this->data['error_ban'] = $this->error['ban'];
        } else {
            $this->data['error_ban'] = '';
        }

        if ($comment['website']) {
            $this->data['has_website'] = true;
        } else {
            $this->data['has_website'] = false;
        }

        if ($comment['email']) {
            $this->data['has_email'] = true;
        } else {
            $this->data['has_email'] = false;
        }

        $this->data['lang_text_delete_all'] = sprintf($this->data['lang_text_delete_all'], $comment['ip_address']);

        $this->data['lang_text_add_name'] = sprintf($this->data['lang_text_add_name'], $comment['name']);

        $this->data['lang_text_add_email'] = sprintf($this->data['lang_text_add_email'], $comment['email']);

        $this->data['lang_text_add_website'] = sprintf($this->data['lang_text_add_website'], $comment['website']);

        $this->data['id'] = $this->request->get['id'];

        if ($this->setting->get('notice_edit_spam')) {
            $this->data['info'] = $this->data['lang_notice'];
        }

        $this->components = array('common/header', 'common/footer');

        $this->loadView('edit/spam');
    }

    public function dismiss()
    {
        $this->loadModel('edit/spam');

        $this->model_edit_spam->dismiss();
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        if (!isset($this->request->post['delete']) || !in_array($this->request->post['delete'], array('delete_this', 'delete_all'))) {
            $this->error['delete'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['ban']) || !in_array($this->request->post['ban'], array('ban', 'no_ban'))) {
            $this->error['ban'] = $this->data['lang_error_selection'];
        }

        if ($this->error) {
            $this->data['error'] = $this->data['lang_message_error'];

            return false;
        } else {
            return true;
        }
    }
}
