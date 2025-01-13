<?php
namespace Commentics;

class MainCommentsController extends Controller
{
    private $top_poster = 0;
    private $most_likes = 0;
    private $first_poster = 0;

    public function index()
    {
        $this->loadLanguage('main/comments');

        $this->loadModel('main/comments');

        if ($this->setting->get('show_permalink') && isset($this->request->get['cmtx_perm']) && $this->validation->isInt($this->request->get['cmtx_perm'])) {
            $filter_comment_id = $this->request->get['cmtx_perm'];
        } else {
            $filter_comment_id = '';
        }

        if ($this->setting->get('show_search') && isset($this->request->post['cmtx_search']) && $this->request->post['cmtx_search']) {
            $filter_comment = $this->request->post['cmtx_search'];
        } else {
            $filter_comment = '';
        }

        if ($this->setting->get('show_sort_by') && isset($this->request->post['cmtx_sort_by'])) {
            $sort = $this->request->post['cmtx_sort_by'];
        } else {
            $sort = $this->setting->get('comments_order');
        }

        switch ($sort) {
            case '1':
                $sort = 'date_added';
                $order = 'desc';
                break;
            case '2':
                $sort = 'date_added';
                $order = 'asc';
                break;
            case '3':
                $sort = 'likes';
                $order = 'desc';
                break;
            case '4':
                $sort = 'dislikes';
                $order = 'desc';
                break;
            case '5':
                $sort = 'rating';
                $order = 'desc';
                break;
            case '6':
                $sort = 'rating';
                $order = 'asc';
                break;
            default:
                $sort = 'date_added';
                $order = 'desc';
        }

        $page = 1;

        if ($this->setting->get('show_pagination')) {
            if (isset($this->request->post['cmtx_page']) && $this->request->post['cmtx_page']) {
                $page = (int) $this->request->post['cmtx_page'];
            }

            /* Allows search engines to index pages */
            if (isset($this->request->get['cmtx_page']) && $this->request->get['cmtx_page']) {
                $page = (int) $this->request->get['cmtx_page'];
            }
        }

        $data = array(
            'filter_comment_id' => $filter_comment_id,
            'filter_page_id'    => $this->page->getId(),
            'filter_comment'    => $filter_comment,
            'count_replies'     => false,
            'group_by'          => '',
            'sort'              => $sort,
            'order'             => $order,
            'start'             => ($page - 1) * $this->setting->get('pagination_amount'),
            'limit'             => (($this->setting->get('show_pagination')) ? $this->setting->get('pagination_amount') : 9999999999)
        );

        $comments = $this->model_main_comments->getComments($data);

        $this->data['total'] = $this->model_main_comments->getComments($data, true);

        $this->data['comments'] = array();

        if ($filter_comment_id) { // if permalink
            $this->data['is_permalink'] = true;
        } else {
            $this->data['is_permalink'] = false;
        }

        if ($filter_comment) { // if search
            $this->data['is_search'] = true;
        } else {
            $this->data['is_search'] = false;
        }

        if ($this->data['total']) {
            if ($this->setting->get('show_bio')) {
                if ($this->setting->get('show_badge_top_poster')) {
                    $this->top_poster = $this->model_main_comments->getTopPoster();
                }

                if ($this->setting->get('show_badge_most_likes')) {
                    $this->most_likes = $this->model_main_comments->getMostLikes();
                }

                if ($this->setting->get('show_badge_first_poster')) {
                    $this->first_poster = $this->model_main_comments->getFirstPoster($this->page->getId());
                }
            }

            foreach ($comments as $comment) {
                $this->data['comments'][$comment['id']] = $this->getComment($comment['id']);
            }

            if ($this->setting->get('show_comment_count')) {
                if ($this->setting->get('count_replies')) {
                    if ($this->data['is_permalink']) {
                        $total = $this->data['total'] + count($this->comment->getReplies($filter_comment_id));
                    } else {
                        $data['count_replies'] = true;
                        $total = $this->model_main_comments->getComments($data, true);
                    }
                } else {
                    $total = $this->data['total'];
                }

                $this->data['lang_heading_comments'] .= ' (' . $total . ')';
            }

            if ($this->setting->get('website_new_window')) {
                $this->data['website_new_window'] = 'target="_blank"';
            } else {
                $this->data['website_new_window'] = '';
            }

            if ($this->setting->get('website_no_follow')) {
                $this->data['website_no_follow'] = 'rel="nofollow"';
            } else {
                $this->data['website_no_follow'] = '';
            }

            if ($this->setting->get('share_new_window')) {
                $this->data['share_new_window'] = 'target="_blank"';
            } else {
                $this->data['share_new_window'] = '';
            }

            if ($this->setting->get('hide_replies')) {
                $this->data['hide_replies'] = 'cmtx_hide';
            } else {
                $this->data['hide_replies'] = '';
            }

            $this->data['avatar_type']             = $this->setting->get('avatar_type');
            $this->data['show_level']              = $this->setting->get('show_level');
            $this->data['show_bio']                = $this->setting->get('show_bio');
            $this->data['show_badge_top_poster']   = $this->setting->get('show_badge_top_poster');
            $this->data['show_badge_most_likes']   = $this->setting->get('show_badge_most_likes');
            $this->data['show_badge_first_poster'] = $this->setting->get('show_badge_first_poster');
            $this->data['show_website']            = $this->setting->get('show_website');
            $this->data['show_says']               = $this->setting->get('show_says');
            $this->data['show_rating']             = $this->setting->get('show_rating');
            $this->data['show_headline']           = $this->setting->get('show_headline');
            $this->data['show_date']               = $this->setting->get('show_date');
            $this->data['date_auto']               = $this->setting->get('date_auto');
            $this->data['show_like']               = $this->setting->get('show_like');
            $this->data['show_dislike']            = $this->setting->get('show_dislike');
            $this->data['show_share']              = $this->setting->get('show_share');
            $this->data['show_share_digg']         = $this->setting->get('show_share_digg');
            $this->data['show_share_facebook']     = $this->setting->get('show_share_facebook');
            $this->data['show_share_linkedin']     = $this->setting->get('show_share_linkedin');
            $this->data['show_share_reddit']       = $this->setting->get('show_share_reddit');
            $this->data['show_share_twitter']      = $this->setting->get('show_share_twitter');
            $this->data['show_share_weibo']        = $this->setting->get('show_share_weibo');
            $this->data['show_flag']               = $this->setting->get('show_flag');
            $this->data['show_permalink']          = $this->setting->get('show_permalink');
            $this->data['show_pagination']         = $this->setting->get('show_pagination');
            $this->data['pagination_type']         = $this->setting->get('pagination_type');
            $this->data['pagination_amount']       = $this->setting->get('pagination_amount');
            $this->data['reply_max_depth']         = $this->setting->get('reply_depth');

            $this->data['is_preview'] = false;

            if ($this->setting->get('show_reply') && $this->setting->get('enabled_form') && $this->page->isFormEnabled()) {
                $this->data['show_reply'] = true;
            } else {
                $this->data['show_reply'] = false;
            }

            if ($this->setting->get('show_edit') && $this->setting->get('enabled_form') && $this->page->isFormEnabled()) {
                $this->data['show_edit'] = true;
            } else {
                $this->data['show_edit'] = false;
            }

            if ($this->setting->get('show_delete') && $this->session->getId()) {
                $this->data['show_delete'] = true;
            } else {
                $this->data['show_delete'] = false;
            }

            $outer_components = array();

            if ($this->setting->get('show_average_rating')) {
                $outer_components['average_rating'] = $this->getComponent('part/average_rating');
            }

            if ($this->setting->get('show_custom')) {
                $outer_components['custom'] = $this->getComponent('part/custom');
            }

            if ($this->setting->get('show_notify') && $this->setting->get('enabled_email') && $this->setting->get('enabled_form') && $this->page->isFormEnabled()) {
                $outer_components['notify'] = $this->getComponent('part/notify');
            }

            if ($this->setting->get('show_online') && $this->setting->get('viewers_enabled')) {
                $outer_components['online'] = $this->getComponent('part/online');
            }

            if ($this->setting->get('show_page_number') && $this->setting->get('show_pagination') && $this->setting->get('pagination_type') == 'multiple') {
                $outer_components['page_number'] = $this->getComponent('part/page_number', array('current_page' => $page, 'total_pages' => ceil($this->data['total'] / $this->setting->get('pagination_amount'))));
            }

            if ($this->setting->get('show_pagination') && $this->setting->get('pagination_type') == 'multiple' && ceil($this->data['total'] / $this->setting->get('pagination_amount')) > 1) {
                $outer_components['pagination'] = $this->getComponent('part/pagination', array('current_page' => $page, 'total_pages' => ceil($this->data['total'] / $this->setting->get('pagination_amount'))));
            }

            if ($this->setting->get('show_rss')) {
                $outer_components['rss'] = $this->getComponent('part/rss');
            }

            if ($this->setting->get('show_search')) {
                $outer_components['search'] = $this->getComponent('part/search');
            }

            if ($this->setting->get('show_social')) {
                $outer_components['social'] = $this->getComponent('part/social');
            }

            if ($this->setting->get('show_sort_by')) {
                $outer_components['sort_by'] = $this->getComponent('part/sort_by');
            }

            if ($this->setting->get('show_topic')) {
                $outer_components['topic'] = $this->getComponent('part/topic');
            }

            for ($i = 1; $i <= 12; $i++) {
                if ($this->setting->get('comments_position_' . $i) && array_key_exists($this->setting->get('comments_position_' . $i), $outer_components)) {
                    $this->data['comments_position_' . $i] = $outer_components[$this->setting->get('comments_position_' . $i)];

                    $this->data['cmtx_empty_position_' . $i] = '';

                    $trimmed = trim($this->data['comments_position_' . $i]);

                    if (empty($trimmed)) {
                        $this->data['comments_position_' . $i] = '&nbsp;';
                        $this->data['cmtx_empty_position_' . $i] = 'cmtx_empty_position';
                    }
                } else {
                    $this->data['comments_position_' . $i] = '&nbsp;';
                    $this->data['cmtx_empty_position_' . $i] = 'cmtx_empty_position';
                }
            }

            if ($this->data['comments_position_1'] != '&nbsp;' || $this->data['comments_position_2'] != '&nbsp;' || $this->data['comments_position_3'] != '&nbsp;') {
                $this->data['row_one'] = true;
            } else {
                $this->data['row_one'] = false;
            }

            if ($this->data['comments_position_4'] != '&nbsp;' || $this->data['comments_position_5'] != '&nbsp;' || $this->data['comments_position_6'] != '&nbsp;') {
                $this->data['row_two'] = true;
            } else {
                $this->data['row_two'] = false;
            }

            if ($this->data['comments_position_7'] != '&nbsp;' || $this->data['comments_position_8'] != '&nbsp;' || $this->data['comments_position_9'] != '&nbsp;') {
                $this->data['row_three'] = true;
            } else {
                $this->data['row_three'] = false;
            }

            if ($this->data['comments_position_10'] != '&nbsp;' || $this->data['comments_position_11'] != '&nbsp;' || $this->data['comments_position_12'] != '&nbsp;') {
                $this->data['row_four'] = true;
            } else {
                $this->data['row_four'] = false;
            }

            if ($this->setting->has('rich_snippets_enabled') && $this->setting->get('rich_snippets_enabled')) {
                $this->data['rich_snippets_enabled'] = true;

                if ($this->setting->get('rich_snippets_type') != 'other') {
                    $this->data['rich_snippets_type'] = $this->setting->get('rich_snippets_type');
                } else {
                    $this->data['rich_snippets_type'] = $this->setting->get('rich_snippets_other');
                }
            } else {
                $this->data['rich_snippets_enabled'] = false;
            }

            $this->data['ratings'] = array(0, 1, 2, 3, 4);

            $this->data['page_reference'] = $this->page->getReference();

            $this->data['session_id'] = $this->session->getId();

            $this->data['ip_address'] = $this->user->getIpAddress();

            /* These are passed to common.js via the template */
            $this->data['cmtx_js_settings_comments'] = array(
                'commentics_url'          => $this->url->getCommenticsUrl(),
                'page_id'                 => (int) $this->page->getId(),
                'is_permalink'            => (bool) $this->data['is_permalink'],
                'lang_text_view'          => $this->data['lang_text_view'],
                'lang_text_reply'         => $this->data['lang_text_reply'],
                'lang_text_replies'       => $this->data['lang_text_replies'],
                'lang_text_replying_to'   => $this->data['lang_text_replying_to'],
                'lang_title_cancel_reply' => $this->data['lang_title_cancel_reply'],
                'lang_link_cancel'        => $this->data['lang_link_cancel'],
                'lang_text_privacy'       => $this->data['lang_text_privacy'],
                'lang_text_terms'         => $this->data['lang_text_terms'],
                'lang_text_agree'         => $this->data['lang_text_agree'],
                'lang_text_not_replying'  => $this->data['lang_text_not_replying'],
                'lang_button_loading'     => $this->data['lang_button_loading'],
                'lang_button_more'        => $this->data['lang_button_more'],
                'lang_button_edit'        => $this->data['lang_button_edit'],
                'lang_button_reply'       => $this->data['lang_button_reply'],
                'lang_link_reply'         => $this->data['lang_link_reply'],
                'lang_link_refresh'       => $this->data['lang_link_refresh'],
                'date_auto'               => (bool) $this->setting->get('date_auto'),
                'show_pagination'         => (bool) $this->setting->get('show_pagination'),
                'quick_reply'             => (bool) $this->setting->get('quick_reply'),
                'pagination_type'         => $this->setting->get('pagination_type'),
                'timeago_suffixAgo'       => $this->data['lang_text_timeago_ago'],
                'timeago_inPast'          => $this->data['lang_text_timeago_second'],
                'timeago_seconds'         => $this->data['lang_text_timeago_seconds'],
                'timeago_minute'          => $this->data['lang_text_timeago_minute'],
                'timeago_minutes'         => $this->data['lang_text_timeago_minutes'],
                'timeago_hour'            => $this->data['lang_text_timeago_hour'],
                'timeago_hours'           => $this->data['lang_text_timeago_hours'],
                'timeago_day'             => $this->data['lang_text_timeago_day'],
                'timeago_days'            => $this->data['lang_text_timeago_days'],
                'timeago_month'           => $this->data['lang_text_timeago_month'],
                'timeago_months'          => $this->data['lang_text_timeago_months'],
                'timeago_year'            => $this->data['lang_text_timeago_year'],
                'timeago_years'           => $this->data['lang_text_timeago_years']
            );

            $this->data['cmtx_js_settings_comments'] = json_encode($this->data['cmtx_js_settings_comments']);
        }

        return $this->data;
    }

