<?php
	require_once ("config.php");
	require_once ("mysql.php");

	function convertToReadableSize( $size ) {
		$base = log($size) / log(1024);
		$suffix = array("B", "KB", "MB", "GB", "TB");
		$f_base = floor($base);
		return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
	}
	//	Routing page
	// Requests come in like example.com/bench/1234
	//	Strip out any gets
	$params = explode("/", $_SERVER["HTTP_HOST"].explode('?', $_SERVER["REQUEST_URI"], 2)[0]);
	$GLOBALS["params"] = $params;

	//	Available pages
	$pages = array("bench", "add", "image", "benchimage", "flickr", "edit",
	               "search", "sitemap.xml", "data.json", "login", "logout",
	               "leaderboard", "user", "rss", "oembed", "api", "tag",
	               "location", "colophon", "offline");

	if(in_array($params[1], $pages)) {
		include($params[1].".php");
	} else {
		include("front.php");
	}

	//	Memory logger
	$mem = "\n\n". convertToReadableSize( memory_get_peak_usage() ) . " | " . $_SERVER["QUERY_STRING"] . " " . $_SERVER['HTTP_USER_AGENT'];
	file_put_contents( "json/mem.log", $mem, FILE_APPEND); 
	die();
