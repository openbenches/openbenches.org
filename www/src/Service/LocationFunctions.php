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
			$item->expiresAfter(3600000);

			//	https://api.stadiamaps.com/geocoding/v1/search?text=Great%20Bedwyn,%20United%20Kingdom&size=1&api_key=abc123
			$geocode_api_key = $_ENV['STADIAMAPS_API_KEY'];
			$geocodeAPI = "https://api.stadiamaps.com/geocoding/v1/search?text=" . urlencode($address_string) . "&size=1&api_key={$geocode_api_key}";
			$options = array(
				'http'=> array(
					'method' => "GET"
				)
			);
			$context = stream_context_create($options);
			$locationJSON = file_get_contents($geocodeAPI, false, $context);
			$locationData = json_decode($locationJSON);

			if( isset( $locationData->bbox[0] ) ) {

				//	https://docs.stadiamaps.com/geocoding-search-autocomplete/api-response-format/#standard-envelope
				$bb_w = $locationData->bbox[0];
				$bb_s = $locationData->bbox[1];
				$bb_e = $locationData->bbox[2];
				$bb_n = $locationData->bbox[3];

				return [$bb_n, $bb_e, $bb_s, $bb_w];
			} else {
				return [null,  null,  null, $locationJSON];
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

	public function getAddress( $latitude, $longitude ): string {
		//	https://api.stadiamaps.com/geocoding/v2/reverse?point.lat=51.49634&point.lon=0.13308&api_key=zzzzz
		$geocode_api_key = $_ENV['STADIAMAPS_API_KEY'];
		$geocodeAPI = "https://api.stadiamaps.com/geocoding/v2/reverse" . 
			"?point.lat=" . $latitude . 
			"&point.lon=" . $longitude .
			"&api_key={$geocode_api_key}";
		$options = array(
			'http'=> array(
				'method' => "GET"
			)
		);
		$context = stream_context_create($options);
		$locationJSON = file_get_contents($geocodeAPI, false, $context);
		$locationData = json_decode($locationJSON);
		
		//	Construct the address from the Who's On First components.
		//	https://github.com/whosonfirst/whosonfirst-placetypes#here-is-a-pretty-picture
		$whosonfirst = $locationData->features[0]->properties->context->whosonfirst;
		
		//	Convert to an array.
		$address_parts_array = [];
		foreach ( $whosonfirst as $key => $value ) {
			$address_parts_array[$key] = $value->name;
		}
		
		//	Construct the address.
		$address_parts = [];

		//	Don't use "name" if this is a street or address.
		if ( "poi" == $locationData->features[0]->properties->layer ) {
			$adddress_name = $locationData->features[0]->properties->name;
			if ( $adddress_name != "" ) {
				$address_parts[] = $adddress_name;
			}	
		}
		
		//	Construct from smallest to biggest.
		if ( array_key_exists( "neighbourhood", $address_parts_array ) ) {
				$address_parts[] = $address_parts_array["neighbourhood"];
		}
		if ( array_key_exists( "borough", $address_parts_array ) ) {
				$address_parts[] = $address_parts_array["borough"];
		}
		if ( array_key_exists( "locality", $address_parts_array ) ) {
				$address_parts[] = $address_parts_array["locality"];
		}
		
		//	Avoid duplicating county information.
		if ( array_key_exists( "macrocounty", $address_parts_array ) ) {
				$address_parts[] = $address_parts_array["macrocounty"];
		} else if ( array_key_exists( "county", $address_parts_array ) ) {
				$address_parts[] = $address_parts_array["county"];
		}
		
		if ( array_key_exists( "region", $address_parts_array ) ) {
				$address_parts[] = $address_parts_array["region"];
		} 
		
		if ( array_key_exists( "macroregion", $address_parts_array ) ) {
				$address_parts[] = $address_parts_array["macroregion"];
		}
		
		if ( array_key_exists( "country", $address_parts_array ) ) {
				$address_parts[] = $address_parts_array["country"];
		} 
		
		//	Remove duplicates.
		$address_parts = array_unique( $address_parts );
		//	Turn into a comma separate string.
		$formatted_address = implode( ", ", $address_parts ); 
		//	Ensure this is all Unicode.
		$formatted_address = mb_convert_encoding( $formatted_address, "UTF-8", "auto" );
	
		return $formatted_address;

		// //	Flip between different providers, because we're cheapskates!
		// $provider = random_int(1,2);

		// // if (0 == $provider) {
		
		// // 	$geocode_api_key = $_ENV['OPENCAGE_API_KEY'];

		// // 	$reverseGeocodeAPI = "https://api.opencagedata.com/geocode/v1/json?q={$latitude}%2C{$longitude}&no_annotations=1&key={$geocode_api_key}";
		// // 	$options = array(
		// // 		'http'=>array(
		// // 			'method'=>"GET",
		// // 			'header'=>"User-Agent: OpenBenches.org\r\n"
		// // 		)
		// // 	);

		// // 	$context = stream_context_create($options);
		// // 	$locationJSON = file_get_contents($reverseGeocodeAPI, false, $context);
		// // 	$locationData = json_decode($locationJSON);
		// // 	try {
		// // 		//	Pre-formated address from GeoCage
		// // 		$formatted_address = $locationData->results[0]->formatted;
		// // 		//	Separate components
		// // 		$address_components = (array) $locationData->results[0]->components;
		// // 		//	Postcode needs removing in order to reduce precision when searching
		// // 		$postcode = $address_components["postcode"];
		// // 		//	Delete the postcode from the pre-formatted address
		// // 		$formatted_address = str_replace($postcode, "", $formatted_address);
		// // 		$formatted_explode = array_map('trim', explode(',', $formatted_address));
		// // 		$formatted_explode = array_filter($formatted_explode);
		// // 		$formatted_address = implode(", " , $formatted_explode);

		// // 	} catch (Exception $e) {
		// // 		$loc = var_export($locationData);
		// // 		error_log("Caught $e - $loc");
		// // 		return "";
		// // 	}

		// // 	return $formatted_address;
		// // } 	if (1 == $provider) {
		// // 	$geocode_api_key = $_ENV['GEOAPIFY_API_KEY'];
		// // 	// $location = urlencode($location);
		// // 	$geocodeAPI = "https://api.geoapify.com/v1/geocode/reverse?lat={$latitude}&lon={$longitude}&apiKey={$geocode_api_key}&type=street";
		// // 	$options = array(
		// // 		'http'=>array(
		// // 			'method'=>"GET",
		// // 			'header'=>"User-Agent: OpenBenches.org\r\n"
		// // 		)
		// // 	);
		// // 	$context = stream_context_create($options);
		// // 	$locationJSON = file_get_contents($geocodeAPI, false, $context);
		// // 	$locationData = json_decode($locationJSON);

		// // 	try {
		// // 		$country = $locationData->features[0]->properties->country ?? null;
		// // 		$state   = $locationData->features[0]->properties->state   ?? null;
		// // 		$county  = $locationData->features[0]->properties->county  ?? null;
		// // 		$city    = $locationData->features[0]->properties->city    ?? null;
		// // 		$suburb  = $locationData->features[0]->properties->suburb  ?? null;
				
		// // 		$address_components = array($suburb, $city, $county, $state, $country);
		// // 		$address_components = array_filter($address_components);
		// // 		$formatted_address = implode(", " , $address_components);
		// // 	} catch (Exception $e) {
		// // 		$loc = var_export($locationData);
		// // 		error_log("Caught $e - $loc");
		// // 		return "";
		// // 	}
		// // 	return $formatted_address;
		// // } if (2 == $provider) {
		// // 	//	https://geocode.maps.co/
		// // 	$geocode_api_key = $_ENV['GEOCODE_API_KEY'];

		// // 	$geocodeAPI = "https://geocode.maps.co/reverse?lat={$latitude}&lon={$longitude}&api_key={$geocode_api_key}";
		// // 	$options = array(
		// // 		'http'=>array(
		// // 			'method'=>"GET",
		// // 			'header'=>"User-Agent: OpenBenches.org\r\n"
		// // 		)
		// // 	);
		// // 	$context = stream_context_create($options);
		// // 	$locationJSON = file_get_contents($geocodeAPI, false, $context);
		// // 	$locationData = json_decode($locationJSON);

		// // 	try {
		// // 		$country = $locationData->address->country ?? null;
		// // 		$state   = $locationData->address->state   ?? null;
		// // 		$county  = $locationData->address->county  ?? null;
		// // 		$city    = $locationData->address->city    ?? null;
		// // 		$suburb  = $locationData->address->suburb  ?? null;

		// // 		$address_components = array($suburb, $city, $county, $state, $country);
		// // 		$address_components = array_filter($address_components);
		// // 		$formatted_address = implode(", " , $address_components);
		// // 	} catch (Exception $e) {
		// // 		$loc = var_export($locationData);
		// // 		error_log("Caught $e - $loc");
		// // 		return "";
		// // 	}
		// // 	return $formatted_address;
		// // }
	}
}
