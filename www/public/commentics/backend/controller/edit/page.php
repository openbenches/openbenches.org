<?php
namespace Commentics;

class EditPageController extends Controller
{
    public function index()
    {
        $this->loadLanguage('edit/page');

        $this->loadModel('edit/page');

        if (!isset($this->request->get['id']) || !$this->page->pageExists($this->request->get['id'])) {
            $this->response->redirect('main/dashboard');
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_edit_page->update($this->request->post, $this->request->get['id']);

                $this->session->data['cmtx_success'] = $this->data['lang_message_success'];

                $this->response->redirect('manage/pages');
            }
        }

        $page = $this->page->getPage($this->request->get['id']);

        if (isset($this->request->post['identifier'])) {
            $this->data['identifier'] = $this->request->post['identifier'];
        } else {
            $this->data['identifier'] = $page['identifier'];
        }

        if (isset($this->request->post['reference'])) {
            $this->data['reference'] = $this->request->post['reference'];
        } else {
            $this->data['reference'] = $page['reference'];
        }

        if (isset($this->request->post['url'])) {
            $this->data['url'] = $this->request->post['url'];
        } else {
            $this->data['url'] = $page['url'];
        }

        if ($page['comments'] == '1') {
            $this->data['lang_text_comments'] = sprintf($this->data['lang_text_comments_single'], $this->url->link('manage/comments', '&filter_page_id=' . $this->request->get['id']), $page['comments']);
        } else {
            $this->data['lang_text_comments'] = sprintf($this->data['lang_text_comments_plural'], $this->url->link('manage/comments', '&filter_page_id=' . $this->request->get['id']), $page['comments']);
        }

        if ($page['subscriptions'] == '1') {
            $this->data['lang_text_subscriptions'] = sprintf($this->data['lang_text_subscriptions_single'], $this->url->link('manage/subscriptions', '&filter_page_id=' . $this->request->get['id']), $page['subscriptions']);
        } else {
            $this->data['lang_text_subscriptions'] = sprintf($this->data['lang_text_subscriptions_plural'], $this->url->link('manage/subscriptions', '&filter_page_id=' . $this->request->get['id']), $page['subscriptions']);
        }

        if (isset($this->request->post['moderate'])) {
            $this->data['moderate'] = $this->request->post['moderate'];
        } else {
            $this->data['moderate'] = $page['moderate'];
        }

        if (isset($this->request->post['is_form_enabled'])) {
            $this->data['is_form_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['is_form_enabled'])) {
            $this->data['is_form_enabled'] = false;
        } else {
            $this->data['is_form_enabled'] = $page['is_form_enabled'];
        }

        $this->data['date_added'] = $this->variable->formatDate($page['date_added'], $this->data['lang_date_time_format'], $this->data);

        if (isset($this->error['identifier'])) {
            $this->data['error_identifier'] = $this->error['identifier'];
        } else {
            $this->data['error_identifier'] = '';
        }

        if (isset($this->error['reference'])) {
            $this->data['error_reference'] = $this->error['reference'];
        } else {
            $this->data['error_reference'] = '';
        }

        if (isset($this->error['url'])) {
            $this->data['error_url'] = $this->error['url'];
        } else {
            $this->data['error_url'] = '';
        }

        if (isset($this->error['moderate'])) {
            $this->data['error_moderate'] = $this->error['moderate'];
        } else {
            $this->data['error_moderate'] = '';
        }

        $this->data['id'] = $this->request->get['id'];

        $this->data['link_back'] = $this->url->link('manage/pages');

        $this->components = array('common/header', 'common/footer');

        $this->loadView('edit/page');
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        $this->loadModel('edit/page');

        if (!isset($this->request->post['identifier']) || $this->model_edit_page->identifierExists($this->request->post['identifier'], $this->request->get['id'])) {
            $this->error['identifier'] = $this->data['lang_error_identifier_exists'];
        }

        if (!isset($this->request->post['identifier']) || $this->validation->length($this->request->post['identifier']) < 1 || $this->validation->length($this->request->post['identifier']) > 250) {
            $this->error['identifier'] = sprintf($this->data['lang_error_length'], 1, 250);
        }

        if (!isset($this->request->post['reference']) || $this->validation->length($this->request->post['reference']) < 1 || $this->validation->length($this->request->post['reference']) > 250) {
            $this->error['reference'] = sprintf($this->data['lang_error_length'], 1, 250);
        }

        if (!isset($this->request->post['url']) || !$this->validation->isUrl($this->request->post['url'])) {
            $this->error['url'] = $this->data['lang_error_url'];
        }

        if (!isset($this->request->post['url']) || $this->validation->length($this->request->post['url']) < 1 || $this->validation->length($this->request->post['url']) > 250) {
            $this->error['url'] = sprintf($this->data['lang_error_length'], 1, 250);
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
