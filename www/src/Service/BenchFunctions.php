<?php
// src/Service/BenchFunctions.php
namespace App\Service;

use App\Service\MediaFunctions;
use App\Service\TagsFunctions;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;

class BenchFunctions
{
	public function getRandomBenchID(): int {
		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
		$conn = DriverManager::getConnection($connectionParams);

		$sql = "SELECT `benchID` FROM `benches` 
		        WHERE  `published` = true AND `present` = true 
				  ORDER BY RAND() LIMIT 1";
		$stmt = $conn->prepare($sql);
		$results = $stmt->executeQuery();
		$results_array = $results->fetchAssociative();
		$benchID = $results_array["benchID"];

		return $benchID;
	}

	public function getBench($bench_id): array {
		$mediaFunctions = new MediaFunctions();

		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
		$conn = DriverManager::getConnection($connectionParams);

		//	Bench
		$sql = "SELECT * FROM `benches` WHERE `benchID` =  ? AND `published` = true";
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(1, $bench_id);
		$results = $stmt->executeQuery();
		$results_array = $results->fetchAssociative();

		if ( false != $results_array ) {
			$inscription = $results_array["inscription"];
			$latitude    = $results_array["latitude"];
			$longitude   = $results_array["longitude"];
			$address     = $results_array["address"];
			$timestamp   = $results_array["added"];
			$osmID       = $results_array["osmID"];

			//	Format the address
			$locations = explode("," , $address);
			$locations = array_reverse( $locations );

			$address_array = array();
			$location_link = "/location";
			foreach ( $locations as $location ) {
				if ( null != $location ) {
					$location_link .= "/" . rawurlencode( trim( $location ) );
					$address_array[] = array( "url" => "{$location_link}", "location" => $location);
				}
			}
			$address_array = array_reverse( $address_array );
	
			//	Media
			$sql = "SELECT sha1, users.userID, users.name, users.provider, users.providerID, importURL, licence, media_type, datetime, make, model, width, height, media_types.longName, mediaID
			FROM media
			INNER JOIN users ON media.userID = users.userID
			LEFT JOIN media_types on media.media_type = media_types.shortname
			WHERE benchID = ?
			GROUP BY sha1
			ORDER BY media.mediaID";	//	GROUP BY prevents duplicates images in case of merge.
	
			$stmt = $conn->prepare($sql);
			$stmt->bindValue(1, $bench_id);
			$results = $stmt->executeQuery();
	
			//	Loop through the results to create an array of media
			$media_array = array();
			while ( ( $row = $results->fetchAssociative() ) !== false) {
				$sha1 = $row["sha1"];
				$image_url      = $mediaFunctions->getProxyImageURL( $sha1 );
				$image_url_full = $mediaFunctions->getProxyImageURL( $sha1, null );
	
				//	Who took the photo?
				$userProvider = $row["provider"];
				if("anon" != $userProvider) {
					$userID   = $row["userID"];
					$userName = $row["name"];
				} else {
					$userID = "";
					$userName = "";
				}
	
				//	When was the photo taken?
				$datetime = $row["datetime"];
				if ($datetime != null) {
					$formatted_date = date("jS M Y", strtotime($datetime));
				} else {
					$formatted_date = "";
				}
	
				//	Media ID
				$mediaID = $row["mediaID"];
				//	What camera was used?
				$make = $row["make"];
				$make = ucwords($make);
				$model = $row["model"];
				$model = ucwords($model);

				//	What sort of media is it?
				$mediaType = $row["media_type"];
	
				//	Scale the image for display
				$width  = $row["width"];
				$height = $row["height"];
	
				if ( $width != null & $height != null ){
					$newHeight = $mediaFunctions->getScaledHeight($width, $height, 600);	
				} else {
					$newHeight = "";
				}
	
				//	How is it licenced?
				$licence = $row["licence"];
				$licenceIcon = $mediaFunctions->getLicenseIcon($licence);
	
				//	Where did it come from?
				$importURL = $row["importURL"];
	
				//	What's the alt text?
				$alt = $row["longName"];
	
				//	Add the details to the array
				$media_array[$mediaID] = array(
					          "url" => $image_url,
					      "urlFull" => $image_url_full,
					      "mediaID" => $mediaID,
					         "sha1" => $sha1,
					          'alt' => $alt,
					       "userID" => $userID,
					     "userName" => $userName,
					"formattedDate" => $formatted_date,
					         "make" => $make,
					        "model" => $model,
					    "mediaType" => $mediaType,
					        "width" => 600,
					       "height" => $newHeight,
					      "licence" => $licence,
					  "licenceIcon" => $licenceIcon,
						"importURL" => $importURL,				 
				);
			}

			//	Get any tags
			$tagsFunctions = new TagsFunctions();
			$tags_array = $tagsFunctions->getTagsFromBench($bench_id);

			//	Render the page
			return [
				"bench_id"    => $bench_id,
				"timestamp"   => $timestamp,
				"inscription" => $inscription,
				"longitude"   => $longitude,
				"latitude"    => $latitude,
				"addresses"   => $address_array,
				"medias"      => $media_array,
				"tags"        => $tags_array,
				"osmID"       => $osmID,
			];
		} else {
			return array();
		}
	}

	public function deleteBench($bench_id) {

		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse($_ENV['DATABASE_URL']);
		$conn = DriverManager::getConnection($connectionParams);

		//	Unpublish the bench
		$sql = "UPDATE `benches` SET `published` = false WHERE `benchID` =  ?";
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(1, $bench_id);
		$results = $stmt->executeQuery();
	}

