<?php
require_once ('config.php');

$sha1 = $params[2];
$size = $params[3];

$photo_full_path = get_path_from_hash($sha1);

function imagecreatefromjpegexif($filename)
{
	 $img = imagecreatefromjpeg($filename);
	 $exif = exif_read_data($filename);
	 if ($img && $exif && isset($exif['Orientation']))
	 {
		$ort = $exif['Orientation'];

		if ($ort == 6 || $ort == 5)
			$img = imagerotate($img, 270, null);
		if ($ort == 3 || $ort == 4)
			$img = imagerotate($img, 180, null);
		if ($ort == 8 || $ort == 7)
			$img = imagerotate($img, 90, null);
		if ($ort == 5 || $ort == 4 || $ort == 7)
			imageflip($img, IMG_FLIP_HORIZONTAL);
	 }
	 return $img;
}

if(null != $size){
	$image = imagecreatefromjpegexif($photo_full_path);
	$image = imagescale($image, $size);
	header("Content-Type: image/jpeg");
	imagejpeg($image);
	imagedestroy($image);
} else {
	//	Return the full image (preserves EXIF)
	header('Content-type: image/jpeg');
	readfile($photo_full_path);
}
die();
