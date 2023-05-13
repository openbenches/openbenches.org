<?php
// src/Service/LocationFunctions.php
namespace App\Service;

use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;

class LocationFunctions
{
	public function getBoundingBox( string $address_string ): array {
		$cache = new FilesystemAdapter($_ENV["CACHE"] . "cache_location");
		//	Cache name is the address string
		$value = $cache->get($address_string, function (ItemInterface $item) use( $address_string) {
			//	Cache length in seconds
			$item->expiresAfter(3600);

			//	https://geocode.maps.co/search?q=Bath%20and%20North%20East%20Somerset,%20South%20West%20England,%20England,%20United%20Kingdom
			$geocodeAPI = "https://geocode.maps.co/search?q={$address_string}";
			$options = array(
				'http'=>array(
					'method'=>"GET",
					'header'=>"User-Agent: OpenBenches.org\r\n"
				)
			);
			$context = stream_context_create($options);
			$locationJSON = file_get_contents($geocodeAPI, false, $context);
			$locationData = json_decode($locationJSON);

			if( isset( $locationData[0] ) ) {
				$lat_ne = $locationData[0]->boundingbox[1];
				$lng_ne = $locationData[0]->boundingbox[3];
				$lat_sw = $locationData[0]->boundingbox[0];
				$lng_sw = $locationData[0]->boundingbox[2];

				$lat = $locationData[0]->lat;
				$lng = $locationData[0]->lon;

				return [$lat_ne, $lng_ne, $lat_sw, $lng_sw, $lat, $lng];
			} else {
				return ["",      "",      "",      "",      "",   ""];
			}
	 });

	 return $value;
	}

	public function getBoundingBoxCount( $lat_ne, $lng_ne, $lat_sw, $lng_sw ): int {
		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
		$conn = DriverManager::getConnection($connectionParams);

		//	Count
		$sql = "SELECT COUNT(*)
		        FROM `benches`
		        WHERE
		        `latitude`  BETWEEN ? AND ? AND
		        `longitude` BETWEEN ? AND ? AND
		        `published` = true AND `present` = true";
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(1, $lat_sw);
		$stmt->bindValue(2, $lat_ne);
		$stmt->bindValue(3, $lng_sw);
		$stmt->bindValue(4, $lng_ne);
		$results = $stmt->executeQuery();
		$results_array = $results->fetchAssociative();

		if (false != $results_array) {
			return $results_array["COUNT(*)"];
		} else {
			return 0;
		}
	}

	public function getBoundingBoxBenches( $lat_ne, $lng_ne, $lat_sw, $lng_sw, $start=0, $count=20 ): array {
		$mediaFunctions = new MediaFunctions();

		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
		$conn = DriverManager::getConnection($connectionParams);

		$queryBuilder = $conn->createQueryBuilder();

		$queryBuilder
			->select("MAX(benches.benchID)", "benches.benchID", "benches.inscription", "benches.address", "benches.latitude", "benches.longitude", "benches.added", "MIN(media.sha1)")
			->from("benches")
			->innerJoin('benches', 'media', 'media', 'benches.benchID = media.benchID')
			->where("latitude  BETWEEN ? AND ? AND longitude BETWEEN ? AND ? AND benches.published = true AND benches.present = true")
			->orderBy("benches.benchID", 'DESC')
			->groupBy("benches.benchID")
			->setFirstResult( $start )
			->setMaxResults( $count )
			->setParameter(0, $lat_sw)
			->setParameter(1, $lat_ne)
			->setParameter(2, $lng_sw)
			->setParameter(3, $lng_ne);

		$results = $queryBuilder->executeQuery();

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

	public function getAddress($latitude, $longitude): string {
		//	Flip between different providers, because we're cheapskates!
		$provider = random_int(1,2);

		if (0 == $provider) {
		
			$geocode_api_key = $_ENV['OPENCAGE_API_KEY'];

			$reverseGeocodeAPI = "https://api.opencagedata.com/geocode/v1/json?q={$latitude}%2C{$longitude}&no_annotations=1&key={$geocode_api_key}";
			$options = array(
				'http'=>array(
					'method'=>"GET",
					'header'=>"User-Agent: OpenBenches.org\r\n"
				)
			);

			$context = stream_context_create($options);
			$locationJSON = file_get_contents($reverseGeocodeAPI, false, $context);
			$locationData = json_decode($locationJSON);
			try {
				//	Pre-formated address from GeoCage
				$formatted_address = $locationData->results[0]->formatted;
				//	Separate components
				$address_components = (array) $locationData->results[0]->components;
				//	Postcode needs removing in order to reduce precision when searching
				$postcode = $address_components["postcode"];
				//	Delete the postcode from the pre-formatted address
				$formatted_address = str_replace($postcode, "", $formatted_address);
				$formatted_explode = array_map('trim', explode(',', $formatted_address));
				$formatted_explode = array_filter($formatted_explode);
				$formatted_address = implode(", " , $formatted_explode);

			} catch (Exception $e) {
				$loc = var_export($locationData);
				error_log("Caught $e - $loc");
				return "";
			}

			return $formatted_address;
		} 	if (1 == $provider) {
			$geocode_api_key = $_ENV['GEOAPIFY_API_KEY'];
			// $location = urlencode($location);
			$geocodeAPI = "https://api.geoapify.com/v1/geocode/reverse?lat={$latitude}&lon={$longitude}&apiKey={$geocode_api_key}";
			$options = array(
				'http'=>array(
					'method'=>"GET",
					'header'=>"User-Agent: OpenBenches.org\r\n"
				)
			);
			$context = stream_context_create($options);
			$locationJSON = file_get_contents($geocodeAPI, false, $context);
			$locationData = json_decode($locationJSON);

			try {
				//	Pre-formated address from GeoAPIfy
				$formatted_address = $locationData->features[0]->properties->formatted;
				//	Postcode needs removing in order to reduce precision when searching
				$postcode = $locationData->features[0]->properties->postcode ?? "";
				//	Delete the postcode from the pre-formatted address
				$formatted_address = str_replace($postcode, "", $formatted_address);
				$formatted_explode = array_map('trim', explode(',', $formatted_address));
				$formatted_explode = array_filter($formatted_explode);
				$formatted_address = implode(", " , $formatted_explode);
			} catch (Exception $e) {
				$loc = var_export($locationData);
				error_log("Caught $e - $loc");
				return "";
			}
			return $formatted_address;
		} if (2 == $provider) {
			//	https://geocode.maps.co/
			// $location = urlencode($location);
			$geocodeAPI = "https://geocode.maps.co/reverse?lat={$latitude}&lon={$longitude}";
			$options = array(
				'http'=>array(
					'method'=>"GET",
					'header'=>"User-Agent: OpenBenches.org\r\n"
				)
			);
			$context = stream_context_create($options);
			$locationJSON = file_get_contents($geocodeAPI, false, $context);
			$locationData = json_decode($locationJSON);

			try {
				//	Pre-formated address from maps.co
				$formatted_address = $locationData->display_name;
				//	Postcode needs removing in order to reduce precision when searching
				$postcode = $locationData->address->postcode;
				//	Delete the postcode from the pre-formatted address
				$formatted_address = str_replace($postcode, "", $formatted_address);
				$formatted_explode = array_map('trim', explode(',', $formatted_address));
				$formatted_explode = array_filter($formatted_explode);
				$formatted_address = implode(", " , $formatted_explode);
			} catch (Exception $e) {
				$loc = var_export($locationData);
				error_log("Caught $e - $loc");
				return "";
			}
			return $formatted_address;
		}
	}
}