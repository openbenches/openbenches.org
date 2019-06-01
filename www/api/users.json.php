<?php
ini_set( 'serialize_precision', -1 );	//	https://stackoverflow.com/questions/42981409/php7-1-json-encode-float-issue
require_once ("../mysql.php");

if( isset($_GET["userID"]) )    { $userID    = $_GET["userID"]; }    else { $userID    = null;}
if( isset($_GET["format"]) )    { $format    = $_GET["format"]; }    else { $format    = null;}

if (null != $userID) {
	if (0 == $userID || "all" == $userID){
		$userJSON = get_user(0);
	} else {
		$userJSON[$userID] = get_user((int)$userID);
	}
} else {
	die();
}

if ("raw" == $format) {
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($userJSON, JSON_NUMERIC_CHECK);
} else {
	header('Content-type: text/javascript; charset=utf-8');
	echo "var users = " . json_encode($userJSON, JSON_NUMERIC_CHECK);
}

die();
