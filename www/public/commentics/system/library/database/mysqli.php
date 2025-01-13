<?php
namespace Commentics;

class MySqli
{
    private $link;
    public $query_count;
    public $query_time;
    public $query_error;
    public $query_last;
    public $connected = false;
    public $connect_error;
    public $connect_errno;
    public $installed = false;

    public function connect($hostname, $username, $password, $database, $port, $prefix)
    {
        if (extension_loaded('mysqli')) {
            try {
                if ($port) {
                    $this->link = @mysqli_connect($hostname, $username, $password, $database, $port);
                } else {
                    $this->link = @mysqli_connect($hostname, $username, $password, $database);
                }
            } catch(\Exception $e) {
            }

            if ($this->link) {
                $this->connected = true;

                mysqli_set_charset($this->link, 'utf8mb4');

                $this->query("SET SQL_MODE = ''");

                if ($this->numRows($this->query("SHOW TABLES LIKE '" . $prefix . "comments'"))) {
                    $this->installed = true;
                }
            } else {
                $this->connect_error = mysqli_connect_error();

                $this->connect_errno = mysqli_connect_errno();
            }
        } else {
            $this->connect_error = 'MySQLi extension is not loaded';
        }
    }

    public function query($sql)
    {
        $this->query_count++;

        $this->query_last = $sql;

        $start = microtime(true);

        try {
            $resource = mysqli_query($this->link, $sql);
        } catch(\Exception $e) {
            $resource = false;
        }

        $end = microtime(true);

        $this->query_time += $end - $start;

        if (mysqli_error($this->link)) {
            $trace = debug_backtrace();
            $this->query_error .= '<b>Query</b>: ' . $this->query_last . '<br>';
            $this->query_error .= '<b>Error</b>: ' . mysqli_error($this->link) . ' (' . mysqli_errno($this->link) . ')' . '<br>';
            $this->query_error .= '<b>Place</b>: ' . $trace[0]['file'] . ' (line ' . $trace[0]['line'] . ')' . '<br><br>';
        }

        return $resource;
    }

    public function escape($value)
    {
        return mysqli_real_escape_string($this->link, $value);
    }

    public function row($query)
    {
        return mysqli_fetch_assoc($query);
    }

    public function rows($query)
    {
        $result = array();

        while ($row = mysqli_fetch_assoc($query)) {
            $result[] = $row;
        }

        return $result;
    }

    public function numRows($resource)
    {
        return mysqli_num_rows($resource);
    }

    public function insertId()
    {
        return mysqli_insert_id($this->link);
    }

    public function affectedRows()
    {
        return mysqli_affected_rows($this->link);
    }

    public function getServerInfo()
    {
        return mysqli_get_server_info($this->link);
    }
}
