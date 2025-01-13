<?php
define('CMTX_START_TIME', microtime(true)); // record start time

error_reporting(-1); // report every possible error

ini_set('error_log', CMTX_DIR_LOGS . 'errors.log'); // set log path

if (defined('CMTX_INSTALL')) { // if it's the installer
    ini_set('log_errors', 0); // don't log errors
    ini_set('display_errors', 1); // display errors
} else {
    ini_set('log_errors', 1); // log errors
    ini_set('display_errors', 0); // don't display errors
}

ini_set('default_charset', 'utf-8'); // set charset as UTF-8

ini_set('allow_url_fopen', 1); // allow fopen(URL)

/* Make sure HTTP_HOST is set for IIS servers */
if (!isset($_SERVER['HTTP_HOST'])) {
    $_SERVER['HTTP_HOST'] = getenv('HTTP_HOST');
}

/* Make sure REQUEST_URI is set for IIS servers */
if (!isset($_SERVER['REQUEST_URI'])) {
    $_SERVER['REQUEST_URI'] = substr($_SERVER['PHP_SELF'], 1);

    if (isset($_SERVER['QUERY_STRING'])) {
        $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
    }
}

function cmtx_modification($filename)
{
    if (defined('CMTX_FRONTEND')) {
        $file = CMTX_DIR_CACHE . 'modification/' . substr($filename, strlen(CMTX_DIR_ROOT));
    } else if (defined('CMTX_BACKEND')) {
        $file = CMTX_DIR_CACHE . 'modification/' . substr($filename, strlen(CMTX_DIR_ROOT));
    } else {
        return $filename;
    }

    if (substr($filename, 0, strlen(CMTX_DIR_SYSTEM)) == CMTX_DIR_SYSTEM) {
        $file = CMTX_DIR_CACHE . 'modification/' . 'system/' . substr($filename, strlen(CMTX_DIR_SYSTEM));
    }

    if (is_file($file)) {
        return $file;
    }

    return $filename;
}

/* Load the helper classes */
require_once cmtx_modification(CMTX_DIR_HELPER . 'password_compat.php');
require_once cmtx_modification(CMTX_DIR_HELPER . 'remove_directory.php');

/* Load the registry class */
require_once cmtx_modification(CMTX_DIR_ENGINE . 'registry.php');

/* Initialise the registry class */
$cmtx_registry = new \Commentics\Registry();

/* Load the library classes */
require_once cmtx_modification(CMTX_DIR_LIBRARY . 'cache.php');
require_once cmtx_modification(CMTX_DIR_LIBRARY . 'comment.php');
require_once cmtx_modification(CMTX_DIR_LIBRARY . 'cookie.php');
require_once cmtx_modification(CMTX_DIR_LIBRARY . 'database.php');
require_once cmtx_modification(CMTX_DIR_LIBRARY . 'email.php');
require_once cmtx_modification(CMTX_DIR_LIBRARY . 'encryption.php');
require_once cmtx_modification(CMTX_DIR_LIBRARY . 'geo.php');
require_once cmtx_modification(CMTX_DIR_LIBRARY . 'home.php');
require_once cmtx_modification(CMTX_DIR_LIBRARY . 'log.php');
require_once cmtx_modification(CMTX_DIR_LIBRARY . 'modification.php');
require_once cmtx_modification(CMTX_DIR_LIBRARY . 'notify.php');
require_once cmtx_modification(CMTX_DIR_LIBRARY . 'page.php');
require_once cmtx_modification(CMTX_DIR_LIBRARY . 'request.php');
require_once cmtx_modification(CMTX_DIR_LIBRARY . 'response.php');
require_once cmtx_modification(CMTX_DIR_LIBRARY . 'security.php');
require_once cmtx_modification(CMTX_DIR_LIBRARY . 'session.php');
require_once cmtx_modification(CMTX_DIR_LIBRARY . 'setting.php');
require_once cmtx_modification(CMTX_DIR_LIBRARY . 'site.php');
require_once cmtx_modification(CMTX_DIR_LIBRARY . 'task.php');
require_once cmtx_modification(CMTX_DIR_LIBRARY . 'template.php');
require_once cmtx_modification(CMTX_DIR_LIBRARY . 'url.php');
require_once cmtx_modification(CMTX_DIR_LIBRARY . 'user.php');
require_once cmtx_modification(CMTX_DIR_LIBRARY . 'validation.php');
require_once cmtx_modification(CMTX_DIR_LIBRARY . 'variable.php');

/* Initialise the library classes */
$cmtx_db = new \Commentics\Db();

