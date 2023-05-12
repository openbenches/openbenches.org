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

	public function updateBench( $benchID, $inscription, $latitude, $longitude, $userID, $published=true ) {
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
		            `published`  = ?,
		            `userID`     = ?
		         WHERE `benches`.`benchID` = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(1, $latitude);
		$stmt->bindValue(2, $longitude);
		$stmt->bindValue(3, $address);
		$stmt->bindValue(4, $inscription);
		$stmt->bindValue(5, $published);
		$stmt->bindValue(6, $userID);
		$stmt->bindValue(7, $benchID);
		
		//	Run the query
		$results = $stmt->executeQuery();
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

	public function saveTags( $benchID, $tags ) {
		// this function needs to work for when a bench is added or edited
		// when a bench is edited that may mean that the number of tags
		// increases, or decreases to as few as zero
		// easiest way to deal with this is to remove all entries for the
		// bench then add whatever tags were passed

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

	public function mastodonPost( $benchID, $inscription="", $license="CC BY-SA 4.0", $user_provider=null, $user_name=null ) {
		$mastodon = new \MastodonAPI($_ENV["MASTODON_ACCESS_TOKEN"], $_ENV["MASTODON_INSTANCE"]);

		$status_length = 500;

		if ( "anon" != $user_provider ) {
			$from = "℅ $user_name on $user_provider";
		}	else {
			$from = "";
		}
	
		$domain = $_SERVER['SERVER_NAME'];
		$post_url = "https://{$domain}/bench/{$benchID}";
	
		$status_end = "\n" . $post_url . "\n\n" . $from . "\n" . $license;
	
		$status_length = $status_length - mb_strlen($status_end);
	
		$inscription = mb_substr($inscription, 0, $status_length + 10);
		$status = $inscription . $status_end;

		$visibility = "public";
		$status_data = [
			"status"      => $status,
			"visibility"  => "public"
		];

		$result = $mastodon->postStatus($status_data);
	}

	public function twitterPost( $benchID, $inscription=null, $latitude=null, $longitude=null, $license="CC BY-SA 4.0", $user_provider=null, $user_name=null ) {
		//	Send Tweet
		\Codebird\Codebird::setConsumerKey($_ENV["OAUTH_CONSUMER_KEY"], $_ENV["OAUTH_CONSUMER_SECRET"]);
		$cb = \Codebird\Codebird::getInstance();
		$cb->setToken($_ENV["OAUTH_ACCESS_TOKEN"], $_ENV["OAUTH_TOKEN_SECRET"]);

		//	Tweet length is now 280
		// $tweet_length = 280;

		//	Tweet will end with "℅ @twittername"
		if ("twitter" == $user_provider) {
			$from = "℅ @{$user_name}   "; //	Paranoia. A few spaces of padding which will be trimmed before tweeting.
		} else {
			//	Might use this for Github / Facebook names in future
			$from = "   ";
		}

		$domain = $_SERVER['SERVER_NAME'];
		$tweet_url = "https://{$domain}/bench/{$benchID}";

		// To go after the inscription
		$tweet_text = "{$tweet_url}\n{$license}\n{$from}";

		$params = [
			'status'    => $tweet_text,
			'lat'       => $latitude,
			'long'      => $longitude,
			'weighted_character_count' => 'true'
		];

		try {
			$reply = $cb->statuses_update($params);
		} catch (\Exception $e) {
			error_log("Twitter: $e");
			error_log(print_r($reply, TRUE));
			error_log("Status: {$tweet_text}");
		}

		//	Error code back from Twitter
		if ($reply->httpstatus != 200 ) {
			error_log(print_r($reply, TRUE));
			error_log("Status: {$tweet_text}");
		}
	}

	public function emailAdmin( $benchID, $inscription, $provider, $name ) {
		$benchFunctions = new BenchFunctions();
		$duplicate_count = $benchFunctions->getDuplicateCount( $inscription );
		$soundex         = $benchFunctions->getSoundex( $inscription );
		$domain = $_SERVER["SERVER_NAME"];

		mail($_ENV["NOTIFICATION_EMAIL"],
			"Bench {$benchID}",
			"Possible duplicates {$duplicate_count}\n" .
			"{$inscription}\n" .
			"https://{$domain}/bench/{$benchID}\n\n" .
			"Duplicates: https://{$domain}/soundex/?soundex={$soundex}\n" .
			"From {$provider} / {$name}"
		);
	}
}