<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

use App\Service\MediaFunctions;
use App\Service\UserFunctions;
use App\Service\SearchFunctions;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;

class ApiController extends AbstractController
{
	#[Route('/api/bench/{bench_id}', name: 'api_bench')]
	public function api_bench(int $bench_id): JsonResponse {
		$request = Request::createFromGlobals();
		if ( $request->query->get('truncated') == "false") {
			$get_truncated = false;
		} else {
			$get_truncated = true;
		}
		
		if ( $request->query->get('media') == "true" ) {
			$get_media = true;
		} else {
			$get_media = false;
		}
		
		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
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
				'type'		=> 'FeatureCollection',
				'features'  => array()
			);

			# some inscriptions got stored with leading/trailing whitespace
			$inscription=trim($inscription);

			// If displaying on map need to truncate inscriptions longer than
			// 128 chars and add in <br> elements
			// N.B. this logic is also in get_nearest_benches()
			if ( true == $get_truncated ) {
				$inscriptionTruncate = mb_substr( $inscription, 0, 128 );
				if ( $inscriptionTruncate !== $inscription ) {
					$inscription = $inscriptionTruncate . '…';
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
				'id'       => $benchID,
				'type'     => 'Feature',
				'geometry' => array(
					'type' => 'Point',
					# Pass Longitude and Latitude Columns here
					'coordinates' => array($longitude, $latitude)
				),
				# Pass other attribute columns here
				'properties' => array(
					'created_at'   => date_format( date_create($added ), "c" ),
					'popupContent' => $inscription,
					'media'        => $mediaFeature
				),
			);
			# Add feature arrays to feature collection array
			array_push($geojson['features'], $feature);
		}

		//	Render the page
		$response = new JsonResponse($geojson);	
		return $response;
	}

	#[Route('/api/benches/', name: 'api_all_benches')]
	public function api_all_benches(): JsonResponse {
		$request = Request::createFromGlobals();
		if ( $request->query->get('truncated') == "false") {
			$get_truncated = false;
		} else {
			$get_truncated = true;
		}
		
		if ( $request->query->get('media') == "true" ) {
			$get_media = true;
		} else {
			$get_media = false;
		}

		$mediaFunctions = new MediaFunctions();

		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
		$conn = DriverManager::getConnection($connectionParams);

		$queryBuilder = $conn->createQueryBuilder();

		$queryBuilder
			->select("benches.benchID", "benches.inscription", "benches.latitude", "benches.longitude", "benches.added", "media.sha1", "media.licence", "media.media_type", "media.importURL", "media.width", "media.height")
			->from("benches")
			->innerJoin('benches', 'media', 'media', 'benches.benchID = media.benchID')
			->where("benches.published = true AND benches.present = true");
			// ->setFirstResult(0)
		   // ->setMaxResults(2000);
		$results = $queryBuilder->executeQuery();

		//	Build GeoJSON feature collection array
		$geojson = array(
			'type'		=> 'FeatureCollection',
			'features'  => array()
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
						$inscription = $inscriptionTruncate . '…';
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
					'id'       => $row["benchID"],
					'type'     => 'Feature',
					'geometry' => array(
						'type' => 'Point',
						// Pass Longitude and Latitude Columns here
						'coordinates' => array($row["longitude"], $row["latitude"])
					),
					// Pass other attribute columns here
					'properties' => array(
						'created_at'   => date_format( date_create($row["added"] ), "c" ),
						'popupContent' => $inscription,
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

		$response = new JsonResponse($geojson);	
		return $response;
	}

	#[Route('/api/user/{user_id}', name: 'api_user')]
	public function api_user(int $user_id): JsonResponse {
		$mediaFunctions = new MediaFunctions();
		$userFunctions  = new UserFunctions();

		$request = Request::createFromGlobals();
		if ( $request->query->get('truncated') == "false") {
			$get_truncated = false;
		} else {
			$get_truncated = true;
		}
		
		if ( $request->query->get('media') == "true" ) {
			$get_media = true;
		} else {
			$get_media = false;
		}

		$benches_array = $userFunctions->getUserBenches( $user_id, 0, 20000 );

		# Build GeoJSON feature collection array
		$geojson = array(
			'type'		=> 'FeatureCollection',
			'features'  => array()
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
					$inscription = $inscriptionTruncate . '…';
				}
				$inscription = nl2br( $inscription );
			}

			//	Horrible hack to force numeric inscriptions to be strings
			if ( is_numeric( $inscription ) ) {
				$inscription .= " ";
			}

			//	Create GeoJSON
			$feature = array(
				'id'       => $benchID,
				'type'     => 'Feature',
				'geometry' => array(
					'type' => 'Point',
					# Pass Longitude and Latitude Columns here
					'coordinates' => array($longitude, $latitude)
				),
				# Pass other attribute columns here
				'properties' => array(
					'created_at'   => date_format( date_create($added ), "c" ),
					'popupContent' => $inscription,
					'media'        => array(["url" => $image]),
				),
			);
			# Add feature arrays to feature collection array
			array_push($geojson['features'], $feature);
		}

		//	Render the page
		$response = new JsonResponse($geojson);	
		return $response;
	}

	#[Route('/api/search/', name: 'api_search')]
	public function api_search(): JsonResponse {
		$mediaFunctions = new MediaFunctions();
		$searchFunctions  = new SearchFunctions();

		$request = Request::createFromGlobals();
		$query         = $request->query->get("search");
		$get_truncated = $request->query->get('truncated');
		$get_media     = $request->query->get('media');

		//	Get the benches associated with this query
		$benches_array = $searchFunctions->getSearchBenches( $query, 0, 20000 );
		
		# Build GeoJSON feature collection array
		$geojson = array(
			'type'		=> 'FeatureCollection',
			'features'  => array()
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
					$inscription = $inscriptionTruncate . '…';
				}
				$inscription = nl2br( $inscription );
			}

			//	Horrible hack to force numeric inscriptions to be strings
			if ( is_numeric( $inscription ) ) {
				$inscription .= " ";
			}

			//	Create GeoJSON
			$feature = array(
				'id'       => $benchID,
				'type'     => 'Feature',
				'geometry' => array(
					'type' => 'Point',
					# Pass Longitude and Latitude Columns here
					'coordinates' => array($longitude, $latitude)
				),
				# Pass other attribute columns here
				'properties' => array(
					'created_at'   => date_format( date_create($added ), "c" ),
					'popupContent' => $inscription,
					'media'        => array(["url" => $image]),
				),
			);
			# Add feature arrays to feature collection array
			array_push($geojson['features'], $feature);
		}

		//	Render the page
		$response = new JsonResponse($geojson);	
		return $response;
	}

	#[Route('/api/tags/', name: 'api_tags')]
	public function api_tags(): JsonResponse {

		$cache = new FilesystemAdapter("cache_tags");
		$value = $cache->get("tags", function (ItemInterface $item) {
			//	Cache length in seconds
			$item->expiresAfter(3600);
			$dsnParser = new DsnParser();
			$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
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
	
}