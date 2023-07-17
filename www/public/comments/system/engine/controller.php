<?php
namespace Commentics;

abstract class Controller extends Base
{
    public function loadView($cmtx_view)
    {
        foreach ($this->components as $cmtx_component) {
            $this->data[basename($cmtx_component)] = $this->getComponent($cmtx_component);
        }

        if (!isset($this->data['success'])) {
            $this->data['success'] = '';
        }

        if (!isset($this->data['info'])) {
            $this->data['info'] = '';
        }

        if (!isset($this->data['error'])) {
            $this->data['error'] = '';
        }

        if (!isset($this->data['warning'])) {
            $this->data['warning'] = '';
        }

        if (defined('CMTX_BACKEND')) {
            foreach ($this->data as $cmtx_key => &$cmtx_value) {
                if (substr($cmtx_key, 0, 10) == 'lang_hint_') {
                    $cmtx_value = $this->variable->hint($cmtx_value);
                } else if (substr($cmtx_key, 0, 12) == 'lang_dialog_' && substr($cmtx_key, -6) == '_title') {
                    $cmtx_value = $this->variable->encodeDouble($cmtx_value);
                }
            }
        }

        $generated_time = microtime(true) - CMTX_START_TIME;

        $this->data['generated_time'] = round($generated_time, 5);

        $this->data['php_time'] = round($generated_time - $this->db->getQueryTime(), 5);

        $this->data['query_time'] = round($this->db->getQueryTime(), 5);

        $this->data['query_count'] = $this->db->getQueryCount();

        unset($cmtx_component, $cmtx_key, $cmtx_value, $generated_time);

        extract($this->data);

        if (file_exists(CMTX_DIR_VIEW . $this->setting->get('theme') . '/template/' . strtolower($cmtx_view) . '.tpl')) {
            $file = cmtx_modification(CMTX_DIR_VIEW . $this->setting->get('theme') . '/template/' . strtolower($cmtx_view) . '.tpl');
        } else if (file_exists(CMTX_DIR_VIEW . 'default/template/' . strtolower($cmtx_view) . '.tpl')) {
            $file = cmtx_modification(CMTX_DIR_VIEW . 'default/template/' . strtolower($cmtx_view) . '.tpl');
        } else {
            die('<b>Error</b>: Could not load view ' . strtolower($cmtx_view) . '!');
        }

        $cmtx_view = $this->parse($cmtx_view, $file);

        require $cmtx_view;
    }

    public function getComponent($cmtx_component, $cmtx_component_data = array())
    {
        /* Some components have a controller */
        if (file_exists(CMTX_DIR_CONTROLLER . strtolower($cmtx_component) . '.php')) {
            require_once cmtx_modification(CMTX_DIR_CONTROLLER . strtolower($cmtx_component) . '.php');

            $class = '\Commentics\\' . str_replace('/', '', $cmtx_component) . 'Controller';

            $class = str_replace('_', '', $class);

            $controller = new $class($this->registry);

            $this->data = array_merge($this->data, $controller->index($cmtx_component_data));
        }

        extract($this->data);

        ob_start();

        if (file_exists(CMTX_DIR_VIEW . $this->setting->get('theme') . '/template/' . strtolower($cmtx_component) . '.tpl')) {
            $file = cmtx_modification(CMTX_DIR_VIEW . $this->setting->get('theme') . '/template/' . strtolower($cmtx_component) . '.tpl');
        } else if (file_exists(CMTX_DIR_VIEW . 'default/template/' . strtolower($cmtx_component) . '.tpl')) {
            $file = cmtx_modification(CMTX_DIR_VIEW . 'default/template/' . strtolower($cmtx_component) . '.tpl');
        } else {
            return;
        }

        $cmtx_component = $this->parse($cmtx_component, $file);

        require $cmtx_component;

        return ob_get_clean();
    }

    public function loadTemplate($cmtx_template)
    {
        if (file_exists(CMTX_DIR_VIEW . $this->setting->get('theme') . '/template/' . strtolower($cmtx_template) . '.tpl')) {
            $file = cmtx_modification(CMTX_DIR_VIEW . $this->setting->get('theme') . '/template/' . strtolower($cmtx_template) . '.tpl');
        } else if (file_exists(cmtx_modification(CMTX_DIR_VIEW . 'default/template/' . strtolower($cmtx_template) . '.tpl'))) {
            $file = cmtx_modification(CMTX_DIR_VIEW . 'default/template/' . strtolower($cmtx_template) . '.tpl');
        } else {
            die('<b>Error</b>: Could not load template ' . strtolower($cmtx_template) . '!');
        }

        $cmtx_template = $this->parse($cmtx_template, $file);

        return $cmtx_template;
    }

