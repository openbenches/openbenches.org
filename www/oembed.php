<?php
require_once ('config.php');
require_once ('mysql.php');
require_once ('functions.php');

$benchURL = $_GET["url"];
$path = parse_url($benchURL, PHP_URL_PATH)
$path = trim($path, "/");
$benchID = end(explode("/",$url));

if($benchID != null){
	$benchMedia  = get_all_media($benchID)[0];
	$imageWidth  = $benchMedia["width"];
	$imageHeight = $benchMedia["height"];

	$benchImage = get_image_thumb($benchID, $imageWidth);
	list ($userID, $userName) = get_user_from_media($benchID);
	list ($benchID, $benchLat, $benchLong, $benchAddress, $benchInscription, $published) = get_bench_details($benchID);
}

//  https://oembed.com/


/*
<link rel="alternate" type="application/json+oembed"
	href="http://flickr.com/services/oembed?url=http%3A%2F%2Fflickr.com%2Fphotos%2Fbees%2F2362225867%2F&format=json"
	title="Bacon Lollys oEmbed Profile" />
*/

$data = array(
	"version"       => "1.0",
	"type"          => "photo",
	"url"           => "{$benchImage}",
	"width"         => "{$imageWidth}",
	"height"        => ,
	"title"         => "{$benchInscription}",
	"author_name"   => "{$userName}",
	"author_url"    => "https://openbenches.org/user/{$userID}",
	"provider_name" => "OpenBenches",
	"provider_url"  => "https://openbenches.org/",
	"latitude"      => "{$benchLat}",
	"longitude"     => "{$benchLong}",
);

header("Content-Type: application/json+oembed");
echo json_encode($data);
die();
