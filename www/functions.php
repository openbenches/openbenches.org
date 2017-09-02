<?php
require_once ("codebird.php");
require_once ("config.php");

function get_edit_key($benchID){
	$hash = crypt($benchID,EDIT_SALT);
	$key = explode("$",$hash)[3];
	return $key;
}

function is_photosphere($filename) {
	//	As per https://stackoverflow.com/a/1578326/1127699
	$file = file_get_contents($filename);
	if (strpos($file, 'UsePanoramaViewer="True"') > 0 ) {
		return true;
	}
	if (strpos($file, 'ProjectionType="equirectangular"') > 0) {
		return true;
	}
	return false;
}

function get_image_location($file)
{
	if (is_file($file)) {
		$info = exif_read_data($file);
		if ($info !== false) {
				$direction = array('N', 'S', 'E', 'W');
				if (isset($info['GPSLatitude'], $info['GPSLongitude'], $info['GPSLatitudeRef'], $info['GPSLongitudeRef']) &&
					in_array($info['GPSLatitudeRef'], $direction) && in_array($info['GPSLongitudeRef'], $direction)) {

					$lat_degrees_a = explode('/',$info['GPSLatitude'][0]);
					$lat_minutes_a = explode('/',$info['GPSLatitude'][1]);
					$lat_seconds_a = explode('/',$info['GPSLatitude'][2]);
					$lng_degrees_a = explode('/',$info['GPSLongitude'][0]);
					$lng_minutes_a = explode('/',$info['GPSLongitude'][1]);
					$lng_seconds_a = explode('/',$info['GPSLongitude'][2]);

					$lat_degrees = $lat_degrees_a[0] / $lat_degrees_a[1];
					$lat_minutes = $lat_minutes_a[0] / $lat_minutes_a[1];
					$lat_seconds = $lat_seconds_a[0] / $lat_seconds_a[1];
					$lng_degrees = $lng_degrees_a[0] / $lng_degrees_a[1];
					$lng_minutes = $lng_minutes_a[0] / $lng_minutes_a[1];
					$lng_seconds = $lng_seconds_a[0] / $lng_seconds_a[1];

					$lat = (float) $lat_degrees + ((($lat_minutes * 60) + ($lat_seconds)) / 3600);
					$lng = (float) $lng_degrees + ((($lng_minutes * 60) + ($lng_seconds)) / 3600);
					$lat = number_format($lat, 7);
					$lng = number_format($lng, 7);

					//If the latitude is South, make it negative.
					//If the longitude is west, make it negative
					$lat = $info['GPSLatitudeRef'] == 'S' ? $lat * -1 : $lat;
					$lng = $info['GPSLongitudeRef'] == 'W' ? $lng * -1 : $lng;

					return array(
						'lat' => round($lat,10),
						'lng' => round($lng,10)
					);
				}
		}
	}

	return false;
}

function tweet_bench($benchID, $mediaURLs=null, $inscription=null, $latitude=null, $longitude=null, $license=null){
	//	Send Tweet
	\Codebird\Codebird::setConsumerKey(OAUTH_CONSUMER_KEY, OAUTH_CONSUMER_SECRET);
	$cb = \Codebird\Codebird::getInstance();
	$cb->setToken(OAUTH_ACCESS_TOKEN, OAUTH_TOKEN_SECRET);

	//	Add the image
	if(null!=$mediaURLs){
		
		$media_ids = array();
		
		foreach ($mediaURLs as $file) {
			// upload all media files
			$reply = $cb->media_upload(['media' => $file]);
			// and collect their IDs
			$media_ids[] = $reply->media_id_string;
		}
		$media_ids = implode(',', $media_ids);
	}

	$tweet_inscription = mb_substr($inscription, 0, 100);
	if (mb_strlen($inscription) > 100) {
		$tweet_inscription .= "…";
	}

	$domain = $_SERVER['SERVER_NAME'];

	$params = [
		'status'    => "{$tweet_inscription}\nhttps://{$domain}/bench/{$benchID}\n{$license}",
		'lat'       => $latitude,
		'long'      => $longitude,
		'media_ids' => $media_ids
	];
	$reply = $cb->statuses_update($params);
}

