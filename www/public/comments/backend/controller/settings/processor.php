<?php
namespace Commentics;

class SettingsProcessorController extends Controller
{
    public function index()
    {
        $this->loadLanguage('settings/processor');

        $this->loadModel('settings/processor');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_settings_processor->update($this->request->post);
            }
        }

        /* Name */

        if (isset($this->request->post['one_name_enabled'])) {
            $this->data['one_name_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['one_name_enabled'])) {
            $this->data['one_name_enabled'] = false;
        } else {
            $this->data['one_name_enabled'] = $this->setting->get('one_name_enabled');
        }

        if (isset($this->request->post['fix_name_enabled'])) {
            $this->data['fix_name_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['fix_name_enabled'])) {
            $this->data['fix_name_enabled'] = false;
        } else {
            $this->data['fix_name_enabled'] = $this->setting->get('fix_name_enabled');
        }

        if (isset($this->request->post['unique_name_enabled'])) {
            $this->data['unique_name_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['unique_name_enabled'])) {
            $this->data['unique_name_enabled'] = false;
        } else {
            $this->data['unique_name_enabled'] = $this->setting->get('unique_name_enabled');
        }

        if (isset($this->request->post['detect_link_in_name_enabled'])) {
            $this->data['detect_link_in_name_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['detect_link_in_name_enabled'])) {
            $this->data['detect_link_in_name_enabled'] = false;
        } else {
            $this->data['detect_link_in_name_enabled'] = $this->setting->get('detect_link_in_name_enabled');
        }

        if (isset($this->request->post['link_in_name_action'])) {
            $this->data['link_in_name_action'] = $this->request->post['link_in_name_action'];
        } else {
            $this->data['link_in_name_action'] = $this->setting->get('link_in_name_action');
        }

        if (isset($this->request->post['reserved_names_enabled'])) {
            $this->data['reserved_names_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['reserved_names_enabled'])) {
            $this->data['reserved_names_enabled'] = false;
        } else {
            $this->data['reserved_names_enabled'] = $this->setting->get('reserved_names_enabled');
        }

        if (isset($this->request->post['reserved_names_action'])) {
            $this->data['reserved_names_action'] = $this->request->post['reserved_names_action'];
        } else {
            $this->data['reserved_names_action'] = $this->setting->get('reserved_names_action');
        }

        if (isset($this->request->post['dummy_names_enabled'])) {
            $this->data['dummy_names_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['dummy_names_enabled'])) {
            $this->data['dummy_names_enabled'] = false;
        } else {
            $this->data['dummy_names_enabled'] = $this->setting->get('dummy_names_enabled');
        }

        if (isset($this->request->post['dummy_names_action'])) {
            $this->data['dummy_names_action'] = $this->request->post['dummy_names_action'];
        } else {
            $this->data['dummy_names_action'] = $this->setting->get('dummy_names_action');
        }

        if (isset($this->request->post['banned_names_enabled'])) {
            $this->data['banned_names_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['banned_names_enabled'])) {
            $this->data['banned_names_enabled'] = false;
        } else {
            $this->data['banned_names_enabled'] = $this->setting->get('banned_names_enabled');
        }

        if (isset($this->request->post['banned_names_action'])) {
            $this->data['banned_names_action'] = $this->request->post['banned_names_action'];
        } else {
            $this->data['banned_names_action'] = $this->setting->get('banned_names_action');
        }

        if (isset($this->error['link_in_name_action'])) {
            $this->data['error_link_in_name_action'] = $this->error['link_in_name_action'];
        } else {
            $this->data['error_link_in_name_action'] = '';
        }

        if (isset($this->error['reserved_names_action'])) {
            $this->data['error_reserved_names_action'] = $this->error['reserved_names_action'];
        } else {
            $this->data['error_reserved_names_action'] = '';
        }

        if (isset($this->error['dummy_names_action'])) {
            $this->data['error_dummy_names_action'] = $this->error['dummy_names_action'];
        } else {
            $this->data['error_dummy_names_action'] = '';
        }

        if (isset($this->error['banned_names_action'])) {
            $this->data['error_banned_names_action'] = $this->error['banned_names_action'];
        } else {
            $this->data['error_banned_names_action'] = '';
        }

        /* Email */

        if (isset($this->request->post['unique_email_enabled'])) {
            $this->data['unique_email_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['unique_email_enabled'])) {
            $this->data['unique_email_enabled'] = false;
        } else {
            $this->data['unique_email_enabled'] = $this->setting->get('unique_email_enabled');
        }

        if (isset($this->request->post['reserved_emails_enabled'])) {
            $this->data['reserved_emails_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['reserved_emails_enabled'])) {
            $this->data['reserved_emails_enabled'] = false;
        } else {
            $this->data['reserved_emails_enabled'] = $this->setting->get('reserved_emails_enabled');
        }

        if (isset($this->request->post['reserved_emails_action'])) {
            $this->data['reserved_emails_action'] = $this->request->post['reserved_emails_action'];
        } else {
            $this->data['reserved_emails_action'] = $this->setting->get('reserved_emails_action');
        }

        if (isset($this->request->post['dummy_emails_enabled'])) {
            $this->data['dummy_emails_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['dummy_emails_enabled'])) {
            $this->data['dummy_emails_enabled'] = false;
        } else {
            $this->data['dummy_emails_enabled'] = $this->setting->get('dummy_emails_enabled');
        }

        if (isset($this->request->post['dummy_emails_action'])) {
            $this->data['dummy_emails_action'] = $this->request->post['dummy_emails_action'];
        } else {
            $this->data['dummy_emails_action'] = $this->setting->get('dummy_emails_action');
        }

        if (isset($this->request->post['banned_emails_enabled'])) {
            $this->data['banned_emails_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['banned_emails_enabled'])) {
            $this->data['banned_emails_enabled'] = false;
        } else {
            $this->data['banned_emails_enabled'] = $this->setting->get('banned_emails_enabled');
        }

        if (isset($this->request->post['banned_emails_action'])) {
            $this->data['banned_emails_action'] = $this->request->post['banned_emails_action'];
        } else {
            $this->data['banned_emails_action'] = $this->setting->get('banned_emails_action');
        }

        if (isset($this->error['reserved_emails_action'])) {
            $this->data['error_reserved_emails_action'] = $this->error['reserved_emails_action'];
        } else {
            $this->data['error_reserved_emails_action'] = '';
        }

        if (isset($this->error['dummy_emails_action'])) {
            $this->data['error_dummy_emails_action'] = $this->error['dummy_emails_action'];
        } else {
            $this->data['error_dummy_emails_action'] = '';
        }

        if (isset($this->error['banned_emails_action'])) {
            $this->data['error_banned_emails_action'] = $this->error['banned_emails_action'];
        } else {
            $this->data['error_banned_emails_action'] = '';
        }

        /* Town */

        if (isset($this->request->post['fix_town_enabled'])) {
            $this->data['fix_town_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['fix_town_enabled'])) {
            $this->data['fix_town_enabled'] = false;
        } else {
            $this->data['fix_town_enabled'] = $this->setting->get('fix_town_enabled');
        }

        if (isset($this->request->post['detect_link_in_town_enabled'])) {
            $this->data['detect_link_in_town_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['detect_link_in_town_enabled'])) {
            $this->data['detect_link_in_town_enabled'] = false;
        } else {
            $this->data['detect_link_in_town_enabled'] = $this->setting->get('detect_link_in_town_enabled');
        }

        if (isset($this->request->post['link_in_town_action'])) {
            $this->data['link_in_town_action'] = $this->request->post['link_in_town_action'];
        } else {
            $this->data['link_in_town_action'] = $this->setting->get('link_in_town_action');
        }

        if (isset($this->request->post['reserved_towns_enabled'])) {
            $this->data['reserved_towns_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['reserved_towns_enabled'])) {
            $this->data['reserved_towns_enabled'] = false;
        } else {
            $this->data['reserved_towns_enabled'] = $this->setting->get('reserved_towns_enabled');
        }

        if (isset($this->request->post['reserved_towns_action'])) {
            $this->data['reserved_towns_action'] = $this->request->post['reserved_towns_action'];
        } else {
            $this->data['reserved_towns_action'] = $this->setting->get('reserved_towns_action');
        }

        if (isset($this->request->post['dummy_towns_enabled'])) {
            $this->data['dummy_towns_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['dummy_towns_enabled'])) {
            $this->data['dummy_towns_enabled'] = false;
        } else {
            $this->data['dummy_towns_enabled'] = $this->setting->get('dummy_towns_enabled');
        }

        if (isset($this->request->post['dummy_towns_action'])) {
            $this->data['dummy_towns_action'] = $this->request->post['dummy_towns_action'];
        } else {
            $this->data['dummy_towns_action'] = $this->setting->get('dummy_towns_action');
        }

        if (isset($this->request->post['banned_towns_enabled'])) {
            $this->data['banned_towns_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['banned_towns_enabled'])) {
            $this->data['banned_towns_enabled'] = false;
        } else {
            $this->data['banned_towns_enabled'] = $this->setting->get('banned_towns_enabled');
        }

        if (isset($this->request->post['banned_towns_action'])) {
            $this->data['banned_towns_action'] = $this->request->post['banned_towns_action'];
        } else {
            $this->data['banned_towns_action'] = $this->setting->get('banned_towns_action');
        }

        if (isset($this->error['link_in_town_action'])) {
            $this->data['error_link_in_town_action'] = $this->error['link_in_town_action'];
        } else {
            $this->data['error_link_in_town_action'] = '';
        }

        if (isset($this->error['reserved_towns_action'])) {
            $this->data['error_reserved_towns_action'] = $this->error['reserved_towns_action'];
        } else {
            $this->data['error_reserved_towns_action'] = '';
        }

        if (isset($this->error['dummy_towns_action'])) {
            $this->data['error_dummy_towns_action'] = $this->error['dummy_towns_action'];
        } else {
            $this->data['error_dummy_towns_action'] = '';
        }

        if (isset($this->error['banned_towns_action'])) {
            $this->data['error_banned_towns_action'] = $this->error['banned_towns_action'];
        } else {
            $this->data['error_banned_towns_action'] = '';
        }

        /* Website */

        if (isset($this->request->post['approve_websites'])) {
            $this->data['approve_websites'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['approve_websites'])) {
            $this->data['approve_websites'] = false;
        } else {
            $this->data['approve_websites'] = $this->setting->get('approve_websites');
        }

        if (isset($this->request->post['validate_website_ping'])) {
            $this->data['validate_website_ping'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['validate_website_ping'])) {
            $this->data['validate_website_ping'] = false;
        } else {
            $this->data['validate_website_ping'] = $this->setting->get('validate_website_ping');
        }

        if (isset($this->request->post['reserved_websites_enabled'])) {
            $this->data['reserved_websites_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['reserved_websites_enabled'])) {
            $this->data['reserved_websites_enabled'] = false;
        } else {
            $this->data['reserved_websites_enabled'] = $this->setting->get('reserved_websites_enabled');
        }

        if (isset($this->request->post['reserved_websites_action'])) {
            $this->data['reserved_websites_action'] = $this->request->post['reserved_websites_action'];
        } else {
            $this->data['reserved_websites_action'] = $this->setting->get('reserved_websites_action');
        }

        if (isset($this->request->post['dummy_websites_enabled'])) {
            $this->data['dummy_websites_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['dummy_websites_enabled'])) {
            $this->data['dummy_websites_enabled'] = false;
        } else {
            $this->data['dummy_websites_enabled'] = $this->setting->get('dummy_websites_enabled');
        }

        if (isset($this->request->post['dummy_websites_action'])) {
            $this->data['dummy_websites_action'] = $this->request->post['dummy_websites_action'];
        } else {
            $this->data['dummy_websites_action'] = $this->setting->get('dummy_websites_action');
        }

        if (isset($this->request->post['banned_websites_as_website_enabled'])) {
            $this->data['banned_websites_as_website_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['banned_websites_as_website_enabled'])) {
            $this->data['banned_websites_as_website_enabled'] = false;
        } else {
            $this->data['banned_websites_as_website_enabled'] = $this->setting->get('banned_websites_as_website_enabled');
        }

        if (isset($this->request->post['banned_websites_as_website_action'])) {
            $this->data['banned_websites_as_website_action'] = $this->request->post['banned_websites_as_website_action'];
        } else {
            $this->data['banned_websites_as_website_action'] = $this->setting->get('banned_websites_as_website_action');
        }

        if (isset($this->error['reserved_websites_action'])) {
            $this->data['error_reserved_websites_action'] = $this->error['reserved_websites_action'];
        } else {
            $this->data['error_reserved_websites_action'] = '';
        }

        if (isset($this->error['dummy_websites_action'])) {
            $this->data['error_dummy_websites_action'] = $this->error['dummy_websites_action'];
        } else {
            $this->data['error_dummy_websites_action'] = '';
        }

        if (isset($this->error['banned_websites_as_website_action'])) {
            $this->data['error_banned_websites_as_website_action'] = $this->error['banned_websites_as_website_action'];
        } else {
            $this->data['error_banned_websites_as_website_action'] = '';
        }

        /* Comment */

        if (isset($this->request->post['approve_images'])) {
            $this->data['approve_images'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['approve_images'])) {
            $this->data['approve_images'] = false;
        } else {
            $this->data['approve_images'] = $this->setting->get('approve_images');
        }

        if (isset($this->request->post['approve_videos'])) {
            $this->data['approve_videos'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['approve_videos'])) {
            $this->data['approve_videos'] = false;
        } else {
            $this->data['approve_videos'] = $this->setting->get('approve_videos');
        }

        if (isset($this->request->post['approve_uploads'])) {
            $this->data['approve_uploads'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['approve_uploads'])) {
            $this->data['approve_uploads'] = false;
        } else {
            $this->data['approve_uploads'] = $this->setting->get('approve_uploads');
        }

        if (isset($this->request->post['comment_convert_links'])) {
            $this->data['comment_convert_links'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['comment_convert_links'])) {
            $this->data['comment_convert_links'] = false;
        } else {
            $this->data['comment_convert_links'] = $this->setting->get('comment_convert_links');
        }

        if (isset($this->request->post['comment_convert_emails'])) {
            $this->data['comment_convert_emails'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['comment_convert_emails'])) {
            $this->data['comment_convert_emails'] = false;
        } else {
            $this->data['comment_convert_emails'] = $this->setting->get('comment_convert_emails');
        }

        if (isset($this->request->post['comment_links_new_window'])) {
            $this->data['comment_links_new_window'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['comment_links_new_window'])) {
            $this->data['comment_links_new_window'] = false;
        } else {
            $this->data['comment_links_new_window'] = $this->setting->get('comment_links_new_window');
        }

        if (isset($this->request->post['comment_links_nofollow'])) {
            $this->data['comment_links_nofollow'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['comment_links_nofollow'])) {
            $this->data['comment_links_nofollow'] = false;
        } else {
            $this->data['comment_links_nofollow'] = $this->setting->get('comment_links_nofollow');
        }

        if (isset($this->request->post['comment_minimum_characters'])) {
            $this->data['comment_minimum_characters'] = $this->request->post['comment_minimum_characters'];
        } else {
            $this->data['comment_minimum_characters'] = $this->setting->get('comment_minimum_characters');
        }

        if (isset($this->request->post['comment_minimum_words'])) {
            $this->data['comment_minimum_words'] = $this->request->post['comment_minimum_words'];
        } else {
            $this->data['comment_minimum_words'] = $this->setting->get('comment_minimum_words');
        }

        if (isset($this->request->post['comment_maximum_characters'])) {
            $this->data['comment_maximum_characters'] = $this->request->post['comment_maximum_characters'];
        } else {
            $this->data['comment_maximum_characters'] = $this->setting->get('comment_maximum_characters');
        }

        if (isset($this->request->post['comment_maximum_lines'])) {
            $this->data['comment_maximum_lines'] = $this->request->post['comment_maximum_lines'];
        } else {
            $this->data['comment_maximum_lines'] = $this->setting->get('comment_maximum_lines');
        }

        if (isset($this->request->post['comment_maximum_smilies'])) {
            $this->data['comment_maximum_smilies'] = $this->request->post['comment_maximum_smilies'];
        } else {
            $this->data['comment_maximum_smilies'] = $this->setting->get('comment_maximum_smilies');
        }

        if (isset($this->request->post['comment_long_word'])) {
            $this->data['comment_long_word'] = $this->request->post['comment_long_word'];
        } else {
            $this->data['comment_long_word'] = $this->setting->get('comment_long_word');
        }

        if (isset($this->request->post['comment_line_breaks'])) {
            $this->data['comment_line_breaks'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['comment_line_breaks'])) {
            $this->data['comment_line_breaks'] = false;
        } else {
            $this->data['comment_line_breaks'] = $this->setting->get('comment_line_breaks');
        }

        if (isset($this->request->post['detect_link_in_comment_enabled'])) {
            $this->data['detect_link_in_comment_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['detect_link_in_comment_enabled'])) {
            $this->data['detect_link_in_comment_enabled'] = false;
        } else {
            $this->data['detect_link_in_comment_enabled'] = $this->setting->get('detect_link_in_comment_enabled');
        }

        if (isset($this->request->post['link_in_comment_action'])) {
            $this->data['link_in_comment_action'] = $this->request->post['link_in_comment_action'];
        } else {
            $this->data['link_in_comment_action'] = $this->setting->get('link_in_comment_action');
        }

        if (isset($this->request->post['banned_websites_as_comment_enabled'])) {
            $this->data['banned_websites_as_comment_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['banned_websites_as_comment_enabled'])) {
            $this->data['banned_websites_as_comment_enabled'] = false;
        } else {
            $this->data['banned_websites_as_comment_enabled'] = $this->setting->get('banned_websites_as_comment_enabled');
        }

        if (isset($this->request->post['banned_websites_as_comment_action'])) {
            $this->data['banned_websites_as_comment_action'] = $this->request->post['banned_websites_as_comment_action'];
        } else {
            $this->data['banned_websites_as_comment_action'] = $this->setting->get('banned_websites_as_comment_action');
        }

        if (isset($this->error['comment_minimum_characters'])) {
            $this->data['error_comment_minimum_characters'] = $this->error['comment_minimum_characters'];
        } else {
            $this->data['error_comment_minimum_characters'] = '';
        }

        if (isset($this->error['comment_minimum_words'])) {
            $this->data['error_comment_minimum_words'] = $this->error['comment_minimum_words'];
        } else {
            $this->data['error_comment_minimum_words'] = '';
        }

        if (isset($this->error['comment_maximum_characters'])) {
            $this->data['error_comment_maximum_characters'] = $this->error['comment_maximum_characters'];
        } else {
            $this->data['error_comment_maximum_characters'] = '';
        }

        if (isset($this->error['comment_maximum_lines'])) {
            $this->data['error_comment_maximum_lines'] = $this->error['comment_maximum_lines'];
        } else {
            $this->data['error_comment_maximum_lines'] = '';
        }

        if (isset($this->error['comment_maximum_smilies'])) {
            $this->data['error_comment_maximum_smilies'] = $this->error['comment_maximum_smilies'];
        } else {
            $this->data['error_comment_maximum_smilies'] = '';
        }

        if (isset($this->error['comment_long_word'])) {
            $this->data['error_comment_long_word'] = $this->error['comment_long_word'];
        } else {
            $this->data['error_comment_long_word'] = '';
        }

        if (isset($this->error['link_in_comment_action'])) {
            $this->data['error_link_in_comment_action'] = $this->error['link_in_comment_action'];
        } else {
            $this->data['error_link_in_comment_action'] = '';
        }

        if (isset($this->error['banned_websites_as_comment_action'])) {
            $this->data['error_banned_websites_as_comment_action'] = $this->error['banned_websites_as_comment_action'];
        } else {
            $this->data['error_banned_websites_as_comment_action'] = '';
        }

        /* Headline */

        if (isset($this->request->post['headline_minimum_characters'])) {
            $this->data['headline_minimum_characters'] = $this->request->post['headline_minimum_characters'];
        } else {
            $this->data['headline_minimum_characters'] = $this->setting->get('headline_minimum_characters');
        }

        if (isset($this->request->post['headline_minimum_words'])) {
            $this->data['headline_minimum_words'] = $this->request->post['headline_minimum_words'];
        } else {
            $this->data['headline_minimum_words'] = $this->setting->get('headline_minimum_words');
        }

        if (isset($this->request->post['headline_maximum_characters'])) {
            $this->data['headline_maximum_characters'] = $this->request->post['headline_maximum_characters'];
        } else {
            $this->data['headline_maximum_characters'] = $this->setting->get('headline_maximum_characters');
        }

        if (isset($this->request->post['detect_link_in_headline_enabled'])) {
            $this->data['detect_link_in_headline_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['detect_link_in_headline_enabled'])) {
            $this->data['detect_link_in_headline_enabled'] = false;
        } else {
            $this->data['detect_link_in_headline_enabled'] = $this->setting->get('detect_link_in_headline_enabled');
        }

        if (isset($this->request->post['link_in_headline_action'])) {
            $this->data['link_in_headline_action'] = $this->request->post['link_in_headline_action'];
        } else {
            $this->data['link_in_headline_action'] = $this->setting->get('link_in_headline_action');
        }

        if (isset($this->request->post['banned_websites_as_headline_enabled'])) {
            $this->data['banned_websites_as_headline_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['banned_websites_as_headline_enabled'])) {
            $this->data['banned_websites_as_headline_enabled'] = false;
        } else {
            $this->data['banned_websites_as_headline_enabled'] = $this->setting->get('banned_websites_as_headline_enabled');
        }

        if (isset($this->request->post['banned_websites_as_headline_action'])) {
            $this->data['banned_websites_as_headline_action'] = $this->request->post['banned_websites_as_headline_action'];
        } else {
            $this->data['banned_websites_as_headline_action'] = $this->setting->get('banned_websites_as_headline_action');
        }

        if (isset($this->error['headline_minimum_characters'])) {
            $this->data['error_headline_minimum_characters'] = $this->error['headline_minimum_characters'];
        } else {
            $this->data['error_headline_minimum_characters'] = '';
        }

        if (isset($this->error['headline_minimum_words'])) {
            $this->data['error_headline_minimum_words'] = $this->error['headline_minimum_words'];
        } else {
            $this->data['error_headline_minimum_words'] = '';
        }

        if (isset($this->error['headline_maximum_characters'])) {
            $this->data['error_headline_maximum_characters'] = $this->error['headline_maximum_characters'];
        } else {
            $this->data['error_headline_maximum_characters'] = '';
        }

        if (isset($this->error['link_in_headline_action'])) {
            $this->data['error_link_in_headline_action'] = $this->error['link_in_headline_action'];
        } else {
            $this->data['error_link_in_headline_action'] = '';
        }

        if (isset($this->error['banned_websites_as_headline_action'])) {
            $this->data['error_banned_websites_as_headline_action'] = $this->error['banned_websites_as_headline_action'];
        } else {
            $this->data['error_banned_websites_as_headline_action'] = '';
        }

        /* Notify */

        if (isset($this->request->post['notify_type'])) {
            $this->data['notify_type'] = $this->request->post['notify_type'];
        } else {
            $this->data['notify_type'] = $this->setting->get('notify_type');
        }

        if (isset($this->request->post['notify_format'])) {
            $this->data['notify_format'] = $this->request->post['notify_format'];
        } else {
            $this->data['notify_format'] = $this->setting->get('notify_format');
        }

        if (isset($this->error['notify_type'])) {
            $this->data['error_notify_type'] = $this->error['notify_type'];
        } else {
            $this->data['error_notify_type'] = '';
        }

        if (isset($this->error['notify_format'])) {
            $this->data['error_notify_format'] = $this->error['notify_format'];
        } else {
            $this->data['error_notify_format'] = '';
        }

        /* Cookie */

        if (isset($this->request->post['form_cookie'])) {
            $this->data['form_cookie'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['form_cookie'])) {
            $this->data['form_cookie'] = false;
        } else {
            $this->data['form_cookie'] = $this->setting->get('form_cookie');
        }

        if (isset($this->request->post['form_cookie_days'])) {
            $this->data['form_cookie_days'] = $this->request->post['form_cookie_days'];
        } else {
            $this->data['form_cookie_days'] = $this->setting->get('form_cookie_days');
        }

        if (isset($this->error['form_cookie_days'])) {
            $this->data['error_form_cookie_days'] = $this->error['form_cookie_days'];
        } else {
            $this->data['error_form_cookie_days'] = '';
        }

        /* Other */

        if (isset($this->request->post['check_capitals_enabled'])) {
            $this->data['check_capitals_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['check_capitals_enabled'])) {
            $this->data['check_capitals_enabled'] = false;
        } else {
            $this->data['check_capitals_enabled'] = $this->setting->get('check_capitals_enabled');
        }

        if (isset($this->request->post['check_capitals_percentage'])) {
            $this->data['check_capitals_percentage'] = $this->request->post['check_capitals_percentage'];
        } else {
            $this->data['check_capitals_percentage'] = $this->setting->get('check_capitals_percentage');
        }

        if (isset($this->request->post['check_capitals_action'])) {
            $this->data['check_capitals_action'] = $this->request->post['check_capitals_action'];
        } else {
            $this->data['check_capitals_action'] = $this->setting->get('check_capitals_action');
        }

        if (isset($this->request->post['check_repeats_enabled'])) {
            $this->data['check_repeats_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['check_repeats_enabled'])) {
            $this->data['check_repeats_enabled'] = false;
        } else {
            $this->data['check_repeats_enabled'] = $this->setting->get('check_repeats_enabled');
        }

        if (isset($this->request->post['check_repeats_amount'])) {
            $this->data['check_repeats_amount'] = $this->request->post['check_repeats_amount'];
        } else {
            $this->data['check_repeats_amount'] = $this->setting->get('check_repeats_amount');
        }

        if (isset($this->request->post['check_repeats_action'])) {
            $this->data['check_repeats_action'] = $this->request->post['check_repeats_action'];
        } else {
            $this->data['check_repeats_action'] = $this->setting->get('check_repeats_action');
        }

        if (isset($this->request->post['spam_words_enabled'])) {
            $this->data['spam_words_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['spam_words_enabled'])) {
            $this->data['spam_words_enabled'] = false;
        } else {
            $this->data['spam_words_enabled'] = $this->setting->get('spam_words_enabled');
        }

        if (isset($this->request->post['spam_words_action'])) {
            $this->data['spam_words_action'] = $this->request->post['spam_words_action'];
        } else {
            $this->data['spam_words_action'] = $this->setting->get('spam_words_action');
        }

        if (isset($this->request->post['mild_swear_words_enabled'])) {
            $this->data['mild_swear_words_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['mild_swear_words_enabled'])) {
            $this->data['mild_swear_words_enabled'] = false;
        } else {
            $this->data['mild_swear_words_enabled'] = $this->setting->get('mild_swear_words_enabled');
        }

        if (isset($this->request->post['mild_swear_words_action'])) {
            $this->data['mild_swear_words_action'] = $this->request->post['mild_swear_words_action'];
        } else {
            $this->data['mild_swear_words_action'] = $this->setting->get('mild_swear_words_action');
        }

        if (isset($this->request->post['strong_swear_words_enabled'])) {
            $this->data['strong_swear_words_enabled'] = true;
        } else if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['strong_swear_words_enabled'])) {
            $this->data['strong_swear_words_enabled'] = false;
        } else {
            $this->data['strong_swear_words_enabled'] = $this->setting->get('strong_swear_words_enabled');
        }

        if (isset($this->request->post['strong_swear_words_action'])) {
            $this->data['strong_swear_words_action'] = $this->request->post['strong_swear_words_action'];
        } else {
            $this->data['strong_swear_words_action'] = $this->setting->get('strong_swear_words_action');
        }

        if (isset($this->request->post['swear_word_masking'])) {
            $this->data['swear_word_masking'] = $this->request->post['swear_word_masking'];
        } else {
            $this->data['swear_word_masking'] = $this->setting->get('swear_word_masking');
        }

        if (isset($this->error['check_capitals_percentage'])) {
            $this->data['error_check_capitals_percentage'] = $this->error['check_capitals_percentage'];
        } else {
            $this->data['error_check_capitals_percentage'] = '';
        }

        if (isset($this->error['check_capitals_action'])) {
            $this->data['error_check_capitals_action'] = $this->error['check_capitals_action'];
        } else {
            $this->data['error_check_capitals_action'] = '';
        }

        if (isset($this->error['check_repeats_amount'])) {
            $this->data['error_check_repeats_amount'] = $this->error['check_repeats_amount'];
        } else {
            $this->data['error_check_repeats_amount'] = '';
        }

        if (isset($this->error['check_repeats_action'])) {
            $this->data['error_check_repeats_action'] = $this->error['check_repeats_action'];
        } else {
            $this->data['error_check_repeats_action'] = '';
        }

        if (isset($this->error['spam_words_action'])) {
            $this->data['error_spam_words_action'] = $this->error['spam_words_action'];
        } else {
            $this->data['error_spam_words_action'] = '';
        }

        if (isset($this->error['mild_swear_words_action'])) {
            $this->data['error_mild_swear_words_action'] = $this->error['mild_swear_words_action'];
        } else {
            $this->data['error_mild_swear_words_action'] = '';
        }

        if (isset($this->error['strong_swear_words_action'])) {
            $this->data['error_strong_swear_words_action'] = $this->error['strong_swear_words_action'];
        } else {
            $this->data['error_strong_swear_words_action'] = '';
        }

        if (isset($this->error['swear_word_masking'])) {
            $this->data['error_swear_word_masking'] = $this->error['swear_word_masking'];
        } else {
            $this->data['error_swear_word_masking'] = '';
        }

        $this->data['link_detect_links'] = $this->url->link('data/list', '&type=detect_links');

        $this->data['link_reserved_names'] = $this->url->link('data/list', '&type=reserved_names');

        $this->data['link_dummy_names'] = $this->url->link('data/list', '&type=dummy_names');

        $this->data['link_banned_names'] = $this->url->link('data/list', '&type=banned_names');

        $this->data['link_reserved_emails'] = $this->url->link('data/list', '&type=reserved_emails');

        $this->data['link_dummy_emails'] = $this->url->link('data/list', '&type=dummy_emails');

        $this->data['link_banned_emails'] = $this->url->link('data/list', '&type=banned_emails');

        $this->data['link_reserved_towns'] = $this->url->link('data/list', '&type=reserved_towns');

        $this->data['link_dummy_towns'] = $this->url->link('data/list', '&type=dummy_towns');

        $this->data['link_banned_towns'] = $this->url->link('data/list', '&type=banned_towns');

        $this->data['link_reserved_websites'] = $this->url->link('data/list', '&type=reserved_websites');

        $this->data['link_dummy_websites'] = $this->url->link('data/list', '&type=dummy_websites');

        $this->data['link_banned_websites'] = $this->url->link('data/list', '&type=banned_websites');

        $this->data['link_spam_words'] = $this->url->link('data/list', '&type=spam_words');

        $this->data['link_mild_swear_words'] = $this->url->link('data/list', '&type=mild_swear_words');

        $this->data['link_strong_swear_words'] = $this->url->link('data/list', '&type=strong_swear_words');

        $this->components = array('common/header', 'common/footer');

        $this->loadView('settings/processor');
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        /* Name */

        if (!isset($this->request->post['link_in_name_action']) || !in_array($this->request->post['link_in_name_action'], array('error', 'approve', 'ban'))) {
            $this->error['link_in_name_action'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['reserved_names_action']) || !in_array($this->request->post['reserved_names_action'], array('error', 'approve', 'ban'))) {
            $this->error['reserved_names_action'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['dummy_names_action']) || !in_array($this->request->post['dummy_names_action'], array('error', 'approve', 'ban'))) {
            $this->error['dummy_names_action'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['banned_names_action']) || !in_array($this->request->post['banned_names_action'], array('error', 'approve', 'ban'))) {
            $this->error['banned_names_action'] = $this->data['lang_error_selection'];
        }

        /* Email */

        if (!isset($this->request->post['reserved_emails_action']) || !in_array($this->request->post['reserved_emails_action'], array('error', 'approve', 'ban'))) {
            $this->error['reserved_emails_action'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['dummy_emails_action']) || !in_array($this->request->post['dummy_emails_action'], array('error', 'approve', 'ban'))) {
            $this->error['dummy_emails_action'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['banned_emails_action']) || !in_array($this->request->post['banned_emails_action'], array('error', 'approve', 'ban'))) {
            $this->error['banned_emails_action'] = $this->data['lang_error_selection'];
        }

        /* Town */

        if (!isset($this->request->post['reserved_towns_action']) || !in_array($this->request->post['reserved_towns_action'], array('error', 'approve', 'ban'))) {
            $this->error['reserved_towns_action'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['dummy_towns_action']) || !in_array($this->request->post['dummy_towns_action'], array('error', 'approve', 'ban'))) {
            $this->error['dummy_towns_action'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['banned_towns_action']) || !in_array($this->request->post['banned_towns_action'], array('error', 'approve', 'ban'))) {
            $this->error['banned_towns_action'] = $this->data['lang_error_selection'];
        }

        /* Website */

        if (!isset($this->request->post['reserved_websites_action']) || !in_array($this->request->post['reserved_websites_action'], array('error', 'approve', 'ban'))) {
            $this->error['reserved_websites_action'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['dummy_websites_action']) || !in_array($this->request->post['dummy_websites_action'], array('error', 'approve', 'ban'))) {
            $this->error['dummy_websites_action'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['banned_websites_as_website_action']) || !in_array($this->request->post['banned_websites_as_website_action'], array('error', 'approve', 'ban'))) {
            $this->error['banned_websites_as_website_action'] = $this->data['lang_error_selection'];
        }

        /* Comment */

        if (!isset($this->request->post['comment_minimum_characters']) || !$this->validation->isInt($this->request->post['comment_minimum_characters']) || $this->request->post['comment_minimum_characters'] < 1 || $this->request->post['comment_minimum_characters'] > 999) {
            $this->error['comment_minimum_characters'] = sprintf($this->data['lang_error_range'], 1, 999);
        }

        if (!isset($this->request->post['comment_minimum_words']) || !$this->validation->isInt($this->request->post['comment_minimum_words']) || $this->request->post['comment_minimum_words'] < 1 || $this->request->post['comment_minimum_words'] > 999) {
            $this->error['comment_minimum_words'] = sprintf($this->data['lang_error_range'], 1, 999);
        }

        if (!isset($this->request->post['comment_maximum_characters']) || !$this->validation->isInt($this->request->post['comment_maximum_characters']) || $this->request->post['comment_maximum_characters'] < 1 || $this->request->post['comment_maximum_characters'] > 99999) {
            $this->error['comment_maximum_characters'] = sprintf($this->data['lang_error_range'], 1, 99999);
        }

        if (!isset($this->request->post['comment_maximum_lines']) || !$this->validation->isInt($this->request->post['comment_maximum_lines']) || $this->request->post['comment_maximum_lines'] < 1 || $this->request->post['comment_maximum_lines'] > 99999) {
            $this->error['comment_maximum_lines'] = sprintf($this->data['lang_error_range'], 1, 99999);
        }

        if (!isset($this->request->post['comment_maximum_smilies']) || !$this->validation->isInt($this->request->post['comment_maximum_smilies']) || $this->request->post['comment_maximum_smilies'] < 1 || $this->request->post['comment_maximum_smilies'] > 999) {
            $this->error['comment_maximum_smilies'] = sprintf($this->data['lang_error_range'], 1, 999);
        }

        if (!isset($this->request->post['comment_long_word']) || !$this->validation->isInt($this->request->post['comment_long_word']) || $this->request->post['comment_long_word'] < 1 || $this->request->post['comment_long_word'] > 999) {
            $this->error['comment_long_word'] = sprintf($this->data['lang_error_range'], 1, 999);
        }

        if (!isset($this->request->post['link_in_comment_action']) || !in_array($this->request->post['link_in_comment_action'], array('error', 'approve', 'ban'))) {
            $this->error['link_in_comment_action'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['banned_websites_as_comment_action']) || !in_array($this->request->post['banned_websites_as_comment_action'], array('error', 'approve', 'ban'))) {
            $this->error['banned_websites_as_comment_action'] = $this->data['lang_error_selection'];
        }

        /* Headline */

        if (!isset($this->request->post['headline_minimum_characters']) || !$this->validation->isInt($this->request->post['headline_minimum_characters']) || $this->request->post['headline_minimum_characters'] < 1 || $this->request->post['headline_minimum_characters'] > 250) {
            $this->error['headline_minimum_characters'] = sprintf($this->data['lang_error_range'], 1, 250);
        }

        if (!isset($this->request->post['headline_minimum_words']) || !$this->validation->isInt($this->request->post['headline_minimum_words']) || $this->request->post['headline_minimum_words'] < 1 || $this->request->post['headline_minimum_words'] > 250) {
            $this->error['headline_minimum_words'] = sprintf($this->data['lang_error_range'], 1, 250);
        }

        if (!isset($this->request->post['headline_maximum_characters']) || !$this->validation->isInt($this->request->post['headline_maximum_characters']) || $this->request->post['headline_maximum_characters'] < 1 || $this->request->post['headline_maximum_characters'] > 250) {
            $this->error['headline_maximum_characters'] = sprintf($this->data['lang_error_range'], 1, 250);
        }

        if (!isset($this->request->post['link_in_headline_action']) || !in_array($this->request->post['link_in_headline_action'], array('error', 'approve', 'ban'))) {
            $this->error['link_in_headline_action'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['banned_websites_as_headline_action']) || !in_array($this->request->post['banned_websites_as_headline_action'], array('error', 'approve', 'ban'))) {
            $this->error['banned_websites_as_headline_action'] = $this->data['lang_error_selection'];
        }

        /* Notify */

        if (!isset($this->request->post['notify_type']) || !in_array($this->request->post['notify_type'], array('all', 'custom'))) {
            $this->error['notify_type'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['notify_format']) || !in_array($this->request->post['notify_format'], array('html', 'text'))) {
            $this->error['notify_format'] = $this->data['lang_error_selection'];
        }

        /* Cookie */

        if (!isset($this->request->post['form_cookie_days']) || !$this->validation->isInt($this->request->post['form_cookie_days']) || $this->request->post['form_cookie_days'] < 1 || $this->request->post['form_cookie_days'] > 1000) {
            $this->error['form_cookie_days'] = sprintf($this->data['lang_error_range'], 1, 1000);
        }

        /* Other */

        if (!isset($this->request->post['check_capitals_percentage']) || !$this->validation->isInt($this->request->post['check_capitals_percentage']) || $this->request->post['check_capitals_percentage'] < 1 || $this->request->post['check_capitals_percentage'] > 100) {
            $this->error['check_capitals_percentage'] = sprintf($this->data['lang_error_range'], 1, 100);
        }

        if (!isset($this->request->post['check_capitals_action']) || !in_array($this->request->post['check_capitals_action'], array('error', 'approve', 'ban'))) {
            $this->error['check_capitals_action'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['check_repeats_amount']) || !$this->validation->isInt($this->request->post['check_repeats_amount']) || $this->request->post['check_repeats_amount'] < 3 || $this->request->post['check_repeats_amount'] > 100) {
            $this->error['check_repeats_amount'] = sprintf($this->data['lang_error_range'], 3, 100);
        }

        if (!isset($this->request->post['check_repeats_action']) || !in_array($this->request->post['check_repeats_action'], array('error', 'approve', 'ban'))) {
            $this->error['check_repeats_action'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['spam_words_action']) || !in_array($this->request->post['spam_words_action'], array('error', 'approve', 'ban'))) {
            $this->error['spam_words_action'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['mild_swear_words_action']) || !in_array($this->request->post['mild_swear_words_action'], array('mask', 'mask_approve', 'error', 'approve', 'ban'))) {
            $this->error['mild_swear_words_action'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['strong_swear_words_action']) || !in_array($this->request->post['strong_swear_words_action'], array('mask', 'mask_approve', 'error', 'approve', 'ban'))) {
            $this->error['strong_swear_words_action'] = $this->data['lang_error_selection'];
        }

        if (!isset($this->request->post['swear_word_masking']) || $this->validation->length($this->request->post['swear_word_masking']) < 1 || $this->validation->length($this->request->post['swear_word_masking']) > 10) {
            $this->error['swear_word_masking'] = sprintf($this->data['lang_error_length'], 1, 10);
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
