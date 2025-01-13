<?php
namespace Commentics;

class Db
{
    private $driver = '';
    public $query_count = 0;
    public $query_time = 0;
    public $query_error;
    public $query_last;
    public $connected = false;
    public $connect_error;
    public $connect_errno;
    public $installed = false;

    public function connect($hostname, $username, $password, $database, $port, $prefix, $driver)
    {
        $file = CMTX_DIR_LIBRARY . 'database/' . $driver . '.php';

        if (file_exists($file)) {
            require_once cmtx_modification($file);

            $class = '\Commentics\\' . $driver;

            $this->driver = new $class();
        } else {
            $this->connect_error = 'Could not load database driver ' . $driver;

            return;
        }

        $this->driver->connect($hostname, $username, $password, $database, $port, $prefix);

        $this->connected     = $this->driver->connected;
        $this->connect_error = $this->driver->connect_error;
        $this->connect_errno = $this->driver->connect_errno;
        $this->installed     = $this->driver->installed;
    }

    public function query($sql)
    {
        $result = $this->driver->query($sql);

        $this->query_count = $this->driver->query_count;
        $this->query_time  = $this->driver->query_time;
        $this->query_error = $this->driver->query_error;
        $this->query_last  = $this->driver->query_last;

        return $result;
    }

    public function escape($value)
    {
        return $this->driver->escape($value);
    }

    public function backticks($value)
    {
        return '`' . str_replace('.', '`.`', $value) . '`';
    }

    public function row($query)
    {
        return $this->driver->row($query);
    }

    public function rows($query)
    {
        return $this->driver->rows($query);
    }

    public function numRows($resource)
    {
        return $this->driver->numRows($resource);
    }

    public function insertId()
    {
        return $this->driver->insertId();
    }

    public function affectedRows()
    {
        return $this->driver->affectedRows();
    }

    public function getServerInfo()
    {
        return $this->driver->getServerInfo();
    }

    public function getQueryCount()
    {
        return $this->query_count;
    }

    public function getQueryTime()
    {
        return $this->query_time;
    }

    public function getQueryError()
    {
        return $this->query_error;
    }

    public function isConnected()
    {
        return $this->connected;
    }

    public function getConnectError()
    {
        return $this->connect_error;
    }

    public function getConnectErrno()
    {
        return $this->connect_errno;
    }

    public function isInstalled()
    {
        return $this->installed;
    }
}
