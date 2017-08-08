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

function tweet_bench($benchID, $sha1=null, $inscription=null, $latitude=null, $longitude=null, $license=null){
	//	Send Tweet
	\Codebird\Codebird::setConsumerKey(OAUTH_CONSUMER_KEY, OAUTH_CONSUMER_SECRET);
	$cb = \Codebird\Codebird::getInstance();
	$cb->setToken(OAUTH_ACCESS_TOKEN, OAUTH_TOKEN_SECRET);

	//	Add the image
	if(null!=$sha1){
		$reply = $cb->media_upload(['media' => "https://openbenches.org/image/{$sha1}/2048"]);
		$media_ids[] = $reply->media_id_string;
		$media_ids = implode(',', $media_ids);
	} else {
		$media_ids = null;
	}

	$tweet_inscription = substr($inscription, 0, 100);
	if (strlen($inscription) > 100) {
		$tweet_inscription .= "…";
	}

	$params = [
		'status'    => "{$tweet_inscription}\nhttps://openbenches.org/bench/{$benchID}\n{$license}",
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

	var map = L.map('map', {
		center: [{$lat},{$long}],
		zoom: {$zoom},
	});

	var baseMaps = {
		"Map View": grayscale,
		"Satellite View": satellite
	};

	grayscale.addTo(map);

	L.control.layers(baseMaps).addTo(map);

	var markers = L.markerClusterGroup({
		maxClusterRadius: 30
	});
</script>
EOT;

	echo $mapJavaScript;
}
