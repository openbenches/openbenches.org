<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

use App\Service\MediaFunctions;
use App\Service\UserFunctions;
use App\Service\BenchFunctions;

class UserController extends AbstractController
{
	#[Route("/test", name: "test")]
	public function test(): Response {
		$test = var_export($_SESSION, true);
		return $this->render("test.html.twig", [ 
		//	"test" => $test
		]);
	}

	#[Route("/mastodon_login", name: "mastodon_login")]
	public function mastodon_login(): Response {
		//	Any ?get requests
		$request = Request::createFromGlobals();
		$mastodon = $request->query->get("mastodon");
		$server   = $request->query->get("server");
		$code     = $request->query->get("code");

		$userFunctions  = new UserFunctions();

		//	For HTTP requests
		$userAgent = "openbenches/0.1";
		$domain = $_ENV["DOMAIN"];

		//	Has the user sent a Mastodon server?
		if (isset( $mastodon )) {
			//	Lowercase it for better string matching
			$mastodon = strtolower( $mastodon );
			//  If so, extract the server's address
			if ( filter_var( $mastodon, FILTER_VALIDATE_URL ) ) {
				$server = parse_url( $mastodon, PHP_URL_HOST );
			} else {
				return $this->render("mastodon_login.html.twig", [ "error" => "No valid URl found." ]);
			}
		} else if ( isset( $server ) ) {
			//	If a server has been sent, use that
			$server = strtolower( $server );
		} else {
			//	Nothing sent. Display the regular login screen
			return $this->render("mastodon_login.html.twig", []);
		}

		//	Get the credentials associated with this server
		$credentials = $userFunctions->getMastodonAppDetails( $server );

		if ( !isset( $code ) ) {
			//	If there is no existing app. Create a new one
			if ( !$credentials ) {
				$client = HttpClient::create();
				
				try {
					$response = $client->request("POST", "https://{$server}/api/v1/apps", [
						"headers" => [
							"User-Agent" => $userAgent,
						],
						"body" => [
							'client_name'   => "Login to " . $_ENV["NAME"],
							'redirect_uris' => "{$domain}mastodon_login?server={$server}&",
							'scopes'        => "read:accounts",
							'website'       => "{$domain}"
						]
					]);
	
					//	If an error occurred
					if ( 200 !== $response->getStatusCode() ) {
						return $this->render("mastodon_login.html.twig", [ "error" => "Please check the domain and try again." ]);
					}
	
				} catch (TransportExceptionInterface $e) {
					return $this->render("mastodon_login.html.twig", [ "error" => "Something went wrong. Please check the domain and try again." ]);
				}

				//	Get the response
				$content = $response->toArray();
				$client_id     = $content["client_id"];
				$client_secret = $content["client_secret"];
				//	Create the app in the database
				$userFunctions->addMastodonAppDetails( $server, $client_id, $client_secret );
			}  else {
				//	Use the stored app credentials
				$client_id     = $credentials["client_id"];
				$client_secret = $credentials["client_secret"];
			}
			
			//	Redirect the user to the login URl on their provided server
			$login_URl = "https://{$server}/oauth/authorize".
			"?client_id={$client_id}" .
			"&scope=read:accounts" .
			"&redirect_uri={$domain}mastodon_login%3Fserver={$server}%26" .
			"&response_type=code";

			return $this->redirect( $login_URl, 301 );
			die();
		}

		//	A code has been provided
		//	Read the previously saved credentials for the server
		$client_id     = $credentials["client_id"];
		$client_secret = $credentials["client_secret"];

		//	Get the Bearer token
		$client = HttpClient::create();
		$response = $client->request("POST", "https://{$server}/oauth/token", [
			"headers" => [
				"User-Agent" => $userAgent,
			],
			"body" => [
				"client_id"     => $client_id,
				"client_secret" => $client_secret,
				"redirect_uri"  => "{$domain}mastodon_login?server={$server}&",
				"grant_type"    => "authorization_code",
				"code"          => $code,
				"scope"         => "read:accounts"
			]
		]);

		//	If an error occurred
		if ( 200 !== $response->getStatusCode() ) {
			return $this->render("mastodon_login.html.twig", [ "error" => "An error occurred fetching the Authorization code." ]);
		}

		//	Get the token
		$content = $response->toArray();
		$access_token = $content["access_token"];

		//	Verify the user's credentials
		$client = HttpClient::create();
		$response = $client->request("GET", "https://{$server}/api/v1/accounts/verify_credentials", [
			"headers" => [
				"User-Agent"    => $userAgent,
				"Authorization" => "Bearer {$access_token}"
			]
		]);

		//	If an error occurred
		if ( 200 !== $response->getStatusCode() ) {
			return $this->render("mastodon_login.html.twig", [ "error" => "Your details could not be verified." ]);
		}

		//	Get the user's details
		$content = $response->toArray();

		//	Save the user to the database
		$mastodon_username = $content["username"];
		$mastodon_id       = $content["url"];
		$userFunctions->addUser( $mastodon_username, "mastodon", $mastodon_id );
	
		//	Add the user to the session in a HORRIBLE hack
		$_SESSION["_sf2_attributes"]["auth0_session"]["user"]["nickname"] = "@{$mastodon_username}@{$server}";
		$_SESSION["_sf2_attributes"]["auth0_session"]["user"]["sub"]      = "mastodon|$mastodon_id";
		$_SESSION["_sf2_attributes"]["auth0_session"]["user"]["picture"]  = $content["avatar"];

		//	See if a small delay helps here
		sleep(2);
		
		//	Redirect the newly logged in user to the add page
		return $this->redirect( "/add", 301 );
	}

