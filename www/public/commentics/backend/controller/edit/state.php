<?php
namespace Commentics;

class EditStateController extends Controller
{
    public function index()
    {
        $this->loadLanguage('edit/state');

        $this->loadModel('edit/state');

        if (!isset($this->request->get['id']) || !$this->geo->stateExists($this->request->get['id'])) {
            $this->response->redirect('main/dashboard');
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_edit_state->update($this->request->post, $this->request->get['id']);

                $this->session->data['cmtx_success'] = $this->data['lang_message_success'];

                $this->response->redirect('manage/states');
            }
        }

        $state = $this->geo->getState($this->request->get['id']);

        if (isset($this->request->post['name'])) {
            $this->data['name'] = $this->request->post['name'];
        } else {
            $this->data['name'] = $state['name'];
        }

        if (isset($this->request->post['country_code'])) {
            $this->data['country_code'] = $this->request->post['country_code'];
        } else {
            $this->data['country_code'] = $state['country_code'];
        }

        if (isset($this->request->post['enabled'])) {
            $this->data['enabled'] = $this->request->post['enabled'];
        } else {
            $this->data['enabled'] = $state['enabled'];
        }

        $this->data['date_added'] = $this->variable->formatDate($state['date_added'], $this->data['lang_date_time_format'], $this->data);

        if (isset($this->error['name'])) {
            $this->data['error_name'] = $this->error['name'];
        } else {
            $this->data['error_name'] = '';
        }

        if (isset($this->error['country_code'])) {
            $this->data['error_country_code'] = $this->error['country_code'];
        } else {
            $this->data['error_country_code'] = '';
        }

        if (isset($this->error['enabled'])) {
            $this->data['error_enabled'] = $this->error['enabled'];
        } else {
            $this->data['error_enabled'] = '';
        }

        $this->data['id'] = $this->request->get['id'];

        $this->data['countries'] = $this->geo->getCountries(true);

        $this->data['link_back'] = $this->url->link('manage/states');

        $this->components = array('common/header', 'common/footer');

        $this->loadView('edit/state');
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

        if (!isset($this->request->post['country_code']) || !$this->request->post['country_code'] || !$this->geo->countryExistsByCode($this->request->post['country_code'])) {
            $this->error['country_code'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['enabled']) || !in_array($this->request->post['enabled'], array('0', '1'))) {
            $this->error['enabled'] = $this->data['lang_error_selection'];
        }

        if ($this->error) {
            $this->data['error'] = $this->data['lang_message_error'];

            return false;
        } else {
            return true;
        }
    }
}
