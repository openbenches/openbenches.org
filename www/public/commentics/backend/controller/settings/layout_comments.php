<?php
namespace Commentics;

class SettingsLayoutCommentsController extends Controller
{
    public function index()
    {
        $this->loadLanguage('settings/layout_comments');

        $this->loadModel('settings/layout_comments');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_settings_layout_comments->update($this->request->post);
            }
        }

        /* General */

        if (isset($this->request->post['show_comment_count'])) {
            $this->data['show_comment_count'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_comment_count'])) {
            $this->data['show_comment_count'] = false;
        } else {
            $this->data['show_comment_count'] = $this->setting->get('show_comment_count');
        }

        if (isset($this->request->post['count_replies'])) {
            $this->data['count_replies'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['count_replies'])) {
            $this->data['count_replies'] = false;
        } else {
            $this->data['count_replies'] = $this->setting->get('count_replies');
        }

        if (isset($this->request->post['comments_order'])) {
            $this->data['comments_order'] = $this->request->post['comments_order'];
        } else {
            $this->data['comments_order'] = $this->setting->get('comments_order');
        }

        if (isset($this->request->post['comments_position_1'])) {
            $this->data['comments_position_1'] = $this->request->post['comments_position_1'];
        } else {
            $this->data['comments_position_1'] = $this->setting->get('comments_position_1');
        }

        if (isset($this->request->post['comments_position_2'])) {
            $this->data['comments_position_2'] = $this->request->post['comments_position_2'];
        } else {
            $this->data['comments_position_2'] = $this->setting->get('comments_position_2');
        }

        if (isset($this->request->post['comments_position_3'])) {
            $this->data['comments_position_3'] = $this->request->post['comments_position_3'];
        } else {
            $this->data['comments_position_3'] = $this->setting->get('comments_position_3');
        }

        if (isset($this->request->post['comments_position_4'])) {
            $this->data['comments_position_4'] = $this->request->post['comments_position_4'];
        } else {
            $this->data['comments_position_4'] = $this->setting->get('comments_position_4');
        }

        if (isset($this->request->post['comments_position_5'])) {
            $this->data['comments_position_5'] = $this->request->post['comments_position_5'];
        } else {
            $this->data['comments_position_5'] = $this->setting->get('comments_position_5');
        }

        if (isset($this->request->post['comments_position_6'])) {
            $this->data['comments_position_6'] = $this->request->post['comments_position_6'];
        } else {
            $this->data['comments_position_6'] = $this->setting->get('comments_position_6');
        }

        if (isset($this->request->post['comments_position_7'])) {
            $this->data['comments_position_7'] = $this->request->post['comments_position_7'];
        } else {
            $this->data['comments_position_7'] = $this->setting->get('comments_position_7');
        }

        if (isset($this->request->post['comments_position_8'])) {
            $this->data['comments_position_8'] = $this->request->post['comments_position_8'];
        } else {
            $this->data['comments_position_8'] = $this->setting->get('comments_position_8');
        }

        if (isset($this->request->post['comments_position_9'])) {
            $this->data['comments_position_9'] = $this->request->post['comments_position_9'];
        } else {
            $this->data['comments_position_9'] = $this->setting->get('comments_position_9');
        }

        if (isset($this->request->post['comments_position_10'])) {
            $this->data['comments_position_10'] = $this->request->post['comments_position_10'];
        } else {
            $this->data['comments_position_10'] = $this->setting->get('comments_position_10');
        }

        if (isset($this->request->post['comments_position_11'])) {
            $this->data['comments_position_11'] = $this->request->post['comments_position_11'];
        } else {
            $this->data['comments_position_11'] = $this->setting->get('comments_position_11');
        }

        if (isset($this->request->post['comments_position_12'])) {
            $this->data['comments_position_12'] = $this->request->post['comments_position_12'];
        } else {
            $this->data['comments_position_12'] = $this->setting->get('comments_position_12');
        }

        if (isset($this->error['comments_order'])) {
            $this->data['error_comments_order'] = $this->error['comments_order'];
        } else {
            $this->data['error_comments_order'] = '';
        }

        if (isset($this->error['comments_position_1'])) {
            $this->data['error_comments_position_1'] = $this->error['comments_position_1'];
        } else {
            $this->data['error_comments_position_1'] = '';
        }

        if (isset($this->error['comments_position_2'])) {
            $this->data['error_comments_position_2'] = $this->error['comments_position_2'];
        } else {
            $this->data['error_comments_position_2'] = '';
        }

        if (isset($this->error['comments_position_3'])) {
            $this->data['error_comments_position_3'] = $this->error['comments_position_3'];
        } else {
            $this->data['error_comments_position_3'] = '';
        }

        if (isset($this->error['comments_position_4'])) {
            $this->data['error_comments_position_4'] = $this->error['comments_position_4'];
        } else {
            $this->data['error_comments_position_4'] = '';
        }

        if (isset($this->error['comments_position_5'])) {
            $this->data['error_comments_position_5'] = $this->error['comments_position_5'];
        } else {
            $this->data['error_comments_position_5'] = '';
        }

        if (isset($this->error['comments_position_6'])) {
            $this->data['error_comments_position_6'] = $this->error['comments_position_6'];
        } else {
            $this->data['error_comments_position_6'] = '';
        }

        if (isset($this->error['comments_position_7'])) {
            $this->data['error_comments_position_7'] = $this->error['comments_position_7'];
        } else {
            $this->data['error_comments_position_7'] = '';
        }

        if (isset($this->error['comments_position_8'])) {
            $this->data['error_comments_position_8'] = $this->error['comments_position_8'];
        } else {
            $this->data['error_comments_position_8'] = '';
        }

        if (isset($this->error['comments_position_9'])) {
            $this->data['error_comments_position_9'] = $this->error['comments_position_9'];
        } else {
            $this->data['error_comments_position_9'] = '';
        }

        if (isset($this->error['comments_position_10'])) {
            $this->data['error_comments_position_10'] = $this->error['comments_position_10'];
        } else {
            $this->data['error_comments_position_10'] = '';
        }

        if (isset($this->error['comments_position_11'])) {
            $this->data['error_comments_position_11'] = $this->error['comments_position_11'];
        } else {
            $this->data['error_comments_position_11'] = '';
        }

        if (isset($this->error['comments_position_12'])) {
            $this->data['error_comments_position_12'] = $this->error['comments_position_12'];
        } else {
            $this->data['error_comments_position_12'] = '';
        }

        /* Avatar */

        if (isset($this->request->post['avatar_type'])) {
            $this->data['avatar_type'] = $this->request->post['avatar_type'];
        } else {
            $this->data['avatar_type'] = $this->setting->get('avatar_type');
        }

        if (isset($this->request->post['gravatar_default'])) {
            $this->data['gravatar_default'] = $this->request->post['gravatar_default'];
        } else {
            $this->data['gravatar_default'] = $this->setting->get('gravatar_default');
        }

        if (isset($this->request->post['gravatar_custom'])) {
            $this->data['gravatar_custom'] = $this->request->post['gravatar_custom'];
        } else {
            $this->data['gravatar_custom'] = $this->setting->get('gravatar_custom');
        }

        if (isset($this->request->post['gravatar_size'])) {
            $this->data['gravatar_size'] = $this->request->post['gravatar_size'];
        } else {
            $this->data['gravatar_size'] = $this->setting->get('gravatar_size');
        }

        if (isset($this->request->post['gravatar_audience'])) {
            $this->data['gravatar_audience'] = $this->request->post['gravatar_audience'];
        } else {
            $this->data['gravatar_audience'] = $this->setting->get('gravatar_audience');
        }

        if (isset($this->request->post['avatar_selection_attribution'])) {
            $this->data['avatar_selection_attribution'] = $this->request->post['avatar_selection_attribution'];
        } else {
            $this->data['avatar_selection_attribution'] = $this->setting->get('avatar_selection_attribution');
        }

        if (isset($this->request->post['avatar_upload_min_posts'])) {
            $this->data['avatar_upload_min_posts'] = $this->request->post['avatar_upload_min_posts'];
        } else {
            $this->data['avatar_upload_min_posts'] = $this->setting->get('avatar_upload_min_posts');
        }

        if (isset($this->request->post['avatar_upload_min_days'])) {
            $this->data['avatar_upload_min_days'] = $this->request->post['avatar_upload_min_days'];
        } else {
            $this->data['avatar_upload_min_days'] = $this->setting->get('avatar_upload_min_days');
        }

        if (isset($this->request->post['avatar_upload_max_size'])) {
            $this->data['avatar_upload_max_size'] = $this->request->post['avatar_upload_max_size'];
        } else {
            $this->data['avatar_upload_max_size'] = $this->setting->get('avatar_upload_max_size');
        }

        if (isset($this->request->post['avatar_upload_approve'])) {
            $this->data['avatar_upload_approve'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['avatar_upload_approve'])) {
            $this->data['avatar_upload_approve'] = false;
        } else {
            $this->data['avatar_upload_approve'] = $this->setting->get('avatar_upload_approve');
        }

        if (isset($this->request->post['avatar_user_link'])) {
            $this->data['avatar_user_link'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['avatar_user_link'])) {
            $this->data['avatar_user_link'] = false;
        } else {
            $this->data['avatar_user_link'] = $this->setting->get('avatar_user_link');
        }

        if (isset($this->request->post['avatar_link_days'])) {
            $this->data['avatar_link_days'] = $this->request->post['avatar_link_days'];
        } else {
            $this->data['avatar_link_days'] = $this->setting->get('avatar_link_days');
        }

        if (isset($this->request->post['show_level'])) {
            $this->data['show_level'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_level'])) {
            $this->data['show_level'] = false;
        } else {
            $this->data['show_level'] = $this->setting->get('show_level');
        }

        if (isset($this->request->post['level_5'])) {
            $this->data['level_5'] = $this->request->post['level_5'];
        } else {
            $this->data['level_5'] = $this->setting->get('level_5');
        }

        if (isset($this->request->post['level_4'])) {
            $this->data['level_4'] = $this->request->post['level_4'];
        } else {
            $this->data['level_4'] = $this->setting->get('level_4');
        }

        if (isset($this->request->post['level_3'])) {
            $this->data['level_3'] = $this->request->post['level_3'];
        } else {
            $this->data['level_3'] = $this->setting->get('level_3');
        }

        if (isset($this->request->post['level_2'])) {
            $this->data['level_2'] = $this->request->post['level_2'];
        } else {
            $this->data['level_2'] = $this->setting->get('level_2');
        }

        if (isset($this->request->post['level_1'])) {
            $this->data['level_1'] = $this->request->post['level_1'];
        } else {
            $this->data['level_1'] = $this->setting->get('level_1');
        }

        if (isset($this->request->post['level_0'])) {
            $this->data['level_0'] = $this->request->post['level_0'];
        } else {
            $this->data['level_0'] = $this->setting->get('level_0');
        }

        if (isset($this->request->post['show_bio'])) {
            $this->data['show_bio'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_bio'])) {
            $this->data['show_bio'] = false;
        } else {
            $this->data['show_bio'] = $this->setting->get('show_bio');
        }

        if (isset($this->request->post['show_badge_top_poster'])) {
            $this->data['show_badge_top_poster'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_badge_top_poster'])) {
            $this->data['show_badge_top_poster'] = false;
        } else {
            $this->data['show_badge_top_poster'] = $this->setting->get('show_badge_top_poster');
        }

        if (isset($this->request->post['show_badge_most_likes'])) {
            $this->data['show_badge_most_likes'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_badge_most_likes'])) {
            $this->data['show_badge_most_likes'] = false;
        } else {
            $this->data['show_badge_most_likes'] = $this->setting->get('show_badge_most_likes');
        }

        if (isset($this->request->post['show_badge_first_poster'])) {
            $this->data['show_badge_first_poster'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_badge_first_poster'])) {
            $this->data['show_badge_first_poster'] = false;
        } else {
            $this->data['show_badge_first_poster'] = $this->setting->get('show_badge_first_poster');
        }

        if (isset($this->error['avatar_type'])) {
            $this->data['error_avatar_type'] = $this->error['avatar_type'];
        } else {
            $this->data['error_avatar_type'] = '';
        }

        if (isset($this->error['gravatar_default'])) {
            $this->data['error_gravatar_default'] = $this->error['gravatar_default'];
        } else {
            $this->data['error_gravatar_default'] = '';
        }

        if (isset($this->error['gravatar_custom'])) {
            $this->data['error_gravatar_custom'] = $this->error['gravatar_custom'];
        } else {
            $this->data['error_gravatar_custom'] = '';
        }

        if (isset($this->error['gravatar_size'])) {
            $this->data['error_gravatar_size'] = $this->error['gravatar_size'];
        } else {
            $this->data['error_gravatar_size'] = '';
        }

        if (isset($this->error['gravatar_audience'])) {
            $this->data['error_gravatar_audience'] = $this->error['gravatar_audience'];
        } else {
            $this->data['error_gravatar_audience'] = '';
        }

        if (isset($this->error['avatar_selection_attribution'])) {
            $this->data['error_avatar_selection_attribution'] = $this->error['avatar_selection_attribution'];
        } else {
            $this->data['error_avatar_selection_attribution'] = '';
        }

        if (isset($this->error['avatar_upload_min_posts'])) {
            $this->data['error_avatar_upload_min_posts'] = $this->error['avatar_upload_min_posts'];
        } else {
            $this->data['error_avatar_upload_min_posts'] = '';
        }

        if (isset($this->error['avatar_upload_min_days'])) {
            $this->data['error_avatar_upload_min_days'] = $this->error['avatar_upload_min_days'];
        } else {
            $this->data['error_avatar_upload_min_days'] = '';
        }

        if (isset($this->error['avatar_upload_max_size'])) {
            $this->data['error_avatar_upload_max_size'] = $this->error['avatar_upload_max_size'];
        } else {
            $this->data['error_avatar_upload_max_size'] = '';
        }

        if (isset($this->error['avatar_link_days'])) {
            $this->data['error_avatar_link_days'] = $this->error['avatar_link_days'];
        } else {
            $this->data['error_avatar_link_days'] = '';
        }

        if (isset($this->error['level_5'])) {
            $this->data['error_level_5'] = $this->error['level_5'];
        } else {
            $this->data['error_level_5'] = '';
        }

        if (isset($this->error['level_4'])) {
            $this->data['error_level_4'] = $this->error['level_4'];
        } else {
            $this->data['error_level_4'] = '';
        }

        if (isset($this->error['level_3'])) {
            $this->data['error_level_3'] = $this->error['level_3'];
        } else {
            $this->data['error_level_3'] = '';
        }

        if (isset($this->error['level_2'])) {
            $this->data['error_level_2'] = $this->error['level_2'];
        } else {
            $this->data['error_level_2'] = '';
        }

        if (isset($this->error['level_1'])) {
            $this->data['error_level_1'] = $this->error['level_1'];
        } else {
            $this->data['error_level_1'] = '';
        }

        if (isset($this->error['level_0'])) {
            $this->data['error_level_0'] = $this->error['level_0'];
        } else {
            $this->data['error_level_0'] = '';
        }

        /* Name */

        if (isset($this->request->post['show_says'])) {
            $this->data['show_says'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_says'])) {
            $this->data['show_says'] = false;
        } else {
            $this->data['show_says'] = $this->setting->get('show_says');
        }

        if (isset($this->request->post['show_website'])) {
            $this->data['show_website'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_website'])) {
            $this->data['show_website'] = false;
        } else {
            $this->data['show_website'] = $this->setting->get('show_website');
        }

        if (isset($this->request->post['website_new_window'])) {
            $this->data['website_new_window'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['website_new_window'])) {
            $this->data['website_new_window'] = false;
        } else {
            $this->data['website_new_window'] = $this->setting->get('website_new_window');
        }

        if (isset($this->request->post['website_no_follow'])) {
            $this->data['website_no_follow'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['website_no_follow'])) {
            $this->data['website_no_follow'] = false;
        } else {
            $this->data['website_no_follow'] = $this->setting->get('website_no_follow');
        }

        /* Town */

        if (isset($this->request->post['show_town'])) {
            $this->data['show_town'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_town'])) {
            $this->data['show_town'] = false;
        } else {
            $this->data['show_town'] = $this->setting->get('show_town');
        }

        /* State */

        if (isset($this->request->post['show_state'])) {
            $this->data['show_state'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_state'])) {
            $this->data['show_state'] = false;
        } else {
            $this->data['show_state'] = $this->setting->get('show_state');
        }

        /* Country */

        if (isset($this->request->post['show_country'])) {
            $this->data['show_country'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_country'])) {
            $this->data['show_country'] = false;
        } else {
            $this->data['show_country'] = $this->setting->get('show_country');
        }

        /* Headline */

        if (isset($this->request->post['show_headline'])) {
            $this->data['show_headline'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_headline'])) {
            $this->data['show_headline'] = false;
        } else {
            $this->data['show_headline'] = $this->setting->get('show_headline');
        }

        /* Rating */

        if (isset($this->request->post['show_rating'])) {
            $this->data['show_rating'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_rating'])) {
            $this->data['show_rating'] = false;
        } else {
            $this->data['show_rating'] = $this->setting->get('show_rating');
        }

        /* Date */

        if (isset($this->request->post['show_date'])) {
            $this->data['show_date'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_date'])) {
            $this->data['show_date'] = false;
        } else {
            $this->data['show_date'] = $this->setting->get('show_date');
        }

        if (isset($this->request->post['date_auto'])) {
            $this->data['date_auto'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['date_auto'])) {
            $this->data['date_auto'] = false;
        } else {
            $this->data['date_auto'] = $this->setting->get('date_auto');
        }

        /* Like */

        if (isset($this->request->post['show_like'])) {
            $this->data['show_like'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_like'])) {
            $this->data['show_like'] = false;
        } else {
            $this->data['show_like'] = $this->setting->get('show_like');
        }

        /* Dislike */

        if (isset($this->request->post['show_dislike'])) {
            $this->data['show_dislike'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_dislike'])) {
            $this->data['show_dislike'] = false;
        } else {
            $this->data['show_dislike'] = $this->setting->get('show_dislike');
        }

        /* Share */

        if (isset($this->request->post['show_share'])) {
            $this->data['show_share'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_share'])) {
            $this->data['show_share'] = false;
        } else {
            $this->data['show_share'] = $this->setting->get('show_share');
        }

        if (isset($this->request->post['share_new_window'])) {
            $this->data['share_new_window'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['share_new_window'])) {
            $this->data['share_new_window'] = false;
        } else {
            $this->data['share_new_window'] = $this->setting->get('share_new_window');
        }

        if (isset($this->request->post['show_share_digg'])) {
            $this->data['show_share_digg'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_share_digg'])) {
            $this->data['show_share_digg'] = false;
        } else {
            $this->data['show_share_digg'] = $this->setting->get('show_share_digg');
        }

        if (isset($this->request->post['show_share_facebook'])) {
            $this->data['show_share_facebook'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_share_facebook'])) {
            $this->data['show_share_facebook'] = false;
        } else {
            $this->data['show_share_facebook'] = $this->setting->get('show_share_facebook');
        }

        if (isset($this->request->post['show_share_linkedin'])) {
            $this->data['show_share_linkedin'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_share_linkedin'])) {
            $this->data['show_share_linkedin'] = false;
        } else {
            $this->data['show_share_linkedin'] = $this->setting->get('show_share_linkedin');
        }

        if (isset($this->request->post['show_share_reddit'])) {
            $this->data['show_share_reddit'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_share_reddit'])) {
            $this->data['show_share_reddit'] = false;
        } else {
            $this->data['show_share_reddit'] = $this->setting->get('show_share_reddit');
        }

        if (isset($this->request->post['show_share_twitter'])) {
            $this->data['show_share_twitter'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_share_twitter'])) {
            $this->data['show_share_twitter'] = false;
        } else {
            $this->data['show_share_twitter'] = $this->setting->get('show_share_twitter');
        }

        if (isset($this->request->post['show_share_weibo'])) {
            $this->data['show_share_weibo'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_share_weibo'])) {
            $this->data['show_share_weibo'] = false;
        } else {
            $this->data['show_share_weibo'] = $this->setting->get('show_share_weibo');
        }

        $this->data['shares'] = $this->model_settings_layout_comments->getShares();

        /* Flag */

        if (isset($this->request->post['show_flag'])) {
            $this->data['show_flag'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_flag'])) {
            $this->data['show_flag'] = false;
        } else {
            $this->data['show_flag'] = $this->setting->get('show_flag');
        }

        if (isset($this->request->post['flag_max_per_user'])) {
            $this->data['flag_max_per_user'] = $this->request->post['flag_max_per_user'];
        } else {
            $this->data['flag_max_per_user'] = $this->setting->get('flag_max_per_user');
        }

        if (isset($this->request->post['flag_min_per_comment'])) {
            $this->data['flag_min_per_comment'] = $this->request->post['flag_min_per_comment'];
        } else {
            $this->data['flag_min_per_comment'] = $this->setting->get('flag_min_per_comment');
        }

        if (isset($this->request->post['flag_disapprove'])) {
            $this->data['flag_disapprove'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['flag_disapprove'])) {
            $this->data['flag_disapprove'] = false;
        } else {
            $this->data['flag_disapprove'] = $this->setting->get('flag_disapprove');
        }

        if (isset($this->error['flag_max_per_user'])) {
            $this->data['error_flag_max_per_user'] = $this->error['flag_max_per_user'];
        } else {
            $this->data['error_flag_max_per_user'] = '';
        }

        if (isset($this->error['flag_min_per_comment'])) {
            $this->data['error_flag_min_per_comment'] = $this->error['flag_min_per_comment'];
        } else {
            $this->data['error_flag_min_per_comment'] = '';
        }

        /* Edit */

        if (isset($this->request->post['show_edit'])) {
            $this->data['show_edit'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_edit'])) {
            $this->data['show_edit'] = false;
        } else {
            $this->data['show_edit'] = $this->setting->get('show_edit');
        }

        if (isset($this->request->post['max_edits'])) {
            $this->data['max_edits'] = $this->request->post['max_edits'];
        } else {
            $this->data['max_edits'] = $this->setting->get('max_edits');
        }

        if (isset($this->error['max_edits'])) {
            $this->data['error_max_edits'] = $this->error['max_edits'];
        } else {
            $this->data['error_max_edits'] = '';
        }

        /* Delete */

        if (isset($this->request->post['show_delete'])) {
            $this->data['show_delete'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_delete'])) {
            $this->data['show_delete'] = false;
        } else {
            $this->data['show_delete'] = $this->setting->get('show_delete');
        }

        /* Permalink */

        if (isset($this->request->post['show_permalink'])) {
            $this->data['show_permalink'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_permalink'])) {
            $this->data['show_permalink'] = false;
        } else {
            $this->data['show_permalink'] = $this->setting->get('show_permalink');
        }

        /* Reply */

        if (isset($this->request->post['show_reply'])) {
            $this->data['show_reply'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_reply'])) {
            $this->data['show_reply'] = false;
        } else {
            $this->data['show_reply'] = $this->setting->get('show_reply');
        }

        if (isset($this->request->post['quick_reply'])) {
            $this->data['quick_reply'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['quick_reply'])) {
            $this->data['quick_reply'] = false;
        } else {
            $this->data['quick_reply'] = $this->setting->get('quick_reply');
        }

        if (isset($this->request->post['hide_replies'])) {
            $this->data['hide_replies'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['hide_replies'])) {
            $this->data['hide_replies'] = false;
        } else {
            $this->data['hide_replies'] = $this->setting->get('hide_replies');
        }

        if (isset($this->request->post['reply_depth'])) {
            $this->data['reply_depth'] = $this->request->post['reply_depth'];
        } else {
            $this->data['reply_depth'] = $this->setting->get('reply_depth');
        }

        if (isset($this->error['reply_depth'])) {
            $this->data['error_reply_depth'] = $this->error['reply_depth'];
        } else {
            $this->data['error_reply_depth'] = '';
        }

        /* Average Rating */

        if (isset($this->request->post['show_average_rating'])) {
            $this->data['show_average_rating'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_average_rating'])) {
            $this->data['show_average_rating'] = false;
        } else {
            $this->data['show_average_rating'] = $this->setting->get('show_average_rating');
        }

        if (isset($this->request->post['average_rating_guest'])) {
            $this->data['average_rating_guest'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['average_rating_guest'])) {
            $this->data['average_rating_guest'] = false;
        } else {
            $this->data['average_rating_guest'] = $this->setting->get('average_rating_guest');
        }

        /* Custom */

        if (isset($this->request->post['show_custom'])) {
            $this->data['show_custom'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_custom'])) {
            $this->data['show_custom'] = false;
        } else {
            $this->data['show_custom'] = $this->setting->get('show_custom');
        }

        if (isset($this->request->post['custom_content'])) {
            $this->data['custom_content'] = $this->request->post['custom_content'];
        } else {
            $this->data['custom_content'] = $this->setting->get('custom_content');
        }

        if (isset($this->error['custom_content'])) {
            $this->data['error_custom_content'] = $this->error['custom_content'];
        } else {
            $this->data['error_custom_content'] = '';
        }

        /* Notify */

        if (isset($this->request->post['show_notify'])) {
            $this->data['show_notify'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_notify'])) {
            $this->data['show_notify'] = false;
        } else {
            $this->data['show_notify'] = $this->setting->get('show_notify');
        }

        /* Online */

        if (isset($this->request->post['show_online'])) {
            $this->data['show_online'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_online'])) {
            $this->data['show_online'] = false;
        } else {
            $this->data['show_online'] = $this->setting->get('show_online');
        }

        if (isset($this->request->post['online_refresh_enabled'])) {
            $this->data['online_refresh_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['online_refresh_enabled'])) {
            $this->data['online_refresh_enabled'] = false;
        } else {
            $this->data['online_refresh_enabled'] = $this->setting->get('online_refresh_enabled');
        }

        if (isset($this->request->post['online_refresh_interval'])) {
            $this->data['online_refresh_interval'] = $this->request->post['online_refresh_interval'];
        } else {
            $this->data['online_refresh_interval'] = $this->setting->get('online_refresh_interval');
        }

        if (isset($this->error['online_refresh_interval'])) {
            $this->data['error_online_refresh_interval'] = $this->error['online_refresh_interval'];
        } else {
            $this->data['error_online_refresh_interval'] = '';
        }

        /* Pagination */

        if (isset($this->request->post['show_pagination'])) {
            $this->data['show_pagination'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_pagination'])) {
            $this->data['show_pagination'] = false;
        } else {
            $this->data['show_pagination'] = $this->setting->get('show_pagination');
        }

        if (isset($this->request->post['pagination_type'])) {
            $this->data['pagination_type'] = $this->request->post['pagination_type'];
        } else {
            $this->data['pagination_type'] = $this->setting->get('pagination_type');
        }

        if (isset($this->request->post['pagination_amount'])) {
            $this->data['pagination_amount'] = $this->request->post['pagination_amount'];
        } else {
            $this->data['pagination_amount'] = $this->setting->get('pagination_amount');
        }

        if (isset($this->request->post['pagination_range'])) {
            $this->data['pagination_range'] = $this->request->post['pagination_range'];
        } else {
            $this->data['pagination_range'] = $this->setting->get('pagination_range');
        }

        if (isset($this->error['pagination_type'])) {
            $this->data['error_pagination_type'] = $this->error['pagination_type'];
        } else {
            $this->data['error_pagination_type'] = '';
        }

        if (isset($this->error['pagination_amount'])) {
            $this->data['error_pagination_amount'] = $this->error['pagination_amount'];
        } else {
            $this->data['error_pagination_amount'] = '';
        }

        if (isset($this->error['pagination_range'])) {
            $this->data['error_pagination_range'] = $this->error['pagination_range'];
        } else {
            $this->data['error_pagination_range'] = '';
        }

        /* Page Number */

        if (isset($this->request->post['show_page_number'])) {
            $this->data['show_page_number'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_page_number'])) {
            $this->data['show_page_number'] = false;
        } else {
            $this->data['show_page_number'] = $this->setting->get('show_page_number');
        }

        if (isset($this->request->post['page_number_format'])) {
            $this->data['page_number_format'] = $this->request->post['page_number_format'];
        } else {
            $this->data['page_number_format'] = $this->setting->get('page_number_format');
        }

        if (isset($this->error['page_number_format'])) {
            $this->data['error_page_number_format'] = $this->error['page_number_format'];
        } else {
            $this->data['error_page_number_format'] = '';
        }

        /* RSS */

        if (isset($this->request->post['show_rss'])) {
            $this->data['show_rss'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_rss'])) {
            $this->data['show_rss'] = false;
        } else {
            $this->data['show_rss'] = $this->setting->get('show_rss');
        }

        if (isset($this->request->post['rss_new_window'])) {
            $this->data['rss_new_window'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['rss_new_window'])) {
            $this->data['rss_new_window'] = false;
        } else {
            $this->data['rss_new_window'] = $this->setting->get('rss_new_window');
        }

        if (isset($this->request->post['rss_limit_enabled'])) {
            $this->data['rss_limit_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['rss_limit_enabled'])) {
            $this->data['rss_limit_enabled'] = false;
        } else {
            $this->data['rss_limit_enabled'] = $this->setting->get('rss_limit_enabled');
        }

        if (isset($this->request->post['rss_limit_amount'])) {
            $this->data['rss_limit_amount'] = $this->request->post['rss_limit_amount'];
        } else {
            $this->data['rss_limit_amount'] = $this->setting->get('rss_limit_amount');
        }

        if (isset($this->error['rss_limit_amount'])) {
            $this->data['error_rss_limit_amount'] = $this->error['rss_limit_amount'];
        } else {
            $this->data['error_rss_limit_amount'] = '';
        }

        /* Search */

        if (isset($this->request->post['show_search'])) {
            $this->data['show_search'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_search'])) {
            $this->data['show_search'] = false;
        } else {
            $this->data['show_search'] = $this->setting->get('show_search');
        }

        /* Social */

        if (isset($this->request->post['show_social'])) {
            $this->data['show_social'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_social'])) {
            $this->data['show_social'] = false;
        } else {
            $this->data['show_social'] = $this->setting->get('show_social');
        }

        if (isset($this->request->post['social_new_window'])) {
            $this->data['social_new_window'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['social_new_window'])) {
            $this->data['social_new_window'] = false;
        } else {
            $this->data['social_new_window'] = $this->setting->get('social_new_window');
        }

        if (isset($this->request->post['show_social_digg'])) {
            $this->data['show_social_digg'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_social_digg'])) {
            $this->data['show_social_digg'] = false;
        } else {
            $this->data['show_social_digg'] = $this->setting->get('show_social_digg');
        }

        if (isset($this->request->post['show_social_facebook'])) {
            $this->data['show_social_facebook'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_social_facebook'])) {
            $this->data['show_social_facebook'] = false;
        } else {
            $this->data['show_social_facebook'] = $this->setting->get('show_social_facebook');
        }

        if (isset($this->request->post['show_social_linkedin'])) {
            $this->data['show_social_linkedin'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_social_linkedin'])) {
            $this->data['show_social_linkedin'] = false;
        } else {
            $this->data['show_social_linkedin'] = $this->setting->get('show_social_linkedin');
        }

        if (isset($this->request->post['show_social_reddit'])) {
            $this->data['show_social_reddit'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_social_reddit'])) {
            $this->data['show_social_reddit'] = false;
        } else {
            $this->data['show_social_reddit'] = $this->setting->get('show_social_reddit');
        }

        if (isset($this->request->post['show_social_twitter'])) {
            $this->data['show_social_twitter'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_social_twitter'])) {
            $this->data['show_social_twitter'] = false;
        } else {
            $this->data['show_social_twitter'] = $this->setting->get('show_social_twitter');
        }

        if (isset($this->request->post['show_social_weibo'])) {
            $this->data['show_social_weibo'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_social_weibo'])) {
            $this->data['show_social_weibo'] = false;
        } else {
            $this->data['show_social_weibo'] = $this->setting->get('show_social_weibo');
        }

        $this->data['socials'] = $this->model_settings_layout_comments->getSocials();

        /* Sort By */

        if (isset($this->request->post['show_sort_by'])) {
            $this->data['show_sort_by'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_sort_by'])) {
            $this->data['show_sort_by'] = false;
        } else {
            $this->data['show_sort_by'] = $this->setting->get('show_sort_by');
        }

        if (isset($this->request->post['show_sort_by_1'])) {
            $this->data['show_sort_by_1'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_sort_by_1'])) {
            $this->data['show_sort_by_1'] = false;
        } else {
            $this->data['show_sort_by_1'] = $this->setting->get('show_sort_by_1');
        }

        if (isset($this->request->post['show_sort_by_2'])) {
            $this->data['show_sort_by_2'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_sort_by_2'])) {
            $this->data['show_sort_by_2'] = false;
        } else {
            $this->data['show_sort_by_2'] = $this->setting->get('show_sort_by_2');
        }

        if (isset($this->request->post['show_sort_by_3'])) {
            $this->data['show_sort_by_3'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_sort_by_3'])) {
            $this->data['show_sort_by_3'] = false;
        } else {
            $this->data['show_sort_by_3'] = $this->setting->get('show_sort_by_3');
        }

        if (isset($this->request->post['show_sort_by_4'])) {
            $this->data['show_sort_by_4'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_sort_by_4'])) {
            $this->data['show_sort_by_4'] = false;
        } else {
            $this->data['show_sort_by_4'] = $this->setting->get('show_sort_by_4');
        }

        if (isset($this->request->post['show_sort_by_5'])) {
            $this->data['show_sort_by_5'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_sort_by_5'])) {
            $this->data['show_sort_by_5'] = false;
        } else {
            $this->data['show_sort_by_5'] = $this->setting->get('show_sort_by_5');
        }

        if (isset($this->request->post['show_sort_by_6'])) {
            $this->data['show_sort_by_6'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_sort_by_6'])) {
            $this->data['show_sort_by_6'] = false;
        } else {
            $this->data['show_sort_by_6'] = $this->setting->get('show_sort_by_6');
        }

        /* Topic */

        if (isset($this->request->post['show_topic'])) {
            $this->data['show_topic'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['show_topic'])) {
            $this->data['show_topic'] = false;
        } else {
            $this->data['show_topic'] = $this->setting->get('show_topic');
        }

        $this->data['elements'] = array(
            array(
                'name'    => $this->data['lang_select_none'],
                'value'   => '',
                'enabled' => true
            ),
            array(
                'name'    => $this->data['lang_select_average_rating'],
                'value'   => 'average_rating',
                'enabled' => ($this->data['show_average_rating'] ? true : false)
            ),
            array(
                'name'    => $this->data['lang_select_custom'],
                'value'   => 'custom',
                'enabled' => ($this->data['show_custom'] ? true : false)
            ),
            array(
                'name'    => $this->data['lang_select_notify'],
                'value'   => 'notify',
                'enabled' => ($this->data['show_notify'] ? true : false)
            ),
            array(
                'name'    => $this->data['lang_select_online'],
                'value'   => 'online',
                'enabled' => ($this->data['show_online'] && $this->setting->get('viewers_enabled') ? true : false)
            ),
            array(
                'name'    => $this->data['lang_select_page_number'],
                'value'   => 'page_number',
                'enabled' => ($this->data['show_page_number'] && $this->data['show_pagination'] && $this->data['pagination_type'] == 'multiple' ? true : false)
            ),
            array(
                'name'    => $this->data['lang_select_pagination'],
                'value'   => 'pagination',
                'enabled' => ($this->data['show_pagination'] && $this->data['pagination_type'] == 'multiple' ? true : false)
            ),
            array(
                'name'    => $this->data['lang_select_rss'],
                'value'   => 'rss',
                'enabled' => ($this->data['show_rss'] ? true : false)
            ),
            array(
                'name'    => $this->data['lang_select_search'],
                'value'   => 'search',
                'enabled' => ($this->data['show_search'] ? true : false)
            ),
            array(
                'name'    => $this->data['lang_select_social'],
                'value'   => 'social',
                'enabled' => ($this->data['show_social'] ? true : false)
            ),
            array(
                'name'    => $this->data['lang_select_sort_by'],
                'value'   => 'sort_by',
                'enabled' => ($this->data['show_sort_by'] ? true : false)
            ),
            array(
                'name'    => $this->data['lang_select_topic'],
                'value'   => 'topic',
                'enabled' => ($this->data['show_topic'] ? true : false)
            )
        );

        if ($this->data['show_online'] && !$this->setting->get('viewers_enabled')) {
            $this->data['info'] = sprintf($this->data['lang_notice'], $this->url->link('settings/viewers'));
        }

        $this->data['layout_detect'] = $this->setting->get('layout_detect');

        if ($this->data['layout_detect']) {
            $layout_settings = $this->model_settings_layout_comments->checkLayoutSettings();

            if ($layout_settings['enabled']) {
                $this->data['layout_settings'] = $layout_settings['enabled'];

                $this->data['lang_dialog_content'] = sprintf($this->data['lang_dialog_content_enabled'], $this->url->link('settings/layout_form'));
            } else if ($layout_settings['disabled']) {
                $this->data['layout_settings'] = $layout_settings['disabled'];

                $this->data['lang_dialog_content'] = sprintf($this->data['lang_dialog_content_disabled'], $this->url->link('settings/layout_form'));
            } else {
                $this->data['layout_settings'] = false;
            }
        }

        $this->components = array('common/header', 'common/footer');

        $this->loadView('settings/layout_comments');
    }

    public function stopLayoutDetect()
    {
        $this->loadModel('settings/layout_comments');

        $this->model_settings_layout_comments->stopLayoutDetect();
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        /* General */

        if (!isset($this->request->post['comments_order']) || !in_array($this->request->post['comments_order'], array('1', '2', '3', '4', '5', '6'))) {
            $this->error['comments_order'] = $this->data['lang_error_selection'];
        }

        $elements = array('', 'average_rating', 'custom', 'notify', 'online', 'page_number', 'pagination', 'rss', 'search', 'social', 'sort_by', 'topic');

        if (!isset($this->request->post['comments_position_1']) || !in_array($this->request->post['comments_position_1'], $elements)) {
            $this->error['comments_position_1'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['comments_position_2']) || !in_array($this->request->post['comments_position_2'], $elements)) {
            $this->error['comments_position_2'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['comments_position_3']) || !in_array($this->request->post['comments_position_3'], $elements)) {
            $this->error['comments_position_3'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['comments_position_4']) || !in_array($this->request->post['comments_position_4'], $elements)) {
            $this->error['comments_position_4'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['comments_position_5']) || !in_array($this->request->post['comments_position_5'], $elements)) {
            $this->error['comments_position_5'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['comments_position_6']) || !in_array($this->request->post['comments_position_6'], $elements)) {
            $this->error['comments_position_6'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['comments_position_7']) || !in_array($this->request->post['comments_position_7'], $elements)) {
            $this->error['comments_position_7'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['comments_position_8']) || !in_array($this->request->post['comments_position_8'], $elements)) {
            $this->error['comments_position_8'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['comments_position_9']) || !in_array($this->request->post['comments_position_9'], $elements)) {
            $this->error['comments_position_9'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['comments_position_10']) || !in_array($this->request->post['comments_position_10'], $elements)) {
            $this->error['comments_position_10'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['comments_position_11']) || !in_array($this->request->post['comments_position_11'], $elements)) {
            $this->error['comments_position_11'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['comments_position_12']) || !in_array($this->request->post['comments_position_12'], $elements)) {
            $this->error['comments_position_12'] = $this->data['lang_error_selection'];
        }

        /* Avatar */

        if (!isset($this->request->post['avatar_type']) || !in_array($this->request->post['avatar_type'], array('', 'gravatar', 'login', 'selection', 'upload'))) {
            $this->error['avatar_type'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['gravatar_default']) || !in_array($this->request->post['gravatar_default'], array('', 'custom', 'mm', 'identicon', 'monsterid', 'wavatar', 'retro', 'robohash'))) {
            $this->error['gravatar_default'] = $this->data['lang_error_selection'];
        }

        if (isset($this->request->post['gravatar_default']) && $this->request->post['gravatar_default'] == 'custom' && isset($this->request->post['gravatar_custom']) && !$this->validation->isUrl($this->request->post['gravatar_custom'])) {
            $this->error['gravatar_custom'] = $this->data['lang_error_url'];
        }

        if (isset($this->request->post['gravatar_custom']) && !empty($this->request->post['gravatar_custom']) && !$this->validation->isUrl($this->request->post['gravatar_custom'])) {
            $this->error['gravatar_custom'] = $this->data['lang_error_url'];
        }

        if (!isset($this->request->post['gravatar_custom']) || $this->validation->length($this->request->post['gravatar_custom']) > 250) {
            $this->error['gravatar_custom'] = sprintf($this->data['lang_error_length'], 0, 250);
        }

        if (!isset($this->request->post['gravatar_size']) || !$this->validation->isInt($this->request->post['gravatar_size']) || $this->request->post['gravatar_size'] < 1 || $this->request->post['gravatar_size'] > 2048) {
            $this->error['gravatar_size'] = sprintf($this->data['lang_error_range'], 1, 2048);
        }

        if (!isset($this->request->post['gravatar_audience']) || !in_array($this->request->post['gravatar_audience'], array('g', 'pg', 'r', 'x'))) {
            $this->error['gravatar_audience'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['avatar_selection_attribution']) || $this->validation->length($this->request->post['avatar_selection_attribution']) > 250) {
            $this->error['avatar_selection_attribution'] = sprintf($this->data['lang_error_length'], 0, 250);
        }

        if (!isset($this->request->post['avatar_upload_min_posts']) || !$this->validation->isInt($this->request->post['avatar_upload_min_posts']) || $this->request->post['avatar_upload_min_posts'] < 0 || $this->request->post['avatar_upload_min_posts'] > 999) {
            $this->error['avatar_upload_min_posts'] = sprintf($this->data['lang_error_range'], 0, 999);
        }

        if (!isset($this->request->post['avatar_upload_min_days']) || !$this->validation->isInt($this->request->post['avatar_upload_min_days']) || $this->request->post['avatar_upload_min_days'] < 0 || $this->request->post['avatar_upload_min_days'] > 999) {
            $this->error['avatar_upload_min_days'] = sprintf($this->data['lang_error_range'], 0, 999);
        }

        if (!isset($this->request->post['avatar_upload_max_size']) || !$this->validation->isFloat($this->request->post['avatar_upload_max_size']) || $this->request->post['avatar_upload_max_size'] < 0.1 || $this->request->post['avatar_upload_max_size'] > 99.9) {
            $this->error['avatar_upload_max_size'] = $this->data['lang_error_max_size'];
        }

        if (!isset($this->request->post['avatar_link_days']) || !$this->validation->isInt($this->request->post['avatar_link_days']) || $this->request->post['avatar_link_days'] < 0 || $this->request->post['avatar_link_days'] > 999) {
            $this->error['avatar_link_days'] = sprintf($this->data['lang_error_range'], 0, 999);
        }

        if (!isset($this->request->post['level_5']) || !$this->validation->isInt($this->request->post['level_5']) || $this->request->post['level_5'] < 0 || $this->request->post['level_5'] > 99999) {
            $this->error['level_5'] = sprintf($this->data['lang_error_range'], 0, 99999);
        }

        if (!isset($this->request->post['level_4']) || !$this->validation->isInt($this->request->post['level_4']) || $this->request->post['level_4'] < 0 || $this->request->post['level_4'] > 99999) {
            $this->error['level_4'] = sprintf($this->data['lang_error_range'], 0, 99999);
        }

        if (!isset($this->request->post['level_3']) || !$this->validation->isInt($this->request->post['level_3']) || $this->request->post['level_3'] < 0 || $this->request->post['level_3'] > 99999) {
            $this->error['level_3'] = sprintf($this->data['lang_error_range'], 0, 99999);
        }

        if (!isset($this->request->post['level_2']) || !$this->validation->isInt($this->request->post['level_2']) || $this->request->post['level_2'] < 0 || $this->request->post['level_2'] > 99999) {
            $this->error['level_2'] = sprintf($this->data['lang_error_range'], 0, 99999);
        }

        if (!isset($this->request->post['level_1']) || !$this->validation->isInt($this->request->post['level_1']) || $this->request->post['level_1'] < 0 || $this->request->post['level_1'] > 99999) {
            $this->error['level_1'] = sprintf($this->data['lang_error_range'], 0, 99999);
        }

        if (!isset($this->request->post['level_0']) || !$this->validation->isInt($this->request->post['level_0']) || $this->request->post['level_0'] < 0 || $this->request->post['level_0'] > 99999) {
            $this->error['level_0'] = sprintf($this->data['lang_error_range'], 0, 99999);
        }

        /* Flag */

        if (!isset($this->request->post['flag_max_per_user']) || !$this->validation->isInt($this->request->post['flag_max_per_user']) || $this->request->post['flag_max_per_user'] < 1 || $this->request->post['flag_max_per_user'] > 1000) {
            $this->error['flag_max_per_user'] = sprintf($this->data['lang_error_range'], 1, 1000);
        }

        if (!isset($this->request->post['flag_min_per_comment']) || !$this->validation->isInt($this->request->post['flag_min_per_comment']) || $this->request->post['flag_min_per_comment'] < 1 || $this->request->post['flag_min_per_comment'] > 1000) {
            $this->error['flag_min_per_comment'] = sprintf($this->data['lang_error_range'], 1, 1000);
        }

        /* Edit */

        if (!isset($this->request->post['max_edits']) || !$this->validation->isInt($this->request->post['max_edits']) || $this->request->post['max_edits'] < 1 || $this->request->post['max_edits'] > 1000) {
            $this->error['max_edits'] = sprintf($this->data['lang_error_range'], 1, 1000);
        }

        /* Reply */

        if (!isset($this->request->post['reply_depth']) || !$this->validation->isInt($this->request->post['reply_depth']) || $this->request->post['reply_depth'] < 1 || $this->request->post['reply_depth'] > 5) {
            $this->error['reply_depth'] = sprintf($this->data['lang_error_range'], 1, 5);
        }

        /* Custom */

        if (!isset($this->request->post['custom_content']) || $this->validation->length($this->request->post['custom_content']) > 250) {
            $this->error['custom_content'] = sprintf($this->data['lang_error_length'], 0, 250);
        }

        /* Online */

        if (!isset($this->request->post['online_refresh_interval']) || !$this->validation->isInt($this->request->post['online_refresh_interval']) || $this->request->post['online_refresh_interval'] < 10 || $this->request->post['online_refresh_interval'] > 999) {
            $this->error['online_refresh_interval'] = sprintf($this->data['lang_error_range'], 10, 999);
        }

        /* Pagination */

        if (!isset($this->request->post['pagination_type']) || !in_array($this->request->post['pagination_type'], array('multiple', 'button', 'infinite'))) {
            $this->error['pagination_type'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['pagination_amount']) || !$this->validation->isInt($this->request->post['pagination_amount']) || $this->request->post['pagination_amount'] < 1 || $this->request->post['pagination_amount'] > 100) {
            $this->error['pagination_amount'] = sprintf($this->data['lang_error_range'], 1, 100);
        }

        if (!isset($this->request->post['pagination_range']) || !$this->validation->isInt($this->request->post['pagination_range']) || $this->request->post['pagination_range'] < 1 || $this->request->post['pagination_range'] > 10) {
            $this->error['pagination_range'] = sprintf($this->data['lang_error_range'], 1, 10);
        }

        /* Page Number */

        if (!isset($this->request->post['page_number_format']) || !in_array($this->request->post['page_number_format'], array('Page X', 'Page X of Y'))) {
            $this->error['page_number_format'] = $this->data['lang_error_selection'];
        }

        /* RSS */

        if (!isset($this->request->post['rss_limit_amount']) || !$this->validation->isInt($this->request->post['rss_limit_amount']) || $this->request->post['rss_limit_amount'] < 1 || $this->request->post['rss_limit_amount'] > 100) {
            $this->error['rss_limit_amount'] = sprintf($this->data['lang_error_range'], 1, 100);
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
