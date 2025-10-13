<?php
// src/Service/UploadFunctions.php
namespace App\Service;

use App\Service\LocationFunctions;
use App\Service\MediaFunctions;
use App\Service\TagsFunctions;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;

class UploadFunctions
{
	public function addBench( $inscription, $latitude, $longitude, $userID ): int {
		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
		$conn = DriverManager::getConnection($connectionParams);

		//	Get the address from the location
		$locationFunctions = new LocationFunctions();
		$address = $locationFunctions->getAddress($latitude, $longitude);
		$address = html_entity_decode($address, ENT_QUOTES , "UTF-8");
		
		//	Trim errant whitespace from the end before inserting
		$inscription = rtrim($inscription);

		$sql = "INSERT INTO `benches`
		       (`benchID`,`latitude`,`longitude`,`address`, `inscription`,`description`,`present`,`published`, `added`,  `userID`)
		VALUES (NULL,      ?,         ?,          ?,         ?,            '',          '1',      '1', CURRENT_TIMESTAMP, ?)";
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(1, $latitude);
		$stmt->bindValue(2, $longitude);
		$stmt->bindValue(3, $address);
		$stmt->bindValue(4, $inscription);
		$stmt->bindValue(5, $userID);
		
		//	Run the query
		$results = $stmt->executeQuery();
		
		//	Get the ID of the row which was just inserted
		return $conn->lastInsertId();
	}

	public function updateBench( $benchID, $inscription, $latitude, $longitude, $published=true ) {
		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
		$conn = DriverManager::getConnection($connectionParams);

		//	Get the address from the location
		$locationFunctions = new LocationFunctions();
		$address = $locationFunctions->getAddress($latitude, $longitude);

		//	Trim errant whitespace from the end before inserting
		$inscription = rtrim($inscription);

		$sql = "UPDATE `benches`
		        SET `latitude`   = ?,
		            `longitude`  = ?,
		            `address`    = ?,
		            `inscription`= ?,
		            `published`  = ?
		         WHERE `benches`.`benchID` = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(1, $latitude);
		$stmt->bindValue(2, $longitude);
		$stmt->bindValue(3, $address);
		$stmt->bindValue(4, $inscription);
		$stmt->bindValue(5, $published);
		$stmt->bindValue(6, $benchID);
		
		//	Run the query
		$results = $stmt->executeQuery();
	}

	public function updateMedia( $mediaID, $mediaType ) {
		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
		$conn = DriverManager::getConnection($connectionParams);

		$sql = "UPDATE media
		        SET media_type = ?
		        WHERE mediaID = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(1, $mediaType);
		$stmt->bindValue(2, $mediaID);
		
		//	Run the query
		$stmt->executeQuery();
	}


