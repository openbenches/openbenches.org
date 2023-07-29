<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\BenchFunctions;
use App\Service\SearchFunctions;

class OembedController extends AbstractController
{
	#[Route('/oembed/', name: 'oembed')]
	public function oembed(): JsonResponse {
		$request = Request::createFromGlobals();
		//	/oembed?url=http%3A%2F%2Fopenbenches.org%2Fbench%2F1234
		$url = urldecode( $request->query->get("url") );
		$parsed_url = parse_url( $url );

		if ( $parsed_url["host"] == $_SERVER['SERVER_NAME']) {
			$path_elements = array_filter( explode( "/", $parsed_url["path"])  );
			$last_element = end($path_elements);
			// var_dump($last_element);die();
			if ( is_numeric($last_element) ) {
				
				$bench_id = $last_element;

				$benchFunctions = new BenchFunctions();
				$bench = $benchFunctions->getBench($bench_id);
				$media_id = array_key_first( $bench["medias"] );
		
				//	As per https://oembed.com/
				$oembed_json = array(
					"version"       => "1.0",
					"type"          => "photo",
					"cache_age"     => "3600",
					"width"         => $bench["medias"][$media_id]["width"],
					"height"        => $bench["medias"][$media_id]["height"],
					"title"         => $bench["inscription"],
					"url"           => $bench["medias"][$media_id]["url"],
					"author_name"   => $bench["medias"][$media_id]["userName"],
					"latitude"      => $bench["latitude"],
					"longitude"     => $bench["longitude"],
					"licence"       => $bench["medias"][$media_id]["licence"],
					"provider_name" => "OpenBenches",
					"provider_url"  => "https://openbenches.org/"
				);
				//	Render the oEmbed
				$response = new JsonResponse($oembed_json);	
				return $response;
			}
		}
		
		//	Render the oEmbed error
		$oembed_json = array( "Error" => "404");
		$response = new JsonResponse($oembed_json);
		$response->setStatusCode(Response::HTTP_NOT_FOUND);
		return $response;
	}
}