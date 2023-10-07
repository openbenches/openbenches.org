<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

use App\Service\MediaFunctions;
use App\Service\UserFunctions;
use App\Service\SearchFunctions;
use App\Service\TagsFunctions;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;

class ApiController extends AbstractController
{
	#[Route("/api/bench/{bench_id}", name: "api_bench")]
	public function api_bench(int $bench_id): JsonResponse {
		$request = Request::createFromGlobals();
		if ( $request->query->get("truncated") == "false") {
			$get_truncated = false;
		} else {
			$get_truncated = true;
		}
		
		if ( $request->query->get("media") == "true" ) {
			$get_media = true;
		} else {
			$get_media = false;
		}
		
		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse( $_ENV["DATABASE_URL"] );
		$conn = DriverManager::getConnection($connectionParams);

		//	Inscription
		$sql = "SELECT `benchID`, `latitude`, `longitude`, `inscription`, `added`
		        FROM   `benches`
				  WHERE  `benchID` =  ?
				  AND    `published` = true";
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(1, $bench_id);
		$results = $stmt->executeQuery();
		$results_array = $results->fetchAssociative();

		if (false != $results_array) {
			$benchID     = $results_array["benchID"];
			$inscription = $results_array["inscription"];
			$latitude    = $results_array["latitude"];
			$longitude   = $results_array["longitude"];
			$added       = $results_array["added"];

			# Build GeoJSON feature collection array
			$geojson = array(
				"type"		=> "FeatureCollection",
				"features"  => array()
			);

			# some inscriptions got stored with leading/trailing whitespace
			$inscription=trim($inscription);

			// If displaying on map need to truncate inscriptions longer than
			// 128 chars and add in <br> elements
			// N.B. this logic is also in get_nearest_benches()
			if ( true == $get_truncated ) {
				$inscriptionTruncate = mb_substr( $inscription, 0, 128 );
				if ( $inscriptionTruncate !== $inscription ) {
					$inscription = $inscriptionTruncate . "…";
				}
				$inscription=nl2br( $inscription );
			}

			//	Horrible hack to force numeric inscriptions to be strings
			if ( is_numeric( $inscription ) ) {
				$inscription .= " ";
			}

			//	Get Media
			$media_array = array();
			if ( "false" != $get_media ) {
				$sql = "SELECT media.benchID, media.sha1, media.importURL, media.licence, media.media_type, media.userID, media.width, media.height, media.mediaID, users.name, users.provider
						FROM `media`
						INNER JOIN `users` on media.userID = users.userID
						WHERE media.benchID =  ?";
				$stmt = $conn->prepare($sql);
				$stmt->bindValue(1, $bench_id);
				$results = $stmt->executeQuery();

				//	Loop through the results to create an array of media
				while ( ( $row = $results->fetchAssociative() ) !== false) {
					$media_data = array();

					$media_data["URL"] = "/image/{$row["sha1"]}";

					if(null != $row["importURL"]) {
						$media_data["importURL"] = $row["importURL"];
					}

					$media_data["mediaID"]     = $row["mediaID"];
					$media_data["licence"]     = $row["licence"];
					$media_data["media_type"]  = $row["media_type"];
					$media_data["sha1"]        = $row["sha1"];
					$media_data["user"]        = $row["userID"];
					$media_data["userprovider"]= $row["provider"];
					if ( "anon" == $row["provider"] ) {
						$media_data["username"]    = "anon";
					} else {
						$media_data["username"]    = $row["name"];
					}
					$media_data["width"]       = $row["width"];
					$media_data["height"]      = $row["height"];

					//	Add all the media details to the response
					if (sizeof($media_data) > 0){
						$media_array[$benchID][] = $media_data;
					}
				}
			}
			
			if (isset($media_array[$benchID])){
				$mediaFeature = $media_array[$benchID];
			} else {
				$mediaFeature = null;
			}

			//	Create GeoJSON
			$feature = array(
				"id"       => $benchID,
				"type"     => "Feature",
				"geometry" => array(
					"type" => "Point",
					# Pass Longitude and Latitude Columns here
					"coordinates" => array($longitude, $latitude)
				),
				# Pass other attribute columns here
				"properties" => array(
					"created_at"   => date_format( date_create($added ), "c" ),
					"popupContent" => $inscription,
					"media"        => $mediaFeature
				),
			);
			# Add feature arrays to feature collection array
			array_push($geojson["features"], $feature);
		}

		//	Render the page
		$response = new JsonResponse($geojson);	
		return $response;
	}

