<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;

use App\Service\TagsFunctions;
use App\Service\UserFunctions;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;

class AddController extends AbstractController
{
	#[Route(["/add", "/add/logged_in"], name: 'add')]
	public function add(): Response {

		$user = $this->getUser();

		$username   = null;
		$avatar     = null;
		$provider   = null;
		$providerID = null;

		if( isset( $user ) ) {
			//	Use Auth0 to get user data.
			$username   = $user->getNickname();
			$avatar     = $user->getPicture();
			$identifier = $user->getUserIdentifier();
		} else if ( isset( $_SESSION["_sf2_attributes"]["auth0_session"]["user"] ) ) {
			//	Hack to get Auth0 user data from the session
			$user = $_SESSION["_sf2_attributes"]["auth0_session"]["user"];
			$username   = $user["nickname"];
			$avatar     = $user["picture"];
			$identifier = $user["sub"]; //	For Discord
		}

		//	Some users have unusual User IDs from Auth0.
		//	Discord: oauth2|discord|123456789
		//	OSM:     oidc|openstreetmap-openid|12345
		if ( str_starts_with( haystack:$identifier, needle:"oauth2|" ) ) {
			$identifier = str_replace( 
				search:"oauth2|", 
				replace:"", 
				subject:$identifier,
				count:1
			);
		}
		//	OSM Fix.
		if ( str_starts_with( haystack:$identifier, needle:"oidc|" ) ) {
			$identifier = str_replace( 
				search:"oidc|", 
				replace:"", 
				subject:$identifier,
				count:1
			);
		}
		$provider   = explode( "|", $identifier )[0];
		$providerID = explode( "|", $identifier )[1];

		//	Check if user is banned.
		$userFunctions = new UserFunctions();
		$banned = $userFunctions->isUserBanned( $provider, $providerID );
		if ( $banned ) {
			$response = new Response(
				"You are unable to contribute to OpenBenches.<br>Reason: {$banned}",
				Response::HTTP_FORBIDDEN,
				["content-type" => "text/html"]
			);
			return $response;
		}

		$tagsFunctions = new TagsFunctions();
		$tags_array = $tagsFunctions->getTagsNames();

		return $this->render('add.html.twig', [
			"username"   => $username,
			"avatar"     => $avatar,
			"provider"   => $provider,
			"providerID" => $providerID,
			"tags_array" => $tags_array,
		]);
	}

	#[Route(["/tags"], name: "tags")]
	public function tags() {
		$tagsFunctions = new TagsFunctions();
		$tagsFunctions->getTags();
	}

	#[Route("/flickr", name: "flickr_page")]
	public function flickr_page(): Response {
		$request = Request::createFromGlobals();

		$userFunctions   = new UserFunctions();

		//	Only available to Admin users
		//	Get user from Auth0
		$user = $this->getUser();
		if( isset( $user ) ) {
			$username   = $user->getNickname();
			$avatar     = $user->getPicture();
			$provider   = explode("|", $user->getUserIdentifier())[0];
			$providerID = explode("|", $user->getUserIdentifier())[1];	
		} else {
			die();
		}

		$userFunctions = new UserFunctions();
		$userID = $userFunctions->addUser( $username, $provider, $providerID );

		$admin = ( array_search( $userID, explode(",", $_ENV["ADMIN_USERIDS"])) !== false );

		if ( false == $admin) { die(); }


		//	/flickr/?id=AAAA123
		$flickrID   = $request->query->get("id") ?? false;

		if ( $flickrID )
		{
			$flickrKey = $_ENV["FLICKR_API_KEY"];
			$flickrAPI = "https://api.flickr.com/services/rest/?method=flickr.photos.getInfo&api_key={$flickrKey}&format=json&nojsoncallback=1&photo_id={$flickrID}";

			$flickrJSON = file_get_contents($flickrAPI);
			$flickrData = json_decode($flickrJSON);

			$lat =       $flickrData->{"photo"}->{"location"}->{"latitude"};
			$long =      $flickrData->{"photo"}->{"location"}->{"longitude"};
			$farm =      $flickrData->{"photo"}->{"farm"};
			$server =    $flickrData->{"photo"}->{"server"};
			$id =        $flickrData->{"photo"}->{"id"};
			$owner =     $flickrData->{"photo"}->{"owner"}->{"nsid"};
			$secret =    $flickrData->{"photo"}->{"secret"};
			$o_secret =  $flickrData->{"photo"}->{"originalsecret"};
			$license =   $flickrData->{"photo"}->{"license"};
			$title =     $flickrData->{"photo"}->{"title"}->{"_content"};
			$description=$flickrData->{"photo"}->{"description"}->{"_content"};
			$import = "https://www.flickr.com/photos/{$owner}/{$id}";

			//	Calculate original photo for importing
			$original = "https://farm{$farm}.staticflickr.com/{$server}/{$id}_{$o_secret}_o.jpg";

			//	Possible inscription
			$inscription = $title . " " . $description;

			//	Get the largest scaled image which is not the original
			$flickrSizeAPI = "https://api.flickr.com/services/rest/?method=flickr.photos.getSizes&api_key={$flickrKey}&format=json&nojsoncallback=1&photo_id={$flickrID}";
			$flickrSizeJSON = file_get_contents( $flickrSizeAPI );
			$flickrSizeData = json_decode( $flickrSizeJSON );
			$sizes = $flickrSizeData->{"sizes"}->{"size"};
			end( $sizes );
			$size = prev( $sizes );
			$large = $size->{"source"};
			$b64 = base64_encode( file_get_contents( $large ) );

			switch ($license) {
				case 0:
					$license = "All Rights Reserved";
					break;
				case 1:
					$license = "CC BY-NC-SA 2.0";
					break;
				case 2:
					$license = "CC BY-NC 2.0";
					break;
				case 3:
					$license = "CC BY-NC-ND 2.0";
					break;
				case 4:
					$license = "CC BY 2.0";
					break;
				case 5:
					$license = "CC BY-SA 2.0";
					break;
				case 6:
					$license = "CC BY-ND 2.0";
					break;
				case 7:
					$license = "PD";
					break;
				case 8:
					$license = "USG";
					break;
			}

			//	Render the page
			return $this->render('flickr.html.twig', [
				"flickrID"       => $flickrID,
				"licence" => $license,
				"b64" => $b64,
				"lat" => $lat,
				"long"=>  $long,
				"inscription" => $inscription,
				"original" => $original,
				"large" => $large,
				"import" => $import
			]);
		}
		//	Render the default page
		return $this->render('flickr.html.twig', [
		]);
	}
}