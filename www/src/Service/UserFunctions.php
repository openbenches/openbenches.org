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

	public function getUserDetailsFromSocial( $user_service, $user_name ) {
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
			case "mastodon" :
				$user_avatar = "/images/svg/mastodon.svg";
				break;
			case "openstreetmap-openid" :
				$user_avatar = "/images/svg/openstreetmap-openid.svg";
				break;
			default :
				$user_avatar = null;
		}
		return $user_avatar;
	}

	public function getUserURL( $provider, $providerID, $name ): string {

		switch ( $provider ) {
			case "github" :
				return "https://edent.github.io/github_id/#{$providerID}";
			case "facebook" :
				return "https://facebook.com/{$providerID}";
			case "flickr" :
				return "https://www.flickr.com";
			case "wikipedia" :
				return "https://www.wikipedia.org/";
			case "readtheplaque" :
				return "https://www.readtheplaque.com/";
			case "geograph" :
				return "https://www.geograph.org.uk/";
			case "linkedin" :
				return "https://www.linkedin.com/search/results/all/?keywords={$name}";
			case "wordpress" :
				return "https://wordpress.com/forums/users/{$name}";
			case "twitter" :
				if ( !is_numeric($providerID) ) {
					return "https://twitter.com/{$providerID}";
				} else {
					return "https://twitter.com/intent/user?user_id={$providerID}";
				}
			case "mastodon" :
				return "{$providerID}";
			case "openstreetmap" :
				return "https://www.openstreetmap.org/user/{$name}";
			default :
				return "";
		}
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

	public function isUserBanned( $provider, $providerID ) {
		//	NOTE. Users can also be blocked in Auth0.
		//	That prevents them being able to log in.
		//	This check is necessary as anonymous users don't go through Auth0.
		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
		$conn = DriverManager::getConnection($connectionParams);

		//	Anon user check.
		if ( $provider == null && $providerID == null ) {
			$provider   = "anon";
			$providerID = $_SERVER["REMOTE_ADDR"]; //	Their IP Address
		}

		//	Are they on the naughty list?
		$queryBuilder = $conn->createQueryBuilder();
		$queryBuilder
			->select("reason")
			->from("banned_users")
			->where("provider = :provider AND providerID = :providerID")
			->setParameter("provider",   $provider)
			->setParameter("providerID", $providerID);
		$results = $queryBuilder->executeQuery();
		$results_array = $results->fetchAssociative();

		if ( false == $results_array ) {
			//	User isn't on banned list.
			return false;
		} else {
			//	Why were they banned?
			return $results_array["reason"];
		}
	}

	public function getMastodonAppDetails( $domainName ) {
		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
		$conn = DriverManager::getConnection($connectionParams);

		//	Get user's name and details
		$sql = "SELECT `domain_name`, `client_id`, `client_secret` 
		        FROM `mastodon_apps`
		        WHERE domain_name = ?
		        LIMIT 0 , 1";
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(1, $domainName);
		$results = $stmt->executeQuery();
		$results_array = $results->fetchAssociative();
		return $results_array;
	}

	public function addMastodonAppDetails( $domainName, $client_id, $client_secret ) {
		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
		$conn = DriverManager::getConnection($connectionParams);

		//	Add the new app
		$sql = "INSERT INTO `mastodon_apps`
		(`domain_name`, `client_id`, `client_secret`)
		VALUES
		(?,             ?,           ?)";
		
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(1, $domainName);
		$stmt->bindValue(2, $client_id);
		$stmt->bindValue(3, $client_secret);
		
		//	Run the query
		$results = $stmt->executeQuery();
		
		//	Get the ID of the row which was just inserted
		return true;
	}
}