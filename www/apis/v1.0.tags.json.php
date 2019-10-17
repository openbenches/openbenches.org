<?php
require_once ("mysql.php");

if( isset($_GET["tagText"]) ) { $tagText = $_GET["tagText"]; } else { $tagText = null;}
if( isset($_GET["format"])  ) { $format  = $_GET["format"]; }  else { $format = null;}

if (null == $tagText) {
	//	Return all tags
	$tagsJSON = get_tags();
	$type = "tags";
}

if ("raw" == $format) {
	//	Pure JSON
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($tagsJSON);
} else {
	//	Suitable for JavaScript
	header('Content-type: text/javascript; charset=utf-8');
	echo "var {$type} = " . json_encode($tagsJSON);
}
