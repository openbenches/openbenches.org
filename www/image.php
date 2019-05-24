<?php
require_once ('config.php');

$sha1 = $params[2];

if( isset($params[3]) ) {
	$size = $params[3];
} else {
	$size = null;
}

$photo_full_path = get_path_from_hash($sha1);

function show_scaled_image($imagePath, $size)
{
	try {
		$imagick = new \Imagick(realpath($imagePath));
	} catch (Exception $e) {
		$refer = $_SERVER["HTTP_REFERER"];
		error_log("Image error! {$imagePath} - from {$refer} - {$e}" , 0);
		die();
	}

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

	//	Set the orientation - otherwise it will appear rotated on some browsers
	$imagick->setImageOrientation(imagick::ORIENTATION_TOPLEFT);

	//	Resize the image
	$imagick->resizeImage($size, null, Imagick::FILTER_CATROM,1);

	//	Set the quality
	$imagick->setImageCompressionQuality(85);

	//	Progressive image for slower connections
	$imagick->setInterlaceScheme(Imagick::INTERLACE_PLANE);

	//	Send the image to the browser
	header("Content-Type: image/jpeg");
	ob_clean();	//	http://codeblog.vurdalakov.net/2013/01/solution-php-echo-function-or-print.html
	echo $imagick->getImageBlob();
	$imagick->clear();
	die();
}

if ("exif" == $size){
	$exif_data = exif_read_data($photo_full_path,0,true);
	echo "<pre>";
	echo var_export($exif_data);
	echo "</pre>";
	die();
} else if(null != $size){
	show_scaled_image($photo_full_path, $size);
	die();
} else {
	//	Return the full image (preserves EXIF)
	header('Content-type: image/jpeg');
	ob_clean();
	readfile($photo_full_path);
}
die();
