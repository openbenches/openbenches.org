<?php
require_once ("config.php");

$sha1 = $params[2];

if( isset($params[3]) ) {
	$size = $params[3];
} else {
	$size = null;
}

// Remove the .ext from the hash
$sha1 = explode(".", $sha1)[0];

$photo_full_path = get_path_from_hash($sha1);
$mime = mime_content_type($photo_full_path);

//	If the photo doesn't exist, stop
if(!file_exists($photo_full_path)){
	return null;
}

function show_scaled_image($imagePath, $size)
{
	try {
		$imagick = new \Imagick(realpath($imagePath));
	} catch (Exception $e) {
		$refer = $_SERVER["HTTP_REFERER"];
		error_log("Image error! {$imagePath} - from {$refer} - {$e}" , 0);
		$imagick->clear();
		return null;
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

	try {
		//	Set the orientation - otherwise it will appear rotated on some browsers
		$imagick->setImageOrientation(imagick::ORIENTATION_TOPLEFT);

		//	Resize the image
		$imagick->resizeImage($size, null, Imagick::FILTER_CATROM,1);

		//	Set the quality
		$imagick->setImageCompressionQuality(85);

		//	Progressive image for slower connections
		$imagick->setInterlaceScheme(Imagick::INTERLACE_PLANE);
	} catch (Exception $e) {
		$refer = $_SERVER["HTTP_REFERER"];
		error_log("Image error! {$imagePath} - from {$refer} - size={$size} {$e}" , 0);
		$imagick->clear();
		return null;
	}
	
	//	Send the image to the browser
	header("Content-Type: image/jpeg");
	ob_clean();	//	http://codeblog.vurdalakov.net/2013/01/solution-php-echo-function-or-print.html
	echo $imagick->getImageBlob();
	$imagick->clear();
	return null;
}

if ("exif" == $size){
	$img = new \Imagick($photo_full_path);
	$exif = $img->getImageProperties();
	$img->clear();

	echo "<pre>";
	echo var_export($exif);
	echo "</pre>";
	return null;
} else if(null != $size && "video/mp4" != $mime){
	//	Not a video, not using /size
	show_scaled_image($photo_full_path, $size);
	return null;
} else {
	//	Return the full image (preserves EXIF)
	// $mime = mime_content_type($photo_full_path);
	// header("Content-type: {$mime}");
	// ob_clean();
	// readfile($photo_full_path);
	// 302 Found
	header("Location: /{$photo_full_path}", true, 302);
	exit;
}
