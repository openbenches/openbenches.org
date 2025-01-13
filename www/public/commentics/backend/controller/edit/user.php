<?php
namespace Commentics;

class EditUserController extends Controller
{
    public function index()
    {
        $this->loadLanguage('edit/user');

        $this->loadModel('edit/user');

        if (!isset($this->request->get['id']) || !$this->user->userExists($this->request->get['id'])) {
            $this->response->redirect('main/dashboard');
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_edit_user->update($this->request->post, $this->request->get['id']);

                $this->session->data['cmtx_success'] = $this->data['lang_message_success'];

                $this->response->redirect('manage/users');
            }
        }

        $user = $this->user->getUser($this->request->get['id']);

        if (isset($this->request->post['name'])) {
            $this->data['name'] = $this->request->post['name'];
        } else {
            $this->data['name'] = $user['name'];
        }

        if (isset($this->request->post['email'])) {
            $this->data['email'] = $this->request->post['email'];
        } else {
            $this->data['email'] = $user['email'];
        }

        if ($user['comments'] == '1') {
            $this->data['lang_text_comments'] = sprintf($this->data['lang_text_comments_single'], $this->url->link('manage/comments', '&filter_user_id=' . $this->request->get['id']), $user['comments']);
        } else {
            $this->data['lang_text_comments'] = sprintf($this->data['lang_text_comments_plural'], $this->url->link('manage/comments', '&filter_user_id=' . $this->request->get['id']), $user['comments']);
        }

        if ($user['subscriptions'] == '1') {
            $this->data['lang_text_subscriptions'] = sprintf($this->data['lang_text_subscriptions_single'], $this->url->link('manage/subscriptions', '&filter_user_id=' . $this->request->get['id']), $user['subscriptions']);
        } else {
            $this->data['lang_text_subscriptions'] = sprintf($this->data['lang_text_subscriptions_plural'], $this->url->link('manage/subscriptions', '&filter_user_id=' . $this->request->get['id']), $user['subscriptions']);
        }

        if (isset($this->request->post['moderate'])) {
            $this->data['moderate'] = $this->request->post['moderate'];
        } else {
            $this->data['moderate'] = $user['moderate'];
        }

        $this->data['date_added'] = $this->variable->formatDate($user['date_added'], $this->data['lang_date_time_format'], $this->data);

        if (isset($this->error['name'])) {
            $this->data['error_name'] = $this->error['name'];
        } else {
            $this->data['error_name'] = '';
        }

        if (isset($this->error['email'])) {
            $this->data['error_email'] = $this->error['email'];
        } else {
            $this->data['error_email'] = '';
        }

        if (isset($this->error['moderate'])) {
            $this->data['error_moderate'] = $this->error['moderate'];
        } else {
            $this->data['error_moderate'] = '';
        }

        $this->data['id'] = $this->request->get['id'];

        $this->data['link_back'] = $this->url->link('manage/users');

        $this->components = array('common/header', 'common/footer');

        $this->loadView('edit/user');
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        $this->loadModel('edit/user');

        if ($this->setting->get('unique_name_enabled')) {
            if (isset($this->request->post['name']) && $this->request->post['name'] && $this->model_edit_user->nameExists($this->request->post['name'], $this->request->get['id'])) {
                $this->error['name'] = $this->data['lang_error_name_exists'];
            }
        }

        if (!isset($this->request->post['name']) || $this->validation->length($this->request->post['name']) < 1 || $this->validation->length($this->request->post['name']) > 250) {
            $this->error['name'] = sprintf($this->data['lang_error_length'], 1, 250);
        }

        if ($this->setting->get('unique_email_enabled')) {
            if (isset($this->request->post['email']) && $this->request->post['email'] && $this->model_edit_user->emailExists($this->request->post['email'], $this->request->get['id'])) {
                $this->error['email'] = $this->data['lang_error_email_exists'];
            }
        }

        if (!isset($this->request->post['email']) || $this->validation->length($this->request->post['email']) < 0 || $this->validation->length($this->request->post['email']) > 250) {
            $this->error['email'] = sprintf($this->data['lang_error_length'], 0, 250);
        }

        if (!isset($this->request->post['moderate']) || !in_array($this->request->post['moderate'], array('default', 'never', 'always'))) {
            $this->error['moderate'] = $this->data['lang_error_selection'];
        }

        if ($this->error) {
            $this->data['error'] = $this->data['lang_message_error'];

            return false;
        } else {
            return true;
        }
    }
}
