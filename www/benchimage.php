<?php
require_once ('config.php');

//	Set up the database connection
$mysqli = new mysqli(DB_IP, DB_USER, DB_PASS, DB_TABLE);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

$benchID = $_GET["benchID"];
echo get_image($benchID);

function get_image($benchID)
{
	global $mysqli;

	$get_media = $mysqli->prepare(
		"SELECT sha1, userID FROM media
		WHERE benchID = ?
		LIMIT 0 , 1");

	$get_media->bind_param('i',  $benchID );
	$get_media->execute();
	/* bind result variables */
	$get_media->bind_result($sha1, $userID);

	$html = "";

	# Loop through rows to build feature arrays
	while($get_media->fetch()) {
		$get_media->close();
		$userString = get_user($userID);
		$html .= "<img src='image.php?id={$sha1}' id='proxy-image' class='hand-drawn'/><br>uploaded by {$userString}";
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

	return $userString;
}
