<?php
namespace Commentics;

class Log
{
    private $filename = 'info';

    public function write($message)
    {
        $file = CMTX_DIR_LOGS . $this->filename . '.log';

        $handle = fopen($file, 'a+');

        fwrite($handle, date('d-m-Y G:i:s') . ' - ' . print_r($message, true) . "\r\n");

        fclose($handle);
    }

    public function clear()
    {
        $file = CMTX_DIR_LOGS . $this->filename . '.log';

        if (file_exists($file) && filesize($file)) {
            $handle = fopen($file, 'w');

            fclose($handle);
        }
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;
    }
}
