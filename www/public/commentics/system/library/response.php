<?php
namespace Commentics;

class Response
{
    public function redirect($route)
    {
        header('Location: index.php?route=' . $route);

        die();
    }

    public function addHeader($header)
    {
        if (!headers_sent()) {
            header($header, true);
        }
    }
}
