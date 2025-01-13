<?php
session_name('commentics-iframe-session');

// Default session parameters
$session_parameters = array(
    'cookie_httponly'  => 1,
    'use_only_cookies' => 1,
    'use_trans_sid'    => 0,
    'gc_maxlifetime'   => 1440,
    'cookie_lifetime'  => 0,
    'cookie_path'      => '/',
    'cookie_secure'    => 1
);

/*
 * Set session cookie as SameSite = None to allow
 * cross-domain for the iFrame integration method
 */
if (version_compare(PHP_VERSION, '7.3.0', '>=')) {
    $session_parameters['cookie_samesite'] = 'None';
} else {
    $session_parameters['cookie_path'] = '/; SameSite=None';
}

session_start($session_parameters);
?>
<!DOCTYPE html>
<html>
<head>
<title>Commentics</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="frontend/view/default/javascript/iframe.js"></script>
</head>
<body class="cmtx_iframe_body">
<?php
if (isset($_GET['identifier']) && isset($_GET['reference']) && isset($_GET['url'])) {
    $cmtx_is_iframe  = true;
    $cmtx_identifier = $_GET['identifier'];
    $cmtx_reference  = $_GET['reference'];
    $cmtx_url        = $_GET['url'];
    require 'frontend/index.php';
}
?>
</body>
</html>