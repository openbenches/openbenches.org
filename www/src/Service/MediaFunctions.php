<?php
// src/Service/MediaFunctions.php
namespace App\Service;

class MediaFunctions
{
	public function getScaledHeight( $originalWidth, $originalHeight, $newWidth ): int {
		$ratio  = $newWidth / $originalWidth;
		$newHeight = round( $originalHeight * $ratio );
		return $newHeight;
	}

	public function getLicenseIcon( $shortName ): string {
		//	The exception which doesn't fit
		if ( "CC Zero" == $shortName ) {
			return "cc-zero.svg";
		}
	
		//	Remove the version
		$version = array("1.0","2.0","3.0","4.0");
		$shortName = str_replace($version, "", $shortName);
	
		//	Lower case
		$shortName = strtolower($shortName);
		//	Remove trailing space
		$shortName = rtrim($shortName);
		//	Replace space
		$shortName = str_replace(" ","-",$shortName);
		//	Add file type
		return $shortName . ".svg";
	}

	public function getProxyImageURL( $sha1, $size=600 ): string {
		if ( $_ENV["IMAGE_CACHE_PREFIX"] == "" ) {
			return "//" . $_SERVER['SERVER_NAME'] . "/image/{$sha1}/";
		}

		if ( $size == null ) {
			$quality = null;
		} else {
			$quality = 60;
		}
	
		//	https://images.weserv.nl/docs/

		return $_ENV["IMAGE_CACHE_PREFIX"] .  
		       $_SERVER['SERVER_NAME'] . 
		       "/image/{$sha1}/&w={$size}&q={$quality}&output=webp&il";
	}

	// public function getMediaLocation( $file ) {
	// 	if (is_file($file)) {
	// 		$img = new \Imagick($file);
	// 		$info = $img->getImageProperties("exif:*");
	// 		$img->clear();
	
	// 		if ($info !== false) {
	// 				$direction = array('N', 'S', 'E', 'W');
	// 				if (isset($info['exif:GPSLatitude'], $info['exif:GPSLongitude'], $info['exif:GPSLatitudeRef'],
	// 					 $info['exif:GPSLongitudeRef']) &&
	// 					in_array($info['exif:GPSLatitudeRef'], $direction) && in_array($info['exif:GPSLongitudeRef'], $direction)) {
	
	// 					//	https://stackoverflow.com/questions/19347005/how-can-i-explode-and-trim-whitespace
	// 					$gpsLat = preg_split ('/(\s*,*\s*)*,+(\s*,*\s*)*/',$info['exif:GPSLatitude']);
	// 					$lat_degrees_a = explode('/',$gpsLat[0]);
	// 					$lat_minutes_a = explode('/',$gpsLat[1]);
	// 					$lat_seconds_a = explode('/',$gpsLat[2]);
	
	// 					$gpsLng = preg_split ('/(\s*,*\s*)*,+(\s*,*\s*)*/',$info['exif:GPSLongitude']);
	// 					$lng_degrees_a = explode('/',$gpsLng[0]);
	// 					$lng_minutes_a = explode('/',$gpsLng[1]);
	// 					$lng_seconds_a = explode('/',$gpsLng[2]);
	
	// 					$lat_degrees = $lat_degrees_a[0] / $lat_degrees_a[1];
	// 					$lat_minutes = $lat_minutes_a[0] / $lat_minutes_a[1];
	// 					$lat_seconds = $lat_seconds_a[0] / $lat_seconds_a[1];
	// 					$lng_degrees = $lng_degrees_a[0] / $lng_degrees_a[1];
	// 					$lng_minutes = $lng_minutes_a[0] / $lng_minutes_a[1];
	// 					$lng_seconds = $lng_seconds_a[0] / $lng_seconds_a[1];
	
	// 					$lat = (float) $lat_degrees + ((($lat_minutes * 60) + ($lat_seconds)) / 3600);
	// 					$lng = (float) $lng_degrees + ((($lng_minutes * 60) + ($lng_seconds)) / 3600);
	// 					$lat = number_format($lat, 7);
	// 					$lng = number_format($lng, 7);
	
	// 					//If the latitude is South, make it negative.
	// 					//If the longitude is west, make it negative
	// 					$lat = $info['exif:GPSLatitudeRef'] == 'S' ? $lat * -1 : $lat;
	// 					$lng = $info['exif:GPSLongitudeRef'] == 'W' ? $lng * -1 : $lng;
	
	// 					return array(
	// 						'lat' => round($lat,10),
	// 						'lng' => round($lng,10)
	// 					);
	// 				}
	// 		}
	// 	}
	
	// 	return false;
	// }

