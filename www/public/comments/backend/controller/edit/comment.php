<?php
namespace Commentics;

class EditCommentController extends Controller
{
    public function index()
    {
        $this->loadLanguage('edit/comment');

        $this->loadModel('edit/comment');

        if (!isset($this->request->get['id']) || !$this->comment->commentExists($this->request->get['id'])) {
            $this->response->redirect('main/dashboard');
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_edit_comment->update($this->request->post, $this->request->get['id']);

                $this->session->data['cmtx_success'] = $this->data['lang_message_success'];

                $this->response->redirect('manage/comments');
            }
        }

        $comment = $this->comment->getComment($this->request->get['id']);

        $this->data['user_id'] = $comment['user_id'];

        $this->data['name'] = $comment['name'];

        $this->data['email'] = $comment['email'];

        if (isset($this->request->post['website'])) {
            $this->data['website'] = $this->request->post['website'];
        } else {
            $this->data['website'] = $comment['website'];
        }

        if (isset($this->request->post['town'])) {
            $this->data['town'] = $this->request->post['town'];
        } else {
            $this->data['town'] = $comment['town'];
        }

        if (isset($this->request->post['state_id'])) {
            $this->data['state_id'] = $this->request->post['state_id'];
        } else {
            $this->data['state_id'] = $comment['state_id'];
        }

        if (isset($this->request->post['country_id'])) {
            $this->data['country_id'] = $this->request->post['country_id'];
        } else {
            $this->data['country_id'] = $comment['country_id'];
        }

        if (isset($this->request->post['rating'])) {
            $this->data['rating'] = $this->request->post['rating'];
        } else {
            $this->data['rating'] = $comment['rating'];
        }

        if (isset($this->request->post['headline'])) {
            $this->data['headline'] = $this->request->post['headline'];
        } else {
            $this->data['headline'] = $comment['headline'];
        }

        if (isset($this->request->post['comment'])) {
            $this->data['comment'] = $this->request->post['comment'];
        } else {
            $this->data['comment'] = $this->security->encode($comment['comment']);
        }

        if (isset($this->request->post['reply'])) {
            $this->data['reply'] = $this->request->post['reply'];
        } else {
            $this->data['reply'] = $this->security->encode($comment['reply']);
        }

        if (isset($this->request->post['page_id'])) {
            $this->data['page_id'] = $this->request->post['page_id'];
        } else {
            $this->data['page_id'] = $comment['page_id'];
        }

        if (isset($this->request->post['reply_to'])) {
            $this->data['reply_to'] = $this->request->post['reply_to'];
        } else {
            $this->data['reply_to'] = $comment['reply_to'];
        }

        $replies = count($this->comment->getReplies($this->request->get['id']));

        if ($replies == '1') {
            $this->data['lang_text_replies'] = $this->data['lang_text_replies_single'];
        } else {
            $this->data['lang_text_replies'] = sprintf($this->data['lang_text_replies_plural'], $replies);
        }

        $uploads = $comment['uploads'];

        foreach ($uploads as &$upload) {
            $upload['image'] = CMTX_HTTP_UPLOAD . $upload['folder'] . '/' . $upload['filename'] . '.' . $upload['extension'];
        }

        $this->data['uploads'] = $uploads;

        if (isset($this->request->post['is_approved'])) {
            $this->data['is_approved'] = $this->request->post['is_approved'];
        } else {
            $this->data['is_approved'] = $comment['is_approved'];
        }

        if (isset($this->request->post['notes'])) {
            $this->data['notes'] = $this->request->post['notes'];
        } else {
            $this->data['notes'] = $comment['notes'];
        }

        if (isset($this->request->post['is_sent'])) {
            $this->data['is_sent'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['is_sent'])) {
            $this->data['is_sent'] = false;
        } else {
            $this->data['is_sent'] = $comment['is_sent'];
        }

        if ($comment['sent_to'] == '1') {
            $this->data['lang_text_sent'] = $this->data['lang_text_sent_single'];
        } else {
            $this->data['lang_text_sent'] = sprintf($this->data['lang_text_sent_plural'], $comment['sent_to']);
        }

        if ($comment['likes'] == '1') {
            $this->data['lang_text_likes'] = $this->data['lang_text_likes_single'];
        } else {
            $this->data['lang_text_likes'] = sprintf($this->data['lang_text_likes_plural'], $comment['likes']);
        }

        if ($comment['dislikes'] == '1') {
            $this->data['lang_text_dislikes'] = $this->data['lang_text_dislikes_single'];
        } else {
            $this->data['lang_text_dislikes'] = sprintf($this->data['lang_text_dislikes_plural'], $comment['dislikes']);
        }

