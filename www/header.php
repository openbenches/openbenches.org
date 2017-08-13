<?php
require_once ('config.php');
require_once ('mysql.php');
require_once ('functions.php');


$benchID = $params[2];

?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>OpenBenches - by @edent &amp; @summerbeth</title>

	<link rel="apple-touch-icon"      sizes="180x180" href="/apple-touch-icon.png?cache=2017-08-08">
	<link rel="icon" type="image/png" sizes="32x32"   href="/favicon-32x32.png?cache=2017-08-08">
	<link rel="icon" type="image/png" sizes="16x16"   href="/favicon-16x16.png?cache=2017-08-08">
	<link rel="manifest"                              href="/manifest.json?cache=2017-08-08">
	<link rel="mask-icon"             color="#5bbad5" href="/safari-pinned-tab.svg?cache=2017-08-08">
	<link rel="shortcut icon"                         href="/favicon.ico?cache=2017-08-08">
	<meta name="theme-color" content="#ffffff">

	<meta name="twitter:card"                            content="summary_large_image">
	<meta name="twitter:site"                            content="@openbenches">
	<meta name="twitter:title"       property="og:title" content="OpenBenches">
	<meta name="twitter:description" property="og:url"   content="https://openbenches.org/bench/<?php echo $benchID; ?>">
	<meta name="twitter:image"       property="og:image" content="https://openbenches.org<?php echo get_image_url($benchID); ?>/640">
	<!-- The properties og:type and og:description have been added to enabe Rich Pins https://developers.pinterest.com/docs/rich-pins/articles/? -->	
	<meta                            property="og:type"  content="article">
	<meta                            property="og:description"  content="Check out this bench!">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

	<link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet">
	<link rel="stylesheet" href="/style.css?cache=<?php echo rand(); ?>"/>
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.1.0/dist/leaflet.css" integrity="sha512-wcw6ts8Anuw10Mzh9Ytw4pylW8+NAD4ch3lqm9lzAsTxg0GFeJgoAtxuCLREZSC5lUXdVyo/7yfsqFjQ4S+aKw==" crossorigin=""/>
	<script src="https://unpkg.com/leaflet@1.1.0/dist/leaflet.js" integrity="sha512-mNqn2Wg7tSToJhvHcqfzLMU6J4mkOImSPTxVZAdo+lcPlk+GhZmYgACEe0x35K7YzW1zJ7XyJV/TT1MrdXvMcA==" crossorigin=""></script>

	<script src="https://unpkg.com/leaflet.markercluster@1.0.6/dist/leaflet.markercluster.js" crossorigin=""></script>
	<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.0.6/dist/MarkerCluster.css">
	<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.0.6/dist/MarkerCluster.Default.css">
</head>
<body>
	<h1><a href="/"><img src="/images/openbencheslogo.svg" id="header-image">OpenBenches</a></h1>