	#[Route("/api/benches/", name: "api_all_benches")]
	public function api_all_benches(): JsonResponse {
		$request = Request::createFromGlobals();

		$cache = new FilesystemAdapter($_ENV["CACHE"] . "cache_all_benches" );
		$cache_name = "all_benches";

		if ( $request->query->get( "truncated" ) == "false" ) {
			$get_truncated = false;
			$cache_name .= "full";
		} else {
			$get_truncated = true;
			$cache_name .= "truncated";
		}
		
		if ( $request->query->get( "media" ) == "true" ) {
			$get_media = true;
			$cache_name .= "media";
		} else {
			$get_media = false;
			$cache_name .= "nomedia";
		}

		$value = $cache->get( $cache_name, function (ItemInterface $item) {
			//	Cache length in seconds
			$item->expiresAfter(600); //	10 minutes

			$request = Request::createFromGlobals();
			if ( $request->query->get("truncated") == "false") {
				$get_truncated = false;
			} else {
				$get_truncated = true;
			}
			
			if ( $request->query->get("media") == "true" ) {
				$get_media = true;
			} else {
				$get_media = false;
			}

			$mediaFunctions = new MediaFunctions();
			$dsnParser = new DsnParser();
			$connectionParams = $dsnParser->parse( $_ENV["DATABASE_URL"] );
			$conn = DriverManager::getConnection($connectionParams);
			$queryBuilder = $conn->createQueryBuilder();

			$queryBuilder
				->select("benches.benchID", "benches.inscription", "benches.latitude", "benches.longitude", "benches.added", "media.sha1", "media.licence", "media.media_type", "media.importURL", "media.width", "media.height")
				->from("benches")
				->innerJoin("benches", "media", "media", "benches.benchID = media.benchID")
				->where("benches.published = true AND benches.present = true");
			$results = $queryBuilder->executeQuery();

			//	Build GeoJSON feature collection array
			$geojson = array(
				"type"		=> "FeatureCollection",
				"features"  => array()
			);

			//	Loop through the results to create an array of benches
			$benches_array = array();
			while (($row = $results->fetchAssociative()) !== false) {

				//	If the bench has already been added, add the media to it
				if( isset( $benches_array[$row["benchID"]] ) && $get_media != false ) {
					$media_data = array();

					$media_data["URL"] = "/image/{$row["sha1"]}";

					if( null != $row["importURL"] ) {
						$media_data["importURL"] = $row["importURL"];
					}

					$media_data["licence"]     = $row["licence"];
					$media_data["media_type"]  = $row["media_type"];
					$media_data["sha1"]        = $row["sha1"];
					$media_data["width"]       = $row["width"];
					$media_data["height"]      = $row["height"];

					//	Add all the media details to the response
					array_push( $benches_array[$row["benchID"]]["properties"]["media"], $media_data);				

				} else {
					//	Some inscriptions got stored with leading/trailing whitespace
					$inscription=trim( $row["inscription"] );

					// If displaying on map need to truncate inscriptions longer than
					// 128 chars and add in <br> elements
					if ( true == $get_truncated ) {
						$inscriptionTruncate = mb_substr( $inscription, 0, 128 );
						if ( $inscriptionTruncate !== $inscription ) {
							$inscription = $inscriptionTruncate . "…";
						}
						$inscription=nl2br( $inscription );
					}

					//	Horrible hack to force numeric inscriptions to be strings
					if ( is_numeric( $inscription ) ) {
						$inscription .= " ";
					}

					$media_data = array();
					if( $get_media != false ) {

						$media_data["URL"] = "/image/{$row["sha1"]}";
		
						if( null != $row["importURL"] ) {
							$media_data["importURL"] = $row["importURL"];
						}
		
						$media_data["licence"]     = $row["licence"];
						$media_data["media_type"]  = $row["media_type"];
						$media_data["sha1"]        = $row["sha1"];
						$media_data["width"]       = $row["width"];
						$media_data["height"]      = $row["height"];
					}

					//	Create GeoJSON
					$feature = array(
						"id"       => $row["benchID"],
						"type"     => "Feature",
						"geometry" => array(
							"type" => "Point",
							// Pass Longitude and Latitude Columns here
							"coordinates" => array($row["longitude"], $row["latitude"])
						),
						// Pass other attribute columns here
						"properties" => array(
							"created_at"   => date_format( date_create($row["added"] ), "c" ),
							"popupContent" => $inscription,
							"media" => array(),
						),
					);
					//	Add all the media details to the response
					array_push( $feature["properties"]["media"], $media_data);

					// Add feature to collection array
					$benches_array[$row["benchID"]] = $feature;
				}
			}

			foreach( $benches_array as $bench ){
				array_push($geojson["features"], $bench);
			}
			return $geojson;
		});

		$response = new JsonResponse($value);	
		return $response;
	}