        if ($comment['reports'] == '1') {
            $this->data['lang_text_reports'] = $this->data['lang_text_reports_single'];
        } else {
            $this->data['lang_text_reports'] = sprintf($this->data['lang_text_reports_plural'], $comment['reports']);
        }

        if (isset($this->request->post['is_verified'])) {
            $this->data['is_verified'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['is_verified'])) {
            $this->data['is_verified'] = false;
        } else {
            $this->data['is_verified'] = $comment['is_verified'];
        }

        if (isset($this->request->post['is_sticky'])) {
            $this->data['is_sticky'] = $this->request->post['is_sticky'];
        } else {
            $this->data['is_sticky'] = $comment['is_sticky'];
        }

        if (isset($this->request->post['is_locked'])) {
            $this->data['is_locked'] = $this->request->post['is_locked'];
        } else {
            $this->data['is_locked'] = $comment['is_locked'];
        }

        if (isset($this->request->post['is_admin'])) {
            $this->data['is_admin'] = $this->request->post['is_admin'];
        } else {
            $this->data['is_admin'] = $comment['is_admin'];
        }

        $this->data['ip_address'] = $comment['ip_address'];

        $this->data['date_added'] = $this->variable->formatDate($comment['date_added'], $this->data['lang_date_time_format'], $this->data);

        if (isset($this->error['website'])) {
            $this->data['error_website'] = $this->error['website'];
        } else {
            $this->data['error_website'] = '';
        }

        if (isset($this->error['town'])) {
            $this->data['error_town'] = $this->error['town'];
        } else {
            $this->data['error_town'] = '';
        }

        if (isset($this->error['state_id'])) {
            $this->data['error_state_id'] = $this->error['state_id'];
        } else {
            $this->data['error_state_id'] = '';
        }

        if (isset($this->error['country_id'])) {
            $this->data['error_country_id'] = $this->error['country_id'];
        } else {
            $this->data['error_country_id'] = '';
        }

        if (isset($this->error['rating'])) {
            $this->data['error_rating'] = $this->error['rating'];
        } else {
            $this->data['error_rating'] = '';
        }

        if (isset($this->error['headline'])) {
            $this->data['error_headline'] = $this->error['headline'];
        } else {
            $this->data['error_headline'] = '';
        }

        if (isset($this->error['comment'])) {
            $this->data['error_comment'] = $this->error['comment'];
        } else {
            $this->data['error_comment'] = '';
        }

        if (isset($this->error['reply'])) {
            $this->data['error_reply'] = $this->error['reply'];
        } else {
            $this->data['error_reply'] = '';
        }

        if (isset($this->error['page_id'])) {
            $this->data['error_page_id'] = $this->error['page_id'];
        } else {
            $this->data['error_page_id'] = '';
        }

        if (isset($this->error['reply_to'])) {
            $this->data['error_reply_to'] = $this->error['reply_to'];
        } else {
            $this->data['error_reply_to'] = '';
        }

        if (isset($this->error['is_approved'])) {
            $this->data['error_is_approved'] = $this->error['is_approved'];
        } else {
            $this->data['error_is_approved'] = '';
        }

        if (isset($this->error['notes'])) {
            $this->data['error_notes'] = $this->error['notes'];
        } else {
            $this->data['error_notes'] = '';
        }

        if (isset($this->error['is_sticky'])) {
            $this->data['error_is_sticky'] = $this->error['is_sticky'];
        } else {
            $this->data['error_is_sticky'] = '';
        }

        if (isset($this->error['is_locked'])) {
            $this->data['error_is_locked'] = $this->error['is_locked'];
        } else {
            $this->data['error_is_locked'] = '';
        }

        if (isset($this->error['is_admin'])) {
            $this->data['error_is_admin'] = $this->error['is_admin'];
        } else {
            $this->data['error_is_admin'] = '';
        }

        $this->data['id'] = $this->request->get['id'];

        if ($this->setting->get('use_wysiwyg')) {
            $this->data['wysiwyg_enabled'] = true;
        } else {
            $this->data['wysiwyg_enabled'] = false;
        }

        $this->data['countries'] = $this->geo->getCountries();

        $this->data['pages'] = $this->model_edit_comment->getPages();

        $this->data['extra_fields'] = array();

        if ($this->setting->has('extra_fields_enabled') && $this->setting->get('extra_fields_enabled')) {
            $this->loadModel('module/extra_fields');

            $data = array(
                'group_by' => '',
                'sort'     => 'f.name',
                'order'    => 'asc',
                'start'    => 0,
                'limit'    => 9999
            );

            $extra_fields = $this->model_module_extra_fields->getFields($data);

            foreach ($extra_fields as $extra_field) {
                $this->data['extra_fields'][] = array(
                    'name'  => $extra_field['name'],
                    'value' => $this->model_module_extra_fields->getFieldValue($extra_field['id'], $this->data['id'])
                );
            }
        }

