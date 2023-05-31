<?php
// src/Service/UserFunctions.php
namespace App\Service;

use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

use App\Service\MediaFunctions;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;

class UserFunctions
{
	public function getUserDetails( $userID ): array {
		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
		$conn = DriverManager::getConnection($connectionParams);

		//	Get user's name and details
		$sql = "SELECT `provider`, `providerID`, `name` 
		        FROM `users`
		        WHERE userID = ?
		        LIMIT 0 , 1";
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(1, $userID);
		$results = $stmt->executeQuery();
		$results_array = $results->fetchAssociative();
		return $results_array;
	}

	public function getUserDetailsFromSocial( $user_service, $user_name ): array {
		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
		$conn = DriverManager::getConnection($connectionParams);

		//	Get user's name and details
		$sql = "SELECT `providerID`, `userID` 
		        FROM `users`
		        WHERE `provider` = ? AND `name` = ?
		        LIMIT 0 , 1";
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(1, $user_service);
		$stmt->bindValue(2, $user_name);
		$results = $stmt->executeQuery();
		$results_array = $results->fetchAssociative();
		return $results_array;
	}

	public function getUserBenchCount( $userID ): int {

		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
		$conn = DriverManager::getConnection($connectionParams);

		$sql = "SELECT COUNT(*) FROM `benches` WHERE `userID` = ? AND `published` = true";
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(1, $userID);
		$results = $stmt->executeQuery();
		$results_array = $results->fetchAssociative();

		if (false != $results_array) {
			return $results_array["COUNT(*)"];
		} else {
			return 0;
		}
	}

	public function getUserAvatar( $provider, $providerID, $name ) {
		//	https://cloudinary.com/documentation/social_media_profile_pictures

		switch ( $provider ) {
			case "twitter" :
				$user_avatar = "https://res.cloudinary.com/{$_ENV['CLOUDINARY_KEY']}/image/twitter/{$providerID}.jpg";
				break;
			case "facebook" :
				$user_avatar = "https://res.cloudinary.com/{$_ENV['CLOUDINARY_KEY']}/image/facebook/{$providerID}.jpg";
				break;
			case "github" :
				$user_avatar = "https://avatars0.githubusercontent.com/u/{$providerID}?v=4&amp;s=48";
				break;
			case "flickr" :
				$user_avatar = "/images/svg/flickr.svg";
				break;
			case "wikipedia" :
				$user_avatar = "/images/svg/wikipedia.svg";
				break;
			default :
				$user_avatar = null;
		}
		return $user_avatar;
	}

	public function getUserURL( $provider, $providerID, $name ): string {

		switch ( $provider ) {
			case "github" :
				$userURL = "https://edent.github.io/github_id/#{$providerID}";
				break;
			case "facebook" :
				$userURL = "https://facebook.com/{$providerID}";
				break;
			case "flickr" :
				$userURL = "https://www.flickr.com";
				break;
			case "wikipedia" :
				$userURL = "https://www.wikipedia.org/";
				break;
			case "readtheplaque" :
				$userURL = "https://www.readtheplaque.com/";
				break;
			case "geograph" :
				$userURL = "https://www.geograph.org.uk/";
				break;
			case "linkedin" :
				$userURL = "https://www.linkedin.com/search/results/all/?keywords={$name}";
				break;
			case "twitter" :
				if ( !is_numeric($providerID) ) {
					$userURL = "https://twitter.com/{$providerID}";
					break;
				} else {
					$userURL = "https://twitter.com/intent/user?user_id={$providerID}";
					break;
				}
		}
		return $userURL;
	}

	public function getUserBenches( $user_id, $start=0, $count=20 ): array {
		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
		$conn = DriverManager::getConnection($connectionParams);
		$queryBuilder = $conn->createQueryBuilder();

		$queryBuilder
		->select("MAX(benches.benchID)", "benches.benchID", "benches.inscription", "benches.address", "benches.latitude", "benches.longitude", "benches.added", "MIN(media.sha1)")
		->from("benches")
		->innerJoin('benches', 'media', 'media', 'benches.benchID = media.benchID')
		->where("benches.userID = ? AND benches.published = true AND benches.present = true")
		->orderBy("benches.benchID", 'DESC')
		->groupBy("benches.benchID")
		->setFirstResult( $start )
		->setMaxResults( $count )
		->setParameter(0, $user_id);

		$results = $queryBuilder->executeQuery();

		$mediaFunctions = new MediaFunctions();

		//	Loop through the results to create an array of benches
		$benches_array = array();
		while ( ( $row = $results->fetchAssociative() ) !== false) {
			//	Add the details to the array
			$benches_array[$row["benchID"]] = array(
				"benchID"     => $row["benchID"],
				"inscription" => $row["inscription"],
				"address"     => $row["address"],
				"latitude"    => $row["latitude"],
				"longitude"   => $row["longitude"],
				"added"       => $row["added"],
				"image"       => $mediaFunctions->getProxyImageURL($row["MIN(media.sha1)"]),
			);
		}
		return $benches_array;
	}

