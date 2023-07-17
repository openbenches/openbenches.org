<?php
namespace Commentics;

class ReportVersionCheckModel extends Model
{
    public function clearLog()
    {
        $log_file = CMTX_DIR_LOGS . 'version_check.log';

        $handle = fopen($log_file, 'w');

        fputs($handle, '');

        fclose($handle);
    }

    public function getLog()
    {
        if (file_exists(CMTX_DIR_LOGS . 'version_check.log')) {
            $log = file_get_contents(CMTX_DIR_LOGS . 'version_check.log');
        } else {
            $log = '';
        }

        return $log;
    }

    public function downloadLog()
    {
        if (file_exists(CMTX_DIR_LOGS . 'version_check.log')) {
            $log_file = CMTX_DIR_LOGS . 'version_check.log';

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($log_file) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($log_file));

            readfile($log_file);

            die();
        }
    }
}
