<?php 
require_once ("config.php");
require __DIR__ . '/vendor/autoload.php';
use Auth0\SDK\Auth0;
use Auth0\SDK\API\Authentication;

$auth0 = new Auth0([
	'domain' =>        AUTH0_DOMAIN,
	'client_id' =>     AUTH0_CLIENT_ID,
	'client_secret' => AUTH0_CLIENT_SECRET,
	'redirect_uri' =>  AUTH0_CALLBACK,
]);

// Log out of the local application.
$auth0->logout();

// Setup the Authentication class with required credentials.
// No API calls are made on instantiation.
$auth0_api = new Authentication(AUTH0_DOMAIN);

// Get the Auth0 logout URL to end the Auth0 session as well.
$auth0_logout = $auth0_api->get_logout_link(

    // This needs to be saved in the "Allowed Logout URLs" field in your Application settings.
   AUTH0_LOGOUT_RETURN_URL,
    // Indicate the specific Application.
    AUTH0_CLIENT_ID
);

//	Destroy any remaining edit sessions
unset($_SESSION['edit_bench_id']);

header('Location: ' . $auth0_logout);
exit;