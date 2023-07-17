<?php
namespace Commentics;

class Cookie
{
    private $page;

    public function __construct($registry)
    {
        $this->page = $registry->get('page');
    }

    public function exists($name)
    {
        if (isset($_COOKIE[$name])) {
            return true;
        } else {
            return false;
        }
    }

    public function get($name)
    {
        return $_COOKIE[$name];
    }

    public function set($name, $value, $expires, $path = '/', $domain = null, $secure = null, $httponly = true, $samesite = null)
    {
        /*
         * Set cookies as SameSite = None to allow cross-domain
         * cookies to work with the iFrame integration method
         */
        if (version_compare(PHP_VERSION, '7.3.0', '>=')) {
            if ($this->page->isIFrame()) {
                $secure = true;
                $samesite = 'None';
            } else {
                $samesite = 'Lax';
            }

            setcookie($name, $value, array(
                'expires'  => $expires,
                'path'     => $path,
                'domain'   => $domain,
                'secure'   => $secure,
                'httponly' => $httponly,
                'samesite' => $samesite
            ));
        } else {
            if ($this->page->isIFrame()) {
                $secure = true;
                $path .= '; samesite=None';
            } else {
                $path .= '; samesite=Lax';            
            }

            setcookie($name, $value, $expires, $path, $domain, $secure, $httponly);
        }
    }

    public function delete($name)
    {
        setcookie($name, '', time() - 3600, '/');
    }
}