	public function addMedia( $metadata, $media_type, $benchID, $userID, $licence = "CC BY-SA 4.0", $importURL = null ) : int {
		$mediaFunctions = new MediaFunctions();

		$file = $metadata["tmp_name"];
	
		//	Check to see if this has the right EXIF tags for a photosphere
		if ( $mediaFunctions->isPhotosphere( $file ) ) {
			$media_type = "360";
		} else if ( "360" == $media_type ) {
			//	If it has been miscategorised, remove the media type
			$media_type = null;
		}
	
		//	Files are stored according to their hash
		//	So "abc123" is stored as "/a/b/abc123.jpg"
		$sha1 = sha1_file( $file );
		$directory = substr( $sha1, 0, 1);
		$subdirectory = substr( $sha1, 1, 1);
		$photo_path = "photos/" . $directory . "/" . $subdirectory . "/";
		$photo_full_path = $photo_path . $sha1 . ".jpg";

		//	Move media to the correct location
		//	Create a directory if it doesn't exist
		if ( !is_dir( $photo_path ) ) {
			mkdir( $photo_path, 0777, true );
		}
		rename( $file, $photo_full_path );

		//	Add the media to the database
		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
		$conn = DriverManager::getConnection($connectionParams);

		$sql = "INSERT INTO `media`
		(`mediaID`, `benchID`, `userID`, `sha1`, `licence`, `importURL`, `media_type`, `width`, `height`, `datetime`, `make`, `model`)
		VALUES
		(NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		
		$stmt = $conn->prepare($sql);
		$stmt->bindValue( 1, $benchID);
		$stmt->bindValue( 2, $userID);
		$stmt->bindValue( 3, $sha1);
		$stmt->bindValue( 4, $licence);
		$stmt->bindValue( 5, $importURL);
		$stmt->bindValue( 6, $media_type);
		$stmt->bindValue( 7, $metadata["width"]);
		$stmt->bindValue( 8, $metadata["height"]);
		$stmt->bindValue( 9, $metadata["datetime"]);
		$stmt->bindValue(10, $metadata["make"]);
		$stmt->bindValue(11, $metadata["model"]);
		
		//	Run the query
		$results = $stmt->executeQuery();
		
		//	Get the ID of the row which was just inserted
		return $conn->lastInsertId();
	}

	public function saveTags( $benchID, $tags ) {
		// this function needs to work for when a bench is added or edited
		// when a bench is edited that may mean that the number of tags
		// increases, or decreases to as few as zero
		// easiest way to deal with this is to remove all entries for the
		// bench then add whatever tags were passed

		if ( null != $tags ) {
			$dsnParser = new DsnParser();
			$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
			$conn = DriverManager::getConnection($connectionParams);
	
			//	Delete Old Tags
			$sql = "DELETE FROM `tag_map` WHERE `benchID`=?";
			$stmt = $conn->prepare($sql);
			$stmt->bindValue(1, $benchID);
			$stmt->executeQuery();
			
			//	Find the IDs of the tags
			$tagsFunctions = new TagsFunctions();
			$tagIDs = $tagsFunctions->getTags();
	
			foreach ($tags as $tag) {
				$tagID = array_search($tag, $tagIDs);
	
				$sql = "INSERT INTO `tag_map` (`mapID`, `benchID`, `tagID`)
							VALUES                (NULL,     ?,         ?)";
				$stmt = $conn->prepare($sql);
				$stmt->bindValue(1, $benchID);
				$stmt->bindValue(2, $tagID);
				$stmt->executeQuery();
			}	
		}
	} 

	public function mergeBenches( $originalID, $duplicateID ) {
		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
		$conn = DriverManager::getConnection($connectionParams);

		//	Unpublish duplicate bench
		$sql = "UPDATE benches
			SET published = '0'
			WHERE benches.benchID = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(1, $duplicateID);
		$stmt->executeQuery();

		//	Redirect duplicate bench
		$sql = "INSERT INTO merged_benches (benchID, mergedID)
			VALUES (?, ?)";
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(1, $duplicateID);
		$stmt->bindValue(2, $originalID);
		$stmt->executeQuery();

		//	Merge photos
		$sql = "UPDATE media 
			SET benchID = ? 
			WHERE media.benchID = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(1, $originalID);
		$stmt->bindValue(2, $duplicateID);
		$stmt->executeQuery();
	}

	public function emailAdmin( $benchID, $inscription, $provider, $name, $tags_array ) {
		$benchFunctions = new BenchFunctions();
		$duplicate_count = $benchFunctions->getDuplicateCount( $inscription );
		$soundex         = $benchFunctions->getSoundex( $inscription );
		$domain = $_ENV["DOMAIN"];

		mail($_ENV["NOTIFICATION_EMAIL"],
			"Bench {$benchID}",
			"Possible duplicates {$duplicate_count}\n" .
			"{$inscription}\n" .
			"Tags: "  . implode(",", $tags_array) . "\n" .
			"{$domain}bench/{$benchID}\n\n" .
			"Duplicates: {$domain}soundex/?soundex={$soundex}\n" .
			"From {$provider} / {$name}"
		);
	}
}