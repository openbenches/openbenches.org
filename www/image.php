<?php
require_once ('config.php');

$sha1 = $params[2];
$size = $params[3];

$photo_full_path = get_path_from_hash($sha1);

function show_scaled_image($imagePath, $size)
{
	$imagick = new \Imagick(realpath($imagePath));
	$imagick->resizeImage($size, null, Imagick::FILTER_CATROM,1);
	header("Content-Type: image/jpeg");
	echo $imagick->getImageBlob();
	die();
}

if(null != $size){
	$image = show_scaled_image($photo_full_path, $size);
	die();
} else {
	//	Return the full image (preserves EXIF)
	header('Content-type: image/jpeg');
	readfile($photo_full_path);
}
die();
