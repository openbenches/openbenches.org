<?php
namespace Commentics;

class MainFormModel extends Model
{
    public $uploads = array();
    public $extra_fields = array();
    public $data = array();
    private $json = array();
    private $approve = '';
    private $notes = '';
    private $country = 0;
    private $account = false;

    public function getJson()
    {
        return $this->json;
    }

    public function getNotes()
    {
        return $this->notes;
    }

    public function validateFloodingDelay($is_admin, $page_id)
    {
        if ($this->setting->get('flood_control_delay_enabled') && !$is_admin) {
            if ($this->isFloodingDelay('comments', $page_id)) {
                $this->json['result']['error'] = $this->data['lang_error_flooding_delay'];
            } else if ($this->isFloodingDelay('deleted', $page_id)) {
                $this->json['result']['error'] = $this->data['lang_error_flooding_delay'];
            }
        }
    }

    public function validateFloodingMaximum($is_admin, $page_id)
    {
        if ($this->setting->get('flood_control_maximum_enabled') && !$is_admin) {
            if ($this->isFloodingMaximum('comments', $page_id)) {
                $this->json['result']['error'] = $this->data['lang_error_flooding_maximum'];
            } else if ($this->isFloodingMaximum('deleted', $page_id)) {
                $this->json['result']['error'] = $this->data['lang_error_flooding_maximum'];
            }
        }
    }

    public function validateReferrer()
    {
        if ($this->setting->get('check_referrer')) {
            if (isset($this->request->server['HTTP_REFERER'])) {
                $referrer = $this->url->decode($this->request->server['HTTP_REFERER']);

                $domain = $this->url->decode($this->setting->get('site_domain'));

                if (!$this->variable->stristr($referrer, $domain)) { // if referrer does not contain domain
                    $this->json['result']['error'] = $this->data['lang_error_incorrect_referrer'];
                }
            } else {
                $this->json['result']['error'] = $this->data['lang_error_no_referrer'];
            }
        }
    }

    public function validateHoneypot()
    {
        if ($this->setting->get('check_honeypot') && (!isset($this->request->post['cmtx_honeypot']) || $this->request->post['cmtx_honeypot'])) {
            $this->json['result']['error'] = $this->data['lang_error_honeypot'];
        }
    }

    public function validateTime()
    {
        if ($this->setting->get('check_time') && (!isset($this->request->post['cmtx_time']) || (time() - intval($this->request->post['cmtx_time'])) < 5)) {
            $this->json['result']['error'] = $this->data['lang_error_time'];
        }
    }

    public function validateComment($is_preview)
    {
        if (isset($this->request->post['cmtx_comment']) && $this->request->post['cmtx_comment'] != '') {
            $comment = $this->security->decode($this->request->post['cmtx_comment']);

            $this->request->post['cmtx_original_comment'] = $comment;

            /* Check comment length does not exceed maximum */
            if ($this->validation->length($this->request->post['cmtx_comment']) > $this->setting->get('comment_maximum_characters')) {
                $this->json['error']['comment'] = $this->data['lang_error_comment_max_length'];
            }

            /* Check repeats */
            if ($this->setting->get('check_repeats_enabled') && $this->hasRepeats($comment)) {
                if ($this->setting->get('check_repeats_action') == 'error') {
                    $this->json['error']['comment'] = $this->data['lang_error_comment_has_repeats'];
                } else if ($this->setting->get('check_repeats_action') == 'approve') {
                    $this->approve .= $this->data['lang_error_comment_has_repeats'] . "\r\n";
                } else {
                    $this->json['result']['error'] = $this->data['lang_error_ban'];

                    $this->user->ban($this->data['lang_error_comment_has_repeats']);
                }
            }

            /* Check for long word */
            if ($this->hasLongWord($comment)) {
                $this->json['error']['comment'] = $this->data['lang_error_comment_has_long_word'];
            }

            /* Check maximum lines */
            if ($this->countLines($comment) > $this->setting->get('comment_maximum_lines')) {
                $this->json['error']['comment'] = $this->data['lang_error_comment_max_lines'];
            }

            /* Check minimum words */
            if ($this->countWords($comment) < $this->setting->get('comment_minimum_words')) {
                $this->json['error']['comment'] = $this->data['lang_error_comment_min_words'];
            }

            /* Check for mild swear words */
            if ($this->setting->get('mild_swear_words_enabled')) {
                if ($this->hasWord($comment, 'mild_swear_words')) {
                    if ($this->setting->get('mild_swear_words_action') == 'mask') {
                        if (!$is_preview) {
                            $this->request->post['cmtx_comment'] = $this->maskWord($comment, 'mild_swear_words');
                        }
                    } else if ($this->setting->get('mild_swear_words_action') == 'mask_approve') {
                        if (!$is_preview) {
                            $this->request->post['cmtx_comment'] = $this->maskWord($comment, 'mild_swear_words');

                            $this->approve .= $this->data['lang_error_comment_mild_swearing'] . "\r\n";
                        }
                    } else if ($this->setting->get('mild_swear_words_action') == 'error') {
                        $this->json['error']['comment'] = $this->data['lang_error_comment_mild_swearing'];
                    } else if ($this->setting->get('mild_swear_words_action') == 'approve') {
                        $this->approve .= $this->data['lang_error_comment_mild_swearing'] . "\r\n";
                    } else {
                        $this->json['result']['error'] = $this->data['lang_error_ban'];

                        $this->user->ban($this->data['lang_error_comment_mild_swearing']);
                    }
                }
            }

            /* Check for strong swear words */
            if ($this->setting->get('strong_swear_words_enabled')) {
                if ($this->hasWord($comment, 'strong_swear_words')) {
                    if ($this->setting->get('strong_swear_words_action') == 'mask') {
                        if (!$is_preview) {
                            $this->request->post['cmtx_comment'] = $this->maskWord($comment, 'strong_swear_words');
                        }
                    } else if ($this->setting->get('strong_swear_words_action') == 'mask_approve') {
                        if (!$is_preview) {
                            $this->request->post['cmtx_comment'] = $this->maskWord($comment, 'strong_swear_words');

                            $this->approve .= $this->data['lang_error_comment_strong_swearing'] . "\r\n";
                        }
                    } else if ($this->setting->get('strong_swear_words_action') == 'error') {
                        $this->json['error']['comment'] = $this->data['lang_error_comment_strong_swearing'];
                    } else if ($this->setting->get('strong_swear_words_action') == 'approve') {
                        $this->approve .= $this->data['lang_error_comment_strong_swearing'] . "\r\n";
                    } else {
                        $this->json['result']['error'] = $this->data['lang_error_ban'];

                        $this->user->ban($this->data['lang_error_comment_strong_swearing']);
                    }
                }
            }

            /* Check for spam words */
            if ($this->setting->get('spam_words_enabled')) {
                if ($this->hasWord($comment, 'spam_words')) {
                    if ($this->setting->get('spam_words_action') == 'error') {
                        $this->json['error']['comment'] = $this->data['lang_error_comment_spam'];
                    } else if ($this->setting->get('spam_words_action') == 'approve') {
                        $this->approve .= $this->data['lang_error_comment_spam'] . "\r\n";
                    } else {
                        $this->json['result']['error'] = $this->data['lang_error_ban'];

                        $this->user->ban($this->data['lang_error_comment_spam']);
                    }
                }
            }

            /* Check for banned website */
            if ($this->setting->get('banned_websites_as_comment_enabled') && $this->hasWord($comment, 'banned_websites', false)) {
                if ($this->setting->get('banned_websites_as_comment_action') == 'error') {
                    $this->json['error']['comment'] = $this->data['lang_error_website_banned'];
                } else if ($this->setting->get('banned_websites_as_comment_action') == 'approve') {
                    $this->approve .= $this->data['lang_error_website_banned'] . "\r\n";
                } else {
                    $this->json['result']['error'] = $this->data['lang_error_ban'];

                    $this->user->ban($this->data['lang_error_website_banned']);
                }
            }

            /* Check for link */
            if ($this->setting->get('detect_link_in_comment_enabled') && $this->hasLink($comment)) {
                if ($this->setting->get('link_in_comment_action') == 'error') {
                    $this->json['error']['comment'] = $this->data['lang_error_comment_has_link'];
                } else if ($this->setting->get('link_in_comment_action') == 'approve') {
                    $this->approve .= $this->data['lang_error_comment_has_link'] . "\r\n";
                } else {
                    $this->json['result']['error'] = $this->data['lang_error_ban'];

                    $this->user->ban($this->data['lang_error_comment_has_link']);
                }
            }

            /* Check for image */
            if ($this->setting->get('approve_images') && $this->hasImage($comment)) {
                $this->approve .= $this->data['lang_error_comment_has_image'] . "\r\n";
            }

            /* Check for video */
            if ($this->setting->get('approve_videos') && $this->hasVideo($comment)) {
                $this->approve .= $this->data['lang_error_comment_has_video'] . "\r\n";
            }

            /* Check maximum smilies */
            if ($this->setting->get('enabled_smilies')) {
                if ($this->countSmilies($comment) > $this->setting->get('comment_maximum_smilies')) {
                    $this->json['error']['comment'] = sprintf($this->data['lang_error_comment_max_smilies'], $this->setting->get('comment_maximum_smilies'));
                }
            }

            /* Convert BB code to HTML */
            if ($this->setting->get('enabled_bb_code')) {
                $this->request->post['cmtx_comment'] = $this->addBBCode($this->request->post['cmtx_comment']);

                if ($this->variable->strpos($this->request->post['cmtx_comment'], 'cmtx-invalid-bb-code-link') !== false) {
                    $this->json['error']['comment'] = $this->data['lang_error_comment_invalid_link'];
                }
            }

            /* Check capitals (after BB Code because we don't want to include tags) */
            if ($this->setting->get('check_capitals_enabled') && $this->hasCapitals($this->request->post['cmtx_comment'])) {
                if ($this->setting->get('check_capitals_action') == 'error') {
                    $this->json['error']['comment'] = $this->data['lang_error_comment_has_capitals'];
                } else if ($this->setting->get('check_capitals_action') == 'approve') {
                    $this->approve .= $this->data['lang_error_comment_has_capitals'] . "\r\n";
                } else {
                    $this->json['result']['error'] = $this->data['lang_error_ban'];

                    $this->user->ban($this->data['lang_error_comment_has_capitals']);
                }
            }

            /* Convert web links (non-BB code) to HTML */
            if ($this->setting->get('comment_convert_links')) {
                $this->request->post['cmtx_comment'] = $this->convertLinks($this->request->post['cmtx_comment']);
            }

            /* Convert email links (non-BB code) to HTML */
            if ($this->setting->get('comment_convert_emails')) {
                $this->request->post['cmtx_comment'] = $this->convertEmails($this->request->post['cmtx_comment']);
            }

            /* Wrap each line in a paragraph tag */
            if ($this->setting->get('comment_line_breaks')) {
                $this->request->post['cmtx_comment'] = $this->addLineBreaks($this->request->post['cmtx_comment']);
            } else {
                $this->request->post['cmtx_comment'] = $this->removeLineBreaks($this->request->post['cmtx_comment']);
            }

            /* Purify the comment. Ensures properly balanced tags and neutralizes attacks. */
            $this->request->post['cmtx_comment'] = $this->purifyComment($this->request->post['cmtx_comment']);

            /* Finally remove any space at beginning and end */
            $this->request->post['cmtx_comment'] = trim($this->request->post['cmtx_comment']);

            /* Check comment length exceeds minimum */
            if ($this->getCommentDisplayLength($this->request->post['cmtx_comment']) < $this->setting->get('comment_minimum_characters')) {
                $this->json['error']['comment'] = $this->data['lang_error_comment_min_length'];
            }
        } else {
            $this->json['error']['comment'] = $this->data['lang_error_comment_empty'];
        }
    }