	public function getMediaMetadata( $file, $locationCheck = true ) {
		if (is_file($file)) {
			$img = new \Imagick( $file );
			$info = $img->getImageProperties("exif:*");

			$metadata = array();

			//	Basic parameters
			$metadata["height"]    = $img->getImageHeight();
			$metadata["width"]     = $img->getImageWidth();

			//	From EXIF
			$metadata["datetime" ] = $info["exif:DateTime"] ?? null;
			$metadata["make"]      = $info["exif:Make"]     ?? null;
			$metadata["model"]     = $info["exif:Model"]    ?? null;

			//	Location
			if ($info !== false) {
				$direction = array('N', 'S', 'E', 'W');
				if ( $locationCheck &&
				     isset( $info['exif:GPSLatitude'], 
				            $info['exif:GPSLongitude'],
				            $info['exif:GPSLatitudeRef'],
				            $info['exif:GPSLongitudeRef']) &&
				     in_array($info['exif:GPSLatitudeRef'], $direction) && 
				     in_array($info['exif:GPSLongitudeRef'], $direction)) {

					//	https://stackoverflow.com/questions/19347005/how-can-i-explode-and-trim-whitespace
					$gpsLat = preg_split ('/(\s*,*\s*)*,+(\s*,*\s*)*/',$info['exif:GPSLatitude']);
					$lat_degrees_a = explode('/',$gpsLat[0]);
					$lat_minutes_a = explode('/',$gpsLat[1]);
					$lat_seconds_a = explode('/',$gpsLat[2]);

					$gpsLng = preg_split ('/(\s*,*\s*)*,+(\s*,*\s*)*/',$info['exif:GPSLongitude']);
					$lng_degrees_a = explode('/',$gpsLng[0]);
					$lng_minutes_a = explode('/',$gpsLng[1]);
					$lng_seconds_a = explode('/',$gpsLng[2]);

					$lat_degrees = $lat_degrees_a[0] / $lat_degrees_a[1];
					$lat_minutes = $lat_minutes_a[0] / $lat_minutes_a[1];
					$lat_seconds = $lat_seconds_a[0] / $lat_seconds_a[1];
					$lng_degrees = $lng_degrees_a[0] / $lng_degrees_a[1];
					$lng_minutes = $lng_minutes_a[0] / $lng_minutes_a[1];
					$lng_seconds = $lng_seconds_a[0] / $lng_seconds_a[1];

					$lat = (float) $lat_degrees + ((($lat_minutes * 60) + ($lat_seconds)) / 3600);
					$lng = (float) $lng_degrees + ((($lng_minutes * 60) + ($lng_seconds)) / 3600);
					$lat = number_format($lat, 7);
					$lng = number_format($lng, 7);

					//If the latitude is South, make it negative.
					//If the longitude is west, make it negative
					$lat = $info['exif:GPSLatitudeRef'] == 'S' ? $lat * -1 : $lat;
					$lng = $info['exif:GPSLongitudeRef'] == 'W' ? $lng * -1 : $lng;

					//	Add the various metadata to an array
					$metadata["latitude"]  = round($lat,10);
					$metadata["longitude"] = round($lng,10);
				}
			}
			$img->clear();
			return $metadata;
		}
		
		return null;
	}

	public function isPhotosphere( $filename ) : bool {
		//	Adapted from https://surniaulula.com/2013/apps/wordpress/read-adobe-xmp-xml-in-php/
		$max_size = 512000;     // maximum size read
		$chunk_size = 65536;    // read 64k at a time
		$start_tag = "<x:xmpmeta";
		$end_tag   = "</x:xmpmeta>";
		$xmp_raw = null; 
		$chunk = "";
		
		$file_fh = fopen( $filename, 'rb' );
		
		$file_size = filesize( $filename );
		while ( ( $file_pos = ftell( $file_fh ) ) < $file_size && $file_pos < $max_size ) {
			$chunk .= fread( $file_fh, $chunk_size );
			if ( ( $end_pos = strpos( $chunk, $end_tag ) ) !== false ) {
				if ( ( $start_pos = strpos( $chunk, $start_tag ) ) !== false ) {
					$xmp_raw = substr( $chunk, $start_pos, 
					$end_pos - $start_pos + strlen( $end_tag ) );
				}
				break;  // stop reading after finding the xmp data
			}
		}
		fclose( $file_fh );
	
		if ( strpos( $xmp_raw, 'UsePanoramaViewer="True"' ) > 0 ) {
			return true;
		}
		if ( strpos( $xmp_raw, 'ProjectionType="equirectangular"') > 0 ) {
			return true;
		}
		return false;
	}

}