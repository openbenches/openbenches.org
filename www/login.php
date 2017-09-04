<?php
require_once ("config.php");
require_once ("mysql.php");
require_once ("functions.php");

require_once ('codebird.php');
\Codebird\Codebird::setConsumerKey(ADMIN_CONSUMER_KEY, ADMIN_CONSUMER_SECRET);

$cb = \Codebird\Codebird::getInstance();


session_start();

if (! isset($_SESSION['oauth_token'])) {
	// get the request token
	$reply = $cb->oauth_requestToken([
		'oauth_callback' => 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']
	]);


	// store the token
	$cb->setToken($reply->oauth_token, $reply->oauth_token_secret);
	$_SESSION['oauth_token'] = $reply->oauth_token;
	$_SESSION['oauth_token_secret'] = $reply->oauth_token_secret;
	$_SESSION['oauth_verify'] = true;

	// redirect to auth website
	$auth_url = $cb->oauth_authorize();
	header('Location: ' . $auth_url);
	die();

} elseif (isset($_GET['oauth_verifier']) && isset($_SESSION['oauth_verify'])) {
	// verify the token
	$cb->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
	unset($_SESSION['oauth_verify']);

	// get the access token
	$reply = $cb->oauth_accessToken([
		'oauth_verifier' => $_GET['oauth_verifier']
	]);

	// store the token (which is different from the request token!)
	$_SESSION['oauth_token'] = $reply->oauth_token;
	$_SESSION['oauth_token_secret'] = $reply->oauth_token_secret;

	// send to same URL, without oauth GET parameters
	header('Location: ' . basename(__FILE__));
	die();
}

// assign access token on each page load
$cb->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

$reply = (array) $cb->account_verifyCredentials();

//	If the authorization hasn't worked, clear the session variables and start again
if($reply["errors"]) {
	$_SESSION['oauth_token'] = null;
	$_SESSION['oauth_token'] = null;
	$_SESSION['oauth_token_secret'] = null;
	$_SESSION['oauth_verify'] = null;
	// send to same URL, without oauth GET parameters
	header('Location: ' . basename(__FILE__));
	die();
}

//	Redirect to the Add page
$cache = time();
header("Location: /add/?cache={$cache}");
