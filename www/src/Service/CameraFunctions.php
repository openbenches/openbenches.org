<?php
// src/Service/CameraFunctions.php
namespace App\Service;

use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;
use Doctrine\DBAL\Connection;

class CameraFunctions
{
	public function getMakes() {
		
		$cache = new FilesystemAdapter($_ENV["CACHE"] . "cache_makes");
		$cacheName = "makes";

		$cachedResult = $cache->get($cacheName, function (ItemInterface $item) {
			$item->expiresAfter(3000);
			$dsnParser = new DsnParser();
			$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
			$conn = DriverManager::getConnection($connectionParams);

			$queryBuilder = $conn->createQueryBuilder();
			$queryBuilder
				->select("make", "count(make) AS CountOf")
				->from("media")
				->where("make IS NOT NULL")
				->groupBy("make")
				;
			$results = $queryBuilder->executeQuery();

			$makes = $results->fetchAllAssociative();

			return $makes;
		});
		return $cachedResult;
	}

	public function getModels($make) {
		
		$cache = new FilesystemAdapter($_ENV["CACHE"] . "cache_models");
		$cacheName = "model";

		$cachedResult = $cache->get($cacheName, function (ItemInterface $item) use( $make ) {
			$item->expiresAfter(300);
			$dsnParser = new DsnParser();
			$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
			$conn = DriverManager::getConnection($connectionParams);

			$queryBuilder = $conn->createQueryBuilder();
			$queryBuilder
				->select("model", "count(model) AS CountOf")
				->from("media")
				->where("make LIKE ?")
				->groupBy("model")
				->setParameter(0, "$make");
				
			$results = $queryBuilder->executeQuery();

			$models = $results->fetchAllAssociative();
// var_dump($models);die();
			return $models;
		});
		return $cachedResult;
	}
}