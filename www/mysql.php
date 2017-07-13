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

function insert_media($benchID, $userID, $sha1)
{
	global $mysqli;
	$insert_media = $mysqli->prepare(
		'INSERT INTO `media`
		       (`mediaID`,`benchID`,`userID`,`sha1`)
		VALUES (NULL,      ?,        ?,       ?    );');

	$insert_media->bind_param('iis', $benchID, $userID, $sha1);
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

	$search_user = $mysqli->prepare("SELECT `userID` FROM `users` WHERE `provider` LIKE ? AND `providerID` LIKE ?");

	$search_user->bind_param('ss', $provider, $providerID);
	$search_user->execute();
	$search_user->bind_result($userID);
	# Loop through rows to build feature arrays
	while($search_user->fetch()) {
		if ($userID){
			return $userID;
		}
	}

	$insert_user = $mysqli->prepare("INSERT INTO `users`
		       (`userID`, `provider`, `providerID`, `name`)
		VALUES (NULL,     ?, ?, ?);");

	$insert_user->bind_param('sss', $provider, $providerID, $name);

	$insert_user->execute();

	$resultID = $insert_user->insert_id;
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

function get_all_benches()
{
	global $mysqli;

	$get_benches = $mysqli->prepare(
		"SELECT benchID, latitude, Longitude, inscription, published FROM benches
		WHERE published = true
		LIMIT 0 , 256");

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

function get_image($benchID)
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
			$userString = "<a href='{$importURL}'>{$userString}</a>";
		}
		$licenceHTML = get_licence($licence);
		$html .= "<img src='image.php?id={$sha1}' id='proxy-image' class='hand-drawn'/><br>uploaded by {$userString} {$licenceHTML}";
		break;
	}

	return $html;
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
		$userString .= "{$provider}/{$providerID} {$name}";
	}
	$get_user->close();
	return htmlspecialchars($userString);
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
