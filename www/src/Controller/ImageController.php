<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Request;

use App\Service\BenchFunctions;
use App\Service\MediaFunctions;

class ImageController extends AbstractController
{
	                                           // Old oEmbed sizing
	#[Route(["/image/{sha1}", "/media/{sha1}", "/image/{sha1}/{size}"], name: 'show_media')]
	public function show_media( string $sha1 ): BinaryFileResponse {
		//	Files are stored according to their hash
		//	So "abc123" is stored as "/a/b/abc123.jpg"
		$directory = substr( $sha1, 0, 1);
		$subdirectory = substr( $sha1, 1, 1);
		$photo_path = "photos/" . $directory . "/" . $subdirectory . "/";

		//	Sometimes, broken clients send `/media/abc123.jpg`
		//	This makes sure any errant file extension is stripped off.
		$sha1  = explode( ".", $sha1 )[0];

		$photo_full_path = $photo_path . $sha1 . ".jpg";
		try {
			$response = new BinaryFileResponse( $photo_full_path );
			return $response;
		} catch (\Exception $e) {
			//	Generate an HTTP 404 response
			throw $this->createNotFoundException( "The image does not exist" );
		}
	}

	//	Returns a thumbnail for a specific bench.
	#[Route(["/thumb/{benchID}"], name: "get_thumb")]
	public function get_thumb( int $benchID ): RedirectResponse {

		//	Get the bench.
		$benchFunctions = new BenchFunctions();
		$bench = $benchFunctions->getBench($benchID);
		$media_id = array_key_first( $bench["medias"] );
		$proxyImageURl = $bench["medias"][$media_id]["url"];

		//	Return a URl.
		return $this->redirect( $proxyImageURl );
	}
}