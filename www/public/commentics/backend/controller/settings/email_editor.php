<?php
namespace Commentics;

class SettingsEmailEditorController extends Controller
{
    public function index()
    {
        $this->loadLanguage('settings/email_editor');

        $this->loadModel('settings/email_editor');

        $this->loadModel('common/language');

        $frontend_languages = $this->model_common_language->getFrontendLanguages();

        $backend_languages = $this->model_common_language->getBackendLanguages();

        if (isset($this->request->get['type'])) {
            $type = $this->request->get['type'];

            switch ($type) {
                case 'ban':
                    $this->data['lang_heading']     = $this->data['lang_heading_ban'];
                    $this->data['lang_description'] = $this->data['lang_description_ban'];
                    $this->data['languages']        = $backend_languages;
                    $this->data['keywords']         = '<span>username</span> <span>ip address</span> <span>reason</span> <span>admin link</span> <span>signature</span>';
                    break;
                case 'comment_approve':
                    $this->data['lang_heading']     = $this->data['lang_heading_comment_approve'];
                    $this->data['lang_description'] = $this->data['lang_description_comment_approve'];
                    $this->data['languages']        = $backend_languages;
                    $this->data['keywords']         = '<span>username</span> <span>page reference</span> <span>page url</span> <span>' . $this->setting->get('purpose') . ' url</span> <span>poster</span> <span>' . $this->setting->get('purpose') . '</span> <span>reason</span> <span>admin link</span> <span>signature</span>';
                    break;
                case 'comment_success':
                    $this->data['lang_heading']     = $this->data['lang_heading_comment_success'];
                    $this->data['lang_description'] = $this->data['lang_description_comment_success'];
                    $this->data['languages']        = $backend_languages;
                    $this->data['keywords']         = '<span>username</span> <span>page reference</span> <span>page url</span> <span>' . $this->setting->get('purpose') . ' url</span> <span>poster</span> <span>' . $this->setting->get('purpose') . '</span> <span>admin link</span> <span>signature</span>';
                    break;
                case 'flag':
                    $this->data['lang_heading']     = $this->data['lang_heading_flag'];
                    $this->data['lang_description'] = $this->data['lang_description_flag'];
                    $this->data['languages']        = $backend_languages;
                    $this->data['keywords']         = '<span>username</span> <span>page reference</span> <span>page url</span> <span>' . $this->setting->get('purpose') . ' url</span> <span>poster</span> <span>' . $this->setting->get('purpose') . '</span> <span>admin link</span> <span>signature</span>';
                    break;
                case 'edit':
                    $this->data['lang_heading']     = $this->data['lang_heading_edit'];
                    $this->data['lang_description'] = $this->data['lang_description_edit'];
                    $this->data['languages']        = $backend_languages;
                    $this->data['keywords']         = '<span>username</span> <span>page reference</span> <span>page url</span> <span>' . $this->setting->get('purpose') . ' url</span> <span>poster</span> <span>' . $this->setting->get('purpose') . '</span> <span>admin link</span> <span>signature</span>';
                    break;
                case 'delete':
                    $this->data['lang_heading']     = $this->data['lang_heading_delete'];
                    $this->data['lang_description'] = $this->data['lang_description_delete'];
                    $this->data['languages']        = $backend_languages;
                    $this->data['keywords']         = '<span>username</span> <span>page reference</span> <span>page url</span> <span>' . $this->setting->get('purpose') . ' url</span> <span>poster</span> <span>' . $this->setting->get('purpose') . '</span> <span>admin link</span> <span>signature</span>';
                    break;
                case 'new_version':
                    $this->data['lang_heading']     = $this->data['lang_heading_new_version'];
                    $this->data['lang_description'] = $this->data['lang_description_new_version'];
                    $this->data['languages']        = $backend_languages;
                    $this->data['keywords']         = '<span>username</span> <span>installed version</span> <span>newest version</span> <span>admin link</span> <span>signature</span>';
                    break;
                case 'password_reset':
                    $this->data['lang_heading']     = $this->data['lang_heading_password_reset'];
                    $this->data['lang_description'] = $this->data['lang_description_password_reset'];
                    $this->data['languages']        = $backend_languages;
                    $this->data['keywords']         = '<span>username</span> <span>password</span> <span>admin link</span> <span>signature</span>';
                    break;
                case 'setup_test':
                    $this->data['lang_heading']     = $this->data['lang_heading_setup_test'];
                    $this->data['lang_description'] = $this->data['lang_description_setup_test'];
                    $this->data['languages']        = $backend_languages;
                    $this->data['keywords']         = '<span>username</span> <span>admin link</span> <span>signature</span>';
                    break;
                case 'subscriber_confirmation':
                    $this->data['lang_heading']     = $this->data['lang_heading_subscriber_confirmation'];
                    $this->data['lang_description'] = $this->data['lang_description_subscriber_confirmation'];
                    $this->data['languages']        = $frontend_languages;
                    $this->data['keywords']         = '<span>name</span> <span>page reference</span> <span>page url</span> <span>confirmation link</span> <span>signature</span>';
                    break;
                case 'subscriber_notification_admin':
                    $this->data['lang_heading']     = $this->data['lang_heading_subscriber_notification_admin'];
                    $this->data['lang_description'] = $this->data['lang_description_subscriber_notification_admin'];
                    $this->data['languages']        = $frontend_languages;
                    $this->data['keywords']         = '<span>name</span> <span>page reference</span> <span>page url</span> <span>' . $this->setting->get('purpose') . ' url</span> <span>poster</span> <span>' . $this->setting->get('purpose') . '</span> <span>signature</span> <span>user url</span>';
                    break;
                case 'subscriber_notification_basic':
                    $this->data['lang_heading']     = $this->data['lang_heading_subscriber_notification_basic'];
                    $this->data['lang_description'] = $this->data['lang_description_subscriber_notification_basic'];
                    $this->data['languages']        = $frontend_languages;
                    $this->data['keywords']         = '<span>name</span> <span>page reference</span> <span>page url</span> <span>' . $this->setting->get('purpose') . ' url</span> <span>poster</span> <span>' . $this->setting->get('purpose') . '</span> <span>signature</span> <span>user url</span>';
                    break;
                case 'subscriber_notification_reply':
                    $this->data['lang_heading']     = $this->data['lang_heading_subscriber_notification_reply'];
                    $this->data['lang_description'] = $this->data['lang_description_subscriber_notification_reply'];
                    $this->data['languages']        = $frontend_languages;
                    $this->data['keywords']         = '<span>name</span> <span>page reference</span> <span>page url</span> <span>' . $this->setting->get('purpose') . ' url</span> <span>poster</span> <span>' . $this->setting->get('purpose') . '</span> <span>signature</span> <span>user url</span>';
                    break;
                case 'user_comment_approved':
                    $this->data['lang_heading']     = $this->data['lang_heading_user_comment_approved'];
                    $this->data['lang_description'] = $this->data['lang_description_user_comment_approved'];
                    $this->data['languages']        = $frontend_languages;
                    $this->data['keywords']         = '<span>name</span> <span>page reference</span> <span>page url</span> <span>' . $this->setting->get('purpose') . ' url</span> <span>' . $this->setting->get('purpose') . '</span> <span>signature</span> <span>user url</span>';
                    break;
                default:
                    $this->response->redirect('settings/email_editor&type=ban');
            }
        } else {
            $this->response->redirect('settings/email_editor&type=ban');
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_settings_email_editor->update($this->request->post, $this->request->get['type']);
            }
        }

