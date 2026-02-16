<?php
// src/Service/SearchFunctions.php
namespace App\Service;

use App\Service\MediaFunctions;
use App\Service\TagsFunctions;

use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;
use Doctrine\DBAL\Connection;

class SearchFunctions
{
	public function getSearchBenches( $query, $start=0, $count=20 ) {
		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
		$conn = DriverManager::getConnection($connectionParams);

		$queryBuilder = $conn->createQueryBuilder();
		$queryBuilder
			->select("MAX(benches.benchID)", "benches.benchID", "benches.inscription", "benches.address", "benches.latitude", "benches.longitude", "benches.added", "MIN(media.sha1)")
			->from("benches")
			->innerJoin('benches', 'media', 'media', 'benches.benchID = media.benchID')
			->where("benches.inscription LIKE ? AND benches.published = 1")
			->orderBy("benches.benchID", 'DESC')
			->groupBy("benches.benchID")
			->setFirstResult( $start )
			->setMaxResults( $count )
			->setParameter(0, "%{$query}%");
		$results = $queryBuilder->executeQuery();

		$mediaFunctions = new MediaFunctions();

		//	Loop through the results to create an array of media
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

	public function getSearchBenchCount( $query ): int {

		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
		$conn = DriverManager::getConnection($connectionParams);

		$queryBuilder = $conn->createQueryBuilder();
		$queryBuilder
			->select("COUNT(*)")
			->from("benches")
			->where("benches.inscription LIKE ? AND benches.published = 1")
			->setParameter(0, "%{$query}%");
		$results = $queryBuilder->executeQuery();

		$results_array = $results->fetchAssociative();

		if (false != $results_array) {
			return $results_array["COUNT(*)"];
		} else {
			return 0;
		}
	}

	public function getSoundexBenches( $soundex ) : array {
		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
		$conn = DriverManager::getConnection($connectionParams);

		$queryBuilder = $conn->createQueryBuilder();
		$queryBuilder
			->select("MAX(benches.benchID)", "benches.benchID", "benches.inscription", "benches.address", "benches.latitude", "benches.longitude", "benches.added", "MIN(media.sha1)")
			->from("benches")
			->innerJoin('benches', 'media', 'media', 'benches.benchID = media.benchID')
			->where("SOUNDEX(`inscription`) = ? AND `published` = true")
			->orderBy("benches.benchID", 'ASC')
			->groupBy("benches.benchID")
			->setParameter(0, "$soundex");
		$results = $queryBuilder->executeQuery();

		$mediaFunctions = new MediaFunctions();

		//	Loop through the results to create an array of media
		$benches_array = array();
		while ( ( $row = $results->fetchAssociative() ) !== false) {
			//	Add the details to the array
			$benches_array[] = array(
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

	public function getTagBenches( $tag, $start=0, $count=20 ) {
		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
		$conn = DriverManager::getConnection($connectionParams);
		// $offset = $page * $results;

		$tagFunctions = new TagsFunctions();
		$tagIDs = $tagFunctions->getTags();
		$tagID = array_search($tag, $tagIDs);

		if (null == $tagID) {
			return array();
		}

		$queryBuilder = $conn->createQueryBuilder();
		$queryBuilder
			->select("MAX(benches.benchID)", "benches.benchID", "benches.inscription", "benches.address", "benches.latitude", "benches.longitude", "benches.added", "MIN(media.sha1)", "MAX(tag_map.tagID)")
			->from("benches")
			->innerJoin('benches', 'media',   'media',   'benches.benchID = media.benchID')
			->innerJoin('benches', 'tag_map', 'tag_map', 'benches.benchID = tag_map.benchID')
			->where("tag_map.tagID = ? AND benches.published = 1")
			->orderBy("benches.benchID", 'DESC')
			->groupBy("benches.benchID")
			->setFirstResult( $start )
			->setMaxResults( $count )
			->setParameter( 0, $tagID );
		$results = $queryBuilder->executeQuery();

		$mediaFunctions = new MediaFunctions();

		//	Loop through the results to create an array of media
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

	public function getLatestBenches() {
		$cache = new FilesystemAdapter($_ENV["CACHE"] . "cache_recent");
		$cacheName = "latest";

		$cachedResult = $cache->get($cacheName, function (ItemInterface $item) {
			$item->expiresAfter(300);	//	5 minutes
			$dsnParser = new DsnParser();
			$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
			$conn = DriverManager::getConnection($connectionParams);

			$queryBuilder = $conn->createQueryBuilder();
			$queryBuilder
				->select("MAX(benches.benchID)", "benches.benchID", "benches.inscription", "benches.address", "benches.latitude", "benches.longitude", "benches.added", "benches.userID", "users.name", "MIN(media.sha1)")
				->from("benches")
				->where("benches.published = 1")
				->innerJoin('benches', 'media', 'media', 'benches.benchID = media.benchID')
				->innerJoin('benches', 'users', 'users', 'benches.userID  = users.userID')
				->orderBy("benches.benchID", 'DESC')
				->groupBy("benches.benchID")
				->setMaxResults( 100 );
			$results = $queryBuilder->executeQuery();

			$mediaFunctions = new MediaFunctions();

			//	Loop through the results to create an array of media
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
					"name"        => $row["name"],
					"image"       => $mediaFunctions->getProxyImageURL($row["MIN(media.sha1)"]),
				);
			}
			return $benches_array;
		});

		return $cachedResult;
	}

	public function getSitemapBenches() {
		$cache = new FilesystemAdapter($_ENV["CACHE"] . "cache_sitemap");
		$cacheName = "sitemapxml";

		$cachedResult = $cache->get($cacheName, function (ItemInterface $item) {
			$item->expiresAfter(3600);
			$dsnParser = new DsnParser();
			$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
			$conn = DriverManager::getConnection($connectionParams);

			$queryBuilder = $conn->createQueryBuilder();
			$queryBuilder
				->select("benches.benchID")
				->from("benches")
				->where("benches.published = 1")
				->orderBy("benches.benchID", 'DESC')
				->groupBy("benches.benchID")
				->setFirstResult( 0 )
				->setMaxResults( 1000000 );
			$results = $queryBuilder->executeQuery();

			//	Loop through the results to create an array of media
			$benches_array = array();
			while ( ( $row = $results->fetchAssociative() ) !== false) {
				//	Add the details to the array
				$benches_array[$row["benchID"]] = array(
					"benchID"     => $row["benchID"],
				);
			}
			return $benches_array;
		});

		return $cachedResult;
	}

	public function getMergedBench(int $benchID) {
		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
		$conn = DriverManager::getConnection($connectionParams);

		//	Get ID of merge
		$sql = "SELECT mergedID FROM merged_benches
		WHERE benchID = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(1, $benchID);
		$results = $stmt->executeQuery();
		$results_array = $results->fetchAssociative();
		$mergedBenchID = $results_array["mergedID"] ?? null;
		return $mergedBenchID;
	}

	public function getNearestBenches( $latitude, $longitude, $distance=0.5, $limit=20, $truncated = false, $media = false ) {
		$cache = new FilesystemAdapter($_ENV["CACHE"] . "cache_nearest");
		$cacheName = "nearest_{$latitude}_{$longitude}_{$distance}_{$limit}_{$truncated}_{$media}";

		$cachedResult = $cache->get(
			$cacheName, 
			function (ItemInterface $item) use( 
				$latitude, 
				$longitude, 
				$distance, 
				$limit ) {
			$item->expiresAfter(300);
			$dsnParser = new DsnParser();
			$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
			$conn = DriverManager::getConnection($connectionParams);
	
			//	Haversine formula
			$sql = "SELECT
				(
					6371 * ACOS(COS(RADIANS(?)) *
					COS(RADIANS(latitude)) *
					COS(RADIANS(longitude) -
					RADIANS(?)) +
					SIN(RADIANS(?)) *
					SIN(RADIANS(latitude)))
				)
				AS distance, benchID, latitude, longitude, inscription, published, address, added
				FROM benches
				WHERE published = true AND present = true
				HAVING distance < ?
				ORDER BY distance
				LIMIT 0 , ?";
	
			$stmt = $conn->prepare($sql);
			$stmt->bindValue(1, $latitude);
			$stmt->bindValue(2, $longitude);
			$stmt->bindValue(3, $latitude);
			$stmt->bindValue(4, $distance);
			$stmt->bindValue(5, $limit);

			$results = $stmt->executeQuery();

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
					// "name"        => $row["name"],
					// "image"       => $mediaFunctions->getProxyImageURL($row["MIN(media.sha1)"]),
				);
			}
			return $benches_array;
		});
		return $cachedResult;
	}
}