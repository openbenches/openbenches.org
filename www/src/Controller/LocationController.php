<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;

use App\Service\LocationFunctions;
use App\Service\BenchFunctions;
use App\Service\MediaFunctions;

class LocationController extends AbstractController
{
	#[Route('/location/{address}', name: 'location_address', requirements: ['address' => '.+'])]
	public function show(string $address): Response {
		//	Format the array as an address string so we can get the bounding box
		$address_array = explode( "/", $address );
		$address_array = array_reverse( $address_array );
		$address_string = implode( ", ", $address_array);
		$address_string  = rtrim($address_string , ", ");
		$address_string  = ltrim($address_string , ", ");

		$request = Request::createFromGlobals();
		//	/location/UK/London/?page=2&count=5
		$get_page      = $request->query->get("page");
		$get_count     = $request->query->get("count");

		//	Page
		if( isset( $get_page ) ){
			$get_page = (int)$get_page;
		} else {
			$get_page = 0;
		}

		// Items per page
		if( isset( $get_count ) ){
			$get_count = (int)$get_count;
		} else {
			$get_count = 20;
		}

		//	Pagination for the database
		$first = $get_page * $get_count;
		$last  = $get_count;

		$locationFunctions = new LocationFunctions();
		
		//	Get the bounding box co-ordinates
		list($bb_n, $bb_e, $bb_s, $bb_w) = $locationFunctions->getBoundingBox( $address_string );

		if( $bb_n == null ) {
			//	Generate an HTTP 404 response
			throw $this->createNotFoundException("Location not found");
		}

		//	Get how many benches are in the box
		$benches_count   = $locationFunctions->getBoundingBoxCount($bb_n, $bb_e, $bb_s, $bb_w);
		
		//	Get the benches
		$benches = $locationFunctions->getBoundingBoxBenches($bb_n, $bb_e, $bb_s, $bb_w, $first, $last);

		//	Pagination for the UI
		if( $get_page > 0 ) {
			$previous_page = $get_page - 1;
			$previous_url = "?page={$previous_page}&count={$get_count}";
		} else {
			$previous_url = null;
		}

		if( ( $benches_count > ( ($get_page * $get_count) + $get_count ) ) ) {
			$next_page = $get_page + 1;
			$next_url = "?page={$next_page}&count={$get_count}";
		} else {
			$next_url = null;
		}

		//	Render the page
		return $this->render('location.html.twig', [
			"title"        => $address_string,
			"count"        => $benches_count,
			"benches"      => $benches,
			"bb_n"         => $bb_n,
			"bb_e"         => $bb_e,
			"bb_s"         => $bb_s,
			"bb_w"         => $bb_w,
			"next_url"     => $next_url,
			"previous_url" => $previous_url,
		]);
	}
}