	#[Route("/api/user/{user_id}", name: "api_user")]
	public function api_user(int $user_id): JsonResponse {
		$mediaFunctions = new MediaFunctions();
		$userFunctions  = new UserFunctions();

		$request = Request::createFromGlobals();
		if ( $request->query->get("truncated") == "false") {
			$get_truncated = false;
		} else {
			$get_truncated = true;
		}
		
		if ( $request->query->get("media") == "true" ) {
			$get_media = true;
		} else {
			$get_media = false;
		}

		$benches_array = $userFunctions->getUserBenches( $user_id, 0, 20000 );

		# Build GeoJSON feature collection array
		$geojson = array(
			"type"		=> "FeatureCollection",
			"features"  => array()
		);

		foreach( $benches_array as $bench ) {
			$benchID     = $bench["benchID"];
			$inscription = $bench["inscription"];
			$latitude    = $bench["latitude"];
			$longitude   = $bench["longitude"];
			$added       = $bench["added"];
			$image       = $bench["image"];

			# some inscriptions got stored with leading/trailing whitespace
			$inscription=trim($inscription);

			// If displaying on map need to truncate inscriptions longer than
			// 128 chars and add in <br> elements
			if ( true == $get_truncated ) {
				$inscriptionTruncate = mb_substr( $inscription, 0, 128 );
				if ( $inscriptionTruncate !== $inscription ) {
					$inscription = $inscriptionTruncate . "…";
				}
				$inscription = nl2br( $inscription );
			}

			//	Horrible hack to force numeric inscriptions to be strings
			if ( is_numeric( $inscription ) ) {
				$inscription .= " ";
			}

			//	Create GeoJSON
			$feature = array(
				"id"       => $benchID,
				"type"     => "Feature",
				"geometry" => array(
					"type" => "Point",
					# Pass Longitude and Latitude Columns here
					"coordinates" => array($longitude, $latitude)
				),
				# Pass other attribute columns here
				"properties" => array(
					"created_at"   => date_format( date_create($added ), "c" ),
					"popupContent" => $inscription,
					"media"        => array(["url" => $image]),
				),
			);
			# Add feature arrays to feature collection array
			array_push($geojson["features"], $feature);
		}

		//	Render the page
		$response = new JsonResponse($geojson);	
		return $response;
	}

