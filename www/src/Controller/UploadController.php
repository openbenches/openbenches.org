<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;

use App\Service\MediaFunctions;
use App\Service\UserFunctions;
use App\Service\UploadFunctions;
use App\Service\BenchFunctions;


class UploadController extends AbstractController
{
	#[Route(["/upload", "/upload/logged_in"], name: 'upload')]
	public function upload(Request $request) {
		// Returns the bench ID if successful, or an error message if not. 
		//	POST'd data
		$inscription = $request->request->get( "inscription" );

		$longitude   = $request->request->get( "newLongitude" );
		$latitude    = $request->request->get( "newLatitude" );

		$tags        = $request->request->get( "tags" );

		if ( $tags != "" ) {
			$tags_array = explode(",", $tags);
		} else {
			$tags_array = array();
		}
		
		//	Get metadata about 1st image
		if ( isset( $_FILES['userfile1']['tmp_name'] ) ) {
			$mediaFunctions = new MediaFunctions();

			$filename = $_FILES['userfile1']['tmp_name'];
			$metadata = $mediaFunctions->getMediaMetadata( $filename );
			$media_type1 = $request->request->get( "media_type1" );
		} else {
			$response = new Response(
				"No image found.<br><a href=\"/add\">Please reload this page and try a different photo</a>",
				Response::HTTP_UNSUPPORTED_MEDIA_TYPE,
				['content-type' => 'text/html']
			);
		}
		
		//	If there is a GPS tag on the photo
		if ( isset( $metadata["latitude"] ) )
		{
			$uploadFunctions = new UploadFunctions();
			$userFunctions   = new UserFunctions();

			//	Is the user authenticated?
			$user = $this->getUser();
			if( isset( $user ) ) {
				//	Use Auth0 to get user data
				$username   = $user->getNickname();
				$provider   = explode("|", $user->getUserIdentifier())[0];
				$providerID = explode("|", $user->getUserIdentifier())[1];	
			} else if ( isset( $_SESSION["_sf2_attributes"]["auth0_session"]["user"] ) ) {
				//	Hack to get Auth0 user data from the session
				$user = $_SESSION["_sf2_attributes"]["auth0_session"]["user"];
				$username   = $user["nickname"];
				$provider   = explode("|", $user["sub"])[0];
				$providerID = explode("|", $user["sub"])[1];
			} else {
				$username   = null;
				$provider   = null;
				$providerID = null;
			}

			$userID = $userFunctions->addUser( $username, $provider, $providerID );
			
			$user     = $userFunctions->getUserDetails( $userID );
			$provider = $user["provider"];
			$name     = $user["name"];

			$benchID = $uploadFunctions->addBench( $inscription, $latitude, $longitude, $userID );
	
			if ( isset( $tags_array ) ){
				$uploadFunctions->saveTags( $benchID, $tags_array );
			}

			$metadata["tmp_name"] = $filename;
			$uploadFunctions->addMedia( $metadata, $media_type1, $benchID, $userID );

			//	Upload any subsequent images
			for ( $i = 2; $i <= 4; $i++ ) {
				if ( isset( $_FILES["userfile{$i}"]["tmp_name"] ) ) {
					$filename = $_FILES["userfile{$i}"]["tmp_name"];
					$metadata = $mediaFunctions->getMediaMetadata( $filename );
					$media_type = $request->request->get( "media_type{$i}" );
					$metadata["tmp_name"] = $filename;
					$uploadFunctions->addMedia( $metadata, $media_type, $benchID, $userID );	
				}	
			}

			//	Send admin email
			if ( !empty($_ENV["NOTIFICATION_EMAIL"]) ){
				$uploadFunctions->emailAdmin( $benchID, $inscription, $provider, $name, $tags_array );
			}
			//	Post to Mastodon
			if ( !empty($_ENV["MASTODON_ACCESS_TOKEN"]) ){
				$uploadFunctions->mastodonPost( $benchID, $inscription, "CC BY-SA 4.0", $provider, $name );
			}
			//	Post to Twitter
			// if ( !empty($_ENV["OAUTH_CONSUMER_KEY"]) ){
			// 	$uploadFunctions->twitterPost( $benchID, $inscription, $latitude, $longitude, "CC BY-SA 4.0", $provider, $name );
			// }

			$response = new Response(
				"{$benchID}",
				Response::HTTP_OK,
				["content-type" => "text/plain"]
			);
			return $response;
		} else {
			$response = new Response(
				"No location metadata found in image.<br><a href=\"/add\">Please reload this page and try a different photo</a>",
				Response::HTTP_UNSUPPORTED_MEDIA_TYPE,
				["content-type" => "text/html"]
			);
		}
	}

	#[Route("/merge", name: "merge")]
	public function merge(Request $request) {
		//	POST'd data
		$originalID  = $request->request->get("originalID");
		$duplicateID = $request->request->get("duplicateID");
		if ($originalID != null && $duplicateID != null) {
			$uploadFunctions = new UploadFunctions();

			$uploadFunctions->mergeBenches($originalID, $duplicateID);
			return $this->redirect("/bench/{$duplicateID}");
		}
	}
}