    private function getComment($id)
    {
        $result = $this->cache->get('getcomment_commentid' . $id . '_' . $this->setting->get('language'));

        if ($result !== false) {
            return $result;
        }

        $comment = $this->comment->getComment($id);

        if ($this->setting->get('avatar_type')) {
            $comment['avatar'] = $this->user->getAvatar($comment['user_id']);

            if ($this->setting->get('avatar_type') == 'gravatar') {
                $comment['avatar_bio'] = '//www.gravatar.com/avatar/' . md5(strtolower(trim($comment['email']))) . '?d=' . ($this->setting->get('gravatar_default') == 'custom' ? $this->url->encode($this->setting->get('gravatar_custom')) : $this->setting->get('gravatar_default')) . '&amp;r=' . $this->setting->get('gravatar_audience') . '&amp;s=158';
            } else {
                if (substr($comment['avatar'], -15) == 'misc/avatar.png') {
                    $comment['avatar_bio'] = $this->loadImage('misc/avatar_bio.png');
                } else {
                    $comment['avatar_bio'] = $comment['avatar'];
                }
            }

            $num_approved_comments = $this->user->getNumApprovedComments($comment['user_id']);

            if ($comment['is_admin']) {
                $comment['level'] = $this->data['lang_text_admin'];
            } else {
                if ($num_approved_comments >= $this->setting->get('level_5')) {
                    $comment['level'] = $this->data['lang_text_level_5'];
                } else if ($num_approved_comments >= $this->setting->get('level_4')) {
                    $comment['level'] = $this->data['lang_text_level_4'];
                } else if ($num_approved_comments >= $this->setting->get('level_3')) {
                    $comment['level'] = $this->data['lang_text_level_3'];
                } else if ($num_approved_comments >= $this->setting->get('level_2')) {
                    $comment['level'] = $this->data['lang_text_level_2'];
                } else if ($num_approved_comments >= $this->setting->get('level_1')) {
                    $comment['level'] = $this->data['lang_text_level_1'];
                } else if ($num_approved_comments >= $this->setting->get('level_0')) {
                    $comment['level'] = $this->data['lang_text_level_0'];
                } else {
                    $comment['level'] = '';
                }
            }

            if ($this->top_poster == $comment['user_id']) {
                $comment['top_poster'] = true;
            } else {
                $comment['top_poster'] = false;
            }

            if ($this->most_likes == $comment['user_id']) {
                $comment['most_likes'] = true;
            } else {
                $comment['most_likes'] = false;
            }

            if ($this->first_poster == $comment['user_id']) {
                $comment['first_poster'] = true;
            } else {
                $comment['first_poster'] = false;
            }

            $comment['bio_info_posts'] = $num_approved_comments;

            if ($this->setting->get('show_like')) {
                $comment['bio_info_likes'] = $this->user->getNumLikedComments($comment['user_id']);
            }

            if ($this->setting->get('show_dislike')) {
                $comment['bio_info_dislikes'] = $this->user->getNumDislikedComments($comment['user_id']);
            }

            $comment['bio_info_since'] = $this->variable->formatDate($comment['date_added_user'], 'M Y', $this->data);
        }

        $location = '';

        if ($this->setting->get('show_town') && $comment['town']) {
            $location .= '<span itemprop="addressLocality">' . $comment['town'] . '</span>, ';
        }

        if ($this->setting->get('show_state') && $comment['state']) {
            $location .= '<span itemprop="addressRegion">' . $comment['state'] . '</span>, ';
        }

        if ($this->setting->get('show_country') && $comment['country']) {
            $location .= '<span itemprop="addressCountry">' . $comment['country'] . '</span>, ';
        }

        $comment['location'] = rtrim($location, ', ');

        if ($this->setting->get('enabled_smilies')) {
            $comment['comment'] = $this->model_main_comments->convertSmilies($comment['comment']);
        }

        $comment['comment'] = $this->model_main_comments->purifyComment($comment['comment']);

        $uploads = $comment['uploads'];

        foreach ($uploads as $key => &$upload) {
            if (file_exists(CMTX_DIR_UPLOAD . $upload['folder'] . '/' . $upload['filename'] . '.' . $upload['extension'])) {
                $upload['image'] = $this->url->getCommenticsUrl() . 'upload/' . $upload['folder'] . '/' . $upload['filename'] . '.' . $upload['extension'];
            } else {
                unset($uploads[$key]);
            }
        }

        $comment['uploads'] = $uploads;

        $comment['permalink'] = $this->comment->buildCommentUrl($comment['id'], $comment['page_url']);

        if ($this->setting->get('date_auto')) {
            $comment['date_added'] = $this->variable->formatDate($comment['date_added'], 'c', $this->data);

            $comment['date_added_title'] = $this->variable->formatDate($comment['date_added'], $this->data['lang_date_time_format'], $this->data);
        } else {
            $day_difference = $this->model_main_comments->calculateDayDifference($comment['date_added']);

            if ($day_difference == 0) {
                $comment['date_added'] = $this->data['lang_text_today'] . ' ' . $this->variable->formatDate($comment['date_added'], $this->data['lang_time_format'], $this->data);
            } else if ($day_difference == 1) {
                $comment['date_added'] = $this->data['lang_text_yesterday'] . ' ' . $this->variable->formatDate($comment['date_added'], $this->data['lang_time_format'], $this->data);
            } else {
                $comment['date_added'] = $this->variable->formatDate($comment['date_added'], $this->data['lang_date_time_format'], $this->data);
            }
        }

        $replies = $this->model_main_comments->getReplies($comment['id']);

        $comment['reply_id'] = array();

        foreach ($replies as $reply) {
            $comment['reply_id'][$reply['id']] = $this->getComment($reply['id']);
        }

        if ($comment['reply_to'] == 0) {
            $this->cache->set('getcomment_commentid' . $id . '_' . $this->setting->get('language'), $comment);
        }

        return $comment;
    }

