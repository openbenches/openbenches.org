<?php
	require_once ('config.php');
	require_once ('mysql.php');
	// Requests come in like example.com/bench/1234
	$params = explode("/", $_SERVER['REQUEST_URI']);
	$GLOBALS["params"] = $params;

	//
	$benchID = $params[2];
	$image_url = "https://openbenches.org" . get_image_url($benchID);

	//	Available pages
	$pages = array("bench", "add", "image", "benchimage", "flickr", "edit", "admin",
	               "search", "sitemap.xml", "data.json");

	if(in_array($params[1], $pages)) {
		include($params[1].".php");
		die();
	} else {
		include("front.php");
	}
