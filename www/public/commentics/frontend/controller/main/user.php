<?php
namespace Commentics;

class MainUserController extends Controller
{
    public function index()
    {
        $this->loadLanguage('main/user');

        $this->loadModel('main/user');

        $this->data['stylesheet'] = $this->loadStylesheet('stylesheet.min.css');

        $this->data['custom'] = $this->loadCustomCss();

        $this->data['common'] = $this->loadJavascript('common-jq.min.js');

        $this->data['commentics_url'] = $this->url->getCommenticsUrl();

        $this->data['lang_heading'] = '';

        $this->data['user'] = '';

        if (isset($this->request->get['u-t'])) {
            if ($this->model_main_user->hasMaxAttempts()) {
                $this->data['error'] = $this->data['lang_error_timeout'];
            } else {
                $user = $this->user->getUserByToken($this->request->get['u-t']);

                if ($user) {
                    $this->model_main_user->resetAttempts();

                    $this->data['user'] = $user;

                    $this->data['lang_title'] .= ': ' . $user['name'];

                    $this->data['lang_heading'] = $user['name'];

                    $this->data['avatar_type'] = $this->setting->get('avatar_type');

                    $this->data['avatar'] = $this->user->getAvatar($user['id'], true);

                    if ($this->data['avatar_type'] == 'gravatar') {
                        $this->data['lang_text_gravatar'] = sprintf($this->data['lang_text_gravatar'], '<a href="https://gravatar.com" target="_blank">https://gravatar.com</a>');
                    } else if ($this->data['avatar_type'] == 'login') {
                        $this->data['lang_text_login'] = sprintf($this->data['lang_text_login'], $this->setting->get('site_name'));
                    } else if ($this->data['avatar_type'] == 'selection') {
                        $this->data['lang_text_selection'] = sprintf($this->data['lang_text_selection'], 'cmtx_avatar_selection_link');

                        $this->data['avatar_selection_attribution'] = $this->security->decode($this->setting->get('avatar_selection_attribution'));

                        $this->data['avatars'] = $this->model_main_user->getSelectableAvatars();
                    } else if ($this->data['avatar_type'] == 'upload') {
                        $this->data['lang_text_upload'] = sprintf($this->data['lang_text_upload'], 'cmtx_avatar_upload_link', $this->setting->get('avatar_upload_max_size'));

                        $this->data['can_upload_avatar'] = true;

                        if ($this->setting->get('avatar_upload_min_posts') > $user['comments']) {
                            $this->data['can_upload_avatar'] = false;

                            $this->data['lang_text_cannot_upload_avatar'] = sprintf($this->data['lang_text_min_posts'], $this->setting->get('avatar_upload_min_posts'));

                            $this->data['lang_text_cannot_upload_avatar'] = str_replace(array('1 comments', '1 reviews', '1 testimonials'), array('1 comment', '1 review', '1 testimonial'), $this->data['lang_text_cannot_upload_avatar']);
                        }

                        if ($this->model_main_user->numDaysSinceUserAdded($user['date_added']) < $this->setting->get('avatar_upload_min_days')) {
                            $this->data['can_upload_avatar'] = false;

                            $this->data['lang_text_cannot_upload_avatar'] = sprintf($this->data['lang_text_min_days'], $this->setting->get('avatar_upload_min_days'));

                            $this->data['lang_text_cannot_upload_avatar'] = str_replace('1 days', '1 day', $this->data['lang_text_cannot_upload_avatar']);
                        }

                        if ($user['avatar_pending_id']) {
                            $this->data['is_avatar_pending'] = true;
                        } else {
                            $this->data['is_avatar_pending'] = false;
                        }
                    }

                    if ($user['to_all']) {
                        $this->data['everything_checked'] = 'checked';
                        $this->data['custom_checked'] = '';
                    } else {
                        $this->data['everything_checked'] = '';
                        $this->data['custom_checked'] = 'checked';
                    }

                    if ($user['format'] == 'html') {
                        $this->data['html_checked'] = 'checked';
                        $this->data['text_checked'] = '';
                    } else {
                        $this->data['html_checked'] = '';
                        $this->data['text_checked'] = 'checked';
                    }

                    if ($user['to_admin']) {
                        $this->data['to_admin_checked'] = 'checked';
                    } else {
                        $this->data['to_admin_checked'] = '';
                    }

                    if ($user['to_reply']) {
                        $this->data['to_reply_checked'] = 'checked';
                    } else {
                        $this->data['to_reply_checked'] = '';
                    }

                    if ($user['to_approve']) {
                        $this->data['to_approve_checked'] = 'checked';
                    } else {
                        $this->data['to_approve_checked'] = '';
                    }

                    if (isset($this->request->get['s-t'])) {
                        $subscription = $this->model_main_user->getSubscription($this->request->get['u-t'], $this->request->get['s-t']);

                        if ($subscription) {
                            if (isset($this->request->get['action']) && $this->request->get['action'] == 'c-s') {
                                if ($subscription['is_confirmed']) {
                                    $this->data['error'] = $this->data['lang_message_error_confirmed'];
                                } else {
                                    $this->model_main_user->confirmSubscription($this->request->get['s-t']);

                                    $this->data['success'] = $this->data['lang_message_success'];
                                }
                            }
                        } else {
                            $this->data['error'] = $this->data['lang_message_error_no_sub'];
                        }
                    }

                    $subscriptions = $this->model_main_user->getSubscriptions($this->request->get['u-t']);

                    $this->data['lang_text_subscriptions_section'] = sprintf($this->data['lang_text_subscriptions_section'], '<span class="count">' . count($subscriptions) . '</span>');

                    foreach ($subscriptions as &$subscription) {
                        $subscription['date_added'] = $this->variable->formatDate($subscription['date_added'], 'c', $this->data);

                        $subscription['date_added_title'] = $this->variable->formatDate($subscription['date_added'], $this->data['lang_date_time_format'], $this->data);
                    }

                    $this->data['subscriptions'] = $subscriptions;

                    /* RTL (Right to Left) */
                    if ($this->setting->get('rtl')) {
                        $this->data['cmtx_dir'] = 'cmtx_rtl';
                    } else {
                        $this->data['cmtx_dir'] = 'cmtx_ltr';
                    }

                    /* These are passed to common.js via the template */
                    $this->data['cmtx_js_settings_user'] = array(
                        'commentics_url'       => $this->url->getCommenticsUrl(),
                        'token'                => $user['token'],
                        'to_all'               => (bool) $user['to_all'],
                        'lang_text_saving'     => $this->data['lang_text_saving'],
                        'lang_text_no_results' => $this->data['lang_text_no_results'],
                        'timeago_suffixAgo'    => $this->data['lang_text_timeago_ago'],
                        'timeago_inPast'       => $this->data['lang_text_timeago_second'],
                        'timeago_seconds'      => $this->data['lang_text_timeago_seconds'],
                        'timeago_minute'       => $this->data['lang_text_timeago_minute'],
                        'timeago_minutes'      => $this->data['lang_text_timeago_minutes'],
                        'timeago_hour'         => $this->data['lang_text_timeago_hour'],
                        'timeago_hours'        => $this->data['lang_text_timeago_hours'],
                        'timeago_day'          => $this->data['lang_text_timeago_day'],
                        'timeago_days'         => $this->data['lang_text_timeago_days'],
                        'timeago_month'        => $this->data['lang_text_timeago_month'],
                        'timeago_months'       => $this->data['lang_text_timeago_months'],
                        'timeago_year'         => $this->data['lang_text_timeago_year'],
                        'timeago_years'        => $this->data['lang_text_timeago_years']
                    );

                    $this->data['cmtx_js_settings_user'] = json_encode($this->data['cmtx_js_settings_user']);
                } else {
                    $this->data['error'] = $this->data['lang_message_error_no_user'];

                    $this->model_main_user->addAttempt();
                }
            }
        } else {
            $this->data['error'] = $this->data['lang_message_error_invalid'];
        }

        $this->loadView('main/user');
    }

