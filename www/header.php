<?php
require_once ('config.php');
require_once ('mysql.php');
require_once ('functions.php');

//	Defaults
$benchLat  = null;
$benchLong = null;

//	Random bench?
if (isset($_POST["random"])) {
	list ($benchID, $benchLat, $benchLong, $benchAddress, $benchInscription, $published) = get_random_bench();
	header('Cache-Control: no-store, must-revalidate');
	header('Expires: 0');
	header('Location: ' . "https://{$_SERVER['HTTP_HOST']}/bench/{$benchID}/",TRUE,302);
	return null;
}

if (isset($params[1])) {
	$page = strtolower($params[1]);
} else {
	$page = null;
}
$benchInscription = "- Welcome to OpenBenches";
$benchImage = "/android-chrome-512x512.png";

$oembedMeta = null;

if ("bench" == $page) {
	$benchID = $params[2];

	if($benchID != null){
		list ($benchID, $benchLat, $benchLong, $benchAddress, $benchInscription, $published, $present, $description) = get_bench_details($benchID);
		$benchImage = get_image_url($benchID) . "/640";

		//	https://oembed.com/
		$oembedMeta = "<link rel='alternate'
		type='application/json+oembed'
		href='https://{$_SERVER['HTTP_HOST']}/oembed/?url=https%3A%2F%2F{$_SERVER['HTTP_HOST']}%2Fbench%2F{$benchID}%2F'
		title='OpenBenches oEmbed Profile' />";
	}

	//	Unpublished benches
	if($benchID != null && !$published) {
		//	Has it been merged?
		$mergedID = get_merged_bench($benchID);
		if (null == $mergedID) {
			//	Nope! Just deleted.  Include 404 content at the end of this page.
			header("HTTP/1.1 404 Not Found");
		} else {
			//	Yup! Where does it live now?
			header("Location: /bench/{$mergedID}",TRUE,301);
			return null;
		}
	}

	// Benches which don't exist
	if($benchID == null) {
		header("HTTP/1.1 404 Not Found");
	}
}
if ("user" == $page) {
	//	Handled in user.php
}

if (null == $page_title) {
	$page_title = $benchInscription;
}

?><!DOCTYPE html>
<html lang="en-GB">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# place: http://ogp.me/ns/place#">
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="monetization" content="$ilp.uphold.com/ieELEKD7epqw">

	<title>OpenBenches <?php echo $page_title; ?></title>

	<!-- Favicons https://realfavicongenerator.net -->
	<link rel="apple-touch-icon"      sizes="180x180" href="/apple-touch-icon.png?cache=2019-05-05">
	<link rel="icon" type="image/png" sizes="32x32"   href="/favicon-32x32.png?cache=2019-05-05">
	<link rel="icon" type="image/png" sizes="16x16"   href="/favicon-16x16.png?cache=2019-05-05">
	<link rel="manifest"                              href="/manifest.json?cache=2019-05-05T16:00">
	<link rel="mask-icon"             color="#5bbad5" href="/safari-pinned-tab.svg?cache=2019-05-05">
	<link rel="shortcut icon"                         href="/favicon.ico?cache=2019-05-05">
	<meta name="theme-color" content="#ffffff">
	<meta name="msapplication-TileColor"    content="#ffc40d">
	<meta name="application-name"           content="OpenBenches">
	<meta name="apple-mobile-web-app-title" content="OpenBenches">
 
	<!-- Mastodon BotsIn.Space Specific Metadata -->
	<link rel="me" href="https://botsin.space/@openbenches">

	<!-- Twitter Specific Metadata https://dev.twitter.com/cards/markup -->
	<meta name="twitter:card"                            content="summary">
	<meta name="twitter:site"                            content="@openbenches">
	<meta name="twitter:creator"                         content="@openbenches">
	<meta name="twitter:title"       property="og:title" content="OpenBenches <?php echo htmlspecialchars($page_title); ?>">
	<meta                            property="og:url"   content="https://<?php echo "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>">
	<meta name="twitter:image"       property="og:image" content="https://<?php echo "$_SERVER[HTTP_HOST]$benchImage"; ?>">
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
	<meta                            property="fb:app_id"                content="<?php echo FACEBOOK_APP_ID; ?>" />

	<!-- https://developers.google.com/search/docs/data-types/sitelinks-searchbox -->
	<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@type":    "WebSite",
		"url":      "https://<?php echo $_SERVER['HTTP_HOST']; ?>/",
		"potentialAction": {
			"@type":       "SearchAction",
			"target":      "https://<?php echo $_SERVER['HTTP_HOST']; ?>/search/?search={search_term_string}",
			"query-input": "required name=search_term_string"
		}
	}
	</script>

	<link rel="alternate" type="application/rss+xml" href="https://<?php echo $_SERVER["HTTP_HOST"]; ?>/rss" />
	<?php echo $oembedMeta; ?>

	<link rel="stylesheet" href="/css/picnic.css?cache=2019-09-19T08:40"/>
	<link rel="stylesheet" href="/css/style.css?cache=2023-04-09T16:06"/>

	<link rel="stylesheet" href="/libs/select2.4.0.13/select2.min.css"  />

	<link rel="stylesheet" href="/libs/leaflet.1.8.0/leaflet.css" />
	<script                 src="/libs/leaflet.1.8.0/leaflet.js"></script>
	<script                 src='/libs/Leaflet.Sleep/Leaflet.Sleep.js'></script>

	<script                 src="/libs/leaflet.markercluster.1.5.3/leaflet.markercluster.js"></script>
	<link rel="stylesheet" href="/libs/leaflet.markercluster.1.5.3/MarkerCluster.css">
	<link rel="stylesheet" href="/libs/leaflet.markercluster.1.5.3/MarkerCluster.Default.css">
</head>
<body itemscope itemtype="https://schema.org/WebPage">
	<hgroup>
		<h1>
			<a href="/">
				<img src="/images/openbencheslogo.svg"
				     id="header-image"
				     alt="A bird flies above a bench">Open<wbr>Benches</a></h1>

				<?php
				echo '<h2 itemprop="description">A map of ' . number_format(get_bench_count()) . " memorial benches - added by people like you</h2>";
				?>
				<ul class="menu">
				  <li><a href="/add/" class="button buttonColour"><strong>+</strong> Add bench</a></li>
				</ul>
			</hgroup>
<?php
//	Unpublished or non-existant benches
if ( ("bench" == $page && $benchID == null) || ("bench" == $page && $benchID != null && !$published) ) {
	include("404.php");
	return null;
}
