<?php
namespace Commentics;

class File
{
    private $time;
    private $status = false;

    public function __construct($settings)
    {
        $this->time = $settings['time'];

        if (is_writable(CMTX_DIR_CACHE . 'database/')) {
            $this->status = true;

            $files = glob(CMTX_DIR_CACHE . 'database/cache.*');

            if ($files) {
                foreach ($files as $file) {
                    $time = substr(strrchr($file, '.'), 1);

                    if ($time < time()) {
                        if (file_exists($file)) {
                            @unlink($file);
                        }
                    }
                }
            }
        }
    }

    public function get($key) {
        if ($this->status) {
            $files = glob(CMTX_DIR_CACHE . 'database/cache.' . $key . '.*');

            if ($files) {
                $handle = fopen($files[0], 'r');

                flock($handle, LOCK_SH);

                $result = fread($handle, filesize($files[0]));

                flock($handle, LOCK_UN);

                fclose($handle);

                $result = json_decode($result, true);
            } else {
                $result = false;
            }
        } else {
            $result = false;
        }

        return $result;
    }

    public function set($key, $value) {
        if ($this->status) {
            $this->delete($key);

            $file = CMTX_DIR_CACHE . 'database/cache.' . $key . '.' . (time() + $this->time);

            $handle = fopen($file, 'w');

            flock($handle, LOCK_EX);

            fwrite($handle, json_encode($value));

            fflush($handle);

            flock($handle, LOCK_UN);

            fclose($handle);
        }
    }

    public function delete($key) {
        if ($this->status) {
            if (substr($key, -1) == '*') { // has wildcard
                $files = glob(CMTX_DIR_CACHE . 'database/cache.' . $key);
            } else {
                $files = glob(CMTX_DIR_CACHE . 'database/cache.' . $key . '.*');
            }

            if ($files) {
                foreach ($files as $file) {
                    if (file_exists($file)) {
                        @unlink($file);
                    }
                }
            }
        }
    }

    public function flush() {
        if ($this->status) {
            remove_directory(CMTX_DIR_CACHE . 'database/', false, false);
        }
    }

    public function getStatus() {
        return $this->status;
    }
}
