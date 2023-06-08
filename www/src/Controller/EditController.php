<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;

use App\Service\MediaFunctions;
use App\Service\BenchFunctions;
use App\Service\UserFunctions;
use App\Service\TagsFunctions;
use App\Service\UploadFunctions;

class EditController extends AbstractController
{
	#[Route('/edit/{benchID}', name: 'edit')]
	public function edit(int $benchID): Response {

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

		$benchFunctions = new BenchFunctions();
		$bench = $benchFunctions->getBench( $benchID );

		$tagsFunctions = new TagsFunctions();
		$bench_tags = $tagsFunctions->getTagsFromBench( $benchID );
		$all_tags   = $tagsFunctions->getTagsNames();

		$admin = ( array_search( $userID, explode(",", $_ENV["ADMIN_USERIDS"])) !== false );

		if ( isset($bench["bench_id"]) ) {
			return $this->render("edit.html.twig", [
				"benchID"     => $bench["bench_id"],
				"inscription" => $bench["inscription"],
				"longitude"   => $bench["longitude"],
				"latitude"    => $bench["latitude"],
				"medias"      => $bench["medias"],
				"username"    => $username,
				"avatar"      => $avatar,
				"provider"    => $provider,
				"providerID"  => $providerID,
				"all_tags"    => $all_tags,
				"bench_tags"  => $bench_tags,
				"uploadURL"   => "/update",
				"admin"       => $admin,
			]);
		}
	}

	#[Route("/update", name: "update")]
	public function update(Request $request) {
		if ( $request->isMethod('POST') ) {
			// Returns the bench ID if successful, or an error message if not. 
			//	POST'd data
			$benchID     = $request->request->get( "benchID" );
			$inscription = $request->request->get( "inscription" );
			$longitude   = $request->request->get( "newLongitude" );
			$latitude    = $request->request->get( "newLatitude" );
			$tags        = $request->request->get( "tags" );
			$delete      = $request->request->get( "delete" );
			//	Ensure booleanness of delete
			$delete      = filter_var( $delete, FILTER_VALIDATE_BOOLEAN );
		
			//	Is the user authenticated?
			$user = $this->getUser();
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

			$benchFunctions = new BenchFunctions();

			$userFunctions = new UserFunctions();
			$userID = $userFunctions->addUser( $username, $provider, $providerID );
			$admin = ( array_search( $userID, explode(",", $_ENV["ADMIN_USERIDS"])) !== false );

			if ( !$delete ) {
				if ( $tags != "" ) {
					$tags_array = explode(",", $tags);
				} else {
					$tags_array = array();
				}
	
				//	Old bench details for email
				$oldBench = $benchFunctions->getBench($benchID);
	
	
				//	Update the bench
				$uploadFunctions = new UploadFunctions();
				$uploadFunctions->updateBench( $benchID, $inscription, $latitude, $longitude, true );
	
				//	Update the tags
				if ( !empty( $tags_array ) ) {
					$uploadFunctions->saveTags( $benchID, $tags_array );
				}
	
				//	Update the media types
				$medias = $oldBench["medias"];
				$mediaTypes = "";
				foreach ( $medias as $media ) {
					$mediaID = $media["mediaID"];
					$oldType = $medias[$mediaID]["mediaType"];
					$newMediaType = $request->request->get( "media_{$mediaID}" );
					$uploadFunctions->updateMedia( $mediaID, $newMediaType );
					$mediaTypes .= "{$mediaID}: Old {$oldType}, New {$newMediaType}.\n";
				}
	
				//	Upload any added images
				$mediaFunctions = new MediaFunctions();
	
				for ( $i = 1; $i <= 4; $i++ ) {
					if ( isset( $_FILES["userfile{$i}"]["tmp_name"] ) ) {
						$filename = $_FILES["userfile{$i}"]["tmp_name"];
						if( "" != $filename ){
							$metadata = $mediaFunctions->getMediaMetadata( $filename );
							$media_type = $request->request->get( "media_type{$i}" );
							$metadata["tmp_name"] = $filename;
							$uploadFunctions->addMedia( $metadata, $media_type, $benchID, $userID );	
						}
					}	
				}
	
				$domain = $_ENV["DOMAIN"];
	
				mail($_ENV["NOTIFICATION_EMAIL"],
					"Edit to Bench {$benchID}",
					"{$domain}bench/{$benchID}\n\n" .
					"Old Inscription:\n" . $oldBench["inscription"]  . "\n" .
					"New Inscription:\n" . $inscription              . "\n" . 
					"Old Lat:\n"   . $oldBench["latitude"]           . "\n" .
					"New Lat:\n"   . $latitude                       . "\n" .
					"Old Long:\n"  . $oldBench["longitude"]          . "\n" .
					"New Long:\n"  . $longitude                      . "\n" .
					"Old Tags:\n"  . implode(",", $oldBench["tags"]) . "\n" .
					"New Tags:\n"  . implode(",", $tags_array)       . "\n" .
					"New Images: " . count($_FILES)                  . "\n" .
					"Media Types: ". $mediaTypes                     . "\n" .   
					"From {$provider} / {$username}"
				);
			} else {
				//	Delete Bench
				if ( $admin ) {
					$benchFunctions->deleteBench( $benchID );
					$domain = $_ENV["DOMAIN"];
	
					mail($_ENV["NOTIFICATION_EMAIL"],
						"Deleted Bench {$benchID}",
						"{$domain}bench/{$benchID}\n\n" . 
						"From {$provider} / {$username}"
					);
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
				"An error occured",
				Response::HTTP_UNSUPPORTED_MEDIA_TYPE,
				["content-type" => "text/html"]
			);
		}
	}
}