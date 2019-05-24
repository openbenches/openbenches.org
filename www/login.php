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

$auth0 = new Auth0([
	'domain' =>        AUTH0_DOMAIN,
	'client_id' =>     AUTH0_CLIENT_ID,
	'client_secret' => AUTH0_CLIENT_SECRET,
	'redirect_uri' =>  AUTH0_CALLBACK,
	'audience' =>      AUTH0_AUDIENCE,
	'scope' =>        'openid profile',
	'persist_id_token' =>      true,
	'persist_access_token' =>  true,
	'persist_refresh_token' => true,
]);


if(!isset($_SESSION)) { session_start(); }

$userInfo = $auth0->getUser();

if (!$userInfo) {
	// We have no user info
	// redirect to Login
	$auth0->login();
} else {
	// User is authenticated
	$username = explode("|", $userInfo["sub"]);

	$userID = insert_user($username[0], $username[1], $userInfo['nickname']);

	// echo "<pre>";
	// var_export($auth0->getAccessToken());
	// echo "</pre>";
	// echo "<pre>";
	// var_export($auth0->getIdToken());
	// echo "</pre>";
}

//	Cache buster
$cache = time();

//	Is this an edit login?
$benchID = $_SESSION['edit_bench_id'];
if(is_numeric($benchID)){
	//	Redirect to the Edit page
	header("Location: /edit/{$benchID}/?cache={$cache}");
} else {
	//	Redirect to the Add page
	header("Location: /add/?cache={$cache}");
}
die();
