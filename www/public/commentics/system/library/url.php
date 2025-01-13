<?php
namespace Commentics;

class Url
{
    private $request;
    private $setting;

    public function __construct($registry)
    {
        $this->request = $registry->get('request');
        $this->setting = $registry->get('setting');
    }

    public function link($route, $args = '')
    {
        $url = 'index.php?route=' . $route;

        if ($args) {
            $url .= $args;
        }

        $url = str_replace(array('&', ' '), array('&amp;', '%20'), $url);

        return $url;
    }

    public function encode($url)
    {
        return urlencode($url);
    }

    public function decode($url)
    {
        return urldecode($url);
    }

    /* Gets the current page URL. It should equal the URL in the browser's address bar. */
    public function getPageUrl()
    {
        return 'http' . ($this->isHttps() ? 's' : '') . '://' . strtolower($this->request->server['HTTP_HOST']) . $this->request->server['REQUEST_URI'];
    }

    /*
     * Gets a protocol-relative version of the Commentics URL setting from 'Settings -> System'.
     * It uses the same protocol as what the browser is using to request the referring page.
     * For example http://www.example.com becomes //www.example.com
     */
    public function getCommenticsUrl()
    {
        $commentics_url = preg_replace('/^https?:/', '', $this->setting->get('commentics_url'));

        /* If URL being viewed contains www. */
        if (substr(strtolower($this->request->server['HTTP_HOST']), 0, 4) == 'www.') {
            /* If Commentics URL setting does not contain www. */
            if (substr(strtolower($commentics_url), 0, 6) != '//www.') {
                $commentics_url = substr_replace($commentics_url, 'www.', 2, 0); // Add www.
            }
        } else { // URL does not contain www.
            /* If Commentics URL setting contains www. */
            if (substr(strtolower($commentics_url), 0, 6) == '//www.') {
                $commentics_url = substr_replace($commentics_url, '', 2, 4); // Remove www.
            }
        }

        return $commentics_url;
    }

    /* Modifies the supplied page URL by adding a page parameter to be used by search engines */
    public function getPaginationUrl($page_url)
    {
        if (strstr($page_url, '?') && strstr($page_url, '=')) {
            $page_url .= '&amp;' . 'cmtx_page=';
        } else {
            $page_url .= '?' . 'cmtx_page=';
        }

        return $page_url;
    }

    /* Determines if the page is being requested securely using an SSL certificate */
    public function isHttps()
    {
        if (isset($this->request->server['HTTPS']) && !empty($this->request->server['HTTPS']) && strtolower($this->request->server['HTTPS']) != 'off') {
            return true;
        } else if (isset($this->request->server['HTTP_X_FORWARDED_PROTO']) && $this->request->server['HTTP_X_FORWARDED_PROTO'] == 'https') {
            return true;
        } else if (isset($this->request->server['HTTP_FRONT_END_HTTPS']) && !empty($this->request->server['HTTP_FRONT_END_HTTPS']) && strtolower($this->request->server['HTTP_FRONT_END_HTTPS']) != 'off') {
            return true;
        }

        return false;
    }
}