	#[Route("/api/search/", name: "api_search")]
	public function api_search(): JsonResponse {
		$mediaFunctions = new MediaFunctions();
		$searchFunctions  = new SearchFunctions();

		$request = Request::createFromGlobals();
		$query         = $request->query->get("search");
		$get_truncated = $request->query->get("truncated");
		$get_media     = $request->query->get("media");

		//	Get the benches associated with this query
		$benches_array = $searchFunctions->getSearchBenches( $query, 0, 20000 );
		
		# Build GeoJSON feature collection array
		$geojson = array(
			"type"		=> "FeatureCollection",
			"features"  => array()
		);

		foreach( $benches_array as $bench ) {
			$benchID     = $bench["benchID"];
			$inscription = $bench["inscription"];
			$latitude    = $bench["latitude"];
			$longitude   = $bench["longitude"];
			$added       = $bench["added"];
			$image       = $bench["image"];

			# some inscriptions got stored with leading/trailing whitespace
			$inscription=trim($inscription);

			// If displaying on map need to truncate inscriptions longer than
			// 128 chars and add in <br> elements
			if ( true == $get_truncated ) {
				$inscriptionTruncate = mb_substr( $inscription, 0, 128 );
				if ( $inscriptionTruncate !== $inscription ) {
					$inscription = $inscriptionTruncate . "…";
				}
				$inscription = nl2br( $inscription );
			}

			//	Horrible hack to force numeric inscriptions to be strings
			if ( is_numeric( $inscription ) ) {
				$inscription .= " ";
			}

			//	Create GeoJSON
			$feature = array(
				"id"       => $benchID,
				"type"     => "Feature",
				"geometry" => array(
					"type" => "Point",
					# Pass Longitude and Latitude Columns here
					"coordinates" => array($longitude, $latitude)
				),
				# Pass other attribute columns here
				"properties" => array(
					"created_at"   => date_format( date_create($added ), "c" ),
					"popupContent" => $inscription,
					"media"        => array(["url" => $image]),
				),
			);
			# Add feature arrays to feature collection array
			array_push($geojson["features"], $feature);
		}

		//	Render the page
		$response = new JsonResponse($geojson);	
		return $response;
	}

	#[Route("/api/nearest/", name: "api_nearest")]
	public function api_nearest(): JsonResponse {
		$mediaFunctions = new MediaFunctions();
		$searchFunctions  = new SearchFunctions();

		$request = Request::createFromGlobals();
		$latitude      = $request->query->get("latitude");
		$longitude     = $request->query->get("longitude");
		$distance      = $request->query->get("distance") ?? 0.5;
		$limit         = $request->query->get("limit") ?? 20;
		$get_truncated = $request->query->get("truncated") ?? false;
		$get_media     = $request->query->get("media") ?? true;

		//	Get the benches associated with this query
		$benches_array = $searchFunctions->getNearestBenches( $latitude, $longitude, $distance, $limit, $get_truncated, $get_media);
		
		# Build GeoJSON feature collection array
		$geojson = array(
			"type"		=> "FeatureCollection",
			"features"  => array()
		);

		foreach( $benches_array as $bench ) {
			$benchID     = $bench["benchID"];
			$inscription = $bench["inscription"];
			$latitude    = $bench["latitude"];
			$longitude   = $bench["longitude"];
			$added       = $bench["added"];
			// $image       = $bench["image"];

			# some inscriptions got stored with leading/trailing whitespace
			$inscription=trim($inscription);

			// If displaying on map need to truncate inscriptions longer than
			// 128 chars and add in <br> elements
			if ( true == $get_truncated ) {
				$inscriptionTruncate = mb_substr( $inscription, 0, 128 );
				if ( $inscriptionTruncate !== $inscription ) {
					$inscription = $inscriptionTruncate . "…";
				}
				$inscription = nl2br( $inscription );
			}

			//	Horrible hack to force numeric inscriptions to be strings
			if ( is_numeric( $inscription ) ) {
				$inscription .= " ";
			}

			//	Create GeoJSON
			$feature = array(
				"id"       => $benchID,
				"type"     => "Feature",
				"geometry" => array(
					"type" => "Point",
					# Pass Longitude and Latitude Columns here
					"coordinates" => array($longitude, $latitude)
				),
				# Pass other attribute columns here
				"properties" => array(
					"created_at"   => date_format( date_create($added ), "c" ),
					"popupContent" => $inscription,
					// "media"        => array(["url" => $image]),
				),
			);
			# Add feature arrays to feature collection array
			array_push($geojson["features"], $feature);
		}

		//	Render the page
		$response = new JsonResponse($geojson);	
		return $response;
	}

