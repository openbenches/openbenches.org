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
if( isset($_GET["results"]) )   { $results   = $_GET["results"]; }   else { $results   = 20;}
if( isset($_GET["media"]) )     { $media     = filter_var($_GET['media'], FILTER_VALIDATE_BOOLEAN); } else { $media = null;}
if( isset($_GET["tagText"]) )   { $tagText   = $_GET["tagText"]; }   else { $tagText   = null;}
if( isset($_GET["search"]) )    { $search    = $_GET["search"]; }    else { $search    = null;}

if ( "true" == $truncated or null == $truncated) {
	$truncated = true;
} else {
	$truncated = false;
}

//	Cheap and nasty file based cacheing
function read_cache( $type ) {
	//	One Hour Cache
	$cache = date("Y-m-d-H");
	//	Make the name safe
	$type = urlencode( $type );
	//	Set the file name
	$filename = "json/{$cache}-{$type}.json";
	
	if ( file_exists( $filename ) ) {
		return json_decode( file_get_contents( $filename ) );
	} else {
		//	Delete old caches
		foreach( glob( "json/*-{$type}.json" ) as $file ) {
			unlink( $file );
		}
		return null;
	}
}

function save_cache ( $type , $geojson ) {
	//	One Hour Cache
	$cache = date("Y-m-d-H");
	$type = urlencode( $type );
	$filename = "json/{$cache}-{$type}.json";
	file_put_contents( $filename, json_encode($geojson, JSON_NUMERIC_CHECK) );
}

if (null != $userID) {
	$type = "user-{$truncated}-{$media}";
	$geojson = read_cache( $type );
	if ( null == $geojson ) {
		$geojson = get_user_map($userID, $truncated, $media);
		save_cache( $type, $geojson );
	}
} else if (null != $latitude && null != $longitude && null != $radius) {
	$geojson = get_nearest_benches($latitude, $longitude, $radius, $results, $truncated, $media);
} else if (null != $benchID){
	$type = "bench-{$benchID}-{$truncated}-{$media}";
	$geojson = read_cache( $type );
	if ( null == $geojson ) {
		$geojson = get_bench($benchID, $truncated, $media);
		save_cache( $type, $geojson );
	}
} else if (null != $tagText){
	$geojson = get_all_benches($tagText, true, $truncated, $media);
} else if (null != $search){
	$type = "search-{$search}";
	$geojson = read_cache( $type );
	if ( null == $geojson ) {
		$geojson = get_search_geojson($search);
		save_cache( $type, $geojson );
	}
} else {
	$type = "all-{$truncated}-{$media}";
	$geojson = read_cache( $type );
	if ( null == $geojson ) {
		$geojson = get_all_benches(0, true, $truncated, $media);
		save_cache( $type, $geojson );
	}
}

if ("raw" == $format) {
	header('Content-Type: application/geo+json; charset=utf-8');
	echo json_encode($geojson, JSON_NUMERIC_CHECK);
} else {
	header('Content-type: application/geo+jsont; charset=utf-8');
	echo "var benches = " . json_encode($geojson, JSON_NUMERIC_CHECK);
}
