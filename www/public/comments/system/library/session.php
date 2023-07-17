<?php
namespace Commentics;

class Session
{
    public $data = array();

    public function __construct()
    {
        $this->data = &$_SESSION;
    }

    public function start()
    {
        session_start();
    }

    public function getId()
    {
        return session_id();
    }

    public function getName()
    {
        return session_name();
    }

    public function regenerate()
    {
        session_regenerate_id(true);
    }

    public function end()
    {
        session_destroy();

        session_unset();
    }
}
