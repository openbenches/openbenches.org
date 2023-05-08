<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;

use App\Service\MediaFunctions;
use App\Service\UserFunctions;
use App\Service\UploadFunctions;


class UploadController extends AbstractController
{
	#[Route(["/upload", "/upload/logged_in"], name: 'upload')]
	public function upload(Request $request) {
		// Returns the bench ID if successful, or an error message if not. 
		//	POST'd data
		$inscription = $request->request->get( "inscription" );

		$longitude   = $request->request->get( "newLongitude" );
		$latitude    = $request->request->get( "newLatitude" );

		$tags        = $request->request->get( "tags[]" );
		if ( $tags != "" ) {
			$tags = explode(",", $tags);
		} else {
			$tags = null;
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
			$userFunctions = new UserFunctions();

			$user = $this->getUser();
			if( isset( $user ) ) {
				$userID = $userFunctions->addUser($user);
			} else {
				$userID = $userFunctions->addUser(null);
			}

			$benchID = $uploadFunctions->addBench( $inscription, $latitude, $longitude, $userID );
	
			// $tagsID  = $uploadFunctions->addTags();

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
			merge_benches($originalID, $duplicateID);
			return $this->render('soundex.html.twig', [
				"duplicateID"       => $duplicateID,
				"soundex"           => "",
			]);
		}
	}
}