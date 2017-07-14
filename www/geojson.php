<?php
// error_reporting( E_ALL );
// ini_set( "display_errors", 1 );
require_once ('mysql.php');

header('Content-type: text/javascript');
// echo "var benches = ".get_nearest_benches(51, -1.23, 100, $limit=2);

if ($_GET["benchID"]){
	$geojson = get_bench($_GET["benchID"]);
} else {
	$geojson = get_all_benches();
}


// var_export($geojson);

echo "var benches = " . json_encode($geojson, JSON_NUMERIC_CHECK);