        $this->data['type'] = $type;

        $this->data['types'] = array(
            $this->data['lang_select_admin']                         => 'admin',
            $this->data['lang_select_ban']                           => 'ban',
            $this->data['lang_select_comment_approve']               => 'comment_approve',
            $this->data['lang_select_comment_success']               => 'comment_success',
            $this->data['lang_select_flag']                          => 'flag',
            $this->data['lang_select_edit']                          => 'edit',
            $this->data['lang_select_delete']                        => 'delete',
            $this->data['lang_select_new_version']                   => 'new_version',
            $this->data['lang_select_password_reset']                => 'password_reset',
            $this->data['lang_select_setup_test']                    => 'setup_test',
            $this->data['lang_select_subscriber']                    => 'subscriber',
            $this->data['lang_select_subscriber_confirmation']       => 'subscriber_confirmation',
            $this->data['lang_select_subscriber_notification_admin'] => 'subscriber_notification_admin',
            $this->data['lang_select_subscriber_notification_basic'] => 'subscriber_notification_basic',
            $this->data['lang_select_subscriber_notification_reply'] => 'subscriber_notification_reply',
            $this->data['lang_select_user']                          => 'user',
            $this->data['lang_select_user_comment_approved']         => 'user_comment_approved'
        );

        if (isset($this->request->post['field'])) {
            $this->data['field'] = $this->request->post['field'];
        } else {
            $this->data['field'] = $this->model_settings_email_editor->getEmail($this->request->get['type']);
        }

        if (isset($this->error['subject'])) {
            $this->data['error_subject'] = $this->error['subject'];
        } else {
            $this->data['error_subject'] = '';
        }

        if (isset($this->error['text'])) {
            $this->data['error_text'] = $this->error['text'];
        } else {
            $this->data['error_text'] = '';
        }

        if (isset($this->error['html'])) {
            $this->data['error_html'] = $this->error['html'];
        } else {
            $this->data['error_html'] = '';
        }

        if ($this->setting->get('notice_settings_email_editor')) {
            $this->data['info'] = $this->data['lang_notice'];
        }

        $this->components = array('common/header', 'common/footer');

        $this->loadView('settings/email_editor');
    }

    public function dismiss()
    {
        $this->loadModel('settings/email_editor');

        $this->model_settings_email_editor->dismiss();
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        foreach ($this->request->post['field'] as $key => $value) {
            if (!isset($value['subject']) || $this->validation->length($value['subject']) < 1 || $this->validation->length($value['subject']) > 250) {
                $this->error['subject'][$key] = sprintf($this->data['lang_error_length'], 1, 250);
            }

            if (!isset($value['text']) || $this->validation->length($value['text']) < 1 || $this->validation->length($value['text']) > 5000) {
                $this->error['text'][$key] = sprintf($this->data['lang_error_length'], 1, 5000);
            }

            if (!isset($value['html']) || $this->validation->length($value['html']) < 1 || $this->validation->length($value['html']) > 5000) {
                $this->error['html'][$key] = sprintf($this->data['lang_error_length'], 1, 5000);
            }
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
