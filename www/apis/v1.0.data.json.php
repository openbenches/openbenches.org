<?php
ini_set( 'serialize_precision', -1 );	//	https://stackoverflow.com/questions/42981409/php7-1-json-encode-float-issue
require_once ('mysql.php');

if( isset($_GET["bench"]) )     { $benchID   = $_GET["bench"]; }     else { $benchID   = null;}
if( isset($_GET["format"]) )    { $format    = $_GET["format"]; }    else { $format    = null;}
if( isset($_GET["latitude"]) )  { $latitude  = $_GET["latitude"]; }  else { $latitude  = null;}
if( isset($_GET["longitude"]) ) { $longitude = $_GET["longitude"]; } else { $longitude = null;}
if( isset($_GET["radius"]) )    { $radius    = $_GET["radius"]; }    else { $radius    = null;}
if( isset($_GET["truncated"]) ) { $truncated = $_GET["truncated"]; } else { $truncated = null;}
if( isset($_GET["userID"]) )    { $userID    = $_GET["userID"]; }    else { $userID    = null;}
if( isset($_GET["provider"]) )  { $provider  = $_GET["provider"]; }  else { $provider  = null;}
if( isset($_GET["media"]) )     { $media     = filter_var($_GET['media'], FILTER_VALIDATE_BOOLEAN); } else { $media = null;}

if ( "true" == $truncated or null == $truncated) {
	$truncated = true;
} else {
	$truncated = false;
}

if (null != $userID) {
	$geojson = get_user_map($userID);
} else if (null != $latitude && null != $longitude && null != $radius) {
	$geojson = get_nearest_benches($latitude, $longitude, $radius, 20, $truncated);
} else if (null != $benchID){
	$geojson = get_bench($benchID, $truncated, $media);
} else {
	$geojson = get_all_benches(0, true, $truncated, $media);
}


if ("raw" == $format) {
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($geojson, JSON_NUMERIC_CHECK);
} else {
	header('Content-type: text/javascript; charset=utf-8');
	echo "var benches = " . json_encode($geojson, JSON_NUMERIC_CHECK);
}

die();
