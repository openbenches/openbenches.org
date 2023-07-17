<?php
/* Stop here if PHP less than 5.3 due to no namespaces */
if (version_compare(PHP_VERSION, '5.3.0', '<')) {
    die('PHP 5.3.7+ required');
}

define('CMTX_INSTALL', true);

define('CMTX_VERSION', '4.4');

header('Content-Type: text/html; charset=utf-8');

// Default session parameters
$session_parameters = array(
    'cookie_httponly'  => 1,
    'use_only_cookies' => 1,
    'use_trans_sid'    => 0,
    'gc_maxlifetime'   => 1440,
    'cookie_lifetime'  => 0,
    'cookie_path'      => '/',
    'cookie_secure'    => 0
);

if (version_compare(PHP_VERSION, '7.3.0', '>=')) {
    $session_parameters['cookie_samesite'] = 'Lax';
} else {
    $session_parameters['cookie_path'] = '/; SameSite=Lax';
}

session_start($session_parameters);

define('CMTX_HTTP_VIEW', 'view/');

define('CMTX_DIR_THIS', str_replace('\\', '/', realpath(__DIR__)) . '/');
define('CMTX_DIR_ROOT', str_replace('\\', '/', realpath(__DIR__ . '/../')) . '/');
define('CMTX_DIR_SYSTEM', CMTX_DIR_ROOT . 'system/');
define('CMTX_DIR_BACKUPS', CMTX_DIR_SYSTEM . 'backups/');
define('CMTX_DIR_CACHE', CMTX_DIR_SYSTEM . 'cache/');
define('CMTX_DIR_ENGINE', CMTX_DIR_SYSTEM . 'engine/');
define('CMTX_DIR_HELPER', CMTX_DIR_SYSTEM . 'helper/');
define('CMTX_DIR_LIBRARY', CMTX_DIR_SYSTEM . 'library/');
define('CMTX_DIR_LOGS', CMTX_DIR_SYSTEM . 'logs/');
define('CMTX_DIR_MODIFICATION', CMTX_DIR_SYSTEM . 'modification/');
define('CMTX_DIR_MODEL', CMTX_DIR_THIS . 'model/');
define('CMTX_DIR_VIEW', CMTX_DIR_THIS . 'view/');
define('CMTX_DIR_CONTROLLER', CMTX_DIR_THIS . 'controller/');
define('CMTX_DIR_3RDPARTY', CMTX_DIR_ROOT . '3rdparty/');

if (file_exists(CMTX_DIR_ROOT . 'config.php') && filesize(CMTX_DIR_ROOT . 'config.php')) {
    require_once CMTX_DIR_ROOT . 'config.php';
}

require_once CMTX_DIR_SYSTEM . 'startup.php';

if (isset($cmtx_request->get['route']) && preg_match('/^[a-z0-9_]+$/i', $cmtx_request->get['route'])) {
    if (file_exists(CMTX_DIR_CONTROLLER . 'main/' . $cmtx_request->get['route'] . '.php')) {
        require_once CMTX_DIR_CONTROLLER . 'main/' . $cmtx_request->get['route'] . '.php';

        $class = '\Commentics\\' . 'Main' . str_replace('_', '', $cmtx_request->get['route']) . 'Controller';

        $controller = new $class($cmtx_registry);

        $controller->index();
    } else {
        require_once CMTX_DIR_CONTROLLER . 'main/start.php';

        $controller = new \Commentics\MainStartController($cmtx_registry);

        $controller->index();
    }
} else {
    require_once CMTX_DIR_CONTROLLER . 'main/start.php';

    $controller = new \Commentics\MainStartController($cmtx_registry);

    $controller->index();
}
