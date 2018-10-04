<?php
ini_set( 'serialize_precision', -1 );	//	https://stackoverflow.com/questions/42981409/php7-1-json-encode-float-issue
require_once ('mysql.php');

$benchID   = $_GET["bench"];
$format    = $_GET["format"];
$latitude  = $_GET["latitude"];
$longitude = $_GET["longitude"];
$radius    = $_GET["radius"];
$forMap    = $_GET["forMap"];

if ( "true" == $forMap) {
  $forMap = True;
} else {
  $forMap = False;
}

if (null != $latitude && null != $longitude && null != $radius) {
	$geojson = get_nearest_benches($latitude, $longitude, $radius, 20, $forMap);
} else if (null != $benchID){
	$geojson = get_bench($benchID,$forMap);
} else {
	$geojson = get_all_benches(0, true, $forMap);
	
}


if ("raw" == $format) {
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($geojson, JSON_NUMERIC_CHECK);
} else {
	header('Content-type: text/javascript; charset=utf-8');
	echo "var benches = " . json_encode($geojson, JSON_NUMERIC_CHECK);
}

die();
