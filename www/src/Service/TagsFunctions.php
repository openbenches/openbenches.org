<?php
// src/Service/TagsFunctions.php
namespace App\Service;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;
use Doctrine\DBAL\Connection;

class TagsFunctions
{
	public function getTagsNames() {
		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
		$conn = DriverManager::getConnection($connectionParams);

		$queryBuilder = $conn->createQueryBuilder();
		$queryBuilder
			->select("tagText")
			->from("tags");
		$results = $queryBuilder->executeQuery();
		$tags_array = $results->fetchFirstColumn();

		return $tags_array;
	}

	public function getTags() {
		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
		$conn = DriverManager::getConnection($connectionParams);

		$queryBuilder = $conn->createQueryBuilder();
		$queryBuilder
			->select("*")
			->from("tags");
		$results = $queryBuilder->executeQuery();

		$tags_array = array();
		while (($row = $results->fetchAssociative()) !== false) {
			$tags_array[ $row["tagID"] ] = $row["tagText"];
		}

		return $tags_array;
	}

	public function getTagsFromBench($benchID): array {
		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
		$conn = DriverManager::getConnection($connectionParams);

		$sql = "SELECT tags.tagText
			 FROM `tag_map`
			 INNER JOIN tags ON (tags.`tagID` = tag_map.`tagID`)
			 WHERE `benchID` = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(1, $benchID);
		$results = $stmt->executeQuery();
		$tags_array = $results->fetchFirstColumn();	
		return $tags_array;
	}
}