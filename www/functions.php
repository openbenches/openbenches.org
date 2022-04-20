<?php
require_once ("codebird.php");
require_once ("config.php");
require_once (__DIR__ . '/vendor/autoload.php');
use Auth0\SDK\Auth0;
use Auth0\SDK\Configuration\SdkConfiguration;
use Auth0\SDK\Utility\HttpResponse;

use Twitter\Text\Parser;


function get_twitter_details(){
	if(!isset($_SESSION)) { session_start(); }

	\Codebird\Codebird::setConsumerKey(ADMIN_CONSUMER_KEY, ADMIN_CONSUMER_SECRET);
	$cb = \Codebird\Codebird::getInstance();

	if (isset($_SESSION['oauth_token']) && isset($_SESSION['oauth_token_secret'])) {
		// assign access token on each page load
		$cb->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
		$reply = (array) $cb->account_verifyCredentials();

		if (isset($reply["errors"]) ){
			//	If the authorization hasn't worked, clear the session variables and start again
			$_SESSION['oauth_token'] = null;
			$_SESSION['oauth_token_secret'] = null;
			$_SESSION['oauth_verify'] = null;
			// send to same URL, without oauth GET parameters
			// header('Location: ' . basename(__FILE__));
			return null;
		}
		// var_export($reply);
		// die();
		//	Get the user's ID & name
		$id_str = $reply["id_str"];
		$screen_name = $reply["screen_name"];
		// echo "You are {$screen_name} with ID {$id_str}";
		return array($id_str, $screen_name);
	}
	return null;
}

function get_user_details($raw = true) {
	if (null == AUTH0_DOMAIN) {
		return null;
	}
	$auth0 = new Auth0([
		'domain'              => AUTH0_DOMAIN,
		'clientId'            => AUTH0_CLIENT_ID,
		'clientSecret'        => AUTH0_CLIENT_SECRET,
		'redirectUri'         => AUTH0_CALLBACK,
		'audience'            => AUTH0_AUDIENCE,
		'scope'               => array('openid profile'),
		'persistIdToken'      => true,
		'persistAccessToken'  => true,
		'persistRefreshToken' => true,
		'cookieSecret'        => AUTH0_COOKIE_SECRET,
	]);

	if(!isset($_SESSION)) { session_start(); }
	$userInfo = $auth0->getUser();
	if (!$userInfo) {
		// We have no user info
		return null;
	} else {
		// User is authenticated
		$username = explode("|", $userInfo["sub"]);

		if ($raw) {
			return array(
								$username[0],
								$username[1],
								$userInfo['nickname']
							);
		} else {
			return array(
								htmlspecialchars($username[0]),
								htmlspecialchars($username[1]),
								htmlspecialchars($userInfo['nickname'])
							);
		}

	}
}

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
		$img = new \Imagick($file);
		$info = $img->getImageProperties("exif:*");
		$img->clear();

		if ($info !== false) {
				$direction = array('N', 'S', 'E', 'W');
				if (isset($info['exif:GPSLatitude'], $info['exif:GPSLongitude'], $info['exif:GPSLatitudeRef'],
				    $info['exif:GPSLongitudeRef']) &&
					in_array($info['exif:GPSLatitudeRef'], $direction) && in_array($info['exif:GPSLongitudeRef'], $direction)) {

					//	https://stackoverflow.com/questions/19347005/how-can-i-explode-and-trim-whitespace
					$gpsLat = preg_split ('/(\s*,*\s*)*,+(\s*,*\s*)*/',$info['exif:GPSLatitude']);
					$lat_degrees_a = explode('/',$gpsLat[0]);
					$lat_minutes_a = explode('/',$gpsLat[1]);
					$lat_seconds_a = explode('/',$gpsLat[2]);

					$gpsLng = preg_split ('/(\s*,*\s*)*,+(\s*,*\s*)*/',$info['exif:GPSLongitude']);
					$lng_degrees_a = explode('/',$gpsLng[0]);
					$lng_minutes_a = explode('/',$gpsLng[1]);
					$lng_seconds_a = explode('/',$gpsLng[2]);

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
					$lat = $info['exif:GPSLatitudeRef'] == 'S' ? $lat * -1 : $lat;
					$lng = $info['exif:GPSLongitudeRef'] == 'W' ? $lng * -1 : $lng;

					return array(
						'lat' => round($lat,10),
						'lng' => round($lng,10)
					);
				}
		}
	}

	return false;
}

