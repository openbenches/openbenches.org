<?php
// src/Service/UploadFunctions.php
namespace App\Service;

use App\Service\LocationFunctions;
use App\Service\MediaFunctions;

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

	public function addMedia( $metadata, $media_type, $benchID, $userID ) : int {
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
		move_uploaded_file($file, $photo_full_path);

		//	Add the media to the database
		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
		$conn = DriverManager::getConnection($connectionParams);

		$sql = "INSERT INTO `media`
		(`mediaID`, `benchID`, `userID`, `sha1`, `licence`, `importURL`, `media_type`, `width`, `height`, `datetime`, `make`, `model`)
		VALUES
		(NULL, ?, ?, ?, 'CC BY-SA 4.0', null, ?, ?, ?, ?, ?, ?)";
		
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(1, $benchID);
		$stmt->bindValue(2, $userID);
		$stmt->bindValue(3, $sha1);
		$stmt->bindValue(4, $media_type);
		$stmt->bindValue(5, $metadata["width"]);
		$stmt->bindValue(6, $metadata["height"]);
		$stmt->bindValue(7, $metadata["datetime"]);
		$stmt->bindValue(8, $metadata["make"]);
		$stmt->bindValue(9, $metadata["model"]);
		
		//	Run the query
		$results = $stmt->executeQuery();
		
		//	Get the ID of the row which was just inserted
		return $conn->lastInsertId();
	}
}