	public function getDuplicateCount( $inscription ): int {
		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
		$conn = DriverManager::getConnection($connectionParams);

		$sql = "SELECT  COUNT(*)
		        FROM   `benches`
		        WHERE  SOUNDEX(`inscription`) = SOUNDEX(?)
		        AND    `published` = true";
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(1, $inscription);
		$results = $stmt->executeQuery();
		$results_array = $results->fetchAssociative();

		if (false != $results_array) {
			//	There should always be at least one result - the bench which was just uploaded
			return $results_array["COUNT(*)"] -1;
		} else {
			return 0;
		}
	}

	public function getSoundex( $inscription ): string {
		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
		$conn = DriverManager::getConnection($connectionParams);

		$sql = "SELECT SOUNDEX(?)";
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(1, $inscription);
		$results = $stmt->executeQuery();
		$results_array = $results->fetchAssociative();

		if (false != $results_array) {
			return $results_array["SOUNDEX(?)"];
		} else {
			return "";
		}
	}

	public function getComments( $bench_id ): array {

		//	Load up the config.
		$commenticsConfig = $_SERVER["DOCUMENT_ROOT"] . "/public/commentics/config.php";

		if ( file_exists( $commenticsConfig ) ) {
			require_once $commenticsConfig;
		} else {
			return [];
		}

		// @phpstan-ignore constant.notFound, constant.notFound, constant.notFound, constant.notFound, constant.notFound (In the separate commentics/config.php) 
		$commenticsDB = "mysqli://" . CMTX_DB_USERNAME . ":" . CMTX_DB_PASSWORD . "@" . CMTX_DB_HOSTNAME . ":" . CMTX_DB_PORT . "/" . CMTX_DB_DATABASE . "?&charset=utf8mb4";

		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse( $commenticsDB );
		$conn = DriverManager::getConnection($connectionParams);

		//	Get all the comments for this page.
		$sql = "SELECT 
					pages.identifier AS page_url,
					comments.comment,
					comments.date_added,
					comments.id,
					users.name
				FROM 
					comments
				INNER JOIN 
					pages ON comments.page_id = pages.id
				INNER JOIN 
					users ON comments.user_id = users.id
				WHERE 
					comments.is_approved = 1
				AND
					pages.identifier = 'openbenches.org/bench/{$bench_id}'
				ORDER BY 
					comments.date_added DESC 
				LIMIT 1000;";

		$stmt = $conn->prepare($sql);
		$results = $stmt->executeQuery();

		$comments_array = array();
		while ( ( $row = $results->fetchAssociative() ) !== false) {
			//	Add the details to the array.
			$comments_array[] = array(
				"id"         => $row["id"],
				"comment"    => $row["comment"],
				"date_added" => $row["date_added"],
				"name"       => $row["name"],
			);
		}

		return $comments_array;
	}

	public function getCommentsHTML( int $bench_id ): string {
		//	Define the Commentics variables.
		$cmtx_identifier = "openbenches.org/bench/" . $bench_id;

		//	Capture the HTML output. This is a *very* ugly hack!
		ob_start();
		//	Unset the referrer to prevent Commentics throwing a wobbly.
		$_SERVER["HTTP_REFERER"] = null;
		require( $_SERVER["DOCUMENT_ROOT"] . "/public/commentics/frontend/index.php");
		$comments_html = ob_get_clean();

		//	Sanitise the HTML and prepare for manipulation.
		// @phpstan-ignore staticMethod.notFound
		$dom = \Dom\HTMLDocument::createFromString( $comments_html, LIBXML_NOERROR | LIBXML_HTML_NOIMPLIED , "UTF-8" );
		
		//	Elements to remove.

		//	Reply link.
		$reply = $dom->querySelector( ".cmtx_reply_area" );
		if ( $reply ) { $reply->parentNode->removeChild( $reply ); }

		//	Preview button.
		$preview = $dom->querySelector( ".cmtx_preview_button_container" );
		if ( $preview ) { $preview->parentNode->removeChild( $preview ); }

		//	Modal Nodes.
		$bullet = $dom->querySelector( "#cmtx_bullet_modal" );
		if ( $bullet ) { $bullet->parentNode->removeChild( $bullet ); }
		$numeric = $dom->querySelector( "#cmtx_numeric_modal" ); 
		if ( $numeric ) { $numeric->parentNode->removeChild($numeric); }
		$link = $dom->querySelector( "#cmtx_link_modal" );
		if ( $link ) { $link->parentNode->removeChild( $link ); }
		$email = $dom->querySelector( "#cmtx_email_modal" );
		if ( $email ) { $email->parentNode->removeChild( $email ); }
		$image = $dom->querySelector( "#cmtx_image_modal" );
		if ( $image ) { $image->parentNode->removeChild( $image ); }
		$youtube = $dom->querySelector( "#cmtx_youtube_modal" );
		if ( $youtube ) { $youtube->parentNode->removeChild( $youtube ); }
		$privacy = $dom->querySelector( "#cmtx_privacy_modal" );
		if ( $privacy ) { $privacy->parentNode->removeChild( $privacy ); }
		$terms = $dom->querySelector( "#cmtx_terms_modal" );
		if ( $terms ) { $terms->parentNode->removeChild( $terms ); }
		$lightbox = $dom->querySelector( "#cmtx_lightbox_modal" );
		if ( $lightbox ) { $lightbox->parentNode->removeChild( $lightbox ); }

		//	Others.
		$required = $dom->querySelector( ".cmtx_required_text" );
		if ( $required ) { $required->parentNode->removeChild( $required ); }
		
		// CSS.
		$links = $dom->getElementsByTagName( "link" );
		foreach ( $links as $css ) {
			$css->parentNode->removeChild( $css );
		}

		//	Elements to improve.

		//	Make button more buttony.
		$button = $dom->querySelector( "#cmtx_submit_button" );
		$button->setAttribute("class", "button");

		//	Send back the comment form and any comments.
		return $dom->saveHTML();
	}
}