function tweet_bench($benchID, $mediaURLs=null, $inscription=null,
                     $latitude=null, $longitude=null, $license=null,
                     $user_provider=null, $user_name=null){

	//	Send Tweet
	\Codebird\Codebird::setConsumerKey(OAUTH_CONSUMER_KEY, OAUTH_CONSUMER_SECRET);
	$cb = \Codebird\Codebird::getInstance();
	$cb->setToken(OAUTH_ACCESS_TOKEN, OAUTH_TOKEN_SECRET);

	//	Add the images
	if(null!=$mediaURLs){

		$media_ids = array();

		foreach ($mediaURLs as $file) {
			try {
				// upload all media files
				$reply = $cb->media_upload(['media' => $file]);
				// and collect their IDs
				$media_ids[] = $reply->media_id_string;
			} catch (\Exception $e) {
				error_log("Twitter Upload $e");
			}
		}
		$media_ids = implode(',', $media_ids);
	}

	//	Tweet length is now 280
	$tweet_length = 280;

	//	Tweet will end with "℅ @twittername"
	if ("twitter" == $user_provider) {
		$from = "℅ @{$user_name}   "; //	Paranoia. A few spaces of padding which will be trimmed before tweeting.
	} else {
		//	Might use this for Github / Facebook names in future
		$from = "   ";
	}

	$domain = $_SERVER['SERVER_NAME'];
	$tweet_url = "https://{$domain}/bench/{$benchID}";

	// To go after the inscription
	$tweet_end = "\n{$tweet_url}\n{$license}\n{$from}";

	//	Left pad the inscription based on the after-matter's length
	$padded_inscription = $tweet_end . "\n" . $inscription;

	//	Run the Twitter weighted length algorithm
	$padded_inscription_data = \Twitter\Text\Parser::create()->parseTweet($padded_inscription);

	//	If it is too long, truncate it
	if ($padded_inscription_data->weightedLength > $tweet_length) {
		$padded_inscription = mb_substr($padded_inscription, 0, $padded_inscription_data->validRangeEnd);
		//	Add ellipsis to show truncation
		$padded_inscription .= "…";
	}

	//	Remove padding and add after-matter
	$text_array = explode("\n", $padded_inscription, 5);
	$tweet_text = trim($text_array[4] . $tweet_end);

	$params = [
		'status'    => $tweet_text,
		'lat'       => $latitude,
		'long'      => $longitude,
		'media_ids' => $media_ids,
		'weighted_character_count' => 'true'
	];
	try {
		$reply = $cb->statuses_update($params);
	} catch (\Exception $e) {
		error_log("Twitter: $e");
		error_log(print_r($reply, TRUE));
		error_log("Status: {$tweet_text}");
	}

	//	Error code back from Twitter
	if ($reply->httpstatus != 200 ) {
		error_log(print_r($reply, TRUE));
		error_log("Status: {$tweet_text}");
	}
}

//	Defaults to a view of the UK
function get_map_javascript($lat = "54.5", $long="-4", $zoom = "5") {
	$mapbox = MAPBOX_API_KEY;
	$mapJavaScript = <<<EOT
<script>

	var Stadia_Outdoors = L.tileLayer('https://tiles.stadiamaps.com/tiles/outdoors/{z}/{x}/{y}{r}.png', {
		minZoom: 2,
		maxZoom: 19,
		attribution: 'Map data © <a href="https://stadiamaps.com/">Stadia Maps</a>, © <a href="https://openmaptiles.org/">OpenMapTiles</a> © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors',
		id: 'stadia.outdoors'
	});

	var OpenStreetMap_Mapnik = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		minZoom: 2,
		maxZoom: 19,
		attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
		id: 'osm.mapnik'
	});

	var ESRI_Satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}.jpeg?token=AAPKda6317ae879941ac8a7fef3a2198a372FZzFP0nkG-58ivR1JcgsuEhn8fB3NS62pZxG4_o1g59yEYHjqTJ1WeX_n9X_k6qS', {
		minZoom: 2,
		maxZoom: 19,
		attribution: '© <a href="https://www.esri.com/">i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community</a>',
		id: 'esri.satellite'
	});

	var map = L.map('map');

	map.on("load", function () {
		if (window.location.hash != "") {
			if(window.location.hash.indexOf("/") > -1)
			{
				var hashArray = window.location.hash.substr(1).split("/");
				if(hashArray.length >= 2)
				{
					var hashLat = hashArray[0];
					var hashLng = hashArray[1];
					var hashZoom = 16; if(hashArray[2] != void 0){hashZoom = hashArray[2];}
					map.setView([hashLat, hashLng], hashZoom);
				}
			}
		}
	});

	map.setView([{$lat}, {$long}], {$zoom});

	var baseMaps = {
		"Map View": Stadia_Outdoors,
		"Mapnik": OpenStreetMap_Mapnik,
		"Satellite View": ESRI_Satellite
	};

	// Rotate between mapping providers depending on date
	var day = new Date().getDate();
	if (day % 2 == 0) {
		Stadia_Outdoors.addTo(map);
	} else {
		OpenStreetMap_Mapnik.addTo(map);
	}

	L.control.layers(baseMaps).addTo(map);

	var markers = L.markerClusterGroup({
		maxClusterRadius: 29
	});