	#[Route("/api/nearest/gpx/", name: "api_gpx")]
	public function api_gpx() {
		$searchFunctions  = new SearchFunctions();

		$request = Request::createFromGlobals();
		$latitude      = $request->query->get("latitude");
		$longitude     = $request->query->get("longitude");
		$distance      = $request->query->get("distance") ?? 0.5;
		$limit         = $request->query->get("limit") ?? 20;

		//	Get the benches associated with this query
		$benches_array = $searchFunctions->getNearestBenches( $latitude, $longitude, $distance, $limit, true, false);
		
		//	GPX
		$gpx = '<?xml version="1.0" encoding="utf-8"?>'.
		'<gpx version="1.1" creator="L-36.com" '.
		'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '.
		'xmlns="http://www.topografix.com/GPX/1/1" '.
		'xsi:schemaLocation="http://www.topografix.com/GPX/1/1 http://www.topografix.com/GPX/1/1/gpx.xsd">';

		foreach($benches_array as $bench) {
			$inscription = $bench["inscription"];
			$latitude    = $bench["latitude"];
			$longitude   = $bench["longitude"];
			
			# some inscriptions got stored with leading/trailing whitespace
			$inscription=trim($inscription);

			// Short inscriptions
			$inscription = mb_substr($inscription, 0, 128);

			//	Horrible hack to force numeric inscriptions to be strings
			if (is_numeric($inscription)) {
				$inscription .= " ";
			}
			$gpx .= '<wpt lat="' . $latitude . '" lon="' . $longitude . '"><name>'. htmlspecialchars( $inscription ) . '</name><sym>Waypoint</sym></wpt>';
		}
		$gpx .= '</gpx>';
		//	Render the page
		header("Content-type: application/gpx+xml; charset=utf-8");
		echo $gpx;
		die();
	}

