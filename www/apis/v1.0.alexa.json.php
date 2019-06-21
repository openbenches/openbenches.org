<?php
ini_set( 'serialize_precision', -1 );	//	https://stackoverflow.com/questions/42981409/php7-1-json-encode-float-issue
require_once ('mysql.php');

//	Raw JSON or padded
if( isset($_GET["format"]) )    { $format    = $_GET["format"]; }    else { $format    = null;}

if( isset($_GET["random"]) )  {
	list ($benchID, $benchLat, $benchLong, $benchAddress, $benchInscription, $published) = get_random_bench();
	$speech = array('speech' => "{$benchInscription}. Located at {$benchAddress}" );
}
else if( isset($_GET["count"]) ) {
	$count = get_bench_count();
	$formatter = new NumberFormatter("en-gb", NumberFormatter::SPELLOUT);
	$count_english = $formatter->format($count);
	$speech = array('speech' => "There are currently {$count_english} benches on the website.");
}
else if( isset($_GET["latest"]) ) {
	list ($benchID, $benchLat, $benchLong, $benchAddress, $benchInscription, $published) = get_latest_bench();
	$speech = array('speech' => "{$benchInscription}. Located at {$benchAddress}" );
}

if ("raw" == $format) {
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($speech, JSON_NUMERIC_CHECK);
} else {
	header('Content-type: text/javascript; charset=utf-8');
	echo "var alexa = " . json_encode($speech, JSON_NUMERIC_CHECK);
}

die();
