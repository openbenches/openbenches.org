<?php
namespace Commentics;

class DataListController extends Controller
{
    public function index()
    {
        $this->loadLanguage('data/list');

        $this->loadModel('data/list');

        if (isset($this->request->get['type'])) {
            $type = $this->request->get['type'];

            switch ($type) {
                case 'banned_emails':
                    $this->data['group']              = 'emails';
                    $this->data['lang_heading']       = $this->data['lang_heading_banned_emails'];
                    $this->data['lang_text_wildcard'] = $this->data['lang_text_wildcard_emails'];
                    $this->data['lang_text_case']     = $this->data['lang_text_case_emails'];
                    $this->data['lang_text_lines']    = $this->data['lang_text_lines'];
                    break;
                case 'banned_names':
                    $this->data['group']              = 'words';
                    $this->data['lang_heading']       = $this->data['lang_heading_banned_names'];
                    $this->data['lang_text_wildcard'] = $this->data['lang_text_wildcard_words'];
                    $this->data['lang_text_case']     = $this->data['lang_text_case_words'];
                    $this->data['lang_text_lines']    = $this->data['lang_text_lines'];
                    break;
                case 'banned_towns':
                    $this->data['group']              = 'words';
                    $this->data['lang_heading']       = $this->data['lang_heading_banned_towns'];
                    $this->data['lang_text_wildcard'] = $this->data['lang_text_wildcard_words'];
                    $this->data['lang_text_case']     = $this->data['lang_text_case_words'];
                    $this->data['lang_text_lines']    = $this->data['lang_text_lines'];
                    break;
                case 'banned_websites':
                    $this->data['group']              = 'websites';
                    $this->data['lang_heading']       = $this->data['lang_heading_banned_websites'];
                    $this->data['lang_text_wildcard'] = $this->data['lang_text_wildcard_websites'];
                    $this->data['lang_text_case']     = $this->data['lang_text_case_websites'];
                    $this->data['lang_text_lines']    = $this->data['lang_text_lines'];
                    break;
                case 'detect_links':
                    $this->data['group']              = 'websites';
                    $this->data['lang_heading']       = $this->data['lang_heading_detect_links'];
                    $this->data['lang_text_wildcard'] = $this->data['lang_text_wildcard_websites'];
                    $this->data['lang_text_case']     = $this->data['lang_text_case_websites'];
                    $this->data['lang_text_lines']    = $this->data['lang_text_lines'];
                    break;
                case 'dummy_emails':
                    $this->data['group']              = 'emails';
                    $this->data['lang_heading']       = $this->data['lang_heading_dummy_emails'];
                    $this->data['lang_text_wildcard'] = $this->data['lang_text_wildcard_emails'];
                    $this->data['lang_text_case']     = $this->data['lang_text_case_emails'];
                    $this->data['lang_text_lines']    = $this->data['lang_text_lines'];
                    break;
                case 'dummy_names':
                    $this->data['group']              = 'words';
                    $this->data['lang_heading']       = $this->data['lang_heading_dummy_names'];
                    $this->data['lang_text_wildcard'] = $this->data['lang_text_wildcard_words'];
                    $this->data['lang_text_case']     = $this->data['lang_text_case_words'];
                    $this->data['lang_text_lines']    = $this->data['lang_text_lines'];
                    break;
                case 'dummy_towns':
                    $this->data['group']              = 'words';
                    $this->data['lang_heading']       = $this->data['lang_heading_dummy_towns'];
                    $this->data['lang_text_wildcard'] = $this->data['lang_text_wildcard_words'];
                    $this->data['lang_text_case']     = $this->data['lang_text_case_words'];
                    $this->data['lang_text_lines']    = $this->data['lang_text_lines'];
                    break;
                case 'dummy_websites':
                    $this->data['group']              = 'websites';
                    $this->data['lang_heading']       = $this->data['lang_heading_dummy_websites'];
                    $this->data['lang_text_wildcard'] = $this->data['lang_text_wildcard_websites'];
                    $this->data['lang_text_case']     = $this->data['lang_text_case_websites'];
                    $this->data['lang_text_lines']    = $this->data['lang_text_lines'];
                    break;
                case 'mild_swear_words':
                    $this->data['group']              = 'words';
                    $this->data['lang_heading']       = $this->data['lang_heading_mild_swear_words'];
                    $this->data['lang_text_wildcard'] = $this->data['lang_text_wildcard_words'];
                    $this->data['lang_text_case']     = $this->data['lang_text_case_words'];
                    $this->data['lang_text_lines']    = $this->data['lang_text_lines'];
                    break;
                case 'reserved_emails':
                    $this->data['group']              = 'emails';
                    $this->data['lang_heading']       = $this->data['lang_heading_reserved_emails'];
                    $this->data['lang_text_wildcard'] = $this->data['lang_text_wildcard_emails'];
                    $this->data['lang_text_case']     = $this->data['lang_text_case_emails'];
                    $this->data['lang_text_lines']    = $this->data['lang_text_lines'];
                    break;
                case 'reserved_names':
                    $this->data['group']              = 'words';
                    $this->data['lang_heading']       = $this->data['lang_heading_reserved_names'];
                    $this->data['lang_text_wildcard'] = $this->data['lang_text_wildcard_words'];
                    $this->data['lang_text_case']     = $this->data['lang_text_case_words'];
                    $this->data['lang_text_lines']    = $this->data['lang_text_lines'];
                    break;
                case 'reserved_towns':
                    $this->data['group']              = 'words';
                    $this->data['lang_heading']       = $this->data['lang_heading_reserved_towns'];
                    $this->data['lang_text_wildcard'] = $this->data['lang_text_wildcard_words'];
                    $this->data['lang_text_case']     = $this->data['lang_text_case_words'];
                    $this->data['lang_text_lines']    = $this->data['lang_text_lines'];
                    break;
                case 'reserved_websites':
                    $this->data['group']              = 'websites';
                    $this->data['lang_heading']       = $this->data['lang_heading_reserved_websites'];
                    $this->data['lang_text_wildcard'] = $this->data['lang_text_wildcard_websites'];
                    $this->data['lang_text_case']     = $this->data['lang_text_case_websites'];
                    $this->data['lang_text_lines']    = $this->data['lang_text_lines'];
                    break;
                case 'spam_words':
                    $this->data['group']              = 'words';
                    $this->data['lang_heading']       = $this->data['lang_heading_spam_words'];
                    $this->data['lang_text_wildcard'] = $this->data['lang_text_wildcard_words'];
                    $this->data['lang_text_case']     = $this->data['lang_text_case_words'];
                    $this->data['lang_text_lines']    = $this->data['lang_text_lines'];
                    break;
                case 'strong_swear_words':
                    $this->data['group']              = 'words';
                    $this->data['lang_heading']       = $this->data['lang_heading_strong_swear_words'];
                    $this->data['lang_text_wildcard'] = $this->data['lang_text_wildcard_words'];
                    $this->data['lang_text_case']     = $this->data['lang_text_case_words'];
                    $this->data['lang_text_lines']    = $this->data['lang_text_lines'];
                    break;
                default:
                    $this->response->redirect('data/list&type=banned_emails');
            }
        } else {
            $this->response->redirect('data/list&type=banned_emails');
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_data_list->update($this->request->post, $this->session->data['cmtx_username'], $this->request->get['type']);
            }
        }

