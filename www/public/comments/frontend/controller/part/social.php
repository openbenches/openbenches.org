<?php
namespace Commentics;

class PartSocialController extends Controller
{
    public function index()
    {
        $this->loadLanguage('part/social');

        if ($this->setting->get('social_new_window')) {
            $this->data['new_window'] = 'target="_blank"';
        } else {
            $this->data['new_window'] = '';
        }

        $this->data['show_digg']     = $this->setting->get('show_social_digg');
        $this->data['show_facebook'] = $this->setting->get('show_social_facebook');
        $this->data['show_linkedin'] = $this->setting->get('show_social_linkedin');
        $this->data['show_reddit']   = $this->setting->get('show_social_reddit');
        $this->data['show_twitter']  = $this->setting->get('show_social_twitter');
        $this->data['show_weibo']    = $this->setting->get('show_social_weibo');

        $url = $this->url->encode($this->page->getUrl());

        $reference = $this->url->encode($this->page->getReference());

        $this->data['digg_url']     = 'http://digg.com/submit?url=' . $url . '&title=' . $reference;
        $this->data['facebook_url'] = 'https://www.facebook.com/sharer.php?u=' . $url;
        $this->data['linkedin_url'] = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $url . '&title=' . $reference;
        $this->data['reddit_url']   = 'https://reddit.com/submit?url=' . $url . '&title=' . $reference;
        $this->data['twitter_url']  = 'https://twitter.com/intent/tweet?url=' . $url . '&text=' . $reference;
        $this->data['weibo_url']    = 'http://service.weibo.com/share/share.php?url=' . $url . '&title=' . $reference;

        return $this->data;
    }
}