    public function saveUploadedAvatar()
    {
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');

            $json = array();

            if (isset($this->request->post['u-t'])) {
                $this->loadLanguage('main/user');

                $this->loadModel('main/user');

                $user = $this->user->getUserByToken($this->request->post['u-t']);

                $ip_address = $this->user->getIpAddress();

                if ($this->setting->get('maintenance_mode')) { // check if in maintenance mode
                    $json['error'] = $this->data['lang_error_maintenance'];
                } else if ($this->model_main_user->hasMaxAttempts()) {
                    $json['error'] = $this->data['lang_error_timeout'];
                } else if (!$user) { // check if user exists
                    $json['error'] = $this->data['lang_error_no_user'];
                    $this->model_main_user->addAttempt();
                } else if ($this->user->isBanned($ip_address)) { // check if user is banned
                    $json['error'] = $this->data['lang_error_banned'];
                } else if ($this->setting->get('avatar_type') != 'upload') { // check if feature enabled
                    $json['error'] = $this->data['lang_error_disabled'];
                } else if ($this->setting->get('avatar_upload_min_posts') > $user['comments']) {
                    $json['error'] = $this->data['lang_error_min_posts'];
                } else if ($this->model_main_user->numDaysSinceUserAdded($user['date_added']) < $this->setting->get('avatar_upload_min_days')) {
                    $json['error'] = $this->data['lang_error_min_days'];
                } else if ($this->model_main_user->numUploadedAvatars($user['id']) > 10) {
                    $json['error'] = $this->data['lang_error_max_uploads'];
                }

                if (!$json) {
                    $result = $this->model_main_user->saveUploadedAvatar($user, $this->request->files);

                    if (is_array($result)) {
                        if ($result['approve']) {
                            $json['success'] = $this->data['lang_text_avatar_approve'];
                        } else {
                            $json['success'] = $this->data['lang_text_avatar_success'];

                            if ($this->setting->get('cache_type')) {
                                $json['success'] .= '. ' . $this->data['lang_text_avatar_cache'];
                            }
                        }
                    } else {
                        $json['error'] = $result;
                    }
                }
            }

            echo json_encode($json);
        }
    }

    public function saveSelectedAvatar()
    {
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');

            $json = array();

            if (isset($this->request->post['u-t'])) {
                $this->loadLanguage('main/user');

                $this->loadModel('main/user');

                $user = $this->user->getUserByToken($this->request->post['u-t']);

                $ip_address = $this->user->getIpAddress();

                if ($this->setting->get('maintenance_mode')) { // check if in maintenance mode
                    $json['error'] = $this->data['lang_error_maintenance'];
                } else if ($this->model_main_user->hasMaxAttempts()) {
                    $json['error'] = $this->data['lang_error_timeout'];
                } else if (!$user) { // check if user exists
                    $json['error'] = $this->data['lang_error_no_user'];
                    $this->model_main_user->addAttempt();
                } else if ($this->user->isBanned($ip_address)) { // check if user is banned
                    $json['error'] = $this->data['lang_error_banned'];
                } else if ($this->setting->get('avatar_type') != 'selection') { // check if feature enabled
                    $json['error'] = $this->data['lang_error_disabled'];
                }

                if (!$json) {
                    $result = $this->model_main_user->saveSelectedAvatar($user, $this->request->post);

                    if (is_array($result)) {
                        $json['success'] = $this->data['lang_text_avatar_success'];

                        if ($this->setting->get('cache_type')) {
                            $json['success'] .= '. ' . $this->data['lang_text_avatar_cache'];
                        }
                    } else {
                        $json['error'] = $result;
                    }
                }
            }

            echo json_encode($json);
        }
    }

    public function save()
    {
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');

            $json = array();

            if (isset($this->request->post['u-t'])) {
                $this->loadLanguage('main/user');

                $this->loadModel('main/user');

                $user_token = $this->request->post['u-t'];

                $ip_address = $this->user->getIpAddress();

                if ($this->setting->get('maintenance_mode')) { // check if in maintenance mode
                    $json['error'] = $this->data['lang_error_maintenance'];
                } else if ($this->model_main_user->hasMaxAttempts()) {
                    $json['error'] = $this->data['lang_error_timeout'];
                } else if (!$this->user->getUserByToken($user_token)) { // check if user exists
                    $json['error'] = $this->data['lang_error_no_user'];
                    $this->model_main_user->addAttempt();
                } else if ($this->user->isBanned($ip_address)) { // check if user is banned
                    $json['error'] = $this->data['lang_error_banned'];
                }

                if (!$json) {
                    $this->model_main_user->save($this->request->post);

                    $json['success'] = $this->data['lang_text_settings_saved'];
                }
            }

            echo json_encode($json);
        }
    }

    public function deleteSubscription()
    {
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');

            $json = array();

            if (isset($this->request->post['u-t']) && isset($this->request->post['s-t'])) {
                $this->loadLanguage('main/user');

                $this->loadModel('main/user');

                $user_token = $this->request->post['u-t'];

                $subscription_token = $this->request->post['s-t'];

                $ip_address = $this->user->getIpAddress();

                if ($this->setting->get('maintenance_mode')) { // check if in maintenance mode
                    $json['error'] = $this->data['lang_error_maintenance'];
                } else if ($this->model_main_user->hasMaxAttempts()) {
                    $json['error'] = $this->data['lang_error_timeout'];
                } else if (!$this->user->getUserByToken($user_token)) { // check if user exists
                    $json['error'] = $this->data['lang_error_no_user'];
                    $this->model_main_user->addAttempt();
                } else if (!$this->model_main_user->getSubscription($user_token, $subscription_token)) { // check if subscription exists
                    $json['error'] = $this->data['lang_error_no_subscription'];
                } else if ($this->user->isBanned($ip_address)) { // check if user is banned
                    $json['error'] = $this->data['lang_error_banned'];
                }

                if (!$json) {
                    $this->model_main_user->deleteSubscription($subscription_token);

                    $json['count'] = count($this->model_main_user->getSubscriptions($user_token));

                    $json['success'] = true;
                }
            }

            echo json_encode($json);
        }
    }

    public function deleteAllSubscriptions()
    {
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');

            $json = array();

            if (isset($this->request->post['u-t'])) {
                $this->loadLanguage('main/user');

                $this->loadModel('main/user');

                $user = $this->user->getUserByToken($this->request->post['u-t']);

                $ip_address = $this->user->getIpAddress();

                if ($this->setting->get('maintenance_mode')) { // check if in maintenance mode
                    $json['error'] = $this->data['lang_error_maintenance'];
                } else if ($this->model_main_user->hasMaxAttempts()) {
                    $json['error'] = $this->data['lang_error_timeout'];
                } else if (!$user) { // check if user exists
                    $json['error'] = $this->data['lang_error_no_user'];
                    $this->model_main_user->addAttempt();
                } else if ($this->user->isBanned($ip_address)) { // check if user is banned
                    $json['error'] = $this->data['lang_error_banned'];
                }

                if (!$json) {
                    $this->model_main_user->deleteAllSubscriptions($user['id']);

                    $json['success'] = true;
                }
            }

            echo json_encode($json);
        }
    }
}
