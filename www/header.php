<?php
require_once ('config.php');
require_once ('mysql.php');
require_once ('functions.php');

$benchID = $params[2];

if($benchID != null){
	list ($benchID, $benchLat, $benchLong, $benchAddress, $benchInscription, $published) = get_bench_details($benchID);
	$benchImage = get_image_url($benchID) . "/640";
} else if ($_GET["random"]) {
    list ($benchID, $benchLat, $benchLong, $benchAddress, $benchInscription, $published) = get_random_bench();
    $benchImage = get_image_url($benchID) . "/640";
} else {
	$benchInscription = "Welcome to OpenBenches";
	$benchImage = "/android-chrome-512x512.png";
}

?><!DOCTYPE html>
<html>
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# place: http://ogp.me/ns/place#">
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

	<!-- Twitter Specific Metadata https://dev.twitter.com/cards/markup -->
	<meta name="twitter:card"                            content="summary_large_image">
	<meta name="twitter:site"                            content="@openbenches">
	<meta name="twitter:creator"                         content="@openbenches" />
	<meta name="twitter:title"       property="og:title" content="OpenBenches">
	<meta                            property="og:url"   content="https://<?php echo "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>">
	<meta name="twitter:image"       property="og:image" content="https://openbenches.org<?php echo $benchImage; ?>">
	<meta                            property="og:image:type"  content="image/jpeg">
	<meta                            property="og:image:width" content="640">
	<meta                            property="og:image:alt"   content="A photo of a bench with a memorial inscription on it.">

	<!-- Pinterest Specific https://developers.pinterest.com/docs/rich-pins/articles/? -->
	<meta                            property="og:type"         content="place">
	<meta name="twitter:description" property="og:description"  content="<?php echo htmlspecialchars($benchInscription); ?>">

	<!-- Facebook Specific Metadata https://developers.facebook.com/docs/sharing/opengraph/object-properties -->
	<meta                            property="place:location:latitude"  content="<?php echo $benchLat;  ?>">
	<meta                            property="place:location:longitude" content="<?php echo $benchLong; ?>">
	<meta                            property="og:rich_attachment"       content="true">

	<!-- https://developers.google.com/search/docs/data-types/sitelinks-searchbox -->
	<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@type":    "WebSite",
		"url":      "https://openbenches.org/",
		"potentialAction": {
			"@type":       "SearchAction",
			"target":      "https://openbenches.org/search/?search={search_term_string}",
			"query-input": "required name=search_term_string"
		}
	}
	</script>

	<link rel="stylesheet" href="/libs/normalize.7.0.0/normalize.min.css">

	<link rel="stylesheet" href="/style.css?cache=2017-09-08T00:20"/>

	<link rel="stylesheet" href="/libs/leaflet.1.1.0/leaflet.css" />
	<script src="/libs/leaflet.1.1.0/leaflet.js"></script>

	<script src="/libs/leaflet.markercluster.1.0.6/leaflet.markercluster.js"></script>
	<link rel="stylesheet" href="/libs/leaflet.markercluster.1.0.6/MarkerCluster.css">
	<link rel="stylesheet" href="/libs/leaflet.markercluster.1.0.6/MarkerCluster.Default.css">
</head>
<body>
	<hgroup itemscope itemtype="https://schema.org/WebPage">
		<h1>
			<a href="/">
				<img src="/images/openbencheslogo.svg"
				     id="header-image"
				     alt="[logo]: a bird flies above a bench">Open<wbr>Benches</a></h1>
