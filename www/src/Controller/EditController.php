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

		$user = $this->getUser();
		$userFunctions = new UserFunctions();
		$userID   = $userFunctions->addUser($user);

		if( isset( $user ) ) {
			$username   = $user->getNickname();
			$avatar     = $user->getPicture();
			$provider   = explode("|", $user->getUserIdentifier())[0];
			$providerID = explode("|", $user->getUserIdentifier())[1];	
		} else {
			die();
		}

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
			$published   = $request->request->get( "published" ) ?? true;
			$tags        = $request->request->get( "tags" );

			if ( $tags != "" ) {
				$tags = explode(",", $tags);
			} else {
				$tags = null;
			}

			//	Old bench details for email
			$benchFunctions = new BenchFunctions();
			$oldBench = $benchFunctions->getBench($benchID);

			//	Is the user authenticated?
			$user = $this->getUser();

			if( null !== $user ) {
				//	Who is this?
				$userFunctions = new UserFunctions();
				$userID   = $userFunctions->addUser($user);
				$user     = $userFunctions->getUserDetails( $userID );
				$provider = $user["provider"];
				$name     = $user["name"];

				//	Update the bench
				$uploadFunctions = new UploadFunctions();
				$uploadFunctions->updateBench( $benchID, $inscription, $latitude, $longitude, $published );

				//	Update the tags
				$uploadFunctions->saveTags( $benchID, $tags );

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

			} else {
				$response = new Response(
					"An error occured",
					Response::HTTP_UNSUPPORTED_MEDIA_TYPE,
					["content-type" => "text/html"]
				);
			}

			$domain = $_ENV["DOMAIN"];

			mail($_ENV["NOTIFICATION_EMAIL"],
				"Edit to Bench {$benchID}",
				"https://{$domain}/bench/{$benchID}\n\n" .
				"Old Inscription:\n" . $oldBench["inscription"]  . "\n" .
				"New Inscription:\n" . $inscription              . "\n" . 
				"Old Lat:\n"   . $oldBench["latitude"]           . "\n" .
				"New Lat:\n"   . $latitude                       . "\n" .
				"Old Long:\n"  . $oldBench["longitude"]          . "\n" .
				"New Long:\n"  . $longitude                      . "\n" .
				"Old Tags:\n"  . implode(",", $oldBench["tags"]) . "\n" .
				"New Tags:\n"  . $tags                           . "\n" .
				"New Images: " . count($_FILES)                  . "\n" .
				"From {$provider} / {$name}"
			);

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