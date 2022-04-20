<?php
require_once ("config.php");
require __DIR__ . '/vendor/autoload.php';
use Auth0\SDK\Auth0;
use Auth0\SDK\Configuration\SdkConfiguration;
use Auth0\SDK\Utility\HttpResponse;

$auth0 = new Auth0([
	'domain'              => AUTH0_DOMAIN,
	'clientId'            => AUTH0_CLIENT_ID,
	'clientSecret'        => AUTH0_CLIENT_SECRET,
	'redirectUri'         => AUTH0_LOGOUT_RETURN_URL,
	'audience'            => AUTH0_AUDIENCE,
	'scope'               => array('openid profile'),
	'persistIdToken'      => true,
	'persistAccessToken'  => true,
	'persistRefreshToken' => true,
	'cookieSecret'        => AUTH0_COOKIE_SECRET,
]);

$session = $auth0->getCredentials();

if ($session) {
	//	Destroy any remaining edit sessions
	unset($_SESSION['edit_bench_id']);
	// Clear the end-user's session, and redirect them to the Auth0 /logout endpoint.
	header('Location: ' . $auth0->logout());
	exit;
} else {
	//	They weren't logged in - so send them to the hopepage
	header('Location: /');
}

exit;