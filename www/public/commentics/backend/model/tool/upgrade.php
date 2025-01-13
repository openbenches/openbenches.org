<?php
namespace Commentics;

class ToolUpgradeModel extends Model
{
    public function getNext($version, $versions)
    {
        $versions = json_decode($versions, true);

        if ($versions) {
            if (is_array($versions)) {
                $versions = $versions['versions'];

                if (isset($versions[$version])) {
                    if ($this->validation->isFloat($versions[$version])) {
                        return $versions[$version];
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getChangelog($version)
    {
        $version = str_replace('.', '-', $version);

        $url = 'https://commentics.com/changelogs/commentics-' . $version . '.txt';

        ini_set('user_agent', 'Commentics');

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Commentics');
        curl_setopt($ch, CURLOPT_URL, $url);

        $changelog = curl_exec($ch);

        curl_close($ch);

        return $changelog;
    }

    public function download($version, $temp_folder)
    {
        $lang = $this->loadWord('tool/upgrade');

        @ignore_user_abort(true);
        @set_time_limit(300);

        $error = '';

        $url = 'https://commentics.com/package/commentics-' . $version . '.zip';

        ini_set('user_agent', 'Commentics');

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Commentics');
        curl_setopt($ch, CURLOPT_URL, $url);

        $package = curl_exec($ch);

        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($package === false) {
            $error = curl_error($ch);
        } else if ($http_code != 200) {
            $error = sprintf($lang['lang_error_status_code'], $http_code);
        }

        curl_close($ch);

        if (!$error) {
            if (is_writable($temp_folder)) {
                $destination = $temp_folder . '/upload.zip';

                $handle = @fopen($destination, 'w+');

                if ($handle) {
                    if (fwrite($handle, $package) === false) {
                        $error = $lang['lang_error_file_stream'];
                    }

                    fclose($handle);
                } else {
                    $error = $lang['lang_error_file_handle'];
                }
            } else {
                $error = $lang['lang_error_temp_write'];
            }
        }

        return $error;
    }

    public function unpack($temp_folder)
    {
        $lang = $this->loadWord('tool/upgrade');

        @ignore_user_abort(true);
        @set_time_limit(300);

        $error = '';

        $zip_file = $temp_folder . '/upload.zip';

        if (file_exists($zip_file)) {
            // We use the ZipArchive class
            $zip = new \ZipArchive();

            // Open the zip file
            $res = $zip->open($zip_file);

            if ($res === true) {
                // Extract the zip file
                if (!$zip->extractTo($temp_folder)) {
                    $error = $lang['lang_error_zip_extract'];
                }

                $zip->close();
            } else {
                $error = sprintf($lang['lang_error_zip_open'], $res);
            }
        } else {
            $error = $lang['lang_error_no_zip'];
        }

        return $error;
    }

    public function verify($temp_folder)
    {
        $lang = $this->loadWord('tool/upgrade');

        $error = '';

        if (!file_exists($temp_folder . '/upload')) {
            $error = sprintf($lang['lang_error_missing_folder'], 'upload');
        }

        if (!file_exists($temp_folder . '/files.php')) {
            $error = sprintf($lang['lang_error_missing_file'], 'files.php');
        }

        if (!file_exists($temp_folder . '/requirements.php')) {
            $error = sprintf($lang['lang_error_missing_file'], 'requirements.php');
        }

        if (!file_exists($temp_folder . '/sql.php')) {
            $error = sprintf($lang['lang_error_missing_file'], 'sql.php');
        }

        return $error;
    }

    public function requirements($temp_folder)
    {
        $lang = $this->loadWord('tool/upgrade');

        $error = '';

        if (file_exists($temp_folder . '/requirements.php')) {
            require_once $temp_folder . '/requirements.php';

            if (!empty($fail)) {
                $error = $fail;
            }
        } else {
            $error = sprintf($lang['lang_error_missing_file'], 'requirements.php');
        }

        return $error;
    }

    public function install($temp_folder)
    {
        $lang = $this->loadWord('tool/upgrade');

        @ignore_user_abort(true);
        @set_time_limit(300);

        // Path to the /upload/ folder inside the extracted zip
        $directory = $temp_folder . '/upload/';

        // Variable to store the list of files to install
        $files = array();

        $path = array($directory . '*');

        while (count($path) != 0) {
            $next = array_shift($path);

            foreach (glob($next) as $file) {
                if (is_dir($file)) {
                    $path[] = $file . '/*';
                }

                $files[] = $file;
            }
        }

        // For every file to upload
        foreach ($files as $file) {
            $destination = substr($file, strlen($directory));

            // Set the corresponding server path depending on its starting folder
            if (substr($destination, 0, 7) == 'backend') {
                $destination = CMTX_DIR_THIS . substr($destination, 7);
            } else if (substr($destination, 0, 8) == 'frontend') {
                $destination = CMTX_DIR_FRONTEND . substr($destination, 8);
            } else if (substr($destination, 0, 6) == 'system') {
                $destination = CMTX_DIR_SYSTEM . substr($destination, 6);
            } else if (substr($destination, 0, 8) == '3rdparty') {
                $destination = CMTX_DIR_3RDPARTY . substr($destination, 8);
            } else {
                $destination = CMTX_DIR_ROOT . $destination;
            }

            // If it's a directory then create it
            if (is_dir($file)) {
                if (!file_exists($destination)) {
                    if (!@mkdir($destination, 0777, true)) {
                        $this->log->setFilename('upgrade');
                        $this->log->write('Could not mkdir: ' . $destination);
                        $this->log->write(error_get_last());

                        return $lang['lang_error_create_dir'];
                    }
                }
            }

            // If it's a file then copy it there
            if (is_file($file)) {
                if (!@copy($file, $destination)) {
                    $this->log->setFilename('upgrade');
                    $this->log->write('Could not copy: ' . $destination);
                    $this->log->write(error_get_last());

                    return $lang['lang_error_copy_file'];
                }
            }
        }

        return '';
    }

    public function database($temp_folder)
    {
        $lang = $this->loadWord('tool/upgrade');

        $error = '';

        if (file_exists($temp_folder . '/sql.php')) {
            require_once $temp_folder . '/sql.php';
        } else {
            $error = sprintf($lang['lang_error_missing_file'], 'sql.php');
        }

        return $error;
    }

    public function clean($temp_folder)
    {
        $lang = $this->loadWord('tool/upgrade');

        $error = '';

        if (file_exists($temp_folder . '/files.php')) {
            require_once $temp_folder . '/files.php';
        } else {
            $error = sprintf($lang['lang_error_missing_file'], 'files.php');
        }

        return $error;
    }

    public function dismiss()
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '0' WHERE `title` = 'notice_tool_upgrade'");
    }

    public function setMaintenanceMode($value)
    {
        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '" . (int) $value . "' WHERE `title` = 'maintenance_mode'");
    }

    public function setVersion($version)
    {
        $this->db->query("INSERT INTO `" . CMTX_DB_PREFIX . "version` SET `version` = '" . $this->db->escape($version) . "', `type` = 'Upgrade', `date_added` = NOW()");

        $this->db->query("UPDATE `" . CMTX_DB_PREFIX . "settings` SET `value` = '0' WHERE `title` = 'new_version_notified'");
    }
}
