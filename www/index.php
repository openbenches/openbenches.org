<?php
	// Requests come in like example.com/bench/1234
	$params = explode("/", $_SERVER['REQUEST_URI']);
	$GLOBALS["params"] = $params;

	//	Available pages
	$pages = array("bench", "add", "image", "geojson", "benchimage", "flickr");

	if(in_array($params[1], $pages)) {
		include($params[1].".php");
		die();
	} else {
		include("map.php");
	}
