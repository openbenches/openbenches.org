<?php
define('CMTX_FRONTEND', true);

define('CMTX_VERSION', '4.4');

if (!session_id()) {
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

    /*
     * If there's an iFrame session, start a session with
     * the same parameters so they can access each other
     */
    if (isset($_COOKIE['commentics-iframe-session'])) {
        session_name('commentics-iframe-session');

        $session_parameters['cookie_secure'] = 1;

        if (version_compare(PHP_VERSION, '7.3.0', '>=')) {
            $session_parameters['cookie_samesite'] = 'None';
        } else {
            $session_parameters['cookie_path'] = '/; SameSite=None';
        }
    } else {
        if (version_compare(PHP_VERSION, '7.3.0', '>=')) {
            $session_parameters['cookie_samesite'] = 'Lax';
        } else {
            $session_parameters['cookie_path'] = '/; SameSite=Lax';
        }
    }

    session_start($session_parameters);
}

if (!headers_sent()) {
    header('Content-Type: text/html; charset=utf-8');
}

define('CMTX_DIR_THIS', dirname(__FILE__) . '/');
define('CMTX_DIR_ROOT', dirname(CMTX_DIR_THIS) . '/');
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
define('CMTX_DIR_UPLOAD', CMTX_DIR_ROOT . 'upload/');

if (file_exists(CMTX_DIR_ROOT . 'config.php') && filesize(CMTX_DIR_ROOT . 'config.php')) {
    require_once CMTX_DIR_ROOT . 'config.php';
} else {
    die('<b>Error</b>: Commentics is not installed!');
}

/*
 * iFrame integration
 */

if (!empty($cmtx_is_iframe)) { // it's an iFrame integration
    define('CMTX_IS_IFRAME', true);

    if (isset($_GET['block'])) { // helps prevent manipulation of the iFrame parameters
        $_SESSION['cmtx_block'] = '1';
        return;
    }

    /* If no identifier is passed, use the page URL */
    if (!$cmtx_identifier) {
        $cmtx_identifier = preg_replace('#^(https?://)?(www.)?#', '', $cmtx_url); // Remove http and www from URL
        $cmtx_identifier = explode('cmtx', $cmtx_identifier); // Remove any cmtx parameters e.g. if permalink
        $cmtx_identifier = $cmtx_identifier[0];
        $cmtx_identifier = rtrim($cmtx_identifier, '&?'); // Remove ampersand and question mark from end
    }

    /* If no reference is passed, set as 'Untitled' */
    if (!$cmtx_reference) {
        $cmtx_reference = 'Untitled';
    }

    /* Set these extra config values if provided */
    $extra_configs = array('logged_in', 'name', 'email', 'website', 'town', 'country', 'state', 'avatar', 'language');

    foreach($extra_configs as $extra_config) {
        if (isset($_GET[$extra_config])) {
            ${'cmtx_' . $extra_config} = $_GET[$extra_config];
        }
    }
}

/*
 * When the page that Commentics is integrated into is viewed, these variables are (or at least should
 * be) set. They are not set however for ajax requests (e.g. when submitting a comment). In these cases,
 * the page ID should be in the ajax $_POST data. In the worst scenario the page ID will remain as its
 * initialised value of zero. Access using $this->page->getIdentifier() and $this->page->getReference().
 */

if (isset($cmtx_identifier)) {
    define('CMTX_IDENTIFIER', $cmtx_identifier);
}

if (isset($cmtx_reference)) {
    define('CMTX_REFERENCE', $cmtx_reference);
}

if (isset($cmtx_url)) {
    define('CMTX_URL', $cmtx_url);
}

/*
 * It's possible to pass user information to Commentics from the page that it is integrated into.
 * This is useful for example if the website has a login system as it saves the user from having
 * to type more than they need to. Make sure that the information will validate form submission.
 * Access these using the $this->user->getLogin() method. For example $this->user->getLogin('name').
 */

if (isset($cmtx_name)) {
    define('CMTX_NAME', $cmtx_name);
}

if (isset($cmtx_email)) {
    define('CMTX_EMAIL', $cmtx_email);
}

if (isset($cmtx_website)) {
    define('CMTX_WEBSITE', $cmtx_website);
}

if (isset($cmtx_town)) {
    define('CMTX_TOWN', $cmtx_town);
}

if (isset($cmtx_country)) {
    define('CMTX_COUNTRY', $cmtx_country);
}

if (isset($cmtx_state)) {
    define('CMTX_STATE', $cmtx_state);
}

if (isset($cmtx_avatar)) {
    define('CMTX_AVATAR', $cmtx_avatar);
}

/*
 * If the website has a login system and you only want logged in users to be able to post,
 * set $cmtx_logged_in = false; and the user will see a message instead of the form.
 */

if (isset($cmtx_logged_in)) {
    define('CMTX_LOGGED_IN', $cmtx_logged_in);
}

/*
 * The user's language can be passed to Commentics. Make sure it matches with its folder name.
 * For example $cmtx_language = 'german'; selects '/frontend/view/default/language/german/'.
 */

if (isset($cmtx_language)) {
    define('CMTX_LANGUAGE', $cmtx_language);
}

require_once CMTX_DIR_SYSTEM . 'startup.php';

if (!$cmtx_db->isConnected()) {
    return;
}

define('CMTX_HTTP_VIEW', $cmtx_url->getCommenticsURL() . 'frontend/view/');
define('CMTX_HTTP_3RDPARTY', $cmtx_url->getCommenticsURL() . '3rdparty/');

if (isset($cmtx_request->get['route']) && (preg_match('/^[a-z0-9_]+\/[a-z0-9_]+$/i', $cmtx_request->get['route']) || preg_match('/^[a-z0-9_]+\/[a-z0-9_]+\/[a-z0-9_]+$/i', $cmtx_request->get['route']))) {
    $parts = explode('/', strtolower($cmtx_request->get['route']));

    if (file_exists(CMTX_DIR_CONTROLLER . $parts[0] . '/' . $parts[1] . '.php')) {
        require_once cmtx_modification(CMTX_DIR_CONTROLLER . $parts[0] . '/' . $parts[1] . '.php');

        $parts = str_replace('_', '', $parts);

        $class = '\Commentics\\' . $parts[0] . $parts[1] . 'Controller';

        $controller = new $class($cmtx_registry);

        if (isset($parts[2]) && substr($parts[2], 0, 2) != '__' && method_exists($controller, $parts[2]) && is_callable(array($controller, $parts[2]))) {
            $controller->{$parts[2]}();
        } else {
            $controller->index();
        }
    } else if (defined('CMTX_IDENTIFIER')) {
        require_once cmtx_modification(CMTX_DIR_CONTROLLER . 'main/page.php');

        $controller = new \Commentics\MainPageController($cmtx_registry);

        $controller->index();
    }
} else if (defined('CMTX_IDENTIFIER')) {
    require_once cmtx_modification(CMTX_DIR_CONTROLLER . 'main/page.php');

    $controller = new \Commentics\MainPageController($cmtx_registry);

    $controller->index();
}
