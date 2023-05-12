<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;

use App\Service\SearchFunctions;

class FeedController extends AbstractController
{
	#[Route(["/atom.xml", "/atom", "/feed", "/rss"], name: "feed_page")]
	public function feed_page(Request $request): Response {
		
		$searchFunctions = new searchFunctions();

		//	Get the benches associated with this query
		$benches_array = $searchFunctions->getLatestBenches();
		
		if ( $request->getRequestUri() == "/feed" || $request->getRequestUri() == "/rss" ) {
			$feed = "rss";
			$type = "application/rss+xml";	
		} else {
			$feed = "atom";
			$type = "application/atom+xml";	
		}

		//	Render the page
		$contents = $this->renderView("{$feed}.xml.twig", [
			"url"     => "https://openbenches.org/",
			"title"   => "OpenBenches",
			"benches" => $benches_array,
		]);

		//	Create response with correct content type
		$response = new Response();
		$response->setContent( $contents );
		$response->headers->set( "Content-Type", $type );

		return $response;
	}
}