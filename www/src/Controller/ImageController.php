<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;

use App\Service\MediaFunctions;


class ImageController extends AbstractController
{
	#[Route(["/image/{sha1}", "/media/{sha1}"], name: 'show_media')]
	public function show_media( string $sha1 ): BinaryFileResponse {
		//	Files are stored according to their hash
		//	So "abc123" is stored as "/a/b/abc123.jpg"
		$directory = substr( $sha1, 0, 1);
		$subdirectory = substr( $sha1, 1, 1);
		$photo_path = "photos/" . $directory . "/" . $subdirectory . "/";
		$photo_full_path = $photo_path . $sha1 . ".jpg";
		$response = new BinaryFileResponse($photo_full_path);
		return $response;
	}
}