</script>
EOT;

	echo $mapJavaScript;
}

function get_exif_from_file($sha1) {
	$filename = get_path_from_hash($sha1, $full = true);
	$exif_data = array();
	$exif_data["datetime"] = null;
	$exif_data["make"]     = null;
	$exif_data["model"]    = null;

	try {
		$img = new \Imagick($filename);
		$exif = $img->getImageProperties();
		$img->clear();
	} catch (\Exception $e) {
		return null;
	}

	if (array_key_exists("exif:GPSDateStamp", $exif)) {
		$date = exif_date_to_timestamp($exif["exif:GPSDateStamp"], $sha1);
	} elseif (array_key_exists("exif:DateTimeOriginal", $exif)) {
		$date = exif_date_to_timestamp($exif["exif:DateTimeOriginal"], $sha1);
	} elseif (array_key_exists("exif:DateTime", $exif)) {
		$date = exif_date_to_timestamp($exif["exif:DateTime"], $sha1);
	} elseif (array_key_exists("exif:DateTimeDigitized", $exif)) {
		$date = exif_date_to_timestamp($exif["exif:DateTimeDigitized"], $sha1);
	} else {
		$date = null;
	}
	$exif_data["datetime"] = $date;

	//	Get the make and model
	if (array_key_exists("exif:Make", $exif)) {
		$exif_data["make"]  = $exif["exif:Make"];
	}
	if (array_key_exists("exif:Model", $exif)) {
		$exif_data["model"] = $exif["exif:Model"];
	}

	return $exif_data;
}


function exif_date_to_timestamp($date) {
	$length = strlen($date);
	if ($length == 10) {
		//	2001:12:25
		$date = str_replace(":", "-", $date);
		$date = $date . " 00:00:00";
		$datetime = date_create_from_format('Y-m-d H:i:s', $date);
		return $datetime->format('Y-m-d H:i:s');
	} else {
		//	Too many different timestamp formats to do manually.
		$date_array = date_parse($date);
		return date('Y-m-d H:i:s', mktime($date_array['hour'], $date_array['minute'], $date_array['second'], $date_array['month'], $date_array['day'], $date_array['year']));
	}
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

function get_location_from_name($location) {
	//	https://api.opencagedata.com/geocode/v1/json?q=Oxford&key=
	$geocode_api_key = OPENCAGE_API_KEY;
	$location = urlencode($location);
	$geocodeAPI = "https://api.opencagedata.com/geocode/v1/json?q={$location}&no_annotations=1&key={$geocode_api_key}";
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
		$lat = $locationData->results[0]->geometry->lat;
		$lng = $locationData->results[0]->geometry->lng;
	} catch (Exception $e) {
		$loc = var_export($locationData);
		error_log("Caught $e - $loc");
		return "";
	}

	return "{$lat}/{$lng}";
}

function get_place_name($latitude, $longitude) {
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
	try {
		$address = $locationData->results[0]->formatted;
	} catch (Exception $e) {
		$loc = var_export($locationData);
		error_log("Caught $e - $loc");
		return "";
	}

	return $address;
}

