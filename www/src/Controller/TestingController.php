<?php
namespace App\Controller;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\SecurityBundle\Security;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;

use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

use Auth0\SDK\Auth0;

class TestingController extends AbstractController
{
	public function __construct(
		         Security $security,
		     ) {
		     }
	
	public function test_date() {
		$cache = new FilesystemAdapter("testdate_cache");
		$value = $cache->get('cached_date', function (ItemInterface $item) {
			$item->expiresAfter(5);
			$date = date("Y-m-d H:i:s");

			return $date;
		});

		return $value;
	}

	#[Route(['/private',"/private/anon"], name: 'private')]
	public function private(Request $request): Response {
		$user = $this->getUser() ?? $_SESSION["_sf2_attributes"]["auth0_session"]["user"] ?? array();

		return new Response(
			'<html><body><pre>' . 
			print_r($user, true) .
			'</pre> Cache time '. $this->test_date() .' <a href="/logout">Logout</a></body></html>'
	  );
	}

	#[Route(["/auth0test"], name: "auth0test")]
	public function auth0test(Request $request): Response {
		$authHeader = $request->headers->get("Auth0-Authorization");

		if ($authHeader) {
			// API Token Path
			try {
				$token = substr($authHeader, 7);
				$auth0 = new Auth0([
					"domain"   => $_ENV["AUTH0_DOMAIN"],
					"clientId" => $_ENV["AUTH0_CLIENT_ID"],
				]);
				
				$decoded    = $auth0->decode($token);
			} catch (\Exception $e) {
				$decoded = "Couldn't use that token - {$authHeader}";
			}
		} else {
			$decoded = "No Auth0-Authorization found.";
		}

		return new Response(
			'<html><body><pre>' . 
			print_r($decoded, true) .
			'</pre> Cache time '. $this->test_date() .' <a href="/logout">Logout</a></body></html>'
	  );
	}

	#[Route(["/auth0test1"], name: "auth0test1")]
	public function auth0test1(Request $request): Response {
			$authHeader = "eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6IlFrVXdSRFl4UlVFelEwRTVRalExUkRJMVFUaERNRU0wTURZM05qTkRPVFE0UXpNek9ERTFOdyJ9.e__9.ZgnZxOOtfczLewlm_agK6mJMYetVTZrHlBlu5qzXbADlhvZB8RraVuFKmFutLZLibMQxz_RY0oh4hRufVWDHJ0kuocW38kRHztDg7R5KOfvJEM46WW49xvhLhKprzkx9WXDDlpCRNL0QbBK2U0F1VjmRpTp1Q5cHEd8PBsa4rGAhfqudXp5JrC2Lm5e7ji0AQ_s7HJhy59b9mTb3tMqHGsrWDZS915zHPYEQtSvg5o9sSx1tCRfsyL6kdsdkaTffQjJDUrT5hpIQ-2_9tGuqioJjP4c0edQ85TaK9UnSxfzMQ8gYez963kbo_Iv1fJyaTVwXR-AVvwK-CeGJAFrheQ";
		// API Token Path
		try {
			$token = $authHeader;
			$auth0 = new Auth0([
				"domain"       => $_ENV["AUTH0_DOMAIN"],
				"clientId"     => $_ENV["AUTH0_ANDROID_CLIENT_ID"],
				"clientSecret" => $_ENV["AUTH0_ANDROID_CLIENT_SECRET"],
				"cookieSecret" => "_"	//	Dummy value.
			]);

			$decoded = $auth0->decode(
				token: $token,
				tokenType: \Auth0\SDK\Token::TYPE_ID_TOKEN,
			);
			
			// $decoded    = $auth0->decode($token);
		} catch (\Exception $e) {
			$decoded = "Couldn't use that token - {$authHeader}\n\n {$e}";
		}
	
		// $claims = $decoded->toArray();

		// $username   = $claims["given_name"];
		// $identifier = $claims["sub"];

		return new Response(
			'<html><body><pre>'.
			print_r($decoded, true) .
			'</pre> Cache time '. $this->test_date()
	  );
	}

	#[Route(["/admin/no-mediatypes"], name: 'mediatypes')]
	public function mediatypes(Request $request): Response {

		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
		$conn = DriverManager::getConnection($connectionParams);

		$queryBuilder = $conn->createQueryBuilder();
		$queryBuilder
			->select("benches.benchID")
			->from("benches")
			->innerJoin('benches', 'media', 'media', 'benches.benchID = media.benchID')
			->where("media.media_type IS NULL AND benches.published = 1")
			->orderBy("benches.benchID", 'DESC')
			->groupBy("benches.benchID")
			;
		$results = $queryBuilder->executeQuery();

		//	Loop through the results to create an array of media
		$benches_array = array();
		while ( ( $row = $results->fetchAssociative() ) !== false) {
			//	Add the details to the array
			$benches_array[$row["benchID"]] = array(
				"benchID"     => $row["benchID"],
				"inscription" => $row["inscription"],
				"address"     => $row["address"],
			);
		}

		//	Render the page
		return $this->render('nomedia.html.twig', [
			"query"         => "Benches without Media Types",
			"count"         => sizeof($benches_array),
			"benches"       => $benches_array,
		]);
	}
}