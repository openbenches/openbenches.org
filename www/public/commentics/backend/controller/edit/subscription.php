<?php
namespace Commentics;

class EditSubscriptionController extends Controller
{
    public function index()
    {
        $this->loadLanguage('edit/subscription');

        $this->loadModel('edit/subscription');

        if (!isset($this->request->get['id']) || !$this->model_edit_subscription->subscriptionExists($this->request->get['id'])) {
            $this->response->redirect('main/dashboard');
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_edit_subscription->update($this->request->post, $this->request->get['id']);

                $this->session->data['cmtx_success'] = $this->data['lang_message_success'];

                $this->response->redirect('manage/subscriptions');
            }
        }

        $subscription = $this->model_edit_subscription->getSubscription($this->request->get['id']);

        $this->data['name'] = $subscription['name'];

        $this->data['email'] = $subscription['email'];

        $this->data['page_reference'] = $subscription['page_reference'];

        if (isset($this->request->post['is_confirmed'])) {
            $this->data['is_confirmed'] = $this->request->post['is_confirmed'];
        } else {
            $this->data['is_confirmed'] = $subscription['is_confirmed'];
        }

        $this->data['ip_address'] = $subscription['ip_address'];

        $this->data['date_added'] = $this->variable->formatDate($subscription['date_added'], $this->data['lang_date_time_format'], $this->data);

        if (isset($this->error['is_confirmed'])) {
            $this->data['error_is_confirmed'] = $this->error['is_confirmed'];
        } else {
            $this->data['error_is_confirmed'] = '';
        }

        $this->data['id'] = $this->request->get['id'];

        $this->data['link_name'] = $this->url->link('edit/user', '&id=' . $subscription['user_id']);

        $this->data['link_page'] = $this->url->link('edit/page', '&id=' . $subscription['page_id']);

        $this->data['link_back'] = $this->url->link('manage/subscriptions');

        $this->components = array('common/header', 'common/footer');

        $this->loadView('edit/subscription');
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        if (!isset($this->request->post['is_confirmed']) || !in_array($this->request->post['is_confirmed'], array('0', '1'))) {
            $this->error['is_confirmed'] = $this->data['lang_error_selection'];
        }

        if ($this->error) {
            $this->data['error'] = $this->data['lang_message_error'];

            return false;
        } else {
            return true;
        }
    }
}