	#[Route("/api/tag/{tag_name}", name: "api_tagged_benches")]
	public function api_tagged_benches(string $tag_name): JsonResponse {
		$request = Request::createFromGlobals();

		$cache = new FilesystemAdapter($_ENV["CACHE"] . "cache_api_tagged_benches" );
		$cache_name = "{$tag_name}_tagged_benches";

		if ( $request->query->get( "truncated" ) == "false" ) {
			$get_truncated = false;
			$cache_name .= "full";
		} else {
			$get_truncated = true;
			$cache_name .= "truncated";
		}
		
		if ( $request->query->get( "media" ) == "true" ) {
			$get_media = true;
			$cache_name .= "media";
		} else {
			$get_media = false;
			$cache_name .= "nomedia";
		}

		$value = $cache->get( $cache_name, function (ItemInterface $item) use($tag_name)  {
			//	Cache length in seconds
			$item->expiresAfter(60); //	1 minute

			$request = Request::createFromGlobals();
			if ( $request->query->get("truncated") == "false") {
				$get_truncated = false;
			} else {
				$get_truncated = true;
			}
			
			if ( $request->query->get("media") == "true" ) {
				$get_media = true;
			} else {
				$get_media = false;
			}

			$tagFunctions = new TagsFunctions();
			$tagIDs = $tagFunctions->getTags();
			$tagID = array_search($tag_name, $tagIDs);

			if (false === $tagID) {
				return array();
			}

			$mediaFunctions = new MediaFunctions();
			$dsnParser = new DsnParser();
			$connectionParams = $dsnParser->parse( $_ENV["DATABASE_URL"] );
			$conn = DriverManager::getConnection($connectionParams);
			$queryBuilder = $conn->createQueryBuilder();

			$queryBuilder
				->select("benches.benchID", "benches.inscription", "benches.latitude", "benches.longitude", "benches.added", "media.sha1", "media.licence", "media.media_type", "media.importURL", "media.width", "media.height")
				->from("benches")
				->innerJoin("benches", "media", "media", "benches.benchID = media.benchID")
				->innerJoin('benches', 'tag_map', 'tag_map', 'benches.benchID = tag_map.benchID')
				->where("tag_map.tagID = ? AND benches.published = true AND benches.present = true")
				->setParameter( 0, $tagID );
			$results = $queryBuilder->executeQuery();

			//	Build GeoJSON feature collection array
			$geojson = array(
				"type"		=> "FeatureCollection",
				"features"  => array()
			);

			//	Loop through the results to create an array of benches
			$benches_array = array();
			while (($row = $results->fetchAssociative()) !== false) {

				//	If the bench has already been added, add the media to it
				if( isset( $benches_array[$row["benchID"]] ) && $get_media != false ) {
					$media_data = array();

					$media_data["URL"] = "/image/{$row["sha1"]}";

					if( null != $row["importURL"] ) {
						$media_data["importURL"] = $row["importURL"];
					}

					$media_data["licence"]     = $row["licence"];
					$media_data["media_type"]  = $row["media_type"];
					$media_data["sha1"]        = $row["sha1"];
					$media_data["width"]       = $row["width"];
					$media_data["height"]      = $row["height"];

					//	Add all the media details to the response
					array_push( $benches_array[$row["benchID"]]["properties"]["media"], $media_data);				

				} else {
					//	Some inscriptions got stored with leading/trailing whitespace
					$inscription=trim( $row["inscription"] );

					// If displaying on map need to truncate inscriptions longer than
					// 128 chars and add in <br> elements
					if ( true == $get_truncated ) {
						$inscriptionTruncate = mb_substr( $inscription, 0, 128 );
						if ( $inscriptionTruncate !== $inscription ) {
							$inscription = $inscriptionTruncate . "…";
						}
						$inscription=nl2br( $inscription );
					}

					//	Horrible hack to force numeric inscriptions to be strings
					if ( is_numeric( $inscription ) ) {
						$inscription .= " ";
					}

					$media_data = array();
					if( $get_media != false ) {

						$media_data["URL"] = "/image/{$row["sha1"]}";
		
						if( null != $row["importURL"] ) {
							$media_data["importURL"] = $row["importURL"];
						}
		
						$media_data["licence"]     = $row["licence"];
						$media_data["media_type"]  = $row["media_type"];
						$media_data["sha1"]        = $row["sha1"];
						$media_data["width"]       = $row["width"];
						$media_data["height"]      = $row["height"];
					}

					//	Create GeoJSON
					$feature = array(
						"id"       => $row["benchID"],
						"type"     => "Feature",
						"geometry" => array(
							"type" => "Point",
							// Pass Longitude and Latitude Columns here
							"coordinates" => array($row["longitude"], $row["latitude"])
						),
						// Pass other attribute columns here
						"properties" => array(
							"created_at"   => date_format( date_create($row["added"] ), "c" ),
							"popupContent" => $inscription,
							"media" => array(),
						),
					);
					//	Add all the media details to the response
					array_push( $feature["properties"]["media"], $media_data);

					// Add feature to collection array
					$benches_array[$row["benchID"]] = $feature;
				}
			}

			foreach( $benches_array as $bench ){
				array_push($geojson["features"], $bench);
			}
			return $geojson;
		});

		$response = new JsonResponse($value);	
		return $response;
	}