        $this->data['type'] = $type;

        $this->data['types'] = array(
            $this->data['lang_select_banned_emails']      => 'banned_emails',
            $this->data['lang_select_banned_names']       => 'banned_names',
            $this->data['lang_select_banned_towns']       => 'banned_towns',
            $this->data['lang_select_banned_websites']    => 'banned_websites',
            $this->data['lang_select_detect_links']       => 'detect_links',
            $this->data['lang_select_dummy_emails']       => 'dummy_emails',
            $this->data['lang_select_dummy_names']        => 'dummy_names',
            $this->data['lang_select_dummy_towns']        => 'dummy_towns',
            $this->data['lang_select_dummy_websites']     => 'dummy_websites',
            $this->data['lang_select_mild_swear_words']   => 'mild_swear_words',
            $this->data['lang_select_reserved_emails']    => 'reserved_emails',
            $this->data['lang_select_reserved_names']     => 'reserved_names',
            $this->data['lang_select_reserved_towns']     => 'reserved_towns',
            $this->data['lang_select_reserved_websites']  => 'reserved_websites',
            $this->data['lang_select_spam_words']         => 'spam_words',
            $this->data['lang_select_strong_swear_words'] => 'strong_swear_words'
        );

        $list = $this->model_data_list->getList($this->request->get['type']);

        if (isset($this->request->post['text'])) {
            $this->data['text'] = $this->request->post['text'];
        } else {
            $this->data['text'] = $list['text'];
        }

        if ($list['modified_by']) {
            $this->data['lang_text_modified_by'] = sprintf($this->data['lang_text_modified_by'], $list['modified_by'], $this->variable->formatDate($list['date_modified'], $this->data['lang_time_format'], $this->data), $this->variable->formatDate($list['date_modified'], $this->data['lang_date_format'], $this->data));
        } else {
            $this->data['lang_text_modified_by'] = $this->data['lang_text_not_modified'];
        }

        if (isset($this->error['text'])) {
            $this->data['error_text'] = $this->error['text'];
        } else {
            $this->data['error_text'] = '';
        }

        $this->components = array('common/header', 'common/footer');

        $this->loadView('data/list');
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        if (!isset($this->request->post['text']) || $this->validation->length($this->request->post['text']) < 0 || $this->validation->length($this->request->post['text']) > 5000) {
            $this->error['text'] = sprintf($this->data['lang_error_length'], 0, 5000);
        }

        if ($this->error) {
            $this->data['error'] = $this->data['lang_message_error'];

            return false;
        } else {
            $this->data['success'] = $this->data['lang_message_success'];

            return true;
        }
    }
}