    public function validateHeadline()
    {
        if ($this->setting->get('enabled_headline') && empty($this->request->post['cmtx_reply_to'])) {
            if (isset($this->request->post['cmtx_headline']) && $this->request->post['cmtx_headline'] != '') {
                $headline = $this->security->decode($this->request->post['cmtx_headline']);

                /* Check minimum length */
                if ($this->validation->length($headline) < $this->setting->get('headline_minimum_characters')) {
                    $this->json['error']['headline'] = $this->data['lang_error_headline_min_length'];
                }

                /* Check minimum words */
                if ($this->countWords($headline) < $this->setting->get('headline_minimum_words')) {
                    $this->json['error']['headline'] = $this->data['lang_error_headline_min_words'];
                }

                /* Check maximum length */
                if ($this->validation->length($headline) > $this->setting->get('headline_maximum_characters')) {
                    $this->json['error']['headline'] = $this->data['lang_error_headline_max_length'];
                }

                /* Check capitals */
                if ($this->setting->get('check_capitals_enabled') && $this->hasCapitals($headline)) {
                    if ($this->setting->get('check_capitals_action') == 'error') {
                        $this->json['error']['headline'] = $this->data['lang_error_headline_has_capitals'];
                    } else if ($this->setting->get('check_capitals_action') == 'approve') {
                        $this->approve .= $this->data['lang_error_headline_has_capitals'] . "\r\n";
                    } else {
                        $json['result']['error'] = $this->data['lang_error_ban'];

                        $this->user->ban($this->data['lang_error_headline_has_capitals']);
                    }
                }

                /* Check repeats */
                if ($this->setting->get('check_repeats_enabled') && $this->hasRepeats($headline)) {
                    if ($this->setting->get('check_repeats_action') == 'error') {
                        $this->json['error']['headline'] = $this->data['lang_error_headline_has_repeats'];
                    } else if ($this->setting->get('check_repeats_action') == 'approve') {
                        $this->approve .= $this->data['lang_error_headline_has_repeats'] . "\r\n";
                    } else {
                        $json['result']['error'] = $this->data['lang_error_ban'];

                        $this->user->ban($this->data['lang_error_headline_has_repeats']);
                    }
                }

                /* Check for mild swear words */
                if ($this->setting->get('mild_swear_words_enabled')) {
                    if ($this->hasWord($headline, 'mild_swear_words')) {
                        if ($this->setting->get('mild_swear_words_action') == 'mask') {
                            if (!$is_preview) {
                                $this->request->post['cmtx_headline'] = $this->maskWord($headline, 'mild_swear_words');
                            }
                        } else if ($this->setting->get('mild_swear_words_action') == 'mask_approve') {
                            if (!$is_preview) {
                                $this->request->post['cmtx_headline'] = $this->maskWord($headline, 'mild_swear_words');

                                $this->approve .= $this->data['lang_error_headline_mild_swearing'] . "\r\n";
                            }
                        } else if ($this->setting->get('mild_swear_words_action') == 'error') {
                            $this->json['error']['headline'] = $this->data['lang_error_headline_mild_swearing'];
                        } else if ($this->setting->get('mild_swear_words_action') == 'approve') {
                            $this->approve .= $this->data['lang_error_headline_mild_swearing'] . "\r\n";
                        } else {
                            $json['result']['error'] = $this->data['lang_error_ban'];

                            $this->user->ban($this->data['lang_error_headline_mild_swearing']);
                        }
                    }
                }

                /* Check for strong swear words */
                if ($this->setting->get('strong_swear_words_enabled')) {
                    if ($this->hasWord($headline, 'strong_swear_words')) {
                        if ($this->setting->get('strong_swear_words_action') == 'mask') {
                            if (!$is_preview) {
                                $this->request->post['cmtx_headline'] = $this->maskWord($headline, 'strong_swear_words');
                            }
                        } else if ($this->setting->get('strong_swear_words_action') == 'mask_approve') {
                            if (!$is_preview) {
                                $this->request->post['cmtx_headline'] = $this->maskWord($headline, 'strong_swear_words');

                                $this->approve .= $this->data['lang_error_headline_strong_swearing'] . "\r\n";
                            }
                        } else if ($this->setting->get('strong_swear_words_action') == 'error') {
                            $this->json['error']['headline'] = $this->data['lang_error_headline_strong_swearing'];
                        } else if ($this->setting->get('strong_swear_words_action') == 'approve') {
                            $this->approve .= $this->data['lang_error_headline_strong_swearing'] . "\r\n";
                        } else {
                            $json['result']['error'] = $this->data['lang_error_ban'];

                            $this->user->ban($this->data['lang_error_headline_strong_swearing']);
                        }
                    }
                }

                /* Check for spam words */
                if ($this->setting->get('spam_words_enabled')) {
                    if ($this->hasWord($headline, 'spam_words')) {
                        if ($this->setting->get('spam_words_action') == 'error') {
                            $this->json['error']['headline'] = $this->data['lang_error_headline_spam'];
                        } else if ($this->setting->get('spam_words_action') == 'approve') {
                            $this->approve .= $this->data['lang_error_headline_spam'] . "\r\n";
                        } else {
                            $json['result']['error'] = $this->data['lang_error_ban'];

                            $this->user->ban($this->data['lang_error_headline_spam']);
                        }
                    }
                }

                /* Check for link */
                if ($this->setting->get('detect_link_in_headline_enabled') && $this->hasLink($headline)) {
                    if ($this->setting->get('link_in_headline_action') == 'error') {
                        $this->json['error']['headline'] = $this->data['lang_error_headline_has_link'];
                    } else if ($this->setting->get('link_in_headline_action') == 'approve') {
                        $this->approve .= $this->data['lang_error_headline_has_link'] . "\r\n";
                    } else {
                        $json['result']['error'] = $this->data['lang_error_ban'];

                        $this->user->ban($this->data['lang_error_headline_has_link']);
                    }
                }

                /* Check for banned website */
                if ($this->setting->get('banned_websites_as_headline_enabled') && $this->hasWord($headline, 'banned_websites', false)) {
                    if ($this->setting->get('banned_websites_as_headline_action') == 'error') {
                        $this->json['error']['headline'] = $this->data['lang_error_website_banned'];
                    } else if ($this->setting->get('banned_websites_as_headline_action') == 'approve') {
                        $this->approve .= $this->data['lang_error_website_banned'] . "\r\n";
                    } else {
                        $json['result']['error'] = $this->data['lang_error_ban'];

                        $this->user->ban($this->data['lang_error_website_banned']);
                    }
                }
            } else if ($this->setting->get('required_headline')) {
                $this->json['error']['headline'] = $this->data['lang_error_headline_empty'];
            } else {
                $this->request->post['cmtx_headline'] = '';
            }
        } else {
            $this->request->post['cmtx_headline'] = '';
        }
    }

