<?php
require_once ('config.php');
require_once ('mysql.php');

$benchID = $params[2];

?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>OpenBenches ALPHA - by @edent &amp; @summerbeth</title>

	<link rel="apple-touch-icon"      sizes="180x180" href="/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32"   href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16"   href="/favicon-16x16.png">
	<link rel="manifest"                              href="/manifest.json">
	<link rel="mask-icon" color="#5bbad5"             href="/safari-pinned-tab.svg">
	<meta name="theme-color" content="#ffffff">

	<meta name="twitter:card"                            content="summary_large_image">
	<meta name="twitter:site"                            content="@openbenches">
	<meta name="twitter:title"       property="og:title" content="OpenBenches">
	<meta name="twitter:description" property="og:url"   content="https://openbenches.org/bench/<?php echo $benchID; ?>">
	<meta name="twitter:image"       property="og:image" content="https://openbenches.org<?php echo get_image_url($benchID); ?>/640">

	<link href="https://fonts.googleapis.com/css?family=Architects+Daughter" rel="stylesheet">
	<link rel="stylesheet" href="/style.css?cache=<?php echo rand(); ?>"/>
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.1.0/dist/leaflet.css" integrity="sha512-wcw6ts8Anuw10Mzh9Ytw4pylW8+NAD4ch3lqm9lzAsTxg0GFeJgoAtxuCLREZSC5lUXdVyo/7yfsqFjQ4S+aKw==" crossorigin=""/>
	<script src="https://unpkg.com/leaflet@1.1.0/dist/leaflet.js" integrity="sha512-mNqn2Wg7tSToJhvHcqfzLMU6J4mkOImSPTxVZAdo+lcPlk+GhZmYgACEe0x35K7YzW1zJ7XyJV/TT1MrdXvMcA==" crossorigin=""></script>

	<script src="https://unpkg.com/leaflet.markercluster@1.0.6/dist/leaflet.markercluster.js" crossorigin=""></script>
	<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.0.6/dist/MarkerCluster.css">
	<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.0.6/dist/MarkerCluster.Default.css">

	<a href="https://github.com/edent/openbenches" class="github-corner" aria-label="View source on Github"><svg width="80" height="80" viewBox="0 0 250 250" style="fill:#151513; color:#fff; position: absolute; top: 0; border: 0; right: 0;" aria-hidden="true"><path d="M0,0 L115,115 L130,115 L142,142 L250,250 L250,0 Z"></path><path d="M128.3,109.0 C113.8,99.7 119.0,89.6 119.0,89.6 C122.0,82.7 120.5,78.6 120.5,78.6 C119.2,72.0 123.4,76.3 123.4,76.3 C127.3,80.9 125.5,87.3 125.5,87.3 C122.9,97.6 130.6,101.9 134.4,103.2" fill="currentColor" style="transform-origin: 130px 106px;" class="octo-arm"></path><path d="M115.0,115.0 C114.9,115.1 118.7,116.5 119.8,115.4 L133.7,101.6 C136.9,99.2 139.9,98.4 142.2,98.6 C133.8,88.0 127.5,74.4 143.8,58.0 C148.5,53.4 154.0,51.2 159.7,51.0 C160.3,49.4 163.2,43.6 171.4,40.1 C171.4,40.1 176.1,42.5 178.8,56.2 C183.1,58.6 187.2,61.8 190.9,65.4 C194.5,69.0 197.7,73.2 200.1,77.6 C213.8,80.2 216.3,84.9 216.3,84.9 C212.7,93.1 206.9,96.0 205.4,96.6 C205.1,102.4 203.0,107.8 198.3,112.5 C181.9,128.9 168.3,122.5 157.7,114.1 C157.9,116.9 156.7,120.9 152.7,124.9 L141.0,136.5 C139.8,137.7 141.6,141.9 141.8,141.8 Z" fill="currentColor" class="octo-body"></path></svg></a><style>.github-corner:hover .octo-arm{animation:octocat-wave 560ms ease-in-out}@keyframes octocat-wave{0%,100%{transform:rotate(0)}20%,60%{transform:rotate(-25deg)}40%,80%{transform:rotate(10deg)}}@media (max-width:500px){.github-corner:hover .octo-arm{animation:none}.github-corner .octo-arm{animation:octocat-wave 560ms ease-in-out}}</style>
</head>
<body>

	<h1><img src="/noun_1130095_cc.png" width="50px"> OpenBenches - ALPHA</h1>
	<span id="forkongithub"><a href="https://github.com/edent/openbenches">Fork me on GitHub</a></span>
	<div class="button-bar">
		<a href="/" class="hand-drawn">Map View</a>
		<a href="/add" class="hand-drawn">New Bench</a>
	</div>
