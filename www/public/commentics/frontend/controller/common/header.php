<?php
namespace Commentics;

class CommonHeaderController extends Controller
{
    public function index()
    {
        $this->data['commentics_url'] = $this->url->getCommenticsUrl();

        /* If this is an iFrame integration, force jQuery to be loaded. */
        if ($this->page->isIFrame() && $this->setting->get('jquery_source') == '') {
            $this->setting->set('jquery_source', 'local');
        }

        switch ($this->setting->get('jquery_source')) {
            case '':
                $this->data['jquery'] = '';
                break;
            case 'local':
                $this->data['jquery'] = $this->loadJavascript('jquery/jquery.min.js');
                break;
            case 'google':
                $this->data['jquery'] = '//ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js';
                break;
            default:
                $this->data['jquery'] = '//code.jquery.com/jquery-3.4.1.min.js';
        }

        if ($this->setting->get('enabled_captcha') && $this->setting->get('captcha_type') == 'recaptcha') {
            $this->data['recaptcha_api'] = 'https://www.google.com/recaptcha/api.js';
        } else {
            $this->data['recaptcha_api'] = '';
        }

        if ($this->setting->get('optimize')) {
            $this->data['autodetect'] = $this->data['timeago'] = $this->data['highlight'] = '';

            if ($this->setting->get('jquery_source') == 'local') {
                $this->data['jquery'] = '';

                $this->data['common'] = $this->loadJavascript('common-jq.min.js');
            } else {
                $this->data['common'] = $this->loadJavascript('common.min.js');
            }

            $this->data['stylesheet'] = $this->loadStylesheet('stylesheet.min.css');
        } else {
            if ($this->setting->get('auto_detect')) {
                $this->data['autodetect'] = $this->loadJavascript('autodetect.js');
            } else {
                $this->data['autodetect'] = '';
            }

            if ($this->setting->get('date_auto')) {
                $this->data['timeago'] = $this->data['commentics_url'] . '3rdparty/timeago/timeago.js';
            } else {
                $this->data['timeago'] = '';
            }

            if ($this->setting->get('enabled_bb_code') && ($this->setting->get('enabled_bb_code_code') || ($this->setting->get('enabled_bb_code_php')))) {
                $this->data['highlight'] = $this->data['commentics_url'] . '3rdparty/highlight/highlight.js';
            } else {
                $this->data['highlight'] = '';
            }

            $this->data['common'] = $this->loadJavascript('common.js');

            $this->data['stylesheet'] = $this->loadStylesheet('stylesheet.css');
        }

        $this->data['custom'] = $this->loadCustomCss();

        return $this->data;
    }
}