    public function validateName($is_admin)
    {
        if (isset($this->request->post['cmtx_name']) && $this->request->post['cmtx_name'] != '') {
            $name = $this->security->decode($this->request->post['cmtx_name']);

            /* Relax name validation if provided by login info */
            if (isset($this->request->post['cmtx_login']) && $this->request->post['cmtx_login'] == '0') {
                if (!$this->isNameValid($name)) {
                    $this->json['error']['name'] = $this->data['lang_error_name_invalid'];
                }

                if (!$this->startsWithLetter($name)) {
                    $this->json['error']['name'] = $this->data['lang_error_name_start'];
                }
            }

            if ($this->validation->length($name) < 1 || $this->validation->length($name) > $this->setting->get('maximum_name')) {
                $this->json['error']['name'] = sprintf($this->data['lang_error_length'], 1, $this->setting->get('maximum_name'));
            }

            if ($this->setting->get('one_name_enabled') && !$this->isOneWord($name)) {
                $this->json['error']['name'] = $this->data['lang_error_name_one_word'];
            }

            if ($this->setting->get('fix_name_enabled')) {
                $this->request->post['cmtx_name'] = $this->variable->fixCase($this->request->post['cmtx_name']);
            }

            if ($this->setting->get('detect_link_in_name_enabled') && $this->hasLink($name)) {
                if ($this->setting->get('link_in_name_action') == 'error') {
                    $this->json['error']['name'] = $this->data['lang_error_name_has_link'];
                } else if ($this->setting->get('link_in_name_action') == 'approve') {
                    $this->approve .= $this->data['lang_error_name_has_link'] . "\r\n";
                } else {
                    $json['result']['error'] = $this->data['lang_error_ban'];

                    $this->user->ban($this->data['lang_error_name_has_link']);
                }
            }

            if ($this->setting->get('reserved_names_enabled') && !$is_admin && $this->hasWord($name, 'reserved_names')) {
                if ($this->setting->get('reserved_names_action') == 'error') {
                    $this->json['error']['name'] = $this->data['lang_error_name_reserved'];
                } else if ($this->setting->get('reserved_names_action') == 'approve') {
                    $this->approve .= $this->data['lang_error_name_reserved'] . "\r\n";
                } else {
                    $json['result']['error'] = $this->data['lang_error_ban'];

                    $this->user->ban($this->data['lang_error_name_reserved']);
                }
            }

            if ($this->setting->get('dummy_names_enabled') && $this->hasWord($name, 'dummy_names')) {
                if ($this->setting->get('dummy_names_action') == 'error') {
                    $this->json['error']['name'] = $this->data['lang_error_name_dummy'];
                } else if ($this->setting->get('dummy_names_action') == 'approve') {
                    $this->approve .= $this->data['lang_error_name_dummy'] . "\r\n";
                } else {
                    $json['result']['error'] = $this->data['lang_error_ban'];

                    $this->user->ban($this->data['lang_error_name_dummy']);
                }
            }

            if ($this->setting->get('banned_names_enabled') && $this->hasWord($name, 'banned_names')) {
                if ($this->setting->get('banned_names_action') == 'error') {
                    $this->json['error']['name'] = $this->data['lang_error_name_banned'];
                } else if ($this->setting->get('banned_names_action') == 'approve') {
                    $this->approve .= $this->data['lang_error_name_banned'] . "\r\n";
                } else {
                    $json['result']['error'] = $this->data['lang_error_ban'];

                    $this->user->ban($this->data['lang_error_name_banned']);
                }
            }
        } else {
            $this->json['error']['name'] = $this->data['lang_error_name_empty'];
        }
    }

    public function validateEmail($is_admin)
    {
        if ($this->setting->get('enabled_email')) {
            if (isset($this->request->post['cmtx_email']) && $this->request->post['cmtx_email'] != '') {
                $email = $this->security->decode($this->request->post['cmtx_email']);

                if (!$this->validation->isEmail($email)) {
                    $this->json['error']['email'] = $this->data['lang_error_email_invalid'];
                }

                if ($this->validation->length($email) < 1 || $this->validation->length($email) > $this->setting->get('maximum_email')) {
                    $this->json['error']['email'] = sprintf($this->data['lang_error_length'], 1, $this->setting->get('maximum_email'));
                }

                if ($this->security->isInjected($email)) {
                    $json['result']['error'] = $this->data['lang_error_ban'];

                    $this->user->ban($this->data['lang_error_email_injected']);
                }

                if ($this->setting->get('reserved_emails_enabled') && !$is_admin && $this->hasWord($email, 'reserved_emails', false)) {
                    if ($this->setting->get('reserved_emails_action') == 'error') {
                        $this->json['error']['email'] = $this->data['lang_error_email_reserved'];
                    } else if ($this->setting->get('reserved_emails_action') == 'approve') {
                        $this->approve .= $this->data['lang_error_email_reserved'] . "\r\n";
                    } else {
                        $json['result']['error'] = $this->data['lang_error_ban'];

                        $this->user->ban($this->data['lang_error_email_reserved']);
                    }
                }

                if ($this->setting->get('dummy_emails_enabled') && $this->hasWord($email, 'dummy_emails', false)) {
                    if ($this->setting->get('dummy_emails_action') == 'error') {
                        $this->json['error']['email'] = $this->data['lang_error_email_dummy'];
                    } else if ($this->setting->get('dummy_emails_action') == 'approve') {
                        $this->approve .= $this->data['lang_error_email_dummy'] . "\r\n";
                    } else {
                        $json['result']['error'] = $this->data['lang_error_ban'];

                        $this->user->ban($this->data['lang_error_email_dummy']);
                    }
                }

                if ($this->setting->get('banned_emails_enabled') && $this->hasWord($email, 'banned_emails', false)) {
                    if ($this->setting->get('banned_emails_action') == 'error') {
                        $this->json['error']['email'] = $this->data['lang_error_email_banned'];
                    } else if ($this->setting->get('banned_emails_action') == 'approve') {
                        $this->approve .= $this->data['lang_error_email_banned'] . "\r\n";
                    } else {
                        $json['result']['error'] = $this->data['lang_error_ban'];

                        $this->user->ban($this->data['lang_error_email_banned']);
                    }
                }
            } else if ($this->setting->get('required_email')) {
                $this->json['error']['email'] = $this->data['lang_error_email_empty'];
            } else {
                $this->request->post['cmtx_email'] = '';
            }
        } else {
            $this->request->post['cmtx_email'] = '';
        }
    }

    public function validateUser()
    {
        if (isset($this->request->post['cmtx_name']) && $this->request->post['cmtx_name'] != '') {
            if (isset($this->request->post['cmtx_email']) && $this->request->post['cmtx_email'] != '') {
                $this->account = $this->user->getUserByNameAndEmail($this->request->post['cmtx_name'], $this->request->post['cmtx_email']);

                if (!$this->account) {
                    if ($this->setting->get('unique_email_enabled')) {
                        if ($this->user->userExistsByEmail($this->request->post['cmtx_email'])) {
                            $this->json['error']['email'] = $this->data['lang_error_email_partial'];
                        }
                    }
                }
            } else {
                $this->account = $this->user->getUserByNameAndNoEmail($this->request->post['cmtx_name']);
            }

            if (!$this->account) {
                if ($this->setting->get('unique_name_enabled')) {
                    if ($this->user->userExistsByName($this->request->post['cmtx_name'])) {
                        $this->json['error']['name'] = $this->data['lang_error_name_partial'];
                    }
                }
            }
        }

        return $this->account;
    }

    public function validateRating($page_id)
    {
        if ($this->setting->get('enabled_rating') && empty($this->request->post['cmtx_reply_to'])) {
            if ($this->setting->get('repeat_rating') == 'hide' && $this->hasUserRated($page_id, $this->user->getIpAddress())) {
                $this->request->post['cmtx_rating'] = 0;

                $this->json['hide_rating'] = true;
            } else {
                if (isset($this->request->post['cmtx_rating']) && $this->request->post['cmtx_rating'] != '') {
                    $rating = $this->security->decode($this->request->post['cmtx_rating']);

                    if (!$this->isRatingValid($rating)) {
                        $this->json['error']['rating'] = $this->data['lang_error_rating_invalid'];
                    } else if ($this->setting->get('repeat_rating') == 'hide') {
                        $this->json['hide_rating'] = true;
                    }
                } else if ($this->setting->get('required_rating')) {
                    $this->json['error']['rating'] = $this->data['lang_error_rating_empty'];
                } else {
                    $this->request->post['cmtx_rating'] = 0;
                }
            }
        } else {
            $this->request->post['cmtx_rating'] = 0;
        }
    }

    public function validateWebsite($is_admin)
    {
        if ($this->setting->get('enabled_website')) {
            if (isset($this->request->post['cmtx_website']) && $this->request->post['cmtx_website'] != '') {
                $scheme = parse_url($this->request->post['cmtx_website'], PHP_URL_SCHEME);

                if ($scheme != 'http' && $scheme != 'https') {
                    $this->request->post['cmtx_website'] = 'http://' . $this->request->post['cmtx_website'];
                }

                $website = $this->security->decode($this->request->post['cmtx_website']);

                if ($this->setting->get('approve_websites')) {
                    $this->approve .= $this->data['lang_error_website_approve'] . "\r\n";
                }

                if (!$this->validation->isUrl($website)) {
                    $this->json['error']['website'] = $this->data['lang_error_website_invalid'];
                } else if ($this->setting->get('validate_website_ping') && !$this->canPingWebsite($website)) {
                    $this->json['error']['website'] = $this->data['lang_error_website_ping'];
                }

                if ($this->validation->length($website) < 1 || $this->validation->length($website) > $this->setting->get('maximum_website')) {
                    $this->json['error']['website'] = sprintf($this->data['lang_error_length'], 1, $this->setting->get('maximum_website'));
                }

                if ($this->setting->get('reserved_websites_enabled') && !$is_admin && $this->hasWord($website, 'reserved_websites', false)) {
                    if ($this->setting->get('reserved_websites_action') == 'error') {
                        $this->json['error']['website'] = $this->data['lang_error_website_reserved'];
                    } else if ($this->setting->get('reserved_websites_action') == 'approve') {
                        $this->approve .= $this->data['lang_error_website_reserved'] . "\r\n";
                    } else {
                        $this->json['result']['error'] = $this->data['lang_error_ban'];

                        $this->user->ban($this->data['lang_error_website_reserved']);
                    }
                }

                if ($this->setting->get('dummy_websites_enabled') && $this->hasWord($website, 'dummy_websites', false)) {
                    if ($this->setting->get('dummy_websites_action') == 'error') {
                        $this->json['error']['website'] = $this->data['lang_error_website_dummy'];
                    } else if ($this->setting->get('dummy_websites_action') == 'approve') {
                        $this->approve .= $this->data['lang_error_website_dummy'] . "\r\n";
                    } else {
                        $this->json['result']['error'] = $this->data['lang_error_ban'];

                        $this->user->ban($this->data['lang_error_website_dummy']);
                    }
                }

                if ($this->setting->get('banned_websites_as_website_enabled') && $this->hasWord($website, 'banned_websites', false)) {
                    if ($this->setting->get('banned_websites_as_website_action') == 'error') {
                        $this->json['error']['website'] = $this->data['lang_error_website_banned'];
                    } else if ($this->setting->get('banned_websites_as_website_action') == 'approve') {
                        $this->approve .= $this->data['lang_error_website_banned'] . "\r\n";
                    } else {
                        $this->json['result']['error'] = $this->data['lang_error_ban'];

                        $this->user->ban($this->data['lang_error_website_banned']);
                    }
                }
            } else if ($this->setting->get('required_website')) {
                $this->json['error']['website'] = $this->data['lang_error_website_empty'];
            } else {
                $this->request->post['cmtx_website'] = '';
            }
        } else {
            $this->request->post['cmtx_website'] = '';
        }
    }