    public function vote()
    {
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');

            $json = array();

            if (isset($this->request->post['cmtx_comment_id']) && isset($this->request->post['cmtx_type']) && in_array($this->request->post['cmtx_type'], array('like', 'dislike'))) {
                $this->loadLanguage('main/comments');

                $this->loadModel('main/comments');

                $comment_id = $this->request->post['cmtx_comment_id'];

                $type = $this->request->post['cmtx_type'];

                $ip_address = $this->user->getIpAddress();

                if ($this->setting->get('maintenance_mode')) { // check if in maintenance mode
                    $json['error'] = $this->data['lang_error_maintenance'];
                } else if ($this->request->post['cmtx_type'] == 'like' && !$this->setting->get('show_like')) { // check if feature enabled
                    $json['error'] = $this->data['lang_error_disabled'];
                } else if ($this->request->post['cmtx_type'] == 'dislike' && !$this->setting->get('show_dislike')) { // check if feature enabled
                    $json['error'] = $this->data['lang_error_disabled'];
                } else if (!$this->comment->commentExists($comment_id)) { // check if comment exists
                    $json['error'] = $this->data['lang_error_no_comment'];
                } else if ($this->model_main_comments->isCommentByIpAddress($comment_id, $ip_address)) { // check if user is voting own comment
                    $json['error'] = $this->data['lang_error_vote_own'];
                } else if ($this->model_main_comments->hasAlreadyVotedComment($comment_id, $ip_address)) { // check if user has already voted this comment
                    $json['error'] = $this->data['lang_error_vote_already'];
                } else if ($this->user->isBanned($ip_address)) { // check if user is banned
                    $json['error'] = $this->data['lang_error_banned'];
                }

                if (!$json) {
                    $this->model_main_comments->addVote($comment_id, $type, $ip_address);

                    $this->cache->delete('getcomment_commentid' . $comment_id . '_*');

                    $json['success'] = true;
                }
            }

            echo json_encode($json);
        }
    }

    public function flag()
    {
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');

            $json = array();

            if (isset($this->request->post['cmtx_comment_id'])) {
                $this->loadLanguage('main/comments');

                $this->loadModel('main/comments');

                $comment_id = $this->request->post['cmtx_comment_id'];

                $comment = $this->comment->getComment($comment_id);

                $ip_address = $this->user->getIpAddress();

                if ($this->setting->get('maintenance_mode')) { // check if in maintenance mode
                    $json['error'] = $this->data['lang_error_maintenance'];
                } else if (!$this->setting->get('show_flag')) { // check if feature enabled
                    $json['error'] = $this->data['lang_error_disabled'];
                } else if (!$comment) { // check if comment exists
                    $json['error'] = $this->data['lang_error_no_comment'];
                } else if ($comment['ip_address'] == $ip_address) { // check if user is reporting own comment
                    $json['error'] = $this->data['lang_error_report_own'];
                } else if ($comment['is_admin']) { // check if user is reporting an admin comment
                    $json['error'] = $this->data['lang_error_report_admin'];
                } else if ($this->model_main_comments->hasAlreadyReportedComment($comment_id, $ip_address)) { // check if user has already reported this comment
                    $json['error'] = $this->data['lang_error_report_already'];
                } else if ($this->user->isBanned($ip_address)) { // check if user is banned
                    $json['error'] = $this->data['lang_error_banned'];
                } else if ($this->model_main_comments->countReportsByIpAddress($ip_address) >= $this->setting->get('flag_max_per_user')) { // check if user has reported more than allowed amount
                    $json['error'] = $this->data['lang_error_report_max'];
                } else if ($comment['is_verified']) { // check if admin has verified this comment
                    $json['error'] = $this->data['lang_error_verified'];
                } else if ($comment['reports'] >= $this->setting->get('flag_min_per_comment')) { // check if comment is already flagged
                    $json['error'] = $this->data['lang_error_flagged'];
                }

                if (!$json) {
                    if (($comment['reports'] + 1) == $this->setting->get('flag_min_per_comment')) { // comment should be flagged
                        if ($this->setting->get('flag_disapprove')) {
                            $this->comment->unapproveComment($comment_id);

                            $this->comment->deleteCache($comment_id);
                        }

                        $this->notify->adminNotifyCommentFlag($comment_id);
                    }

                    $this->model_main_comments->addReport($comment_id, $ip_address);

                    $json['success'] = $this->data['lang_text_reported'];
                }
            }

            echo json_encode($json);
        }
    }

    public function original()
    {
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');

            $json = array();

            if (isset($this->request->post['cmtx_comment_id'])) {
                $this->loadLanguage('main/comments');

                $comment_id = $this->request->post['cmtx_comment_id'];

                $comment = $this->comment->getComment($comment_id);

                $ip_address = $this->user->getIpAddress();

                if ($this->setting->get('maintenance_mode')) { // check if in maintenance mode
                    $json['error'] = $this->data['lang_error_maintenance'];
                } else if (!$this->setting->get('show_edit')) { // check if feature enabled
                    $json['error'] = $this->data['lang_error_disabled'];
                } else if (!$comment) { // check if comment exists
                    $json['error'] = $this->data['lang_error_no_comment'];
                } else if (!$this->user->ownComment($comment)) { // check if user is accessing own comment
                    $json['error'] = $this->data['lang_error_own_comment'];
                } else if ($this->user->isBanned($ip_address)) { // check if user is banned
                    $json['error'] = $this->data['lang_error_banned'];
                }

                if (!$json) {
                    if ($comment['number_edits'] >= $this->setting->get('max_edits')) {
                        $json['error'] = $this->data['lang_error_max_edits'];
                    }

                    $json['original_comment'] = $comment['original_comment'];

                    $json['success'] = true;
                }
            }

            echo json_encode($json);
        }
    }

    public function delete()
    {
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');

            $json = array();

            if (isset($this->request->post['cmtx_comment_id'])) {
                $this->loadLanguage('main/comments');

                $comment_id = $this->request->post['cmtx_comment_id'];

                $comment = $this->comment->getComment($comment_id);

                $ip_address = $this->user->getIpAddress();

                if ($this->setting->get('maintenance_mode')) { // check if in maintenance mode
                    $json['error'] = $this->data['lang_error_maintenance'];
                } else if (!$this->setting->get('show_delete')) { // check if feature enabled
                    $json['error'] = $this->data['lang_error_disabled'];
                } else if (!$comment) { // check if comment exists
                    $json['error'] = $this->data['lang_error_no_comment'];
                } else if (!$this->user->ownComment($comment)) { // check if user is deleting own comment
                    $json['error'] = $this->data['lang_error_own_comment'];
                } else if ($this->user->isBanned($ip_address)) { // check if user is banned
                    $json['error'] = $this->data['lang_error_banned'];
                }

                if (!$json) {
                    /* Notify admins of delete */
                    if (!$this->user->isAdmin()) {
                        $this->notify->adminNotifyCommentDelete($comment_id);
                    }

                    $this->comment->deleteComment($comment_id);

                    $json['success'] = true;
                }
            }

            echo json_encode($json);
        }
    }

    public function getComments()
    {
        if ($this->request->isAjax()) {
            $this->response->addHeader('Content-Type: application/json');

            $json = array();

            $comments = $this->index();

            extract($this->data);

            ob_start();

            require $this->loadTemplate('main/comments');

            $json['result'] = ob_get_clean();

            echo json_encode($json);
        }
    }
}
