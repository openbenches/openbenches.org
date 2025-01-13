<?php
namespace Commentics;

class PartNotifyController extends Controller
{
    public function index()
    {
        $this->loadLanguage('part/notify');

        /* These are passed to common.js via the template */
        $this->data['cmtx_js_settings_notify'] = array(
            'lang_button_processing'   => $this->data['lang_button_processing'],
            'lang_button_notify'       => $this->data['lang_button_notify'],
            'lang_heading_notify'      => $this->data['lang_heading_notify'],
            'lang_text_notify_info'    => $this->data['lang_text_notify_info'],
            'lang_title_cancel_notify' => $this->data['lang_title_cancel_notify'],
            'lang_link_cancel'         => $this->data['lang_link_cancel']
        );

        $this->data['cmtx_js_settings_notify'] = json_encode($this->data['cmtx_js_settings_notify'], JSON_HEX_TAG);

        return $this->data;
    }

    public function notify()
    {
        if ($this->request->isAjax()) {
            $this->loadLanguage('main/form');

            $this->loadLanguage('part/notify');

            $this->loadModel('main/form');

            $this->response->addHeader('Content-Type: application/json');

            $json = array();

            if (!$this->setting->get('show_notify')) { // check if feature enabled
                $json['result']['error'] = $this->data['lang_error_disabled'];
            } else {
                /* Is this an administrator? */
                $is_admin = $this->user->isAdmin();

                if ($this->setting->get('maintenance_mode') && !$is_admin) {
                    $json['result']['error'] = $this->setting->get('maintenance_message');
                } else {
                    if ($this->setting->get('enabled_form')) {
                        $page_id = $this->page->getId();

                        if ($page_id) {
                            $page = $this->page->getPage($page_id);

                            if ($page['is_form_enabled']) {
                                $ip_address = $this->user->getIpAddress();

                                if ($this->user->isBanned($ip_address)) {
                                    $json['result']['error'] = $this->data['lang_error_banned'];
                                } else {
                                    /* Let the model access the language */
                                    $this->model_main_form->data = $this->data;

                                    /* Check referrer */
                                    $this->model_main_form->validateReferrer();

                                    /* Check honeypot */
                                    $this->model_main_form->validateHoneypot();

                                    /* Check time */
                                    $this->model_main_form->validateTime();

                                    /* Name */
                                    $this->model_main_form->validateName($is_admin);

                                    /* Email */
                                    $this->model_main_form->validateEmail($is_admin);

                                    /* User */
                                    $user = $this->model_main_form->validateUser();

                                    /* Question */
                                    $this->model_main_form->validateQuestion();

                                    /* ReCaptcha */
                                    $this->model_main_form->validateReCaptcha();

                                    /* Image Captcha */
                                    $this->model_main_form->validateImageCaptcha();

                                    /* Captcha */
                                    $this->model_main_form->validateCaptcha();

                                    /* Subscription */
                                    if ($user) { // if the user exists
                                        /* Check if user is already subscribed to this page */
                                        if ($this->model_main_form->subscriptionExists($user['id'], $page_id)) {
                                            $json['result']['error'] = $this->data['lang_error_subscribed'];
                                        }

                                        /* Check if user has a pending subscription to any page */
                                        if ($this->model_main_form->userHasSubscriptionAttempt($user['id'])) {
                                            $json['result']['error'] = $this->data['lang_error_pending'];
                                        }
                                    }

                                    /* Check if IP address has a pending subscription to any page */
                                    if ($this->model_main_form->ipHasSubscriptionAttempt($ip_address)) {
                                        $json['result']['error'] = $this->data['lang_error_pending'];
                                    }
                                }
                            } else {
                                $json['result']['error'] = $this->data['lang_error_form_disabled'];
                            }
                        } else {
                            $json['result']['error'] = $this->data['lang_error_page_invalid'];
                        }
                    } else {
                        $json['result']['error'] = $this->data['lang_error_form_disabled'];
                    }
                }
            }

            $json = array_merge($json, $this->model_main_form->getJson());

            if ($json && (isset($json['result']['error']) || isset($json['error']))) {
                if (isset($json['result']['error'])) {
                    $json['error'] = '';
                } else {
                    $json['result']['error'] = $this->data['lang_error_review'];
                }
            } else {
                if ($user) {
                    $user_id = $user['id'];

                    $user_token = $user['token'];
                } else {
                    $user_token = $this->user->createToken();

                    $user_id = $this->user->createUser($this->request->post['cmtx_name'], $this->request->post['cmtx_email'], $user_token, $ip_address);
                }

                if ($this->setting->get('enabled_question')) {
                    $question = $this->model_main_form->getQuestion();

                    if ($question) {
                        $this->session->data['cmtx_question_id_' . $this->page->getId()] = $question['id'];

                        $json['question'] = $question['question'];
                    }
                }

                $subscription_token = $this->user->createToken();

                $subscription_id = $this->model_main_form->addSubscription($user_id, $page_id, $subscription_token, $ip_address);

                $this->notify->subscriberConfirmation($this->setting->get('notify_format'), $this->request->post['cmtx_name'], $this->request->post['cmtx_email'], $page['reference'], $page['url'], $user_token, $subscription_token);

                /* Unset that the Captcha is complete so the user has to pass it again */
                unset($this->session->data['cmtx_captcha_complete_' . $this->page->getId()]);

                $json['result']['success'] = $this->data['lang_text_notify_success'];
            }

            echo json_encode($json);
        }
    }
}
