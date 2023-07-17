<?php
namespace Commentics;

class Notify
{
    private $comment;
    private $db;
    private $email;
    private $page;
    private $security;
    private $setting;
    private $variable;
    private $users = array();
    private $parents = array();
    private $sent_to = 0;

    public function __construct($registry)
    {
        $this->comment  = $registry->get('comment');
        $this->db       = $registry->get('db');
        $this->email    = $registry->get('email');
        $this->page     = $registry->get('page');
        $this->security = $registry->get('security');
        $this->setting  = $registry->get('setting');
        $this->variable = $registry->get('variable');
    }

    public function adminNotifyCommentSuccess($id)
    {
        /* Get admins who have requested to be notified of a successful comment */
        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "admins` WHERE `receive_email_comment_success` = '1' AND `is_enabled` = '1'");

        $admins = $this->db->rows($query);

        if ($admins) {
            $comment = $this->comment->getComment($id); // get the details of the comment

            $email = $this->email->get('comment_success');

            foreach ($admins as $admin) {
                $subject = $this->security->decode($email['subject']);

                $text = str_ireplace('[username]', $admin['username'], $email['text']);
                $text = str_ireplace('[page reference]', $comment['page_reference'], $text);
                $text = str_ireplace('[page url]', $this->prepareUrl($comment['page_url']), $text);
                $text = str_ireplace('[' . $this->setting->get('purpose'). ' url]', $this->prepareUrl($this->comment->buildCommentUrl($comment['id'], $comment['page_url'])), $text);
                $text = str_ireplace('[poster]', $comment['name'], $text);
                $text = str_ireplace('[admin link]', $this->email->getAdminLink(), $text);
                $text = str_ireplace('[signature]', $this->email->getSignatureText($this->page->getSiteId()), $text);
                $text = $this->security->decode($text);
                $text = str_ireplace('[' . $this->setting->get('purpose') . ']', $this->prepareComment($comment['comment'], 'text'), $text);

                $html = str_ireplace('[username]', $admin['username'], $email['html']);
                $html = str_ireplace('[page reference]', $comment['page_reference'], $html);
                $html = str_ireplace('[page url]', $this->prepareUrl($comment['page_url']), $html);
                $html = str_ireplace('[' . $this->setting->get('purpose'). ' url]', $this->prepareUrl($this->comment->buildCommentUrl($comment['id'], $comment['page_url'])), $html);
                $html = str_ireplace('[poster]', $comment['name'], $html);
                $html = str_ireplace('[admin link]', $this->email->getAdminLink(), $html);
                $html = str_ireplace('[signature]', $this->email->getSignatureHtml($this->page->getSiteId()), $html);
                $html = $this->security->decode($html);
                $html = str_ireplace('[' . $this->setting->get('purpose') . ']', $this->prepareComment($comment['comment'], 'html'), $html);

                $this->email->send($admin['email'], null, $subject, $text, $html, $admin['format'], $this->page->getSiteId());
            }
        }
    }

    public function adminNotifyCommentApprove($id)
    {
        /* Get admins who have requested to be notified of a comment that needs approval */
        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "admins` WHERE `receive_email_comment_approve` = '1' AND `is_enabled` = '1'");

        $admins = $this->db->rows($query);

        if ($admins) {
            $comment = $this->comment->getComment($id); // get the details of the comment

            $email = $this->email->get('comment_approve');

            foreach ($admins as $admin) {
                $subject = $this->security->decode($email['subject']);

                $text = str_ireplace('[username]', $admin['username'], $email['text']);
                $text = str_ireplace('[page reference]', $comment['page_reference'], $text);
                $text = str_ireplace('[page url]', $this->prepareUrl($comment['page_url']), $text);
                $text = str_ireplace('[' . $this->setting->get('purpose'). ' url]', $this->prepareUrl($this->comment->buildCommentUrl($comment['id'], $comment['page_url'])), $text);
                $text = str_ireplace('[poster]', $comment['name'], $text);
                $text = str_ireplace('[reason]', $this->prepareReasons($comment['notes'], 'text'), $text);
                $text = str_ireplace('[admin link]', $this->email->getAdminLink(), $text);
                $text = str_ireplace('[signature]', $this->email->getSignatureText($this->page->getSiteId()), $text);
                $text = $this->security->decode($text);
                $text = str_ireplace('[' . $this->setting->get('purpose') . ']', $this->prepareComment($comment['comment'], 'text'), $text);

                $html = str_ireplace('[username]', $admin['username'], $email['html']);
                $html = str_ireplace('[page reference]', $comment['page_reference'], $html);
                $html = str_ireplace('[page url]', $this->prepareUrl($comment['page_url']), $html);
                $html = str_ireplace('[' . $this->setting->get('purpose'). ' url]', $this->prepareUrl($this->comment->buildCommentUrl($comment['id'], $comment['page_url'])), $html);
                $html = str_ireplace('[poster]', $comment['name'], $html);
                $html = str_ireplace('[reason]', $this->prepareReasons($comment['notes'], 'html'), $html);
                $html = str_ireplace('[admin link]', $this->email->getAdminLink(), $html);
                $html = str_ireplace('[signature]', $this->email->getSignatureHtml($this->page->getSiteId()), $html);
                $html = $this->security->decode($html);
                $html = str_ireplace('[' . $this->setting->get('purpose') . ']', $this->prepareComment($comment['comment'], 'html'), $html);

                $this->email->send($admin['email'], null, $subject, $text, $html, $admin['format'], $this->page->getSiteId());
            }
        }
    }

    public function adminNotifyCommentFlag($id)
    {
        /* Get admins who have requested to be notified of a comment that is flagged */
        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "admins` WHERE `receive_email_flag` = '1' AND `is_enabled` = '1'");

        $admins = $this->db->rows($query);

        if ($admins) {
            $comment = $this->comment->getComment($id); // get the details of the comment

            $email = $this->email->get('flag');

            foreach ($admins as $admin) {
                $subject = $this->security->decode(str_ireplace('[username]', $admin['username'], $email['subject']));

                $text = str_ireplace('[username]', $admin['username'], $email['text']);
                $text = str_ireplace('[page reference]', $comment['page_reference'], $text);
                $text = str_ireplace('[page url]', $this->prepareUrl($comment['page_url']), $text);
                $text = str_ireplace('[' . $this->setting->get('purpose'). ' url]', $this->prepareUrl($this->comment->buildCommentUrl($comment['id'], $comment['page_url'])), $text);
                $text = str_ireplace('[poster]', $comment['name'], $text);
                $text = str_ireplace('[admin link]', $this->email->getAdminLink(), $text);
                $text = str_ireplace('[signature]', $this->email->getSignatureText($this->page->getSiteId()), $text);
                $text = $this->security->decode($text);
                $text = str_ireplace('[' . $this->setting->get('purpose') . ']', $this->prepareComment($comment['comment'], 'text'), $text);

                $html = str_ireplace('[username]', $admin['username'], $email['html']);
                $html = str_ireplace('[page reference]', $comment['page_reference'], $html);
                $html = str_ireplace('[page url]', $this->prepareUrl($comment['page_url']), $html);
                $html = str_ireplace('[' . $this->setting->get('purpose'). ' url]', $this->prepareUrl($this->comment->buildCommentUrl($comment['id'], $comment['page_url'])), $html);
                $html = str_ireplace('[poster]', $comment['name'], $html);
                $html = str_ireplace('[admin link]', $this->email->getAdminLink(), $html);
                $html = str_ireplace('[signature]', $this->email->getSignatureHtml($this->page->getSiteId()), $html);
                $html = $this->security->decode($html);
                $html = str_ireplace('[' . $this->setting->get('purpose') . ']', $this->prepareComment($comment['comment'], 'html'), $html);

                $this->email->send($admin['email'], null, $subject, $text, $html, $admin['format'], $this->page->getSiteId());
            }
        }
    }

    public function adminNotifyCommentEdit($id)
    {
        /* Get admins who have requested to be notified of a comment that is edited */
        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "admins` WHERE `receive_email_edit` = '1' AND `is_enabled` = '1'");

        $admins = $this->db->rows($query);

        if ($admins) {
            $comment = $this->comment->getComment($id); // get the details of the comment

            $email = $this->email->get('edit');

            foreach ($admins as $admin) {
                $subject = $this->security->decode(str_ireplace('[username]', $admin['username'], $email['subject']));

                $text = str_ireplace('[username]', $admin['username'], $email['text']);
                $text = str_ireplace('[page reference]', $comment['page_reference'], $text);
                $text = str_ireplace('[page url]', $this->prepareUrl($comment['page_url']), $text);
                $text = str_ireplace('[' . $this->setting->get('purpose'). ' url]', $this->prepareUrl($this->comment->buildCommentUrl($comment['id'], $comment['page_url'])), $text);
                $text = str_ireplace('[poster]', $comment['name'], $text);
                $text = str_ireplace('[admin link]', $this->email->getAdminLink(), $text);
                $text = str_ireplace('[signature]', $this->email->getSignatureText($this->page->getSiteId()), $text);
                $text = $this->security->decode($text);
                $text = str_ireplace('[' . $this->setting->get('purpose') . ']', $this->prepareComment($comment['comment'], 'text'), $text);

                $html = str_ireplace('[username]', $admin['username'], $email['html']);
                $html = str_ireplace('[page reference]', $comment['page_reference'], $html);
                $html = str_ireplace('[page url]', $this->prepareUrl($comment['page_url']), $html);
                $html = str_ireplace('[' . $this->setting->get('purpose'). ' url]', $this->prepareUrl($this->comment->buildCommentUrl($comment['id'], $comment['page_url'])), $html);
                $html = str_ireplace('[poster]', $comment['name'], $html);
                $html = str_ireplace('[admin link]', $this->email->getAdminLink(), $html);
                $html = str_ireplace('[signature]', $this->email->getSignatureHtml($this->page->getSiteId()), $html);
                $html = $this->security->decode($html);
                $html = str_ireplace('[' . $this->setting->get('purpose') . ']', $this->prepareComment($comment['comment'], 'html'), $html);

                $this->email->send($admin['email'], null, $subject, $text, $html, $admin['format'], $this->page->getSiteId());
            }
        }
    }

    public function adminNotifyCommentDelete($id)
    {
        /* Get admins who have requested to be notified of a comment that is deleted */
        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "admins` WHERE `receive_email_delete` = '1' AND `is_enabled` = '1'");

        $admins = $this->db->rows($query);

        if ($admins) {
            $comment = $this->comment->getComment($id); // get the details of the comment

            $email = $this->email->get('delete');

            foreach ($admins as $admin) {
                $subject = $this->security->decode(str_ireplace('[username]', $admin['username'], $email['subject']));

                $text = str_ireplace('[username]', $admin['username'], $email['text']);
                $text = str_ireplace('[page reference]', $comment['page_reference'], $text);
                $text = str_ireplace('[page url]', $this->prepareUrl($comment['page_url']), $text);
                $text = str_ireplace('[' . $this->setting->get('purpose'). ' url]', $this->prepareUrl($this->comment->buildCommentUrl($comment['id'], $comment['page_url'])), $text);
                $text = str_ireplace('[poster]', $comment['name'], $text);
                $text = str_ireplace('[admin link]', $this->email->getAdminLink(), $text);
                $text = str_ireplace('[signature]', $this->email->getSignatureText($this->page->getSiteId()), $text);
                $text = $this->security->decode($text);
                $text = str_ireplace('[' . $this->setting->get('purpose') . ']', $this->prepareComment($comment['comment'], 'text'), $text);

                $html = str_ireplace('[username]', $admin['username'], $email['html']);
                $html = str_ireplace('[page reference]', $comment['page_reference'], $html);
                $html = str_ireplace('[page url]', $this->prepareUrl($comment['page_url']), $html);
                $html = str_ireplace('[' . $this->setting->get('purpose'). ' url]', $this->prepareUrl($this->comment->buildCommentUrl($comment['id'], $comment['page_url'])), $html);
                $html = str_ireplace('[poster]', $comment['name'], $html);
                $html = str_ireplace('[admin link]', $this->email->getAdminLink(), $html);
                $html = str_ireplace('[signature]', $this->email->getSignatureHtml($this->page->getSiteId()), $html);
                $html = $this->security->decode($html);
                $html = str_ireplace('[' . $this->setting->get('purpose') . ']', $this->prepareComment($comment['comment'], 'html'), $html);

                $this->email->send($admin['email'], null, $subject, $text, $html, $admin['format'], $this->page->getSiteId());
            }
        }
    }

    /* Notify the admin that there is a newer version available */
    public function adminNotifyNewVersion($installed, $newest)
    {
        /* Get super admins */
        $query = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "admins` WHERE `is_super` = '1' AND `is_enabled` = '1'");

        $admins = $this->db->rows($query);

        if ($admins) {
            $email = $this->email->get('new_version');

            foreach ($admins as $admin) {
                $subject = $this->security->decode($email['subject']);

                $text = str_ireplace('[username]', $admin['username'], $email['text']);
                $text = str_ireplace('[installed version]', 'v' . $installed, $text);
                $text = str_ireplace('[newest version]', 'v' . $newest, $text);
                $text = str_ireplace('[admin link]', $this->email->getAdminLink(), $text);
                $text = str_ireplace('[signature]', $this->email->getSignatureText(), $text);
                $text = $this->security->decode($text);

                $html = str_ireplace('[username]', $admin['username'], $email['html']);
                $html = str_ireplace('[installed version]', 'v' . $installed, $html);
                $html = str_ireplace('[newest version]', 'v' . $newest, $html);
                $html = str_ireplace('[admin link]', $this->email->getAdminLink(), $html);
                $html = str_ireplace('[signature]', $this->email->getSignatureHtml(), $html);
                $html = $this->security->decode($html);

                $this->email->send($admin['email'], null, $subject, $text, $html, $admin['format']);
            }
        }
    }

    /* Notify the user that their comment is approved */
    public function approvalNotification($id)
    {
        $comment = $this->comment->getComment($id); // get the details of the comment

        if ($comment['is_approved']) { // sanity check
            return;
        }

        if (!$comment['email']) { // check that the user has an email address
            return;
        }

        if (!$comment['to_all'] && !$comment['to_approve']) { // only notify the user if they have requested it
            return;
        }

        /* Check that the user has a confirmed subscription for the page */
        $query = $this->db->query("SELECT *
                                   FROM `" . CMTX_DB_PREFIX . "subscriptions`
                                   WHERE `user_id` = '" . (int) $comment['user_id'] . "'
                                   AND `page_id` = '" . (int) $comment['page_id'] . "'
                                   AND `is_confirmed` = '1'");

        if (!$this->db->numRows($query)) {
            return;
        }

        $email = $this->email->get('user_comment_approved');

        $subject = $this->security->decode(str_ireplace('[name]', $comment['name'], $email['subject']));

        $text = str_ireplace('[name]', $comment['name'], $email['text']);
        $text = str_ireplace('[page reference]', $comment['page_reference'], $text);
        $text = str_ireplace('[page url]', $this->prepareUrl($comment['page_url']), $text);
        $text = str_ireplace('[' . $this->setting->get('purpose'). ' url]', $this->prepareUrl($this->comment->buildCommentUrl($comment['id'], $comment['page_url'])), $text);
        $text = str_ireplace('[user url]', $this->prepareUrl($this->buildUserUrl($comment['token'])), $text);
        $text = str_ireplace('[signature]', $this->email->getSignatureText($this->page->getSiteId()), $text);
        $text = $this->security->decode($text);
        $text = str_ireplace('[' . $this->setting->get('purpose') . ']', $this->prepareComment($comment['comment'], 'text'), $text);

        $html = str_ireplace('[name]', $comment['name'], $email['html']);
        $html = str_ireplace('[page reference]', $comment['page_reference'], $html);
        $html = str_ireplace('[page url]', $this->prepareUrl($comment['page_url']), $html);
        $html = str_ireplace('[' . $this->setting->get('purpose'). ' url]', $this->prepareUrl($this->comment->buildCommentUrl($comment['id'], $comment['page_url'])), $html);
        $html = str_ireplace('[user url]', $this->prepareUrl($this->buildUserUrl($comment['token'])), $html);
        $html = str_ireplace('[signature]', $this->email->getSignatureHtml($this->page->getSiteId()), $html);
        $html = $this->security->decode($html);
        $html = str_ireplace('[' . $this->setting->get('purpose') . ']', $this->prepareComment($comment['comment'], 'html'), $html);

        $this->email->send($comment['email'], $comment['name'], $subject, $text, $html, $comment['format'], $this->page->getSiteId());
    }

    /* Request confirmation from the user that they want to be subscribed */
    public function subscriberConfirmation($format, $name, $to_email, $page_reference, $page_url, $user_token, $subscription_token)
    {
        $email = $this->email->get('subscriber_confirmation');

        $subject = $this->security->decode($email['subject']);

        $text = str_ireplace('[name]', $name, $email['text']);
        $text = str_ireplace('[page reference]', $page_reference, $text);
        $text = str_ireplace('[page url]', $this->prepareUrl($page_url), $text);
        $text = str_ireplace('[confirmation link]', $this->prepareUrl($this->buildSubscriptionConfirmationUrl($user_token, $subscription_token)), $text);
        $text = str_ireplace('[signature]', $this->email->getSignatureText($this->page->getSiteId()), $text);
        $text = $this->security->decode($text);

        $html = str_ireplace('[name]', $name, $email['html']);
        $html = str_ireplace('[page reference]', $page_reference, $html);
        $html = str_ireplace('[page url]', $this->prepareUrl($page_url), $html);
        $html = str_ireplace('[confirmation link]', $this->prepareUrl($this->buildSubscriptionConfirmationUrl($user_token, $subscription_token)), $html);
        $html = str_ireplace('[signature]', $this->email->getSignatureHtml($this->page->getSiteId()), $html);
        $html = $this->security->decode($html);

        $this->email->send($to_email, $name, $subject, $text, $html, $format, $this->page->getSiteId());
    }

    /* Notify subscribers of a new comment */
    public function subscriberNotification($id)
    {
        /* Get all user subscribers and store as property (stops users from receiving more than one email) */
        $query = $this->db->query("SELECT `id` FROM `" . CMTX_DB_PREFIX . "users`
                                   WHERE `email` != ''
                                   AND (`to_all` = '1' OR `to_admin` = '1' OR `to_reply` = '1')");

        $users = $this->db->rows($query);

        foreach ($users as $user) {
            array_push($this->users, $user['id']);
        }

        $comment = $this->comment->getComment($id); // get the details of the comment

        if ($comment['is_sent']) { // sanity check
            return;
        }

        $this->users = array_diff($this->users, array($comment['user_id'])); // don't notify the poster of the comment

        if ($comment['reply_to']) { // if the comment has at least one parent
            $this->getParents($comment['reply_to']); // get every parent

            $this->notifySubscribersReply($comment); // notify parent users that they have a reply
        }

        if ($comment['is_admin']) { // if the comment was made by an administrator
            $this->notifySubscribersAdmin($comment); // notify users that an administrator has posted
        } else {
            $this->notifySubscribersBasic($comment); // otherwise notify users with a generic email
        }

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "comments` SET `is_sent` = '1', `sent_to` = '" . (int) $this->sent_to . "', `date_modified` = NOW() WHERE `id` = '" . (int) $id . "'");
    }

    /* Gets user IDs of parent comments */
    private function getParents($reply_to)
    {
        $query = $this->db->query("SELECT `u`.`id` AS `user_id`, `c`.`reply_to` AS `reply_to`
                                   FROM `" . CMTX_DB_PREFIX . "comments` `c`
                                   LEFT JOIN `" . CMTX_DB_PREFIX . "users` `u` ON `c`.`user_id` = `u`.`id`
                                   WHERE `c`.`id` = '" . (int) $reply_to . "'");

        $parent = $this->db->row($query);

        if (!in_array($parent['user_id'], $this->parents)) { // if the user is not already stored
            array_push($this->parents, $parent['user_id']); // store the user
        }

        if ($parent['reply_to']) { // if the parent has a parent
            $this->getParents($parent['reply_to']); // re-call this method until no parent
        }
    }

    private function notifySubscribersReply($comment)
    {
        /* Get subscribers who have requested a reply notification for the comment's page and whose subscription is confirmed */
        $query = $this->db->query("SELECT `u`.*, `p`.`reference` AS `page_reference`, `p`.`url` AS `page_url`
                                   FROM `" . CMTX_DB_PREFIX . "users` `u`
                                   LEFT JOIN `" . CMTX_DB_PREFIX . "subscriptions` `s` ON `u`.`id` = `s`.`user_id`
                                   LEFT JOIN `" . CMTX_DB_PREFIX . "pages` `p` ON `s`.`page_id` = `p`.`id`
                                   WHERE `u`.`email` != ''
                                   AND (`u`.`to_all` = '1' OR `u`.`to_reply` = '1')
                                   AND `u`.`id` IN (" . $this->db->escape(implode(',', $this->parents)) . ")
                                   AND `s`.`page_id` = '" . (int) $comment['page_id'] . "'
                                   AND `s`.`is_confirmed` = '1'");

        $users = $this->db->rows($query);

        if ($users) {
            $email = $this->email->get('subscriber_notification_reply');

            foreach ($users as $user) {
                if (in_array($user['id'], $this->users)) { // if the user has not already received an email
                    $subject = $this->security->decode(str_ireplace('[name]', $user['name'], $email['subject']));

                    $text = str_ireplace('[name]', $user['name'], $email['text']);
                    $text = str_ireplace('[page reference]', $user['page_reference'], $text);
                    $text = str_ireplace('[page url]', $this->prepareUrl($user['page_url']), $text);
                    $text = str_ireplace('[' . $this->setting->get('purpose'). ' url]', $this->prepareUrl($this->comment->buildCommentUrl($comment['id'], $user['page_url'])), $text);
                    $text = str_ireplace('[poster]', $comment['name'], $text);
                    $text = str_ireplace('[user url]', $this->prepareUrl($this->buildUserUrl($user['token'])), $text);
                    $text = str_ireplace('[signature]', $this->email->getSignatureText($this->page->getSiteId()), $text);
                    $text = $this->security->decode($text);
                    $text = str_ireplace('[' . $this->setting->get('purpose') . ']', $this->prepareComment($comment['comment'], 'text'), $text);

                    $html = str_ireplace('[name]', $user['name'], $email['html']);
                    $html = str_ireplace('[page reference]', $user['page_reference'], $html);
                    $html = str_ireplace('[page url]', $this->prepareUrl($user['page_url']), $html);
                    $html = str_ireplace('[' . $this->setting->get('purpose'). ' url]', $this->prepareUrl($this->comment->buildCommentUrl($comment['id'], $user['page_url'])), $html);
                    $html = str_ireplace('[poster]', $comment['name'], $html);
                    $html = str_ireplace('[user url]', $this->prepareUrl($this->buildUserUrl($user['token'])), $html);
                    $html = str_ireplace('[signature]', $this->email->getSignatureHtml($this->page->getSiteId()), $html);
                    $html = $this->security->decode($html);
                    $html = str_ireplace('[' . $this->setting->get('purpose') . ']', $this->prepareComment($comment['comment'], 'html'), $html);

                    $this->email->send($user['email'], $user['name'], $subject, $text, $html, $user['format'], $this->page->getSiteId());

                    $this->sent_to++;

                    $this->users = array_diff($this->users, array($user['id'])); // update the user list so that this user is not emailed again
                }
            }
        }
    }

    private function notifySubscribersAdmin($comment)
    {
        /* Get subscribers who have requested an admin notification for the comment's page and whose subscription is confirmed */
        $query = $this->db->query("SELECT `u`.*, `p`.`reference` AS `page_reference`, `p`.`url` AS `page_url`
                                   FROM `" . CMTX_DB_PREFIX . "users` `u`
                                   LEFT JOIN `" . CMTX_DB_PREFIX . "subscriptions` `s` ON `u`.`id` = `s`.`user_id`
                                   LEFT JOIN `" . CMTX_DB_PREFIX . "pages` `p` ON `s`.`page_id` = `p`.`id`
                                   WHERE `u`.`email` != ''
                                   AND (`u`.`to_all` = '1' OR `u`.`to_admin` = '1')
                                   AND `s`.`page_id` = '" . (int) $comment['page_id'] . "'
                                   AND `s`.`is_confirmed` = '1'");

        $users = $this->db->rows($query);

        if ($users) {
            $email = $this->email->get('subscriber_notification_admin');

            foreach ($users as $user) {
                if (in_array($user['id'], $this->users)) { // if the user has not already received an email
                    $subject = $this->security->decode(str_ireplace('[name]', $user['name'], $email['subject']));

                    $text = str_ireplace('[name]', $user['name'], $email['text']);
                    $text = str_ireplace('[page reference]', $user['page_reference'], $text);
                    $text = str_ireplace('[page url]', $this->prepareUrl($user['page_url']), $text);
                    $text = str_ireplace('[' . $this->setting->get('purpose'). ' url]', $this->prepareUrl($this->comment->buildCommentUrl($comment['id'], $user['page_url'])), $text);
                    $text = str_ireplace('[poster]', $comment['name'], $text);
                    $text = str_ireplace('[user url]', $this->prepareUrl($this->buildUserUrl($user['token'])), $text);
                    $text = str_ireplace('[signature]', $this->email->getSignatureText($this->page->getSiteId()), $text);
                    $text = $this->security->decode($text);
                    $text = str_ireplace('[' . $this->setting->get('purpose') . ']', $this->prepareComment($comment['comment'], 'text'), $text);

                    $html = str_ireplace('[name]', $user['name'], $email['html']);
                    $html = str_ireplace('[page reference]', $user['page_reference'], $html);
                    $html = str_ireplace('[page url]', $this->prepareUrl($user['page_url']), $html);
                    $html = str_ireplace('[' . $this->setting->get('purpose'). ' url]', $this->prepareUrl($this->comment->buildCommentUrl($comment['id'], $user['page_url'])), $html);
                    $html = str_ireplace('[poster]', $comment['name'], $html);
                    $html = str_ireplace('[user url]', $this->prepareUrl($this->buildUserUrl($user['token'])), $html);
                    $html = str_ireplace('[signature]', $this->email->getSignatureHtml($this->page->getSiteId()), $html);
                    $html = $this->security->decode($html);
                    $html = str_ireplace('[' . $this->setting->get('purpose') . ']', $this->prepareComment($comment['comment'], 'html'), $html);

                    $this->email->send($user['email'], $user['name'], $subject, $text, $html, $user['format'], $this->page->getSiteId());

                    $this->sent_to++;

                    $this->users = array_diff($this->users, array($user['id'])); // update the user list so that this user is not emailed again
                }
            }
        }
    }

    private function notifySubscribersBasic($comment)
    {
        /* Get subscribers who have requested a notification for the comment's page and whose subscription is confirmed */
        $query = $this->db->query("SELECT `u`.*, `p`.`reference` AS `page_reference`, `p`.`url` AS `page_url`
                                   FROM `" . CMTX_DB_PREFIX . "users` `u`
                                   LEFT JOIN `" . CMTX_DB_PREFIX . "subscriptions` `s` ON `u`.`id` = `s`.`user_id`
                                   LEFT JOIN `" . CMTX_DB_PREFIX . "pages` `p` ON `s`.`page_id` = `p`.`id`
                                   WHERE `u`.`email` != ''
                                   AND `u`.`to_all` = '1'
                                   AND `s`.`page_id` = '" . (int) $comment['page_id'] . "'
                                   AND `s`.`is_confirmed` = '1'");

        $users = $this->db->rows($query);

        if ($users) {
            $email = $this->email->get('subscriber_notification_basic');

            foreach ($users as $user) {
                if (in_array($user['id'], $this->users)) { // if the user has not already received an email
                    $subject = $this->security->decode(str_ireplace('[name]', $user['name'], $email['subject']));

                    $text = str_ireplace('[name]', $user['name'], $email['text']);
                    $text = str_ireplace('[page reference]', $user['page_reference'], $text);
                    $text = str_ireplace('[page url]', $this->prepareUrl($user['page_url']), $text);
                    $text = str_ireplace('[' . $this->setting->get('purpose'). ' url]', $this->prepareUrl($this->comment->buildCommentUrl($comment['id'], $user['page_url'])), $text);
                    $text = str_ireplace('[poster]', $comment['name'], $text);
                    $text = str_ireplace('[user url]', $this->prepareUrl($this->buildUserUrl($user['token'])), $text);
                    $text = str_ireplace('[signature]', $this->email->getSignatureText($this->page->getSiteId()), $text);
                    $text = $this->security->decode($text);
                    $text = str_ireplace('[' . $this->setting->get('purpose') . ']', $this->prepareComment($comment['comment'], 'text'), $text);

                    $html = str_ireplace('[name]', $user['name'], $email['html']);
                    $html = str_ireplace('[page reference]', $user['page_reference'], $html);
                    $html = str_ireplace('[page url]', $this->prepareUrl($user['page_url']), $html);
                    $html = str_ireplace('[' . $this->setting->get('purpose'). ' url]', $this->prepareUrl($this->comment->buildCommentUrl($comment['id'], $user['page_url'])), $html);
                    $html = str_ireplace('[poster]', $comment['name'], $html);
                    $html = str_ireplace('[user url]', $this->prepareUrl($this->buildUserUrl($user['token'])), $html);
                    $html = str_ireplace('[signature]', $this->email->getSignatureHtml($this->page->getSiteId()), $html);
                    $html = $this->security->decode($html);
                    $html = str_ireplace('[' . $this->setting->get('purpose') . ']', $this->prepareComment($comment['comment'], 'html'), $html);

                    $this->email->send($user['email'], $user['name'], $subject, $text, $html, $user['format'], $this->page->getSiteId());

                    $this->sent_to++;

                    $this->users = array_diff($this->users, array($user['id'])); // update the user list so that this user is not emailed again
                }
            }
        }
    }

    private function prepareComment($comment, $format = 'html')
    {
        if ($format == 'text') {
            $comment = str_ireplace('<br>', "\r\n", $comment);

            $comment = str_ireplace('</p>', "\r\n\r\n", $comment);

            $comment = str_ireplace('<li>', '- ', $comment);
            $comment = str_ireplace('</li>', "\r\n", $comment);
            $comment = str_ireplace('</ul>', "\r\n", $comment);
            $comment = str_ireplace('</ol>', "\r\n", $comment);

            $comment = strip_tags($comment);
        }

        if ($format == 'text') {
            $comment = $this->security->decode($comment);
        }

        if ($format == 'text') {
            $comment = preg_replace('/(\r\n){3,}/', "\r\n\r\n", $comment);
        }

        $comment = trim($comment);

        return $comment;
    }

    private function prepareReasons($reasons, $format = 'html')
    {
        $prepared_reasons = '';

        if ($format == 'text') {
            return $reasons;
        } else {
            foreach (explode("\r\n", $reasons) as $line) {
                if (trim($line)) {
                    $prepared_reasons .= $line . '<br>';
                }
            }
        }

        return $prepared_reasons;
    }

    private function buildCommentUrl($id, $url)
    {
        $url .= '?cmtx_perm=' . $id . '#cmtx_perm_' . $id;

        return $url;
    }

    private function buildUserUrl($user_token)
    {
        /* u-t = user_token */
        $url = $this->setting->get('commentics_url') . 'frontend/index.php?route=main/user&u-t=' . $user_token;

        return $url;
    }

    private function buildSubscriptionConfirmationUrl($user_token, $subscription_token)
    {
        /* u-t = user_token, s-t = subscription_token, c-s = confirm_subscription */
        $url = $this->setting->get('commentics_url') . 'frontend/index.php?route=main/user&u-t=' . $user_token . '&s-t=' . $subscription_token . '&action=c-s';

        return $url;
    }

    private function prepareUrl($url)
    {
        return str_replace(' ', '%20', $url);
    }
}
