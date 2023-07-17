<?php
namespace Commentics;

class Request
{
    public $get = array();
    public $post = array();
    public $server = array();
    public $cookie = array();
    public $files = array();

    public function __construct()
    {
        $this->get    = $this->clean($_GET);
        $this->post   = $this->clean($_POST);
        $this->server = $this->clean($_SERVER);
        $this->cookie = $this->clean($_COOKIE);
        $this->files  = $this->clean($_FILES);
    }

    public function isAjax()
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        } else {
            return false;
        }
    }

    public function getCsvData($file)
    {
        $csv_data = array();

        $file = fopen($file, 'r');

        if ($file) {
            while ($row = fgetcsv($file)) {
                $csv_data[] = $row;
            }

            fclose($file);
        }

        return $csv_data;
    }

    private function clean($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                unset($data[$key]);

                $data[$this->clean($key)] = $this->clean($value);
            }
        } else {
            $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

            $data = str_replace("\t", ' ', $data);

            $data = preg_replace('/  */', ' ', $data);

            $data = trim($data);
        }

        return $data;
    }
}