if (defined('CMTX_DB_HOSTNAME')) {
    $cmtx_db->connect(CMTX_DB_HOSTNAME, CMTX_DB_USERNAME, CMTX_DB_PASSWORD, CMTX_DB_DATABASE, CMTX_DB_PORT, CMTX_DB_PREFIX, CMTX_DB_DRIVER);

    if (!$cmtx_db->connected) {
        if (defined('CMTX_FRONTEND')) {
            return;
        } else {
            die('<b>Error</b>: ' . $cmtx_db->getConnectError() . ($cmtx_db->getConnectErrno() ? ' (' . $cmtx_db->getConnectErrno() . ')' : ''));
        }
    }
}

$cmtx_registry->set('db', $cmtx_db);

$cmtx_setting = new \Commentics\Setting($cmtx_registry);
$cmtx_registry->set('setting', $cmtx_setting);

/* Time zone */
if ($cmtx_setting->has('time_zone')) {
    date_default_timezone_set($cmtx_setting->get('time_zone')); // set time zone PHP
} else {
    date_default_timezone_set('UTC'); // set time zone PHP
}

if ($cmtx_db->isConnected()) {
    $cmtx_db->query("SET time_zone = '" . $cmtx_db->escape(date('P')) . "'"); // set time zone DB
}

$cmtx_log = new \Commentics\Log();
$cmtx_registry->set('log', $cmtx_log);

$cmtx_request = new \Commentics\Request();
$cmtx_registry->set('request', $cmtx_request);

$cmtx_response = new \Commentics\Response();
$cmtx_registry->set('response', $cmtx_response);

$cmtx_security = new \Commentics\Security();
$cmtx_registry->set('security', $cmtx_security);

$cmtx_session = new \Commentics\Session();
$cmtx_registry->set('session', $cmtx_session);

$cmtx_validation = new \Commentics\Validation();
$cmtx_registry->set('validation', $cmtx_validation);

$cmtx_variable = new \Commentics\Variable();
$cmtx_registry->set('variable', $cmtx_variable);

$cmtx_cache = new \Commentics\Cache($cmtx_registry);
$cmtx_registry->set('cache', $cmtx_cache);

$cmtx_modification = new \Commentics\Modification($cmtx_registry);
$cmtx_registry->set('modification', $cmtx_modification);

$cmtx_url = new \Commentics\Url($cmtx_registry);
$cmtx_registry->set('url', $cmtx_url);

$cmtx_encryption = new \Commentics\Encryption($cmtx_registry);
$cmtx_registry->set('encryption', $cmtx_encryption);

$cmtx_email = new \Commentics\Email($cmtx_registry);
$cmtx_registry->set('email', $cmtx_email);

$cmtx_comment = new \Commentics\Comment($cmtx_registry);
$cmtx_registry->set('comment', $cmtx_comment);

$cmtx_geo = new \Commentics\Geo($cmtx_registry);
$cmtx_registry->set('geo', $cmtx_geo);

$cmtx_page = new \Commentics\Page($cmtx_registry);
$cmtx_registry->set('page', $cmtx_page);

$cmtx_cookie = new \Commentics\Cookie($cmtx_registry);
$cmtx_registry->set('cookie', $cmtx_cookie);

$cmtx_notify = new \Commentics\Notify($cmtx_registry);
$cmtx_registry->set('notify', $cmtx_notify);

$cmtx_user = new \Commentics\User($cmtx_registry);
$cmtx_registry->set('user', $cmtx_user);

$cmtx_home = new \Commentics\Home($cmtx_registry);
$cmtx_registry->set('home', $cmtx_home);

$cmtx_site = new \Commentics\Site($cmtx_registry);
$cmtx_registry->set('site', $cmtx_site);

$cmtx_task = new \Commentics\Task($cmtx_registry);
$cmtx_registry->set('task', $cmtx_task);

$cmtx_template = new \Commentics\Template($cmtx_registry);
$cmtx_registry->set('template', $cmtx_template);

/* Load the engine classes */
require_once cmtx_modification(CMTX_DIR_ENGINE . 'base.php');
require_once cmtx_modification(CMTX_DIR_ENGINE . 'controller.php');
require_once cmtx_modification(CMTX_DIR_ENGINE . 'model.php');

if (!defined('CMTX_INSTALL') && $cmtx_db->isInstalled()) {
    if ((defined('CMTX_FRONTEND') && $cmtx_setting->get('error_reporting_frontend')) || (defined('CMTX_BACKEND') && $cmtx_setting->get('error_reporting_backend'))) {
        error_reporting(-1); // report every possible error
    } else {
        error_reporting(0); // turn off all error reporting
    }

    if ($cmtx_setting->get('error_reporting_method') == 'log') {
        ini_set('log_errors', 1); // log errors
        ini_set('display_errors', 0); // don't display errors
    } else {
        ini_set('log_errors', 0); // don't log errors
        ini_set('display_errors', 1); // display errors
    }
}
