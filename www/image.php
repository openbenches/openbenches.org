<?php
require_once ('config.php');

$sha1 = $params[2];
$size = $params[3];

$photo_full_path = get_path_from_hash($sha1);

function show_scaled_image($imagePath, $size)
{
	$imagick = new \Imagick(realpath($imagePath));

	//	Some phones (mostly iPhones) have rotated images
	//	Use the EXIF to correct
	//	http://php.net/manual/en/imagick.getimageorientation.php#111448
	$orientation = $imagick->getImageOrientation();
	switch($orientation) {
		case imagick::ORIENTATION_BOTTOMRIGHT:
			$imagick->rotateimage("#000", 180); // rotate 180 degrees
		break;

		case imagick::ORIENTATION_RIGHTTOP:
			$imagick->rotateimage("#000", 90); // rotate 90 degrees CW
		break;

		case imagick::ORIENTATION_LEFTBOTTOM:
			$imagick->rotateimage("#000", -90); // rotate 90 degrees CCW
		break;
	}

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