	public function getLeaderboardBenches(): array {

		$cache = new FilesystemAdapter($_ENV["CACHE"] .  "cache_leaderboard" );
		$cache_name = "leaderboard_benches";
		$value = $cache->get( $cache_name, function (ItemInterface $item) {
			//	Cache length in seconds
			$item->expiresAfter(60); //	1 minute
			$userFunctions = new UserFunctions();

			$dsnParser = new DsnParser();
			$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
			$conn = DriverManager::getConnection($connectionParams);

			$queryBuilder = $conn->createQueryBuilder();

			$queryBuilder
				->select("users.userID, users.name, users.provider, users.providerID, COUNT(*) AS USERCOUNT")
				->from("benches")
				->innerJoin('benches', 'users', 'users', 'benches.userID = users.userID')
				->where("benches.published = true AND benches.present = true AND users.provider != 'anon'")
				->orderBy("USERCOUNT", 'DESC')
				->groupBy("users.userID");
			$results = $queryBuilder->executeQuery();

			$leaderboard_array = array();
			while ( ( $row = $results->fetchAssociative() ) !== false) {
				//	Add the details to the array
				$leaderboard_array[$row["userID"]] = array(
					"userID"     => $row["userID"],
					"username"   => $row["name"],
					"provider"   => $row["provider"],
					"avatar"     => $userFunctions->getUserAvatar( $row["provider"], $row["providerID"], "" ),
					"count"      => $row["USERCOUNT"],
				);
			}
			return $leaderboard_array;
		});
		return $value;
	}

	public function getLeaderboardMedia(): array {
		$cache = new FilesystemAdapter($_ENV["CACHE"] . "cache_leaderboard" );
		$cache_name = "leaderboard_media";
		$value = $cache->get( $cache_name, function (ItemInterface $item) {
			//	Cache length in seconds
			$item->expiresAfter(60); //	1 minute
			$userFunctions = new UserFunctions();

			$dsnParser = new DsnParser();
			$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
			$conn = DriverManager::getConnection($connectionParams);
	
			$queryBuilder = $conn->createQueryBuilder();
	
			//	TODO: This doesn't detect if a media is associated with a bench which has been deleted
			$queryBuilder
				->select("users.userID, users.name, users.provider, users.providerID, COUNT(*) AS USERCOUNT")
				->from("media")
				->innerJoin('media', 'users', 'users', 'media.userID = users.userID')
				->where("users.provider != 'anon'")
				->orderBy("USERCOUNT", 'DESC')
				->groupBy("users.userID");
			$results = $queryBuilder->executeQuery();
	
			$leaderboard_array = array();
			while ( ( $row = $results->fetchAssociative() ) !== false) {
				//	Add the details to the array
				$leaderboard_array[$row["userID"]] = array(
					"userID"     => $row["userID"],
					"username"   => $row["name"],
					"provider"   => $row["provider"],
					"avatar"     => $userFunctions->getUserAvatar( $row["provider"], $row["providerID"], "" ),
					"count"      => $row["USERCOUNT"],
				);
			}
			return $leaderboard_array;	
		});

		return $value;
	}

	public function addUser( $username=null, $provider=null, $providerID=null ): int {
		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
		$conn = DriverManager::getConnection($connectionParams);

		if( isset( $username ) ) {
			//	Does the user exist?
			$queryBuilder = $conn->createQueryBuilder();
			$queryBuilder
				->select("userID")
				->from("users")
				->where("provider LIKE :provider AND providerID LIKE :providerID")
				->setParameter("provider",   $provider)
				->setParameter("providerID", $providerID);
			$results = $queryBuilder->executeQuery();
			$results_array = $results->fetchAssociative();

			if (false != $results_array) {
				//	User already exists
				return $results_array["userID"];
			}
		} else {
			//	Anonymous user
			$username   = date(\DateTime::RFC3339);
			$provider   = "anon";
			$providerID = $_SERVER["REMOTE_ADDR"]; //	Their IP Address
			$exists     = false;
		}

		//	Add the new user
		$sql = "INSERT INTO `users`
		(`userID`, `provider`, `providerID`, `name`)
		VALUES
		(NULL,      ?,          ?,            ?)";
		
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(1, $provider);
		$stmt->bindValue(2, $providerID);
		$stmt->bindValue(3, $username);
		
		//	Run the query
		$results = $stmt->executeQuery();
		
		//	Get the ID of the row which was just inserted
		return $conn->lastInsertId();
	}
}