	#[Route("/api/tags/", name: "api_tags")]
	public function api_tags(): JsonResponse {

		$cache = new FilesystemAdapter($_ENV["CACHE"] ."cache_tags");
		$value = $cache->get("tags", function (ItemInterface $item) {
			//	Cache length in seconds
			$item->expiresAfter(3600);
			$dsnParser = new DsnParser();
			$connectionParams = $dsnParser->parse( $_ENV["DATABASE_URL"] );
			$conn = DriverManager::getConnection($connectionParams);

			$queryBuilder = $conn->createQueryBuilder();

			$queryBuilder
				->select("tagID", "tagText")
				->from("tags");
			$results = $queryBuilder->executeQuery();

			//	Loop through the results to create an array of benches
			$tags_array = array();
			while (($row = $results->fetchAssociative()) !== false) {
				//	https://select2.org/data-sources/arrays
				$tags_array[] = array( "id" => $row["tagID"], "text" => $row["tagText"] );
			}
			return $tags_array;
		});

		//	Render the page
		$response = new JsonResponse($value);	
		return $response;
	}	

	#[Route("/api/cache/", name: "api_cache")]
	public function api_cache(): JsonResponse {
		//	Tests whether the cache is working
		$cache = new FilesystemAdapter($_ENV["CACHE"] ."cache_time");

		$value = $cache->get("cache_time", function (ItemInterface $item) {
			$item->expiresAfter(600);

			$dsnParser = new DsnParser();
			$connectionParams = $dsnParser->parse( $_ENV["DATABASE_URL"] );
			$conn = DriverManager::getConnection($connectionParams);

			$queryBuilder = $conn->createQueryBuilder();

			$queryBuilder->select("CURRENT_TIMESTAMP()");
			$results = $queryBuilder->executeQuery();
	  
			return $results->fetchAllAssociative();
		});
		
		return new JsonResponse( $value );
	}

	#[Route("/api/benches.tsv", name: "api_all_benches_tsv")]
	public function api_all_benches_tsv() {
		$request = Request::createFromGlobals();

		$cache = new FilesystemAdapter($_ENV["CACHE"] . "cache_all_benches" );
		$cache_name = "all_benches_tsv";

		$value = $cache->get( $cache_name, function (ItemInterface $item) {
			//	Cache length in seconds
			$item->expiresAfter(600); //	10 minutes

			$request = Request::createFromGlobals();
			
			$dsnParser = new DsnParser();
			$connectionParams = $dsnParser->parse( $_ENV["DATABASE_URL"] );
			$conn = DriverManager::getConnection($connectionParams);
			$queryBuilder = $conn->createQueryBuilder();

			$queryBuilder
				->select("benches.benchID", "benches.inscription", "benches.latitude", "benches.longitude")
				->from("benches")
				->where("benches.published = true AND benches.present = true");
			$results = $queryBuilder->executeQuery();

			//	Build TSV feature collection array
		
			//	Loop through the results to create an array of benches
			$tsv = "id\tlongitude\tlatitude\tinscription\n";

			while (($row = $results->fetchAssociative()) !== false) {

				$tsv_id = $row["benchID"];
				$tsv_longitude = $row["longitude"];
				$tsv_latitude  = $row["latitude"];

				//	Some inscriptions got stored with leading/trailing whitespace
				$tsv_inscription=trim( $row["inscription"] );

				// If displaying on map need to truncate inscriptions longer than
				// 128 chars and add in <br> elements
				$tsv_inscriptionTruncate = mb_substr( $tsv_inscription, 0, 64 );
				if ( $tsv_inscriptionTruncate !== $tsv_inscription ) {
					$tsv_inscription = $tsv_inscriptionTruncate . "…";
				}

				$tsv_inscription = str_replace(array("\r\n", "\r", "\n"), "<br />", $tsv_inscription);

				//	Remove tabs from inscription
				$tsv_inscription = trim( preg_replace( '/\t+/', '', $tsv_inscription ) );
				
				$tsv .= "{$tsv_id}\t{$tsv_longitude}\t{$tsv_latitude}\t{$tsv_inscription}\n";				
			}

			return trim($tsv);
		});

		$response = new Response(
			'Content',
			Response::HTTP_OK,
			['content-type' => 'text/plain']
		);

		$response->setContent($value);

		return $response;
	}
}