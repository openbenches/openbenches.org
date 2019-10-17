<?php
require_once ("mysql.php");

if( isset($_GET["userID"]) )    { $userID    = $_GET["userID"]; }    else { $userID    = null;}
if( isset($_GET["format"]) )    { $format    = $_GET["format"]; }    else { $format    = null;}

if (null == $userID) {
	//	Return all users
	$userJSON = get_all_users();
} else {
	//	Return a specific user
	$userJSON[$userID] = get_user((int)$userID);
}

if ("raw" == $format) {
	//	Pure JSON
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($userJSON);
} else {
	//	Suitable for JavaScript
	header('Content-type: text/javascript; charset=utf-8');
	echo "var users = " . json_encode($userJSON);
}
