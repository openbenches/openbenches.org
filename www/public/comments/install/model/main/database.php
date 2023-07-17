<?php
namespace Commentics;

class MainDatabaseModel extends Model
{
    public function writeConfig()
    {
        $data = '';

        $data .= '<?php' . PHP_EOL;
        $data .= '/* Database Details */' . PHP_EOL;
        $data .= 'define(\'CMTX_DB_DATABASE\', \'' . addslashes($this->request->post['database']) . '\');' . PHP_EOL;
        $data .= 'define(\'CMTX_DB_USERNAME\', \'' . addslashes($this->security->decode($this->request->post['username'])) . '\');' . PHP_EOL;
        $data .= 'define(\'CMTX_DB_PASSWORD\', \'' . addslashes($this->security->decode($this->request->post['password'])) . '\');' . PHP_EOL;
        $data .= 'define(\'CMTX_DB_HOSTNAME\', \'' . addslashes($this->request->post['hostname']) . '\');' . PHP_EOL;
        $data .= 'define(\'CMTX_DB_PORT\', \'' . addslashes($this->request->post['port']) . '\');' . PHP_EOL;
        $data .= 'define(\'CMTX_DB_PREFIX\', \'' . addslashes($this->request->post['prefix']) . '\');' . PHP_EOL;
        $data .= 'define(\'CMTX_DB_DRIVER\', \'' . addslashes($this->request->post['driver']) . '\');' . PHP_EOL;

        $handle = fopen('../config.php', 'w');

        fputs($handle, preg_replace('/\t+/', '', $data));

        fclose($handle);

        chmod('../config.php', 0444);
    }
}
