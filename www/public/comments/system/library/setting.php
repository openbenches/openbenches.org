<?php
namespace Commentics;

class Setting
{
    private $db = '';
    private $settings = array();

    public function __construct($registry)
    {
        $this->db = $registry->get('db');

        if ($this->db->isConnected() && $this->db->isInstalled()) {
            $settings = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "settings`");

            while ($setting = $this->db->row($settings)) {
                $this->settings[$setting['title']] = $setting['value'];

                if (defined('CMTX_' . strtoupper($setting['title']))) {
                    $this->settings[$setting['title']] = constant('CMTX_' . strtoupper($setting['title']));
                }
            }
        }
    }

    public function get($title)
    {
        return $this->settings[$title];
    }

    public function set($title, $value)
    {
        $this->settings[$title] = $value;
    }

    public function has($title)
    {
        if (isset($this->settings[$title])) {
            return true;
        } else {
            return false;
        }
    }

    public function refresh()
    {
        $settings = $this->db->query("SELECT * FROM `" . CMTX_DB_PREFIX . "settings`");

        while ($setting = $this->db->row($settings)) {
            $this->settings[$setting['title']] = $setting['value'];

            if (defined('CMTX_' . strtoupper($setting['title']))) {
                $this->settings[$setting['title']] = constant('CMTX_' . strtoupper($setting['title']));
            }
        }
    }
}
