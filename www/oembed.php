<?php
require_once ('config.php');
require_once ('mysql.php');
require_once ('functions.php');

$thumbWidth = 1024;
//	URL is passed as https://openbenches.org/oembed/?url=https%3A%2F%2Fopenbenches.org%2Fbench%2F1234%2F
$benchURL = $_GET["url"];
//	Get the path
$path = parse_url($benchURL, PHP_URL_PATH);
//	Remove any trailing slashes
$path = trim($path, "/");
//	Get the bench ID
$paths = explode("/",$path);
$benchID = end($paths);

if($benchID != null){
	//	Get the first bench image
	$benchMedia  = get_all_media($benchID)[$benchID][0];

	$imageWidth   = $benchMedia["width"];
	$imageHeight  = $benchMedia["height"];
	$sha1         = $benchMedia["sha1"];
	$licence      = $benchMedia["licence"];

	//	Calculate image thumbnail parameters
	$thumbURL     = get_image_cache($sha1, $thumbWidth);
	$thumbRatio   = $thumbWidth / $imageWidth;
	$thumbHeight  = round($thumbRatio * $imageHeight);

	//	User Info
	$userID   = $benchMedia["user"];
	$user     = get_user($userID);
	$userName = $user["name"];

	//	Bench info
	list ($benchID, $benchLat, $benchLong, $benchAddress, $benchInscription, $published) = get_bench_details($benchID);
}

//  https://oembed.com/

$data = array(
	"version"       => "1.0",
	"type"          => "photo",
	"url"           => "{$thumbURL}",
	"width"         => "{$thumbWidth}",
	"height"        => "{$thumbHeight}",
	"title"         => "{$benchInscription}",
	"author_name"   => "{$userName}",
	"author_url"    => "https://{$_SERVER['HTTP_HOST']}/user/{$userID}",
	"provider_name" => "OpenBenches",
	"provider_url"  => "https://{$_SERVER['HTTP_HOST']}/",
	"latitude"      => "{$benchLat}",
	"longitude"     => "{$benchLong}",
	"licence"       => "{$licence}",
);

header("Content-Type: application/json+oembed");
echo json_encode($data);
unset($data);
