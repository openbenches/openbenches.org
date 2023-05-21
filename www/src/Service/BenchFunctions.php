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
			WHERE benchID = ?";
	
			$stmt = $conn->prepare($sql);
			$stmt->bindValue(1, $bench_id);
			$results = $stmt->executeQuery();
	
			//	Loop through the results to create an array of media
			$media_array = array();
			while ( ( $row = $results->fetchAssociative() ) !== false) {
				$sha1 = $row["sha1"];
				$image_url = $mediaFunctions->getProxyImageURL( $sha1 );
	
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
				"inscription" => $inscription,
				"longitude"   => $longitude,
				"latitude"    => $latitude,
				"addresses"   => $address_array,
				"medias"      => $media_array,
				"tags"        => $tags_array,
			];
		} else {
			return array();
		}
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
}