    private function parse($name, $file)
    {
        if (defined('CMTX_FRONTEND')) {
            $cached_file = CMTX_DIR_CACHE . 'template/' . str_replace('/', '_', strtolower($name)) . '.tpl';

            $parse_template = false;

            clearstatcache();

            if (file_exists($cached_file)) {
                $cached_file_time = filemtime($cached_file);

                $template_file_time = filemtime($file);

                /* If modification time of both files was determined */
                if ($cached_file_time && $template_file_time) {
                    /* If cached file is older than template file */
                    if ($cached_file_time < $template_file_time) {
                        $parse_template = true;
                    }
                }
            } else {
                $parse_template = true;
            }

            if ($parse_template) {
                $code = file_get_contents($file);

                $this->template->setCode($code);

                $this->template->setMinify($this->setting->get('optimize'));

                $parsed = $this->template->parse();

                $handle = fopen($cached_file, 'w');

                if ($handle) {
                    fputs($handle, $parsed);

                    fclose($handle);
                }

                if (!file_exists($cached_file)) {
                    die('<b>Error</b>: Could not save cache for ' . strtolower($name) . '!');
                }
            }

            return $cached_file;
        } else {
            return $file;
        }
    }

    public function loadLanguage($cmtx_language)
    {
        /* Load general language file if it exists */
        if (file_exists(CMTX_DIR_VIEW . $this->setting->get('theme') . '/language/' . $this->setting->get('language') . '/general.php')) {
            require cmtx_modification(CMTX_DIR_VIEW . $this->setting->get('theme') . '/language/' . $this->setting->get('language') . '/general.php');
        } else if (file_exists(CMTX_DIR_VIEW . 'default/language/' . $this->setting->get('language') . '/general.php')) {
            require cmtx_modification(CMTX_DIR_VIEW . 'default/language/' . $this->setting->get('language') . '/general.php');
        } else if (file_exists(CMTX_DIR_VIEW . 'default/language/english/general.php')) {
            require cmtx_modification(CMTX_DIR_VIEW . 'default/language/english/general.php');
        }

        /* Always load requested language file */
        if (file_exists(CMTX_DIR_VIEW . $this->setting->get('theme') . '/language/' . $this->setting->get('language') . '/' . strtolower($cmtx_language) . '.php')) {
            require_once cmtx_modification(CMTX_DIR_VIEW . $this->setting->get('theme') . '/language/' . $this->setting->get('language') . '/' . strtolower($cmtx_language) . '.php');
        } else if (file_exists(CMTX_DIR_VIEW . 'default/language/' . $this->setting->get('language') . '/' . strtolower($cmtx_language) . '.php')) {
            require_once cmtx_modification(CMTX_DIR_VIEW . 'default/language/' . $this->setting->get('language') . '/' . strtolower($cmtx_language) . '.php');
        } else if (file_exists(CMTX_DIR_VIEW . 'default/language/english/' . strtolower($cmtx_language) . '.php')) {
            require_once cmtx_modification(CMTX_DIR_VIEW . 'default/language/english/' . strtolower($cmtx_language) . '.php');
        } else {
            die('<b>Error</b>: Could not load language ' . strtolower($cmtx_language) . '!');
        }

        /* Load custom language file if it exists */
        if (file_exists(CMTX_DIR_VIEW . $this->setting->get('theme') . '/language/' . $this->setting->get('language') . '/custom.php')) {
            require cmtx_modification(CMTX_DIR_VIEW . $this->setting->get('theme') . '/language/' . $this->setting->get('language') . '/custom.php');
        } else if (file_exists(CMTX_DIR_VIEW . 'default/language/' . $this->setting->get('language') . '/custom.php')) {
            require cmtx_modification(CMTX_DIR_VIEW . 'default/language/' . $this->setting->get('language') . '/custom.php');
        } else if (file_exists(CMTX_DIR_VIEW . 'default/language/english/custom.php')) {
            require cmtx_modification(CMTX_DIR_VIEW . 'default/language/english/custom.php');
        }

        /* Change the comment type wording if configured */
        if ($this->setting->get('purpose') != 'comment') {
            $_ = $this->changePurpose($_);
        }

        /* Combine language files together */
        $this->data = array_merge($this->data, $_);
    }
}