    public function validateTown($is_admin)
    {
        if ($this->setting->get('enabled_town')) {
            if (isset($this->request->post['cmtx_town']) && $this->request->post['cmtx_town'] != '') {
                $town = $this->security->decode($this->request->post['cmtx_town']);

                if (!$this->isTownValid($town)) {
                    $this->json['error']['town'] = $this->data['lang_error_town_invalid'];
                }

                if (!$this->startsWithLetter($town)) {
                    $this->json['error']['town'] = $this->data['lang_error_town_start'];
                }

                if ($this->validation->length($town) < 1 || $this->validation->length($town) > $this->setting->get('maximum_town')) {
                    $this->json['error']['town'] = sprintf($this->data['lang_error_length'], 1, $this->setting->get('maximum_town'));
                }

                if ($this->setting->get('fix_town_enabled')) {
                    $this->request->post['cmtx_town'] = $this->variable->fixCase($this->request->post['cmtx_town']);
                }

                if ($this->setting->get('detect_link_in_town_enabled') && $this->hasLink($town)) {
                    if ($this->setting->get('link_in_town_action') == 'error') {
                        $this->json['error']['town'] = $this->data['lang_error_town_has_link'];
                    } else if ($this->setting->get('link_in_town_action') == 'approve') {
                        $this->approve .= $this->data['lang_error_town_has_link'] . "\r\n";
                    } else {
                        $this->json['result']['error'] = $this->data['lang_error_ban'];

                        $this->user->ban($this->data['lang_error_town_has_link']);
                    }
                }

                if ($this->setting->get('reserved_towns_enabled') && !$is_admin && $this->hasWord($town, 'reserved_towns')) {
                    if ($this->setting->get('reserved_towns_action') == 'error') {
                        $this->json['error']['town'] = $this->data['lang_error_town_reserved'];
                    } else if ($this->setting->get('reserved_towns_action') == 'approve') {
                        $this->approve .= $this->data['lang_error_town_reserved'] . "\r\n";
                    } else {
                        $this->json['result']['error'] = $this->data['lang_error_ban'];

                        $this->user->ban($this->data['lang_error_town_reserved']);
                    }
                }

                if ($this->setting->get('dummy_towns_enabled') && $this->hasWord($town, 'dummy_towns')) {
                    if ($this->setting->get('dummy_towns_action') == 'error') {
                        $this->json['error']['town'] = $this->data['lang_error_town_dummy'];
                    } else if ($this->setting->get('dummy_towns_action') == 'approve') {
                        $this->approve .= $this->data['lang_error_town_dummy'] . "\r\n";
                    } else {
                        $this->json['result']['error'] = $this->data['lang_error_ban'];

                        $this->user->ban($this->data['lang_error_town_dummy']);
                    }
                }

                if ($this->setting->get('banned_towns_enabled') && $this->hasWord($town, 'banned_towns')) {
                    if ($this->setting->get('banned_towns_action') == 'error') {
                        $this->json['error']['town'] = $this->data['lang_error_town_banned'];
                    } else if ($this->setting->get('banned_towns_action') == 'approve') {
                        $this->approve .= $this->data['lang_error_town_banned'] . "\r\n";
                    } else {
                        $this->json['result']['error'] = $this->data['lang_error_ban'];

                        $this->user->ban($this->data['lang_error_town_banned']);
                    }
                }
            } else if ($this->setting->get('required_town')) {
                $this->json['error']['town'] = $this->data['lang_error_town_empty'];
            } else {
                $this->request->post['cmtx_town'] = '';
            }
        } else {
            $this->request->post['cmtx_town'] = '';
        }
    }

    public function validateCountry()
    {
        if ($this->setting->get('enabled_country')) {
            if (isset($this->request->post['cmtx_country']) && $this->request->post['cmtx_country'] != '') {
                $this->country = $this->security->decode($this->request->post['cmtx_country']);

                if (!$this->geo->countryValid($this->country)) {
                    $this->json['error']['country'] = $this->data['lang_error_country_invalid'];
                }
            } else if ($this->setting->get('required_country')) {
                $this->json['error']['country'] = $this->data['lang_error_country_empty'];
            } else {
                $this->request->post['cmtx_country'] = 0;
            }
        } else {
            $this->request->post['cmtx_country'] = 0;
        }
    }

    public function validateState()
    {
        if ($this->setting->get('enabled_state')) {
            if (isset($this->request->post['cmtx_state']) && $this->request->post['cmtx_state'] != '') {
                $state = $this->security->decode($this->request->post['cmtx_state']);

                if (!$this->geo->stateValid($state, $this->country)) {
                    $this->json['error']['error']['state'] = $this->data['lang_error_state_invalid'];
                }
            } else if ($this->setting->get('required_state')) {
                $this->json['error']['error']['state'] = $this->data['lang_error_state_empty'];
            } else {
                $this->request->post['cmtx_state'] = 0;
            }
        } else {
            $this->request->post['cmtx_state'] = 0;
        }
    }

    public function validateQuestion()
    {
        if ($this->setting->get('enabled_question')) {
            if (isset($this->request->post['cmtx_answer']) && $this->request->post['cmtx_answer'] != '') {
                $answer = $this->security->decode($this->request->post['cmtx_answer']);

                if (isset($this->session->data['cmtx_question_id_' . $this->page->getId()])) {
                    $question_id = $this->session->data['cmtx_question_id_' . $this->page->getId()];

                    if (!$this->isAnswerValid($question_id, $answer)) {
                        $this->json['error']['answer'] = $this->data['lang_error_answer_invalid'];
                    }
                } else {
                    /* The session may have expired */
                    $this->json['error']['answer'] = $this->data['lang_error_question_empty'];
                }

                /* Generate a new question to answer */
                if (isset($this->json['error']['answer'])) {
                    $question = $this->getQuestion();

                    if ($question) {
                        $this->session->data['cmtx_question_id_' . $this->page->getId()] = $question['id'];

                        $this->json['question'] = $question['question'];
                    }
                }
            } else {
                $this->json['error']['answer'] = $this->data['lang_error_answer_empty'];
            }
        }
    }

    public function validateExtraFields($is_preview)
    {
        if ($this->setting->has('extra_fields_enabled') && $this->setting->get('extra_fields_enabled')) {
            $fields = $this->getExtraFields();

            foreach ($fields as $field) {
                $field_name = 'cmtx_field_' . $field['id'];

                if (isset($this->request->post[$field_name])) {
                    if ($field['is_required'] && $this->request->post[$field_name] == '') {
                        $this->json['error'][$field_name] = $this->data['lang_error_field_required'];
                    } else if ($this->request->post[$field_name]) {
                        $value = $this->security->decode($this->request->post[$field_name]);

                        if ($field['type'] == 'select') {
                            $values = explode(',', $field['values']);

                            if (!in_array($this->request->post[$field_name], $values)) {
                                $this->json['error'][$field_name] = $this->data['lang_error_field_invalid'];
                            }
                        } else if (in_array($field['type'], array('text', 'textarea'))) {
                            if ($this->validation->length($value) < $field['minimum']) {
                                $this->json['error'][$field_name] = $this->data['lang_error_field_min_length'];
                            }

                            if ($this->validation->length($value) > $field['maximum']) {
                                $this->json['error'][$field_name] = $this->data['lang_error_field_max_length'];
                            }

                            if ($field['validation']) {
                                if (!preg_match($this->security->decode($field['validation']), $value)) {
                                    $this->json['error'][$field_name] = $this->data['lang_error_field_invalid'];
                                }
                            }

                            if ($field['type'] == 'textarea') {
                                $this->request->post[$field_name] = $this->removeLineBreaks($this->request->post[$field_name]);
                            }
                        }

                        if ($is_preview) {
                            if ($field['display']) {
                                $this->extra_fields[$field['name']] = $this->request->post[$field_name];
                            }
                        } else {
                            $this->extra_fields['field_' . $field['id']] = $this->request->post[$field_name];
                        }
                    }
                } else {
                    $this->json['error'][$field_name] = $this->data['lang_error_field_required'];
                }
            }
        }
    }