        $this->data['link_name'] = $this->url->link('edit/user', '&id=' . $comment['user_id']);

        $this->data['link_spam'] = $this->security->decode($this->url->link('edit/spam', '&id=' . $this->request->get['id']));

        $this->data['default_country'] = $this->setting->get('default_country');

        $this->data['link_back'] = $this->url->link('manage/comments');

        if ($this->setting->get('notice_edit_comment')) {
            $this->data['info'] = $this->data['lang_notice'];
        }

        $this->components = array('common/header', 'common/footer');

        $this->loadView('edit/comment');
    }

    public function getStates()
    {
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');

            $json = array();

            if (isset($this->request->post['country_id']) && $this->request->post['country_id']) {
                $this->loadModel('edit/comment');

                $states = $this->model_edit_comment->getStates($this->request->post['country_id']);

                foreach ($states as $state) {
                    $json[] = array(
                        'id' => $state['id'],
                        'name' => $state['name']
                    );
                }
            }

            echo json_encode($json);
        } else {
            $this->response->redirect('main/dashboard');
        }
    }

    public function getReplies()
    {
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');

            $json = array();

            if (isset($this->request->post['id']) && isset($this->request->post['page_id'])) {
                $this->loadLanguage('edit/comment');

                $this->loadModel('edit/comment');

                $replies = $this->model_edit_comment->getReplies($this->request->post['id'], $this->request->post['page_id']);

                foreach ($replies as $reply) {
                    $json[] = array(
                        'id' => $reply['id'],
                        'name' => $reply['name'],
                        'date_added' => $this->variable->formatDate($reply['date_added'], $this->data['lang_date_time_format'], $this->data)
                    );
                }
            }

            echo json_encode($json);
        } else {
            $this->response->redirect('main/dashboard');
        }
    }

    public function dismiss()
    {
        $this->loadModel('edit/comment');

        $this->model_edit_comment->dismiss();
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        $this->loadModel('edit/comment');

        if (isset($this->request->post['website']) && !empty($this->request->post['website']) && !$this->validation->isUrl($this->request->post['website'])) {
            $this->error['website'] = $this->data['lang_error_url'];
        }

        if (!isset($this->request->post['website']) || $this->validation->length($this->request->post['website']) > 250) {
            $this->error['website'] = sprintf($this->data['lang_error_length'], 0, 250);
        }

        if (!isset($this->request->post['town']) || $this->validation->length($this->request->post['town']) > 250) {
            $this->error['town'] = sprintf($this->data['lang_error_length'], 0, 250);
        }

        if (!isset($this->request->post['state_id']) || ($this->request->post['state_id'] && !$this->geo->stateExists($this->request->post['state_id']))) {
            $this->error['state_id'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['country_id']) || ($this->request->post['country_id'] && !$this->geo->countryExists($this->request->post['country_id']))) {
            $this->error['country_id'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['rating']) || !in_array($this->request->post['rating'], array('', '1', '2', '3', '4', '5'))) {
            $this->error['rating'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['headline']) || $this->validation->length($this->request->post['headline']) > 250) {
            $this->error['headline'] = sprintf($this->data['lang_error_length'], 0, 250);
        }

        if (!isset($this->request->post['reply']) || $this->validation->length($this->request->post['reply']) > $this->setting->get('comment_maximum_characters')) {
            $this->error['reply'] = sprintf($this->data['lang_error_length'], 0, $this->setting->get('comment_maximum_characters'));
        }

        if (!isset($this->request->post['page_id']) || !$this->page->pageExists($this->request->post['page_id'])) {
            $this->error['page_id'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['reply_to']) || ($this->request->post['reply_to'] != '0' && !$this->comment->commentExists($this->request->post['reply_to']))) {
            $this->error['reply_to'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['is_approved']) || !in_array($this->request->post['is_approved'], array('0', '1'))) {
            $this->error['is_approved'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['notes']) || $this->validation->length($this->request->post['notes']) > 1000) {
            $this->error['notes'] = sprintf($this->data['lang_error_length'], 0, 1000);
        }

        if (isset($this->request->post['is_sticky']) && !in_array($this->request->post['is_sticky'], array('0', '1'))) {
            $this->error['is_sticky'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['is_locked']) || !in_array($this->request->post['is_locked'], array('0', '1'))) {
            $this->error['is_locked'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['is_admin']) || !in_array($this->request->post['is_admin'], array('0', '1'))) {
            $this->error['is_admin'] = $this->data['lang_error_selection'];
        }

        if ($this->error) {
            $this->data['error'] = $this->data['lang_message_error'];

            return false;
        } else {
            return true;
        }
    }
}
