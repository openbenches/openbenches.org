<?php
namespace Commentics;

class MainDashboardController extends Controller
{
    public function index()
    {
        if (!$this->setting->get('checklist_complete')) {
            $this->response->redirect('main/checklist');
        }

        $this->loadLanguage('main/dashboard');

        $this->loadModel('main/dashboard');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate()) {
                $this->model_main_dashboard->update($this->request->post, $this->session->data['cmtx_username']);
            }
        }

        if ($this->setting->get('avatar_type') == 'upload' && $this->model_main_dashboard->getNumCommentsApprove() && $this->model_main_dashboard->getNumAvatarsApprove()) {
            $this->data['warning'] = sprintf($this->data['lang_message_approval'], $this->url->link('manage/comments', '&filter_approved=0'), $this->url->link('manage/users', '&filter_avatar_approved=0'));
        } else if ($this->model_main_dashboard->getNumCommentsApprove()) {
            $this->data['warning'] = sprintf($this->data['lang_message_comments'], $this->url->link('manage/comments', '&filter_approved=0'));
        } else if ($this->setting->get('avatar_type') == 'upload' && $this->model_main_dashboard->getNumAvatarsApprove()) {
            $this->data['warning'] = sprintf($this->data['lang_message_avatars'], $this->url->link('manage/users', '&filter_avatar_approved=0'));
        } else if ($this->model_main_dashboard->hasErrors()) {
            $this->data['warning'] = sprintf($this->data['lang_message_errors'], $this->url->link('report/errors'));
        }

        $site_issue = false;

        $current_version = $this->model_main_dashboard->getCurrentVersion();

        if (extension_loaded('curl') || (bool) ini_get('allow_url_fopen')) {
            $latest_version = $this->home->getLatestVersion();

            if ($this->validation->isFloat($latest_version)) {
                if (version_compare($current_version, $latest_version, '<')) {
                    $this->data['version_check'] = array(
                        'type'        => 'negative',
                        'text'        => $this->data['lang_text_version_newer'],
                        'link_href'   => $this->url->link('tool/upgrade'),
                        'link_text'   => $this->data['lang_link_upgrade'],
                        'link_target' => '_self'
                    );
                } else {
                    $this->data['version_check'] = array(
                        'type'        => 'positive',
                        'text'        => $this->data['lang_text_version_latest'],
                        'link_href'   => '',
                        'link_text'   => '',
                        'link_target' => ''
                    );
                }
            } else {
                $site_issue = true;

                $this->data['version_check'] = array(
                    'type'        => 'negative',
                    'text'        => $this->data['lang_text_site_issue'],
                    'link_href'   => $this->url->link('report/version_check'),
                    'link_text'   => $this->data['lang_link_log'],
                    'link_target' => '_self'
                );
            }
        } else {
            $this->data['version_check'] = array(
                'type'        => 'negative',
                'text'        => $this->data['lang_text_unable'],
                'link_href'   => '',
                'link_text'   => '',
                'link_target' => ''
            );
        }

        $this->data['lang_text_last_login'] = sprintf($this->data['lang_text_last_login'], $this->variable->formatDate($this->model_main_dashboard->getLastLogin(), $this->data['lang_time_format'], $this->data), $this->variable->formatDate($this->model_main_dashboard->getLastLogin(), $this->data['lang_date_format'], $this->data));

        $this->data['lang_text_stats_action'] = sprintf($this->data['lang_text_stats_action'], $this->model_main_dashboard->getNumCommentsApprove(), $this->model_main_dashboard->getNumCommentsFlagged());
        $this->data['lang_text_stats_today'] = sprintf($this->data['lang_text_stats_today'], $this->model_main_dashboard->getNumCommentsNew(), $this->model_main_dashboard->getNumSubscriptionsNew(), $this->model_main_dashboard->getNumBansNew());
        $this->data['lang_text_stats_total'] = sprintf($this->data['lang_text_stats_total'], $this->model_main_dashboard->getNumCommentsTotal(), $this->model_main_dashboard->getNumSubscriptionsTotal(), $this->model_main_dashboard->getNumBansTotal());

        if ($this->setting->get('language_backend') == 'english') {
            $this->data['lang_text_stats_action'] = str_replace(array('1 comments that require', '1 reviews that require', '1 testimonials that require'), array('1 comment that requires', '1 review that requires', '1 testimonial that requires'), $this->data['lang_text_stats_action']);
            $this->data['lang_text_stats_action'] = str_replace(array('1 comments are', '1 reviews are', '1 testimonials are'), array('1 comment is', '1 review is', '1 testimonial is'), $this->data['lang_text_stats_action']);

            $this->data['lang_text_stats_today'] = str_replace(array('1 new comments', '1 new reviews', '1 new testimonials'), array('1 new comment', '1 new review', '1 new testimonial'), $this->data['lang_text_stats_today']);
            $this->data['lang_text_stats_today'] = str_replace('1 new subscriptions', '1 new subscription', $this->data['lang_text_stats_today']);
            $this->data['lang_text_stats_today'] = str_replace('1 new bans', '1 new ban', $this->data['lang_text_stats_today']);

            $this->data['lang_text_stats_total'] = str_replace(array('1 comments', '1 reviews', '1 testimonials'), array('1 comment', '1 review', '1 testimonial'), $this->data['lang_text_stats_total']);
            $this->data['lang_text_stats_total'] = str_replace('1 subscriptions', '1 subscription', $this->data['lang_text_stats_total']);
            $this->data['lang_text_stats_total'] = str_replace('1 bans', '1 ban', $this->data['lang_text_stats_total']);
        }

        $this->data['tip_of_the_day'] = $this->model_main_dashboard->getTipOfTheDay();

        if (extension_loaded('curl') || (bool) ini_get('allow_url_fopen')) {
            if ($site_issue) {
                $this->data['news'] = $this->data['lang_text_no_news'];
            } else {
                $news = $this->home->getNews();

                $news = $this->security->encode($news);

                $news = nl2br($news, false);

                $this->data['news'] = $news;
            }
        } else {
            $this->data['news'] = $this->data['lang_text_no_news'];
        }

        $this->data['quick_links'] = $this->model_main_dashboard->getQuickLinks();

        $this->data['licence'] = $this->setting->get('licence');

        if ($this->setting->get('licence')) {
            if (extension_loaded('curl') || (bool) ini_get('allow_url_fopen')) {
                if ($site_issue) {
                    $this->data['licence_result'] = 'valid';
                } else {
                    $check = $this->home->checkLicence($this->setting->get('licence'), $this->setting->get('forum_user'));

                    $check = json_decode($check, true);

                    if (isset($check['result']) && $check['result'] == 'valid') {
                        $this->data['licence_result'] = 'valid';
                    } else {
                        $this->data['licence_result'] = 'invalid';
                    }
                }
            } else {
                $this->data['licence_result'] = 'unable';
            }
        } else {
            $this->data['licence_result'] = 'none';

            if ($this->model_main_dashboard->getDaysInstalled() >= 10) {
                $this->data['info'] = sprintf($this->data['lang_notice'], 'https://commentics.com/pricing');
            }
        }

        if ($this->data['licence_result'] != 'valid') {
            $this->model_main_dashboard->enabledPoweredBy();
        }

        if ($this->setting->has('chart_enabled') && $this->setting->get('chart_enabled')) {
            $this->data['chart_enabled'] = true;

            $this->data['chart_comments'] = $this->model_main_dashboard->getChartComments();

            $this->data['chart_subscriptions'] = $this->model_main_dashboard->getChartSubscriptions();
        } else {
            $this->data['chart_enabled'] = false;
        }

        if ((extension_loaded('curl') || (bool) ini_get('allow_url_fopen')) && !$site_issue) {
            $this->home->callHome();

            $sponsors = $this->home->getSponsors();

            $sponsors = json_decode($sponsors, true);

            $this->data['sponsors'] = $sponsors['sponsors'];
        } else {
            $this->data['sponsors'] = array();
        }

        if (isset($this->request->post['notes'])) {
            $this->data['notes'] = $this->request->post['notes'];
        } else {
            $this->data['notes'] = $this->model_main_dashboard->getNotes();
        }

        if (isset($this->error['notes'])) {
            $this->data['error_notes'] = $this->error['notes'];
        } else {
            $this->data['error_notes'] = '';
        }

        if ($this->setting->get('check_referrer')) {
            $url = $this->url->decode($this->url->getPageUrl());

            $domain = $this->url->decode($this->setting->get('site_domain'));

            if (!$this->variable->stristr($url, $domain)) { // if URL does not contain domain
                $this->model_main_dashboard->disableCheckReferrer();
            }
        }

        $this->data['lang_title_version_check'] = sprintf($this->data['lang_title_version_check'], $current_version);

        $this->data['version_detect'] = $this->setting->get('version_detect');

        if ($this->data['version_detect']) {
            $this->data['version_issue'] = $this->model_main_dashboard->checkVersionIssue($current_version);

            $this->data['lang_dialog_version_content'] = sprintf($this->data['lang_dialog_version_content'], CMTX_VERSION, $current_version, $this->setting->get('commentics_url') . 'install/');
        }

        $this->data['system_detect'] = $this->setting->get('system_detect');

        if ($this->data['system_detect']) {
            $this->data['system_settings'] = $this->model_main_dashboard->checkSystemSettings();

            $this->data['lang_dialog_system_content'] = sprintf($this->data['lang_dialog_system_content'], $this->url->link('settings/system'));
        }

        $this->components = array('common/header', 'common/footer');

        $this->loadView('main/dashboard');
    }

    public function stopVersionDetect()
    {
        $this->loadModel('main/dashboard');

        $this->model_main_dashboard->stopVersionDetect();
    }

    public function stopSystemDetect()
    {
        $this->loadModel('main/dashboard');

        $this->model_main_dashboard->stopSystemDetect();
    }

    private function validate()
    {
        $this->loadModel('common/poster');

        $unpostable = $this->model_common_poster->unpostable($this->data);

        if ($unpostable) {
            $this->data['error'] = $unpostable;

            return false;
        }

        if (!isset($this->request->post['notes']) || $this->validation->length($this->request->post['notes']) < 0 || $this->validation->length($this->request->post['notes']) > 5000) {
            $this->error['notes'] = sprintf($this->data['lang_error_length'], 0, 5000);
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
