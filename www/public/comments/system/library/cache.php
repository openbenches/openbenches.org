<?php
namespace Commentics;

class Cache
{
    private $db;
    private $setting;
    private $driver = '';

    public function __construct($registry)
    {
        $this->db      = $registry->get('db');
        $this->setting = $registry->get('setting');

        if (!$this->db->isConnected() || !$this->db->isInstalled()) {
            return;
        }

        if ($this->setting->has('cache_type') && $this->setting->get('cache_type')) {
            $driver = $this->setting->get('cache_type');

            $file = CMTX_DIR_LIBRARY . 'cache/' . $driver . '.php';

            if (file_exists($file)) {
                require_once cmtx_modification($file);

                $class = '\Commentics\\' . $driver;

                $settings = array(
                    'time' => $this->setting->get('cache_time'),
                    'host' => $this->setting->get('cache_host'),
                    'port' => $this->setting->get('cache_port')
                );

                $this->driver = new $class($settings);
            } else {
                die('<b>Error</b>: Could not load cache driver ' . $driver . '!');
            }
        }
    }

    public function set($key, $value) {
        if ($this->driver) {
            $this->driver->set($key, $value);
        }
    }

    public function get($key) {
        if ($this->driver) {
            $result = $this->driver->get($key);
        } else {
            $result = false;
        }

        return $result;
    }

    public function delete($key) {
        if ($this->driver) {
            $this->driver->delete($key);
        }
    }

    public function flush() {
        if ($this->driver) {
            $this->driver->flush();
        }
    }

    public function getStatus() {
        return $this->driver->status;
    }
}