function get_map_javascript($lat = "54.5", $long="-4", $zoom = "5") {
	$mapJavaScript = <<<EOT
<script>
	var attribution = 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors, ' +
		'<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
		'Imagery © <a href="https://mapbox.com">Mapbox</a>';

	var grayscale = L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/outdoors-v10/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1IjoiZWRlbnQiLCJhIjoiY2o0dmVnZjVhMHA1MDMzcWp4YmtzcWNsbiJ9.DIgG0nrOK4bnswj2RFfLgQ', {
		minZoom: 2,
		maxZoom: 18,
		attribution: attribution,
		id: 'mapbox.light'
	});

	var satellite = L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/satellite-streets-v10/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1IjoiZWRlbnQiLCJhIjoiY2o0dmVnZjVhMHA1MDMzcWp4YmtzcWNsbiJ9.DIgG0nrOK4bnswj2RFfLgQ', {
			minZoom: 2,
			maxZoom: 18,
			attribution: attribution,
			id: 'mapbox.satellite'
		});

	var map = L.map('map');

	map.on("load", function () {
		if (window.location.hash != "") {
			var hashArray = window.location.hash.substr(1).split("/");
			var hashLat = hashArray[0];
			var hashLng = hashArray[1];
			var hashZoom = hashArray[2];
			map.setView([hashLat, hashLng], hashZoom);
		}
	});

	map.setView([{$lat}, {$long}], {$zoom});

	var baseMaps = {
		"Map View": grayscale,
		"Satellite View": satellite
	};

	grayscale.addTo(map);

	L.control.layers(baseMaps).addTo(map);

	var markers = L.markerClusterGroup({
		maxClusterRadius: 29
	});
</script>
EOT;

	echo $mapJavaScript;
}

function get_exif_html($filename) {
	$exif_data = exif_read_data($filename,0,true);

	$exif = $exif_data["EXIF"];

	if (array_key_exists("DateTime", $exif)) {
		$dateHTML = exif_date_to_timestamp($exif["DateTime"]);
	} else if (array_key_exists("DateTimeOriginal", $exif)) {
		$dateHTML = exif_date_to_timestamp($exif["DateTimeOriginal"]);
	} else if (array_key_exists("DateTimeDigitized", $exif)) {
		$dateHTML = exif_date_to_timestamp($exif["DateTimeDigitized"]);
	} else if (array_key_exists("GPSDateStamp", $exif)) {
		$dateHTML = exif_date_to_timestamp($exif["GPSDateStamp"]);
	}

	$exifHTML = "{$dateHTML}";

	return $exifHTML;
}

function exif_date_to_timestamp($date) {
	//	Take the first 10 characters e.g. 2017:08:25 and turn it into a date
	$datestring = ( substr( str_replace(":", "-", $date) ,0, 10 ));
	$datetime = new DateTime($datestring);
	$time = $datetime->format('c');
	$human = $datetime->format('jS') . " " . $datetime->format('F') . " " . $datetime->format('Y');
	$dateHTML = "<time datetime=\"{$time}\">{$human}</time>";
	return $dateHTML;
}

function get_path_from_hash($sha1, $full = true) {
	$directory = substr($sha1,0,1);
	$subdirectory = substr($sha1,1,1);
	$photo_path = "photos/".$directory."/".$subdirectory."/";
	
	if($full) {
		return $photo_path.$sha1.".jpg";
	}
	
	return $photo_path;
}

function get_place_name($latitude, $longitude) {
	// https://nominatim.openstreetmap.org/reverse?format=json&lat=51.522221&lon=-0.125833&zoom=18&addressdetails=1
	$geocode_api_key = OPENCAGE_API_KEY;

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
	$address = $locationData->results[0]->formatted;

	return htmlspecialchars($address, ENT_NOQUOTES);
}
