<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;

use App\Service\SearchFunctions;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;

class SearchController extends AbstractController
{
	#[Route('/search', name: 'search_page')]
	public function search_page(): Response {
		$request = Request::createFromGlobals();
		//	/search/?search=test&page=1&count=20
		$query     = $request->query->get("search");
		$soundex   = $request->query->get("soundex") ?? false;

		$get_page  = $request->query->get("page");
		$get_count = $request->query->get("count");

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

		$searchFunctions = new searchFunctions();

		if ( !$soundex ) {
			//	Get the benches associated with this query
			$benches_array = $searchFunctions->getSearchBenches( $query, $first, $last );
			$benches_count = $searchFunctions->getSearchBenchCount( $query );

			//	Pagination for the UI
			if( $get_page > 0 ) {
				$previous_page = $get_page - 1;
				$previous_url = "?search={$query}&page={$previous_page}&count={$get_count}";
			} else {
				$previous_url = null;
			}

			if( ( $benches_count > ( ($get_page * $get_count) + $get_count ) ) ) {
				$next_page = $get_page + 1;
				$next_url = "?search={$query}&page={$next_page}&count={$get_count}";
			} else {
				$next_url = null;
			}

			//	Render the page
			return $this->render('search.html.twig', [
				"query"         => $query,
				"benches_count" => number_format( $benches_count ),
				"benches"       => $benches_array,
				"next_url"      => $next_url,
				"previous_url"  => $previous_url,
			]);

		} else {
			$benches_array = $searchFunctions->getSoundexBenches( $soundex );
			//	Render the page
			return $this->render('soundex.html.twig', [
				"benches"       => $benches_array,
				"soundex"       => $soundex,
				"benches_count" => count( $benches_array ),
			]);
		}
	}

	#[Route("/soundex", name: "soundex_page")]
	public function soundex_page(): Response {
		$request = Request::createFromGlobals();
		//	/soundex/?soundex=AAAA123
		$soundex   = $request->query->get("soundex") ?? false;
		$searchFunctions = new searchFunctions();

		$benches_array = $searchFunctions->getSoundexBenches( $soundex );
		//	Render the page
		return $this->render('soundex.html.twig', [
			"benches"       => $benches_array,
			"soundex"       => $soundex,
			"benches_count" => count( $benches_array ),
		]);
	}

	#[Route(["/tag/{tag}"], name: 'tag_page')]
	public function tag_page( string $tag ): Response {
		//	TODO - add pagination
		$request = Request::createFromGlobals();
		//	/tag/cat
		$tag = strtolower($tag);
		$searchFunctions = new searchFunctions();
		$benches_array = $searchFunctions->getTagBenches( $tag );
		//	Render the page
		return $this->render('tag.html.twig', [
			"benches"       => $benches_array,
			"tag"           => $tag,
			"benches_count" => count( $benches_array ),
			"previous_url"  => null,
			"next_url"      => null,
		]);
	}
}