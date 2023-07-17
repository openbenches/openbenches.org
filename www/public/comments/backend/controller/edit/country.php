<?php
namespace Commentics;

class EditCountryController extends Controller
{
    public function index()
    {
        $this->loadLanguage('edit/country');

        $this->loadModel('edit/country');

        $this->loadModel('common/language');

        if (!isset($this->request->get['id']) || !$this->geo->countryExists($this->request->get['id'])) {
            $this->response->redirect('main/dashboard');
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_edit_country->update($this->request->post, $this->request->get['id']);

                $this->session->data['cmtx_success'] = $this->data['lang_message_success'];

                $this->response->redirect('manage/countries');
            }
        }

        $country = $this->geo->getCountry($this->request->get['id']);

        if (isset($this->request->post['name'])) {
            $this->data['name'] = $this->request->post['name'];
        } else {
            $this->data['name'] = $this->model_edit_country->getName($country['code']);
        }

        if (isset($this->request->post['code'])) {
            $this->data['code'] = $this->request->post['code'];
        } else {
            $this->data['code'] = $country['code'];
        }

        if (isset($this->request->post['top'])) {
            $this->data['top'] = $this->request->post['top'];
        } else {
            $this->data['top'] = $country['top'];
        }

        if (isset($this->request->post['enabled'])) {
            $this->data['enabled'] = $this->request->post['enabled'];
        } else {
            $this->data['enabled'] = $country['enabled'];
        }

        $this->data['date_added'] = $this->variable->formatDate($country['date_added'], $this->data['lang_date_time_format'], $this->data);

        if (isset($this->error['name'])) {
            $this->data['error_name'] = $this->error['name'];
        } else {
            $this->data['error_name'] = '';
        }

        if (isset($this->error['code'])) {
            $this->data['error_code'] = $this->error['code'];
        } else {
            $this->data['error_code'] = '';
        }

        if (isset($this->error['top'])) {
            $this->data['error_top'] = $this->error['top'];
        } else {
            $this->data['error_top'] = '';
        }

        if (isset($this->error['enabled'])) {
            $this->data['error_enabled'] = $this->error['enabled'];
        } else {
            $this->data['error_enabled'] = '';
        }

        $this->data['id'] = $this->request->get['id'];

        $this->data['languages'] = $this->model_common_language->getFrontendLanguages();

        $this->data['link_back'] = $this->url->link('manage/countries');

        $this->components = array('common/header', 'common/footer');

        $this->loadView('edit/country');
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        foreach ($this->request->post['name'] as $key => $value) {
            if ($this->validation->length($value) < 1 || $this->validation->length($value) > 250) {
                $this->error['name'][$key] = sprintf($this->data['lang_error_length'], 1, 250);
            }
        }

        if (!isset($this->request->post['code']) || $this->validation->length($this->request->post['code']) != 3 || !$this->validation->isUpper($this->request->post['code'])) {
            $this->error['code'] = $this->data['lang_error_code_invalid'];
        }

        if (!isset($this->request->post['code']) || $this->geo->countryExistsByCode($this->request->post['code'], $this->request->get['id'])) {
            $this->error['code'] = $this->data['lang_error_code_exists'];
        }

        if (!isset($this->request->post['top']) || !in_array($this->request->post['top'], array('0', '1'))) {
            $this->error['top'] = $this->data['lang_error_selection'];
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
