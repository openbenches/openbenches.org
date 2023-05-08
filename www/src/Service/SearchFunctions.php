<?php
// src/Service/SearchFunctions.php
namespace App\Service;

use App\Service\MediaFunctions;

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
			->orderBy("benches.benchID", 'DESC')
			->groupBy("benches.benchID")
			->setParameter(0, "%{$soundex}%");
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
}