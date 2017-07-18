<?php
require_once ('mysql.php');

header('Content-type: text/javascript');

if (null != $params[2]){
	$geojson = get_bench($params[2]);
} else {
	$geojson = get_all_benches();
}

echo "var benches = " . json_encode($geojson, JSON_NUMERIC_CHECK);