	#[Route("/user", name: "no_user")]
	public function no_user(): Response {
		//	Generate an HTTP 404 response
		throw $this->createNotFoundException( "The user does not exist." );
	}

	#[Route('/user/{user_id}', name: 'show_user')]
	public function show_user( $user_id ): Response {

		if ( !is_numeric( $user_id ) ) {
			//	Generate an HTTP 404 response
			throw $this->createNotFoundException( "The user does not exist." );
		}

		$request = Request::createFromGlobals();
		//	/user/1234?page=2&count=5
		$get_page      = $request->query->get("page");
		$get_count     = $request->query->get("count");

		//	Page
		if( isset( $get_page ) ){
			$get_page = (int)$get_page;
		} else {
			$get_page = 0;
		}

		// Items per page
		if( isset( $get_count ) ){
			$get_count = (int)$get_count;
		} else {
			$get_count = 20;
		}

		//	Pagination for the database
		$first = $get_page * $get_count;
		$last  = $get_count;

		$mediaFunctions = new MediaFunctions();
		$userFunctions  = new UserFunctions();

		$results_array = $userFunctions->getUserDetails( $user_id );

		if (false != $results_array) {
			$provider   = $results_array["provider"];
			$providerID = $results_array["providerID"];
			$name       = $results_array["name"];

			$avatar_url    = $userFunctions->getUserAvatar( $provider, $providerID, $name );
			$user_url      = $userFunctions->getUserURL( $provider, $providerID, $name );

			//	Get the benches associated with this user
			$benches_array = $userFunctions->getUserBenches( $user_id, $first, $last );
			$benches_count = $userFunctions->getUserBenchCount( $user_id);

			//	Pagination for the UI
			if( $get_page > 0 ) {
				$previous_page = $get_page - 1;
				$previous_url = "?page={$previous_page}&count={$get_count}";
			} else {
				$previous_url = null;
			}

			if( ( $benches_count > ( ($get_page * $get_count) + $get_count ) ) ) {
				$next_page = $get_page + 1;
				$next_url = "?page={$next_page}&count={$get_count}";
			} else {
				$next_url = null;
			}

			//	Render the page
			return $this->render('user.html.twig', [
				"user_id" => $user_id,
				"user_name"    => $name,
				"avatar_url" => $avatar_url,
				"user_external_url" => $user_url,
				"user_provider" => $provider,
				"benches_count" => $benches_count,
				"benches" => $benches_array,
				"next_url" => $next_url,
				"previous_url" => $previous_url,
				
			]);
		} else {
			//	Generate an HTTP 404 response
			throw $this->createNotFoundException("The user does not exist");
		}

		return $this->render('user.html.twig', [
			'user_id' => $user_id,
		]);
	}

	#[Route('/user/{user_service}/{user_name}', name: 'show_service_user')]
	public function show_service_user($user_service, $user_name): Response {
		$request = Request::createFromGlobals();
		//	/user/twitter/edent?page=2&count=5
		$get_page      = $request->query->get("page");
		$get_count     = $request->query->get("count");

		//	Page
		if( isset( $get_page ) ){
			$get_page = (int)$get_page;
		} else {
			$get_page = 0;
		}

		// Items per page
		if( isset( $get_count ) ){
			$get_count = (int)$get_count;
		} else {
			$get_count = 20;
		}

		//	Pagination for the database
		$first = $get_page * $get_count;
		$last  = $get_count;

		$mediaFunctions = new MediaFunctions();
		$userFunctions  = new UserFunctions();

		$results_array = $userFunctions->getUserDetailsFromSocial( $user_service, $user_name );

		if (false != $results_array) {
			$providerID = $results_array["providerID"];
			$user_id     = $results_array["userID"];
			$provider   = $user_service;
			$name       = $user_name;

			$avatar_url    = $userFunctions->getUserAvatar( $provider, $providerID, $name );
			$user_url      = $userFunctions->getUserURL( $provider, $providerID, $name );

			//	Get the benches associated with this user
			$benches_array = $userFunctions->getUserBenches( $user_id, $first, $last );
			$benches_count = $userFunctions->getUserBenchCount( $user_id);

			//	Pagination for the UI
			if( $get_page > 0 ) {
				$previous_page = $get_page - 1;
				$previous_url = "?page={$previous_page}&count={$get_count}";
			} else {
				$previous_url = null;
			}

			if( ( $benches_count > ( ($get_page * $get_count) + $get_count ) ) ) {
				$next_page = $get_page + 1;
				$next_url = "?page={$next_page}&count={$get_count}";
			} else {
				$next_url = null;
			}

			//	Render the page
			return $this->render('user.html.twig', [
				"user_id" => $user_id,
				"user_name"    => $name,
				"avatar_url" => $avatar_url,
				"user_external_url" => $user_url,
				"user_provider" => $provider,
				"benches_count" => $benches_count,
				"benches" => $benches_array,
				"next_url" => $next_url,
				"previous_url" => $previous_url,
				
			]);
		} else {
			//	Generate an HTTP 404 response
			throw $this->createNotFoundException("The user does not exist");
		}

		return $this->render('user.html.twig', [
			'user_id' => $user_id,
		]);
	}
}