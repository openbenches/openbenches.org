<?php
require_once ('config.php');

//	Set up the database connection
$mysqli = new mysqli(DB_IP, DB_USER, DB_PASS, DB_TABLE);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

if (!$mysqli->set_charset("utf8")) {
	printf("Error loading character set utf8: %s\n", $mysqli->error);
	exit();
}

function insert_bench($lat, $long, $inscription, $userID)
{
	$inscription = htmlspecialchars($inscription, ENT_NOQUOTES);

	global $mysqli;
	$insert_bench = $mysqli->prepare(
		"INSERT INTO `benches`
		       (`benchID`,`latitude`,`longitude`,`inscription`,`description`,`present`,`published`, `added`,  `userID`)
		VALUES (NULL,      ?,        ?,           ?,           '',           '1'  ,    '1', CURRENT_TIMESTAMP, ?      )");
	$insert_bench->bind_param('ddsi', $lat, $long, $inscription, $userID);
	$insert_bench->execute();
	$resultID = $insert_bench->insert_id;
	if ($resultID) {
		return $resultID;
	} else {
		return null;
	}
}

function edit_bench($lat, $long, $inscription, $benchID)
{
	$inscription = htmlspecialchars($inscription, ENT_NOQUOTES);

	global $mysqli;
	$edit_bench = $mysqli->prepare(
		"UPDATE `benches`
		    SET `latitude` =    ?,
		        `longitude` =   ?,
		        `inscription` = ?
		  WHERE `benches`.`benchID` = ?");
	$edit_bench->bind_param('ddsi', $lat, $long, $inscription, $benchID);
	$edit_bench->execute();
	$edit_bench->close();

	return true;
}

function insert_media($benchID, $userID, $sha1, $licence = "CC BY-SA 4.0", $import=null)
{
	global $mysqli;
	$insert_media = $mysqli->prepare(
		'INSERT INTO `media`
		       (`mediaID`,`benchID`,`userID`,`sha1`, `licence`, `importURL`)
		VALUES (NULL,      ?,        ?,       ?,      ?,         ?         );'
	);

	$insert_media->bind_param('iisss', $benchID, $userID, $sha1, $licence, $import);
	$insert_media->execute();
	$resultID = $insert_media->insert_id;
	if ($resultID) {
		return $resultID;
	} else {
		return null;
	}
}

function insert_user($provider, $providerID, $name)
{
	global $mysqli;

	$insert_user = $mysqli->prepare("INSERT INTO `users`
		       (`userID`, `provider`, `providerID`, `name`)
		VALUES (NULL,     ?,           ?,            ?);"
	);

	$insert_user->bind_param('sss', $provider, $providerID, $name);

	$insert_user->execute();

	$resultID = $insert_user->insert_id;
	$insert_user->close();
	if ($resultID) {
		return $resultID;
	} else {
		return null;
	}
}

function get_nearest_benches($lat, $long, $distance=0.5, $limit=20)
{
	global $mysqli;

	$get_benches = $mysqli->prepare(
		"SELECT
			(
				6371 * ACOS(COS(RADIANS(?)) *
				COS(RADIANS(latitude)) *
				COS(RADIANS(longitude) -
				RADIANS(?)) +
				SIN(RADIANS(?)) *
				SIN(RADIANS(latitude)))
			)
			AS distance, benchID, latitude, longitude, inscription, published
		FROM
			benches
		WHERE published = true
		HAVING distance < ?
		ORDER BY distance
		LIMIT 0 , ?");

	$get_benches->bind_param('ddddd', $lat, $long, $lat, $distance, $limit );
	$get_benches->execute();

	/* bind result variables */
	$get_benches->bind_result($dist, $benchID, $benchLat, $benchLong, $benchInscription, $published);

	# Build GeoJSON feature collection array
	$geojson = array(
		'type'      => 'FeatureCollection',
		'features'  => array()
	);
	# Loop through rows to build feature arrays
	while($get_benches->fetch()) {
		$feature = array(
			'id' => $benchID,
			'type' => 'Feature',
			'geometry' => array(
				'type' => 'Point',
				# Pass Longitude and Latitude Columns here
				'coordinates' => array($benchLong, $benchLat)
			),
			# Pass other attribute columns here
			'properties' => array(
				'popupContent' => $benchInscription,
			),
		);
		# Add feature arrays to feature collection array
		array_push($geojson['features'], $feature);
	}

	return json_encode($geojson, JSON_NUMERIC_CHECK);
}

function get_bench($benchID){
	return get_all_benches($benchID);
}

function get_all_benches($id = 0)
{
	global $mysqli;

	if(0==$id){
		$benchQuery = ">";
	} else {
		$benchQuery = "=";
	}

	$get_benches = $mysqli->prepare(
		"SELECT benchID, latitude, Longitude, inscription, published FROM benches
		WHERE published = true
		AND benchID {$benchQuery} ?
		LIMIT 0 , 1024");

	$get_benches->bind_param('i', $id);
	$get_benches->execute();

	/* bind result variables */
	$get_benches->bind_result($benchID, $benchLat, $benchLong, $benchInscription, $published);

	# Build GeoJSON feature collection array
	$geojson = array(
		'type'      => 'FeatureCollection',
		'features'  => array()
	);
	# Loop through rows to build feature arrays
	while($get_benches->fetch()) {
		$feature = array(
			'id' => $benchID,
			'type' => 'Feature',
			'geometry' => array(
				'type' => 'Point',
				# Pass Longitude and Latitude Columns here
				'coordinates' => array($benchLong, $benchLat)
			),
			# Pass other attribute columns here
			'properties' => array(
				'popupContent' => nl2br($benchInscription),
			),
		);
		# Add feature arrays to feature collection array
		array_push($geojson['features'], $feature);
	}
	return $geojson;
	// return json_encode($geojson, JSON_NUMERIC_CHECK);
}

function get_image($benchID, $full = false)
{
	global $mysqli;

	$get_media = $mysqli->prepare(
		"SELECT sha1, userID, importURL, licence FROM media
		WHERE benchID = ?
		LIMIT 0 , 1");

	$get_media->bind_param('i',  $benchID );
	$get_media->execute();
	/* bind result variables */
	$get_media->bind_result($sha1, $userID, $importURL, $licence);

	$html = "";

	# Loop through rows to build feature arrays
	while($get_media->fetch()) {
		$get_media->close();
		$userString = get_user($userID);
		if(null != $importURL) {
			$source = "<a href='{$importURL}'>Image Source</a>";
		}
		$licenceHTML = get_licence($licence);

		if ($full) {
			$imageLink = "/image/{$sha1}";
		} else {
			$imageLink = "/bench/{$benchID}";
		}
		$html .= "<a href='{$imageLink}'><img src='/image/{$sha1}/640' id='proxy-image' class='hand-drawn'/></a><br>{$licenceHTML} {$source}";
		break;
	}

	return $html;
}

function get_image_url($benchID)
{
	global $mysqli;

	$get_media = $mysqli->prepare(
		"SELECT sha1 FROM media
		WHERE benchID = ?
		LIMIT 0 , 1");

	$get_media->bind_param('i',  $benchID );
	$get_media->execute();
	/* bind result variables */
	$get_media->bind_result($sha1);

	$url = "";

	# Loop through rows to build feature arrays
	while($get_media->fetch()) {
		$url = "/image/{$sha1}";
		$get_media->close();
		break;
	}

	return $url;
}

function get_user_from_bench($benchID) {
	global $mysqli;
	$get_user_from_bench = $mysqli->prepare(
		"SELECT userID FROM benches
		WHERE benchID = ?
		LIMIT 0 , 1");

	$get_user_from_bench->bind_param('i',  $benchID);
	$get_user_from_bench->execute();
	/* bind result variables */
	$get_user_from_bench->bind_result($userID);

	# Loop through rows to build feature arrays
	while($get_user_from_bench->fetch()) {
		$get_user_from_bench->close();
		return get_user($userID);
		die();
	}
}

function get_user($userID)
{
	global $mysqli;
	$get_user = $mysqli->prepare(
		"SELECT provider, providerID, name FROM users
		WHERE userID = ?
		LIMIT 0 , 1");


	$get_user->bind_param('i',  $userID);
	$get_user->execute();
	/* bind result variables */
	$get_user->bind_result($provider, $providerID, $name);

	$userString = "";

	# Loop through rows to build feature arrays
	while($get_user->fetch()) {
		if ("anon" != $provider){
			$name = htmlspecialchars($name);
			$userString .= " From <img src='/images/{$provider}.svg' width='50' /> {$name}";
		} else {
			$userString = "";
		}
	}
	$get_user->close();
	return $userString;
}

function get_licence($licenceID)
{
	global $mysqli;

	$get_licence = $mysqli->prepare(
		"SELECT longName, url FROM licences
		WHERE shortName = ?
		LIMIT 0 , 1");

	$get_licence->bind_param('s',  $licenceID );
	$get_licence->execute();
	/* bind result variables */
	$get_licence->bind_result($longName, $url);

	$html = "";

	# Loop through rows to build feature arrays
	while($get_licence->fetch()) {
		$get_licence->close();
		$longName = htmlspecialchars($longName);
		$licenceID = htmlspecialchars($licenceID);
		$html .= "<a href='{$url}' title='{$longName}'>{$licenceID}</a>";
		break;
	}

	return $html;
}

function get_admin_list()
{
	global $mysqli;

	$get_list = $mysqli->prepare("SELECT `benchID`, `inscription` FROM `benches` ORDER BY `benchID` DESC LIMIT 0 , 512");

	$get_list->execute();
	/* bind result variables */
	$get_list->bind_result($benchID, $inscription);

	$html = "<ul>";

	# Loop through rows to build feature arrays
	while($get_list->fetch()) {
		//get_edit_key
		$bench   = $benchID;
		$key     = urlencode(get_edit_key($bench));
		$inscrib = nl2br(htmlspecialchars($inscription, ENT_HTML5, UTF-8, false));
		$html   .= "<li>{$bench} <a href='/edit/{$bench}/{$key}'>{$inscrib}</a></li>";
	}

	return $html .= "</ul>";
}
