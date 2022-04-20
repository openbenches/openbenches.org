<?php
//	No cache for this page. Prevents authentication errors. Hopefully.
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<?php
require_once ("config.php");
require_once ("mysql.php");
require_once ("functions.php");

require_once (__DIR__ . '/vendor/autoload.php');
use Auth0\SDK\Auth0;
use Auth0\SDK\Configuration\SdkConfiguration;
use Auth0\SDK\Utility\HttpResponse;

$auth0 = new Auth0([
	'domain'              => AUTH0_DOMAIN,
	'clientId'            => AUTH0_CLIENT_ID,
	'clientSecret'        => AUTH0_CLIENT_SECRET,
	'redirectUri'         => AUTH0_CALLBACK,
	'audience'            => AUTH0_AUDIENCE,
	'scope'               => array('openid profile'),
	'persistIdToken'      => true,
	'persistAccessToken'  => true,
	'persistRefreshToken' => true,
	'cookieSecret'        => AUTH0_COOKIE_SECRET,
]);


if(!isset($_SESSION)) { session_start(); }

$session = $auth0->getCredentials();

// Is this end-user signing in?
if ($session === null && isset($_GET['code']) && isset($_GET['state'])) {
    if ($auth0->exchange() === false) {
        die("Authentication failed.");
    }
    // Authentication complete!
    // print_r($auth0->getUser());
} else if ($session === null) {
    // They are not. Redirect the end user to the login page.
    header('Location: ' . $auth0->login());
    exit;
} else {
	// User is authenticated
	$userInfo = $auth0->getUser();

	$username = explode("|", $userInfo["sub"]);

	$userID = insert_user($username[0], $username[1], $userInfo['nickname']);
}

//	Cache buster
$cache = time();

//	Is this an edit login?
if(!empty($_SESSION['edit_bench_id'])) {
	$benchID = (int)$_SESSION['edit_bench_id'];
	header("Location: /edit/{$benchID}/?cache={$cache}");
	return null;
}

//	Redirect to the Add page
header("Location: /add/?cache={$cache}");