function save_image($file, $media_type, $benchID, $userID) {
	$filename = $file['name'];
	$file =     $file['tmp_name'];

	if (duplicate_file($file)) {
		return "<h3>Duplicate image: {$filename}</h3>";
	}

	//	Check to see if this has the right EXIF tags for a photosphere
	if (is_photosphere($file)) {
		$media_type = "360";
	} else if ("360" == $media_type){
		//	If it has been miscategorised, remove the media type
		$media_type = null;
	}

	$sha1 = sha1_file($file);
	$photo_full_path = get_path_from_hash($sha1, true);
	$photo_path      = get_path_from_hash($sha1, false);

	//	Move media to the correct location
	if (!is_dir($photo_path)) {
		mkdir($photo_path, 0777, true);
	}
	$moved = move_uploaded_file($file, $photo_full_path);

	$dimensions = get_image_dimensions($sha1);

	if (null != $dimensions) {
		$width  = $dimensions["width"];
		$height = $dimensions["height"];
	} else {
		$width  = null;
		$height = null;
	}

	$exif = get_exif_from_file($sha1);
	$datetime = $exif["datetime"];
	$make     = $exif["make"];
	$model    = $exif["model"];

	//	Add the media to the database
	if ($moved){
		$mediaID = insert_media($benchID, $userID, $sha1, "CC BY-SA 4.0", null, $media_type, $width, $height, $datetime, $make, $model);
		return true;
	} else {
		return("<h3>Unable to move {$filename} to {$photo_full_path} - bench {$benchID} user {$userID} media {$media_type}</h3>");
	}
}

function duplicate_file($filename) {
	$sha1 = sha1_file($filename);
	$photo_full_path = get_path_from_hash($sha1, true);

	//	Does this photo already exit?
	if(file_exists($photo_full_path)){
		return true;
	}
	return false;
}

function get_image_cache($sha1, $size=IMAGE_DEFAULT_SIZE) {
	//	Generate a URL for the cached image. Can be thumbnailed.

	if (IMAGE_CACHE_PREFIX == "") {
		return "//" . $_SERVER['SERVER_NAME'] . "/image/{$sha1}/";
	}

	//	https://images.weserv.nl/docs/
	return IMAGE_CACHE_PREFIX . $_SERVER['SERVER_NAME'] . "/image/{$sha1}/" . "&w={$size}&q=" . IMAGE_CACHE_QUALITY . "&output=webp";

}

function prepare_search_query($q) {
	$q = str_replace(" ","[[:space:]]*", $q);
	$quoteTranslation=array("\"" => "[\"“”]", "“" => "[\"“]", "”" => "[\"”]", "'" => "['‘’]", "‘" => "['‘]", "’" => "['’]");
	$q = strtr($q,$quoteTranslation);
	return $q;
}

function get_image_dimensions($sha1) {
	try {
		$imagick = new \Imagick(realpath(get_path_from_hash($sha1)));
	} catch (Exception $e) {
		error_log("Image error! {$sha1} - {$e}" , 0);
		return null;
	}
	$height = $imagick->getImageHeight();
	$width  = $imagick->getImageWidth();
	$imagick->clear();

	$image_dimensions = array();

	$image_dimensions["width"]  = $width;
	$image_dimensions["height"] = $height;

	return $image_dimensions;
}

function license_to_icon($shortName){
	//	The exception which doesn't fit
	if ("CC Zero" == $shortName){
		return "cc-zero.svg";
	}

	//	Remove the version
	$version = array("1.0","2.0","3.0","4.0");
	$shortName = str_replace($version, "", $shortName);

	//	Lower case
	$shortName = strtolower($shortName);
	//	Remove trailing space
	$shortName = rtrim($shortName);
	//	Replace space
	$shortName = str_replace(" ","-",$shortName);
	//	Add file type
	return $shortName . ".svg";
}

function get_user_avatar($user_provider, $user_providerID, $user_name) {
	//	https://cloudinary.com/documentation/social_media_profile_pictures
	if("twitter"==$user_provider){
		$user_avatar = "res.cloudinary.com/" . CLOUDINARY_KEY . "/image/twitter/{$user_providerID}.jpg";
	} else if("facebook"==$user_provider){
		$user_avatar = "res.cloudinary.com/" . CLOUDINARY_KEY . "/image/facebook/{$user_providerID}.jpg";
	} else if("github"==$user_provider){
		$user_avatar = "avatars0.githubusercontent.com/u/{$user_providerID}?v=4&amp;s=48";
	} else {
		return null;
	}

	return "//{$user_avatar}";
	// if (IMAGE_CACHE_PREFIX == "") {
	// 	return "//{$user_avatar}";
	// }
	// return IMAGE_CACHE_PREFIX . $user_avatar;
}