    public function validateReCaptcha()
    {
        if ($this->setting->get('enabled_captcha') && $this->setting->get('captcha_type') == 'recaptcha' && (bool) ini_get('allow_url_fopen') && !isset($this->session->data['cmtx_captcha_complete_' . $this->page->getId()])) {
            if (isset($this->request->post['g-recaptcha-response'])) {
                $captcha = $this->request->post['g-recaptcha-response'];

                if ($captcha) {
                    $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $this->setting->get('recaptcha_private_key') . '&response=' . $captcha . '&remoteip=' . str_replace(' ', '%20', $this->user->getIpAddress()));

                    $response = json_decode($response);

                    if ($response->success === false) {
                        $this->json['error']['recaptcha'] = $this->data['lang_error_incorrect_recaptcha'];
                    } else {
                        $this->session->data['cmtx_captcha_complete_' . $this->page->getId()] = true;
                    }
                } else {
                    $this->json['error']['recaptcha'] = $this->data['lang_error_no_recaptcha'];
                }
            } else {
                $this->json['error']['recaptcha'] = $this->data['lang_error_no_recaptcha'];
            }
        }
    }

    public function validateImageCaptcha()
    {
        if ($this->setting->get('enabled_captcha') && $this->setting->get('captcha_type') == 'image' && extension_loaded('gd') && function_exists('imagettftext') && is_callable('imagettftext') && !isset($this->session->data['cmtx_captcha_complete_' . $this->page->getId()])) {
            if (!empty($this->request->post['cmtx_captcha'])) {
                if (!empty($this->session->data['cmtx_captcha_answer_' . $this->page->getId()])) {
                    if ($this->variable->strtoupper($this->request->post['cmtx_captcha']) != $this->variable->strtoupper($this->session->data['cmtx_captcha_answer_' . $this->page->getId()])) {
                        $this->json['error']['captcha'] = $this->data['lang_error_incorrect_captcha'];
                    } else {
                        $this->session->data['cmtx_captcha_complete_' . $this->page->getId()] = true;
                    }
                } else {
                    $this->json['error']['captcha'] = $this->data['lang_error_missing_captcha'];
                }
            } else {
                $this->json['error']['captcha'] = $this->data['lang_error_no_captcha'];
            }
        }
    }

    public function validateCaptcha()
    {
        if (isset($this->session->data['cmtx_captcha_complete_' . $this->page->getId()])) {
            $this->json['captcha_complete'] = true;
        }
    }

    public function validatePrivacy($is_preview)
    {
        if ($this->setting->get('enabled_privacy') && !isset($this->request->post['cmtx_privacy'])) {
            if (!$is_preview || ($is_preview && $this->setting->get('agree_to_preview'))) {
                $this->json['result']['error'] = $this->data['lang_error_agree_privacy'];
            }
        }
    }

    public function validateTerms($is_preview)
    {
        if ($this->setting->get('enabled_terms') && !isset($this->request->post['cmtx_terms'])) {
            if (!$is_preview || ($is_preview && $this->setting->get('agree_to_preview'))) {
                $this->json['result']['error'] = $this->data['lang_error_agree_terms'];
            }
        }
    }

    public function validateReply($quick_reply = false)
    {
        if ($this->setting->get('show_reply')) {
            if (isset($this->request->post['cmtx_reply_to']) && $this->request->post['cmtx_reply_to']) {
                if (!$this->comment->commentExists($this->request->post['cmtx_reply_to'])) {
                    $this->json['result']['error'] = $this->data['lang_error_reply_invalid'];
                }
            } else {
                if ($quick_reply) {
                    $this->json['result']['error'] = $this->data['lang_error_reply_required'];
                } else {
                    $this->request->post['cmtx_reply_to'] = 0;
                }
            }
        } else {
            $this->request->post['cmtx_reply_to'] = 0;
        }
    }

    public function validateUpload($is_preview)
    {
        if ($this->setting->get('enabled_upload') && isset($this->request->post['cmtx_upload']) && is_array($this->request->post['cmtx_upload'])) {
            if (!$this->json || ($this->json && (!isset($this->json['result']['error']) && !isset($this->json['error'])))) {
                if (count($this->request->post['cmtx_upload']) > $this->setting->get('maximum_upload_amount')) {
                    $this->json['result']['error'] = sprintf($this->data['lang_error_image_amount'], $this->setting->get('maximum_upload_amount'));
                } else {
                    if ($is_preview) { // don't upload if only a preview
                        foreach ($this->request->post['cmtx_upload'] as $base64) {
                            $this->uploads[] = array(
                                'image' => $base64
                            );
                        }
                    } else {
                        foreach ($this->request->post['cmtx_upload'] as $base64) {
                            $result = $this->createImageFromBase64($base64);

                            if (is_array($result)) {
                                $this->uploads[] = $result;
                            } else {
                                $this->json['result']['error'] = $result;
                            }
                        }

                        if ($this->setting->get('approve_uploads') && $this->uploads) {
                            $this->approve .= $this->data['lang_error_comment_has_upload'] . "\r\n";
                        }
                    }
                }
            }
        }
    }

    /* Checks if the comment contains repeating characters */
    private function hasRepeats($comment)
    {
        if (preg_match('/(.)\\1{' . ($this->setting->get('check_repeats_amount') - 1) . '}/u', $comment)) {
            return true;
        } else {
            return false;
        }
    }

    /* Checks if the comment contains too many capital letters */
    private function hasCapitals($comment)
    {
        $comment = preg_replace('/[^a-z]/i', '', $comment); // remove non-letters

        $number_of_letters = $this->validation->length($comment); // number of letters

        $number_of_capitals = $this->validation->length(preg_replace('/[^A-Z]/', '', $comment)); // number of capitals

        if ($number_of_letters > 3 && $number_of_capitals != 0) { // if check is appropriate
            $percentage_of_capitals = ($number_of_capitals / $number_of_letters) * 100; // percentage of capitals

            if ($percentage_of_capitals >= $this->setting->get('check_capitals_percentage')) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /* Checks if the comment contains a long word */
    private function hasLongWord($comment)
    {
        $comment = str_replace("\r\n", ' ', $comment);

        $words = explode(' ', $comment);

        foreach ($words as $word) {
            if ($this->validation->length($word) >= $this->setting->get('comment_long_word')) { // if word length is longer than allowed length
                return true;
            }
        }

        return false;
    }

    /* Get the approx length of the comment as it appears on the screen */
    private function getCommentDisplayLength($comment)
    {
        $comment = preg_replace('/:[A-Z]+:/i', '', $comment); // remove smilies
        $comment = $this->security->decode($comment); // decode HTML entities
        $comment = strip_tags($comment); // strip any tags
        $comment = trim($comment); // remove any space at beginning and end

        return $this->validation->length($comment);
    }

    /* Count the number of lines */
    private function countLines($comment)
    {
        return substr_count($comment, "\r\n") + 1;
    }

    /* Count the number of words */
    private function countWords($comment)
    {
        return count(explode(' ', $comment));
    }

    /* Count the number of smilies */
    private function countSmilies($comment)
    {
        return preg_match_all('/:[A-Z]+:/i', $comment, $matches);
    }

    /* Convert new line endings to line breaks */
    private function addLineBreaks($comment)
    {
        $paragraphs = '';

        foreach (explode("\r\n", $comment) as $line) {
            if (trim($line)) {
                $paragraphs .= '<p>' . $line . '</p>';
            }
        }

        return $paragraphs;
    }

    /* Remove new line endings */
    private function removeLineBreaks($comment)
    {
        return str_replace("\r\n", ' ', $comment);
    }

    /* Convert BB code to HTML */
    private function addBBCode($comment)
    {
        $tags = $this->loadWord('main/form');

        if ($this->setting->get('enabled_bb_code_bold')) {
            $comment = preg_replace('/' . preg_quote($tags['lang_tag_bb_code_bold_start'], '/') . '\s*' . preg_quote($tags['lang_tag_bb_code_bold_end'], '/') . '/is', '', $comment); // remove bold tags with nothing visible inside

            $comment = preg_replace('/' . preg_quote($tags['lang_tag_bb_code_bold_start'], '/') . '(.*?)' . preg_quote($tags['lang_tag_bb_code_bold_end'], '/') . '/is', '<b>$1</b>', $comment);
        }

        if ($this->setting->get('enabled_bb_code_italic')) {
            $comment = preg_replace('/' . preg_quote($tags['lang_tag_bb_code_italic_start'], '/') . '\s*' . preg_quote($tags['lang_tag_bb_code_italic_end'], '/') . '/is', '', $comment);

            $comment = preg_replace('/' . preg_quote($tags['lang_tag_bb_code_italic_start'], '/') . '(.*?)' . preg_quote($tags['lang_tag_bb_code_italic_end'], '/') . '/is', '<i>$1</i>', $comment);
        }

        if ($this->setting->get('enabled_bb_code_underline')) {
            $comment = preg_replace('/' . preg_quote($tags['lang_tag_bb_code_underline_start'], '/') . '\s*' . preg_quote($tags['lang_tag_bb_code_underline_end'], '/') . '/is', '', $comment);

            $comment = preg_replace('/' . preg_quote($tags['lang_tag_bb_code_underline_start'], '/') . '(.*?)' . preg_quote($tags['lang_tag_bb_code_underline_end'], '/') . '/is', '<u>$1</u>', $comment);
        }

        if ($this->setting->get('enabled_bb_code_strike')) {
            $comment = preg_replace('/' . preg_quote($tags['lang_tag_bb_code_strike_start'], '/') . '\s*' . preg_quote($tags['lang_tag_bb_code_strike_end'], '/') . '/is', '', $comment);

            $comment = preg_replace('/' . preg_quote($tags['lang_tag_bb_code_strike_start'], '/') . '(.*?)' . preg_quote($tags['lang_tag_bb_code_strike_end'], '/') . '/is', '<del>$1</del>', $comment);
        }

        if ($this->setting->get('enabled_bb_code_superscript')) {
            $comment = preg_replace('/' . preg_quote($tags['lang_tag_bb_code_superscript_start'], '/') . '\s*' . preg_quote($tags['lang_tag_bb_code_superscript_end'], '/') . '/is', '', $comment);

            $comment = preg_replace('/' . preg_quote($tags['lang_tag_bb_code_superscript_start'], '/') . '(.*?)' . preg_quote($tags['lang_tag_bb_code_superscript_end'], '/') . '/is', '<sup>$1</sup>', $comment);
        }

        if ($this->setting->get('enabled_bb_code_subscript')) {
            $comment = preg_replace('/' . preg_quote($tags['lang_tag_bb_code_subscript_start'], '/') . '\s*' . preg_quote($tags['lang_tag_bb_code_subscript_end'], '/') . '/is', '', $comment);

            $comment = preg_replace('/' . preg_quote($tags['lang_tag_bb_code_subscript_start'], '/') . '(.*?)' . preg_quote($tags['lang_tag_bb_code_subscript_end'], '/') . '/is', '<sub>$1</sub>', $comment);
        }

        if ($this->setting->get('enabled_bb_code_code')) {
            $comment = preg_replace('/' . preg_quote($tags['lang_tag_bb_code_code_start'], '/') . '\s*' . preg_quote($tags['lang_tag_bb_code_code_end'], '/') . '/is', '', $comment);

            $comment = preg_replace_callback('/' . preg_quote($tags['lang_tag_bb_code_code_start'], '/') . '(.*?)' . preg_quote($tags['lang_tag_bb_code_code_end'], '/') . '/is', array($this, 'callbackCode'), $comment);
        }

        if ($this->setting->get('enabled_bb_code_php')) {
            $comment = preg_replace('/' . preg_quote($tags['lang_tag_bb_code_php_start'], '/') . '\s*' . preg_quote($tags['lang_tag_bb_code_php_end'], '/') . '/is', '', $comment);

            $comment = preg_replace_callback('/' . preg_quote($tags['lang_tag_bb_code_php_start'], '/') . '(.*?)' . preg_quote($tags['lang_tag_bb_code_php_end'], '/') . '/is', array($this, 'callbackPhp'), $comment);
        }

        if ($this->setting->get('enabled_bb_code_quote')) {
            $comment = preg_replace('/' . preg_quote($tags['lang_tag_bb_code_quote_start'], '/') . '\s*' . preg_quote($tags['lang_tag_bb_code_quote_end'], '/') . '/is', '', $comment);

            $comment = preg_replace('/' . preg_quote($tags['lang_tag_bb_code_quote_start'], '/') . '(.*?)' . preg_quote($tags['lang_tag_bb_code_quote_end'], '/') . '/is', '<div class="cmtx_quote_box">$1</div>', $comment);
        }

        if ($this->setting->get('enabled_bb_code_line')) {
            $comment = str_ireplace($tags['lang_tag_bb_code_line'], '<hr>', $comment);
        }

        if ($this->setting->get('enabled_bb_code_bullet')) {
            $comment = str_ireplace($tags['lang_tag_bb_code_bullet_1'] . "\r\n", '<ul>', $comment);
            $comment = str_ireplace($tags['lang_tag_bb_code_bullet_2'], '<li>', $comment);
            $comment = str_ireplace($tags['lang_tag_bb_code_bullet_3'] . "\r\n", '</li>', $comment);
            $comment = str_ireplace($tags['lang_tag_bb_code_bullet_4'], '</ul>', $comment);
        }

        if ($this->setting->get('enabled_bb_code_numeric')) {
            $comment = str_ireplace($tags['lang_tag_bb_code_numeric_1'] . "\r\n", '<ol>', $comment);
            $comment = str_ireplace($tags['lang_tag_bb_code_numeric_2'], '<li>', $comment);
            $comment = str_ireplace($tags['lang_tag_bb_code_numeric_3'] . "\r\n", '</li>', $comment);
            $comment = str_ireplace($tags['lang_tag_bb_code_numeric_4'], '</ol>', $comment);
        }

        if ($this->setting->get('enabled_bb_code_link')) {
            $comment = preg_replace_callback('/' . preg_quote($tags['lang_tag_bb_code_link_1'], '/') . '(.*?)' . preg_quote($tags['lang_tag_bb_code_link_4'], '/') . '/is', array($this, 'callbackLinkOne'), $comment);

            $comment = preg_replace_callback('/' . preg_quote($tags['lang_tag_bb_code_link_2'], '/') . '(.*?)' . preg_quote($tags['lang_tag_bb_code_link_3'], '/') . '(.*?)' . preg_quote($tags['lang_tag_bb_code_link_4'], '/') . '/is', array($this, 'callbackLinkTwo'), $comment);
        }

        if ($this->setting->get('enabled_bb_code_email')) {
            $comment = preg_replace_callback('/' . preg_quote($tags['lang_tag_bb_code_email_1'], '/') . '(.*?)' . preg_quote($tags['lang_tag_bb_code_email_4'], '/') . '/is', array($this, 'callbackEmailOne'), $comment);

            $comment = preg_replace_callback('/' . preg_quote($tags['lang_tag_bb_code_email_2'], '/') . '(.*?)' . preg_quote($tags['lang_tag_bb_code_email_3'], '/') . '(.*?)' . preg_quote($tags['lang_tag_bb_code_email_4'], '/') . '/is', array($this, 'callbackEmailTwo'), $comment);
        }

        if ($this->setting->get('enabled_bb_code_image')) {
            $comment = preg_replace_callback('/' . preg_quote($tags['lang_tag_bb_code_image_1'], '/') . '(.*?)' . preg_quote($tags['lang_tag_bb_code_image_2'], '/') . '/is', array($this, 'callbackImage'), $comment);
        }

        if ($this->setting->get('enabled_bb_code_youtube')) {
            $comment = preg_replace_callback('/' . preg_quote($tags['lang_tag_bb_code_youtube_1'], '/') . '(.*?)' . preg_quote($tags['lang_tag_bb_code_youtube_2'], '/') . '/is', array($this, 'callbackYouTube'), $comment);
        }

        return $comment;
    }

    /* Build attributes for links */
    private function getLinkAttributes()
    {
        $link_attributes = '';

        if ($this->setting->get('comment_links_new_window')) { // if links should open in a new window
            $link_attributes .= ' target="_blank"';
        }

        if ($this->setting->get('comment_links_nofollow')) { // if links should contain nofollow attribute
            $link_attributes .= ' rel="nofollow"';
        }

        return $link_attributes;
    }

    private function callbackCode(array $matches)
    {
        $code = $matches[1];

        $code = preg_replace("/(\r\n){2,}/", '<br><br>', $code); // replace instances of 2 or more \r\n with <br>s

        $code = str_ireplace("\r\n", '<br>', $code); // replace remaining line breaks with <br>s

        $code = '<div class="cmtx_code_box">' . $code . '</div>';

        return $code;
    }

    private function callbackPhp(array $matches)
    {
        $php = $matches[1];

        $php = preg_replace("/(\r\n){2,}/", '<br><br>', $php); // replace instances of 2 or more \r\n with <br>s

        $php = str_ireplace("\r\n", '<br>', $php); // replace remaining line breaks with <br>s

        $php = '<div class="cmtx_php_box lang-php">' . $php . '</div>';

        return $php;
    }

    private function callbackLinkOne(array $matches)
    {
        if (filter_var($matches[1], FILTER_VALIDATE_URL)) {
            return '<a href="' . $matches[1] . '"' . $this->getLinkAttributes() . '>' . $matches[1] . '</a>';
        } else {
            return 'cmtx-invalid-bb-code-link';
        }
    }

    private function callbackLinkTwo(array $matches)
    {
        if (filter_var($matches[1], FILTER_VALIDATE_URL)) {
            return '<a href="' . $matches[1] . '"' . $this->getLinkAttributes() . '>' . $matches[2] . '</a>';
        } else {
            return 'cmtx-invalid-bb-code-link';
        }
    }

    private function callbackEmailOne(array $matches)
    {
        if (filter_var($matches[1], FILTER_VALIDATE_EMAIL)) {
            return '<a href="mailto:' . $matches[1] . '"' . $this->getLinkAttributes() . '>' . $matches[1] . '</a>';
        } else {
            return 'cmtx-invalid-bb-code-link';
        }
    }

    private function callbackEmailTwo(array $matches)
    {
        if (filter_var($matches[1], FILTER_VALIDATE_EMAIL)) {
            return '<a href="mailto:' . $matches[1] . '"' . $this->getLinkAttributes() . '>' . $matches[2] . '</a>';
        } else {
            return 'cmtx-invalid-bb-code-link';
        }
    }

    private function callbackImage(array $matches)
    {
        if (filter_var($matches[1], FILTER_VALIDATE_URL)) {
            return '<img src="' . $matches[1] . '">';
        } else {
            return 'cmtx-invalid-bb-code-link';
        }
    }

    private function callbackYouTube(array $matches)
    {
        $url = $matches[1];

        if (filter_var($url, FILTER_VALIDATE_URL)) {
            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
                $video_id = $match[1];

                return '<div class="cmtx_youtube_container"><iframe src="//www.youtube.com/embed/' . $video_id . '" frameborder="0" allowfullscreen></iframe></div>';
            } else {
                return 'cmtx-invalid-bb-code-link';
            }
        } else {
            return 'cmtx-invalid-bb-code-link';
        }
    }

    /* Convert web links (non-BB code) to HTML */
    private function convertLinks($comment)
    {
        $comment = preg_replace("/(^|[\n ])([\w]*?)((ht|f)tp(s)?:\/\/[\w]+[^ \,\"\n\r\t<]*)/is", "$1$2<a href=\"$3\"" . $this->getLinkAttributes() . ">$3</a>", $comment);

        $comment = preg_replace("/(^|[\n ])([\w]*?)((www|ftp)\.[^ \,\"\t\n\r<]*)/is", "$1$2<a href=\"http://$3\"" . $this->getLinkAttributes() . ">$3</a>", $comment);

        return $comment;
    }

    /* Convert email links (non-BB code) to HTML */
    private function convertEmails($comment)
    {
        $comment = preg_replace('/(^|[\n ])([\w]*?)([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})/im', '<a href="mailto:\\3" ' . $this->getLinkAttributes() . '>\\3</a>', $comment);

        return $comment;
    }

    /* Purify the comment. Ensures properly balanced tags and neutralizes attacks. */
    private function purifyComment($comment)
    {
        if (!function_exists('htmLawed')) {
            require_once CMTX_DIR_3RDPARTY . 'htmlawed/htmlawed.php';
        }

        $comment = htmLawed($comment);

        return $comment;
    }

    /* Gets a random question to verify the user is human */
    public function getQuestion()
    {
        $questions = $this->getQuestions();

        if ($questions) {
            $random_key = array_rand($questions, 1);

            return $questions[$random_key];
        } else {
            return false;
        }
    }

    /* Gets all questions */
    private function getQuestions()
    {
        $questions = $this->cache->get('getquestions_' . $this->setting->get('language'));

        if ($questions !== false) {
            return $questions;
        }

        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "questions` WHERE `language` = '" . $this->db->escape($this->setting->get('language')) . "'");

        $questions = $this->db->rows($query);

        if (!$questions) {
            $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "questions` WHERE `language` = 'english'");

            $questions = $this->db->rows($query);
        }

        $this->cache->set('getquestions_' . $this->setting->get('language'), $questions);

        return $questions;
    }

    /*
     * Checks if the name contains only valid characters
     * Letters, ampersand, hyphen, apostrophe, period, space, numbers, extended hyphen
     * \p{L} (any kind of letter from any language)
     */
    private function isNameValid($name)
    {
        if (preg_match('/^[\p{L}&\-\'. 0-9–]+$/u', $name)) {
            return true;
        } else {
            return false;
        }
    }

    /* Checks if the rating is valid */
    private function isRatingValid($rating)
    {
        if (in_array($rating, array('1', '2', '3', '4', '5'))) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Checks if the town contains only valid characters
     * Letters, ampersand, hyphen, apostrophe, period, space
     * \p{L} (any kind of letter from any language)
     */
    private function isTownValid($town)
    {
        if (preg_match('/^[\p{L}&\-\'. ]+$/u', $town)) {
            return true;
        } else {
            return false;
        }
    }

    /* Checks if the answer is valid */
    private function isAnswerValid($question_id, $answer)
    {
        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "questions` WHERE `id` = '" . (int) $question_id . "'");

        $result = $this->db->row($query);

        if ($result) {
            $user_answer = $this->variable->strtolower($answer);

            $real_answer = $this->variable->strtolower($result['answer']);

            if (in_array($user_answer, explode('|', $real_answer))) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /*
     * Checks if the website exists and isn't simply made up
     * Gets the HTTP status code of the website
     */
    private function canPingWebsite($website)
    {
        if (extension_loaded('curl')) {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Commentics');
            curl_setopt($ch, CURLOPT_URL, $website);

            curl_exec($ch);

            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);

            if (!in_array($http_code, array(200, 301, 302))) {
                return false;
            }
        }

        return true;
    }

    /*
     * Checks if the entry starts with a letter
     * \p{L} (any kind of letter from any language)
     */
    private function startsWithLetter($entry)
    {
        if (preg_match('/^[\p{L}]+/u', $entry)) {
            return true;
        } else {
            return false;
        }
    }

    /* Checks if the entry is only one word */
    private function isOneWord($entry)
    {
        if (count(explode(' ', $entry)) == 1) {
            return true;
        } else {
            return false;
        }
    }

    /* Checks if the entry contains a link */
    private function hasLink($entry)
    {
        $list = $this->getList('detect_links');

        $line = strtok($list, "\r\n");

        while ($line !== false) {
            $link = preg_quote($line, '/'); // escape any special characters

            $regexp = "/$link/i"; // i = case-insensitive

            /* Exclude images and YouTube videos */
            if (preg_match($regexp, $entry) && !preg_match('/.*\[IMAGE\].*' . $link . '.*\[\/IMAGE\].*/i', $entry) && !preg_match('/.*\[YOUTUBE\].*' . $link . '.*\[\/YOUTUBE\].*/i', $entry)) {
                return true;
            }

            $line = strtok("\r\n");
        }

        return false;
    }

    /* Checks if the comment contains an image */
    private function hasImage($comment)
    {
        $tag = $this->loadWord('main/form', 'lang_tag_bb_code_image_1');

        $found = stripos($comment, $tag);

        if ($found !== false) {
            return true;
        } else {
            return false;
        }
    }

    /* Checks if the comment contains a video */
    private function hasVideo($comment)
    {
        $tag = $this->loadWord('main/form', 'lang_tag_bb_code_youtube_1');

        $found = stripos($comment, $tag);

        if ($found !== false) {
            return true;
        } else {
            return false;
        }
    }

    /* Checks if the entry contains a word */
    private function hasWord($entry, $list, $boundary = true)
    {
        $list = $this->getList($list);

        $line = strtok($list, "\r\n");

        while ($line !== false) {
            $word = preg_quote($line, '/'); // escape any special characters

            $word = str_ireplace('\*', '[^ .,]*', $word); // allow use of wildcard symbol

            if ($boundary) {
                $regexp = "/\b$word\b/i"; // pattern (b = word boundary, i = case-insensitive)
            } else {
                $regexp = "/$word/i"; // pattern (i = case-insensitive)
            }

            if (preg_match($regexp, $entry)) {
                return true;
            }

            $line = strtok("\r\n");
        }

        return false;
    }

    /* Masks swear words in the entry */
    private function maskWord($entry, $list, $boundary = true)
    {
        $list = $this->getList($list);

        $line = strtok($list, "\r\n");

        while ($line !== false) {
            $word = preg_quote($line, '/'); // escape any special characters

            $word = str_ireplace('\*', '[^ .,]*', $word); // allow use of wildcard symbol

            if ($boundary) {
                $regexp = "/\b$word\b/i"; // pattern (b = word boundary, i = case-insensitive)
            } else {
                $regexp = "/$word/i"; // pattern (i = case-insensitive)
            }

            $entry = preg_replace($regexp, $this->setting->get('swear_word_masking'), $entry);

            $line = strtok("\r\n");
        }

        return $entry;
    }

    /* Gets a list from the database */
    private function getList($type)
    {
        $query = $this->db->query("SELECT `text` FROM `" . CMTX_DB_PREFIX . "data` WHERE `type` = '" . $this->db->escape($type) . "'");

        $result = $this->db->row($query);

        return $result['text'];
    }

    /* Checks if the user has a confirmed subscription for this page */
    public function subscriptionExists($user_id, $page_id)
    {
        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "subscriptions` WHERE `user_id` = '" . (int) $user_id . "' AND `page_id` = '" . (int) $page_id . "' AND `is_confirmed` = '1'"))) {
            return true;
        } else {
            return false;
        }
    }

    /* Checks whether the user has any unconfirmed subscriptions */
    public function userHasSubscriptionAttempt($user_id)
    {
        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "subscriptions` WHERE `user_id` = '" . (int) $user_id . "' AND `is_confirmed` = '0'"))) {
            return true;
        } else {
            return false;
        }
    }

    /* Checks whether the IP address has any unconfirmed subscriptions */
    public function ipHasSubscriptionAttempt($ip_address)
    {
        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "subscriptions` WHERE `ip_address` = '" . $this->db->escape($ip_address) . "' AND `is_confirmed` = '0'"))) {
            return true;
        } else {
            return false;
        }
    }

    /* Adds a new subscription in the database */
    public function addSubscription($user_id, $page_id, $token, $ip_address)
    {
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "subscriptions` SET `user_id` = '" . (int) $user_id . "', `page_id` = '" . (int) $page_id . "', `token` = '" . $this->db->escape($token) . "', `is_confirmed` = '0', `ip_address` = '" . $this->db->escape($ip_address) . "', `date_modified` = NOW(), `date_added` = NOW()");

        return $this->db->insertId();
    }

    /* Checks if the time since the user's last comment is less than the minimum delay */
    private function isFloodingDelay($table, $page_id)
    {
        /* Get the time of the last comment (if any) by the current user */
        if ($this->setting->get('flood_control_delay_all_pages')) { // for all the pages
            $query = $this->db->query("SELECT `date_added` FROM `" . CMTX_DB_PREFIX . $table . "` WHERE `ip_address` = '" . $this->db->escape($this->user->getIpAddress()) . "' ORDER BY `date_added` DESC LIMIT 1");
        } else { // for the current page
            $query = $this->db->query("SELECT `date_added` FROM `" . CMTX_DB_PREFIX . $table . "` WHERE `ip_address` = '" . $this->db->escape($this->user->getIpAddress()) . "' AND `page_id` = '" . (int) $page_id . "' ORDER BY `date_added` DESC LIMIT 1");
        }

        $result = $this->db->row($query);

        /* If a previous comment by the current user was found */
        if ($result) {
            $time = strtotime($result['date_added']);

            $difference = time() - $time;

            /* If the time since the last comment is less than the minimum waiting time */
            if ($difference < $this->setting->get('flood_control_delay_time')) {
                return true;
            }
        }

        return false;
    }

    /* Checks if the number of recent comments by the user exceeds the maximum amount */
    private function isFloodingMaximum($table, $page_id)
    {
        $earlier = date('Y-m-d H:i:s', time() - (3600 * $this->setting->get('flood_control_maximum_period')));

        /* Count the number of comments (if any) within past period by the current user */
        if ($this->setting->get('flood_control_maximum_all_pages')) { // for all the pages
            $query = $this->db->query("SELECT COUNT(*) AS `amount` FROM `" . CMTX_DB_PREFIX . $table . "` WHERE `ip_address` = '" . $this->db->escape($this->user->getIpAddress()) . "' AND `date_added` > '" . $this->db->escape($earlier) . "'");
        } else { // for the current page
            $query = $this->db->query("SELECT COUNT(*) AS `amount` FROM `" . CMTX_DB_PREFIX . $table . "`` WHERE `ip_address` = '" . $this->db->escape($this->user->getIpAddress()) . "' AND `page_id` = '" . (int) $page_id . "' AND `date_added` > '" . $this->db->escape($earlier) . "'");
        }

        $result = $this->db->row($query);

        /* If the number of comments exceeds the maximum amount */
        if ($result['amount'] >= $this->setting->get('flood_control_maximum_amount')) {
            return true;
        }

        return false;
    }

    /* Checks if the user has previously rated the page */
    public function hasUserRated($page_id)
    {
        if ($this->db->numRows($this->db->query("SELECT `id` FROM `" . CMTX_DB_PREFIX . "comments` WHERE `page_id` = '" . (int) $page_id . "' AND `ip_address` = '" . $this->db->escape($this->user->getIpAddress()) . "' AND `rating` != '0'"))) {
            return true;
        }

        if ($this->db->numRows($this->db->query("SELECT `id` FROM `" . CMTX_DB_PREFIX . "ratings` WHERE `page_id` = '" . (int) $page_id . "' AND `ip_address` = '" . $this->db->escape($this->user->getIpAddress()) . "'"))) {
            return true;
        }

        return false;
    }

    /* Checks if the user has previously posted a comment which has been approved by the administrator */
    private function hasUserPreviouslyPostedApprovedComment($user_id)
    {
        if ($this->db->numRows($this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "comments` WHERE `user_id` = '" . (int) $user_id . "' AND `is_approved` = '1'"))) {
            return true;
        } else {
            return false;
        }
    }

    /* Checks if Akismet reports the comment as spam */
    private function isAkismetSpam($ip_address, $page_url, $name, $email, $website, $comment)
    {
        $url = 'https://' . $this->setting->get('akismet_key') . '.rest.akismet.com/1.1/comment-check';

        ini_set('user_agent', 'Commentics');

        $data = array(
            'blog'                 => $this->setting->get('site_url'),
            'user_ip'              => $ip_address,
            'user_agent'           => $this->security->decode($this->user->getUserAgent()),
            'referrer'             => (isset($this->request->server['HTTP_REFERER']) ? $this->security->decode($this->request->server['HTTP_REFERER']) : ''),
            'permalink'            => $this->security->decode($page_url),
            'comment_type'         => 'comment',
            'comment_author'       => $this->security->decode($name),
            'comment_author_email' => $this->security->decode($email),
            'comment_author_url'   => $this->security->decode($website),
            'comment_content'      => $this->security->decode($comment),
            'blog_charset'         => 'UTF-8'
        );

        if ($this->setting->get('akismet_logging')) {
            $this->log->setFilename('akismet');

            $this->log->write('Posting to Akismet');

            $this->log->write('URL: ' . $url);

            $this->log->write($data);
        }

        $data = http_build_query($data);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Commentics');
        curl_setopt($ch, CURLOPT_URL, $url);

        $response = curl_exec($ch);

        if ($this->setting->get('akismet_logging')) {
            if (curl_errno($ch)) {
                $this->log->write('cURL error: ' . curl_errno($ch));
            }

            $this->log->write($response);
        }

        curl_close($ch);

        if ($response != 'false') {
            return true;
        } else {
            return false;
        }
    }

    private function createImageFromBase64($base64)
    {
        $responses = $this->loadWord('main/form');

        /* Perform a very rough check for if the image is over 100 MB */
        if (($this->estimateSizeFromBase64($base64) / pow(1024, 2)) > 100) {
            return $responses['lang_error_image_size'];
        }

        if (!is_writable(CMTX_DIR_UPLOAD)) {
            return $responses['lang_error_upload_writable'];
        }

        $folder = 'comment/' . date('Y') . '/' . date('m');

        if (!is_dir(CMTX_DIR_UPLOAD . $folder)) {
            if (!mkdir(CMTX_DIR_UPLOAD . $folder, 0777, true)) {
                return $responses['lang_error_folder_create'];
            }
        }

        $image_data = base64_decode(preg_replace('/^data:image\/[^;]+;base64,/', '', $base64));

        if (!$image_data) {
            return $responses['lang_error_image_data'];
        }

        $mime_type = $this->estimateMimeTypeFromBase64($base64);

        $allowed_mime_types = array(
            'image/jpeg',
            'image/png',
            'image/gif'
        );

        if (!in_array($mime_type, $allowed_mime_types)) {
            return $responses['lang_error_image_type'];
        }

        switch ($mime_type) {
            case 'image/jpeg':
                $extension = 'jpg';
                break;
            case 'image/png':
                $extension = 'png';
                break;
            case 'image/gif':
                $extension = 'gif';
                break;
            default:
                $extension = 'jpg';
        }

        do {
            $filename = $this->variable->random();
        } while (file_exists(CMTX_DIR_UPLOAD . $folder . '/' . $filename . '.' . $extension));

        $location = CMTX_DIR_UPLOAD . $folder . '/' . $filename . '.' . $extension;

        if (file_put_contents($location, $image_data)) {
            if (filesize($location) > ($this->setting->get('maximum_upload_size') * pow(1024, 2))) {
                @unlink($location);

                return $responses['lang_error_image_size'];
            } else {
                return array(
                    'folder'    => $folder,
                    'filename'  => $filename,
                    'extension' => $extension,
                    'mime_type' => $mime_type,
                    'file_size' => filesize($location)
                );
            }
        } else {
            return $responses['lang_error_image_create'];
        }
    }

    private function estimateMimeTypeFromBase64($base64)
    {
        if (function_exists('getimagesizefromstring') && is_callable('getimagesizefromstring')) {
            $image_data = base64_decode(preg_replace('/^data:image\/[^;]+;base64,/', '', $base64));

            $image_size = @getimagesizefromstring($image_data);

            if (is_array($image_size) && isset($image_size['mime'])) {
                return $image_size['mime'];
            }
        } else if (class_exists('finfo')) {
            $image_data = base64_decode(preg_replace('/^data:image\/[^;]+;base64,/', '', $base64));

            $finfo = new \finfo(FILEINFO_MIME_TYPE);

            $mime_type = $finfo->buffer($image_data);

            if ($mime_type) {
                return $mime_type;
            }
        } else {
            $image_data = explode(':', substr($base64, 0, strpos($base64, ';')));

            if (is_array($image_data) && isset($image_data[1])) {
                return $image_data[1];
            }
        }

        return false;
    }

    private function estimateSizeFromBase64($base64)
    {
        return (int) (strlen(rtrim($base64, '=')) * 3 / 4);
    }

    public function getExtraField($field_id)
    {
        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "fields` WHERE `id` = '" . (int) $field_id . "' AND `is_enabled` = '1'");

        $result = $this->db->row($query);

        return $result;
    }

    private function getExtraFields()
    {
        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "fields` WHERE `is_enabled` = '1' ORDER BY `sort` ASC");

        $result = $this->db->rows($query);

        return $result;
    }

    public function hexColorAllocate($image, $hex, $transparency = false) {
        $hex = ltrim($hex, '#');

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        if ($transparency) {
            return imagecolorallocatealpha($image, $r, $g, $b, 75);
        } else {
            return imagecolorallocate($image, $r, $g, $b);
        }
    }

    public function needsApproval($is_admin, $user, $page, $ip_address) {
        if ($is_admin) { // admin comments don't need to be approved
            $this->approve = '';

            $this->notes = $this->data['lang_text_moderate_admin'];
        } else if ($user && $user['moderate'] == 'always') { // the user's moderation setting has secondary precedence
            $this->approve = $this->data['lang_text_moderate_user_y'];
        } else if ($user && $user['moderate'] == 'never') {
            $this->approve = '';

            $this->notes = $this->data['lang_text_moderate_user_n'];
        } else if ($page['moderate'] == 'always') {
            $this->approve = $this->data['lang_text_moderate_page_y'];
        } else if ($page['moderate'] == 'never') {
            $this->approve = '';

            $this->notes = $this->data['lang_text_moderate_page_n'];
        } else if ($this->approve) {

        } else if ($this->setting->get('approve_comments')) {
            if ($user && $this->setting->get('trust_previous_users')) {
                if ($this->hasUserPreviouslyPostedApprovedComment($user['id'])) {
                    $this->approve = '';

                    $this->notes = $this->data['lang_text_moderate_user_previous'];
                } else {
                    $this->approve = $this->data['lang_text_moderate_all'];
                }
            } else {
                $this->approve = $this->data['lang_text_moderate_all'];
            }
        } else if ($this->setting->has('akismet_enabled') && $this->setting->get('akismet_enabled') && extension_loaded('curl')) {
            if ($this->isAkismetSpam($ip_address, $page['url'], $this->request->post['cmtx_name'], $this->request->post['cmtx_email'], $this->request->post['cmtx_website'], $this->request->post['cmtx_comment'])) {
                $this->approve = $this->data['lang_text_moderate_akismet_y'];
            } else {
                $this->approve = '';

                $this->notes = $this->data['lang_text_moderate_akismet_n'];
            }
        }

        if ($this->approve) {
            $this->notes = rtrim($this->approve, "\r\n");
        }

        return $this->approve;
    }
}
